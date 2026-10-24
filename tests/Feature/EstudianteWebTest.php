<?php

namespace Tests\Feature;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EstudianteWebTest extends TestCase
{
    use RefreshDatabase;

    private function datosValidos(array $extra = []): array
    {
        return array_merge([
            'nombre'    => 'Ana Pérez',
            'email'     => 'ana@correo.com',
            'documento' => '1234567890',
            'carrera'   => 'Ingeniería de Sistemas',
        ], $extra);
    }

    public function test_estudiante_ve_el_listado_sin_botones(): void
    {
        Estudiante::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get('/estudiantes')
            ->assertOk()
            ->assertDontSee('title="Editar"', false)
            ->assertDontSee('title="Eliminar"', false);
    }

    public function test_admin_ve_los_botones(): void
    {
        Estudiante::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('/estudiantes')
            ->assertOk()
            ->assertSee('title="Editar"', false)
            ->assertSee('title="Eliminar"', false);
    }

    public function test_estudiante_no_puede_crear_editar_ni_eliminar(): void
    {
        $e = Estudiante::factory()->create();
        $this->actingAs(User::factory()->create());

        $this->get('/estudiantes/create')->assertForbidden();
        $this->post('/estudiantes', $this->datosValidos())->assertForbidden();
        $this->get("/estudiantes/{$e->id}/edit")->assertForbidden();
        $this->put("/estudiantes/{$e->id}", $this->datosValidos())->assertForbidden();
        $this->delete("/estudiantes/{$e->id}")->assertForbidden();

        $this->assertDatabaseCount('estudiantes', 1);
    }

    public function test_admin_crea_estudiante_y_queda_auditado(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/estudiantes', $this->datosValidos())
            ->assertRedirect(route('estudiantes.index'));

        $this->assertDatabaseHas('estudiantes', ['email' => 'ana@correo.com']);
        $this->assertDatabaseHas('auditorias', [
            'usuario'          => $admin->email,
            'tipo_transaccion' => 'CREAR',
        ]);
    }

    #[DataProvider('documentosInvalidos')]
    public function test_documento_invalido_es_rechazado(string $documento): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->post('/estudiantes', $this->datosValidos(['documento' => $documento]))
            ->assertSessionHasErrors('documento');

        $this->assertDatabaseCount('estudiantes', 0);
    }

    public static function documentosInvalidos(): array
    {
        return [
            'decimal'             => ['12.5'],
            'letras'              => ['abc123'],
            'negativo'            => ['-12345'],
            'notacion cientifica' => ['1e10'],
            'muy corto'           => ['1234'],
            'muy largo'           => ['1234567890123456'],
        ];
    }

    public function test_no_permite_email_ni_documento_duplicados(): void
    {
        Estudiante::factory()->create(['email' => 'ana@correo.com', 'documento' => '1234567890']);

        $this->actingAs(User::factory()->admin()->create())
            ->post('/estudiantes', $this->datosValidos())
            ->assertSessionHasErrors(['email', 'documento']);
    }

    public function test_admin_actualiza_sin_chocar_con_su_propio_documento(): void
    {
        $e = Estudiante::factory()->create(['documento' => '1234567890']);

        $this->actingAs(User::factory()->admin()->create())
            ->put("/estudiantes/{$e->id}", $this->datosValidos(['email' => $e->email, 'nombre' => 'Nuevo Nombre']))
            ->assertRedirect(route('estudiantes.index'));

        $this->assertDatabaseHas('estudiantes', ['id' => $e->id, 'nombre' => 'Nuevo Nombre']);
        $this->assertDatabaseHas('auditorias', ['tipo_transaccion' => 'EDITAR']);
    }

    public function test_admin_elimina_estudiante(): void
    {
        $e = Estudiante::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->delete("/estudiantes/{$e->id}")
            ->assertRedirect(route('estudiantes.index'));

        $this->assertDatabaseMissing('estudiantes', ['id' => $e->id]);
        $this->assertDatabaseHas('auditorias', ['tipo_transaccion' => 'ELIMINAR']);
    }

    // EstudianteWebTest
    public function test_listado_web_esta_paginado(): void
    {
        Estudiante::factory()->count(15)->create();
        $this->actingAs(User::factory()->create());

        $this->get('/estudiantes')
            ->assertOk()
            ->assertViewHas('estudiantes', fn ($p) => $p->count() === 10 && $p->total() === 15);

        $this->get('/estudiantes?page=2')
            ->assertViewHas('estudiantes', fn ($p) => $p->count() === 5);
    }

    public function test_admin_abre_crear_y_editar_con_la_barra_de_navegacion(): void
    {
        $e = Estudiante::factory()->create();
        $this->actingAs(User::factory()->admin()->create());

        $this->get('/estudiantes/create')->assertOk()->assertSee('Cerrar Sesión');
        $this->get("/estudiantes/{$e->id}/edit")->assertOk()->assertSee('Cerrar Sesión');
    }
}