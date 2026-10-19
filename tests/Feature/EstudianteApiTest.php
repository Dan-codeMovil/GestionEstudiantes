<?php

namespace Tests\Feature;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstudianteApiTest extends TestCase
{
    use RefreshDatabase;

    private function token(User $user): string
    {
        return $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => 'password',
        ])->json('access_token');
    }

    private function datosValidos(array $extra = []): array
    {
        return array_merge([
            'nombre'    => 'Ana Pérez',
            'email'     => 'ana@correo.com',
            'documento' => '1234567890',
            'carrera'   => 'Ingeniería de Sistemas',
        ], $extra);
    }

    public function test_estudiante_puede_listar_y_ver(): void
    {
        $e = Estudiante::factory()->create();
        $this->withToken($this->token(User::factory()->create()));

        $this->getJson('/api/estudiantes')->assertOk()->assertJsonCount(1);
        $this->getJson("/api/estudiantes/{$e->id}")->assertOk()->assertJsonPath('id', $e->id);
    }

    public function test_estudiante_recibe_403_al_escribir(): void
    {
        $e = Estudiante::factory()->create();
        $this->withToken($this->token(User::factory()->create()));

        $this->postJson('/api/estudiantes', $this->datosValidos())->assertForbidden();
        $this->putJson("/api/estudiantes/{$e->id}", $this->datosValidos())->assertForbidden();
        $this->deleteJson("/api/estudiantes/{$e->id}")->assertForbidden();

        $this->assertDatabaseCount('estudiantes', 1);
    }

    public function test_admin_crea_actualiza_y_elimina(): void
    {
        $this->withToken($this->token(User::factory()->admin()->create()));

        $id = $this->postJson('/api/estudiantes', $this->datosValidos())
            ->assertCreated()
            ->json('data.id');

        $this->putJson("/api/estudiantes/{$id}", $this->datosValidos(['nombre' => 'Otro Nombre']))
            ->assertOk();
        $this->assertDatabaseHas('estudiantes', ['id' => $id, 'nombre' => 'Otro Nombre']);

        $this->deleteJson("/api/estudiantes/{$id}")->assertOk();
        $this->assertDatabaseMissing('estudiantes', ['id' => $id]);
    }

    public function test_api_rechaza_documento_invalido(): void
    {
        $this->withToken($this->token(User::factory()->admin()->create()));

        $this->postJson('/api/estudiantes', $this->datosValidos(['documento' => '12.5']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('documento');
    }
}