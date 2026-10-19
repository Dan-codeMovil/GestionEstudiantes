<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_invitado_es_redirigido_al_login(): void
    {
        $this->get('/estudiantes')->assertRedirect(route('login'));
    }

    public function test_raiz_redirige_al_listado(): void
    {
        $this->get('/')->assertRedirect(route('estudiantes.index'));
    }

    public function test_login_web_correcto(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/estudiantes');

        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('auditorias', [
            'usuario'          => $user->email,
            'tipo_transaccion' => 'LOGIN',
        ]);
    }

    public function test_login_web_con_clave_incorrecta(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'incorrecta'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_web_se_bloquea_tras_3_fallos(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'mala']);
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertStringContainsString('Demasiados intentos', session('errors')->first('email'));
    }

    public function test_login_api_devuelve_token(): void
    {
        $user = User::factory()->admin()->create();

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['message', 'access_token', 'token_type', 'user' => ['id', 'name', 'rol']])
            ->assertJsonPath('user.rol', 'admin');
    }

    public function test_login_api_con_clave_incorrecta(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'mala'])
            ->assertStatus(401)
            ->assertJson(['message' => 'Credenciales incorrectas']);
    }

    public function test_login_api_se_bloquea_tras_3_fallos(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/api/login', ['email' => $user->email, 'password' => 'mala']);
        }

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password'])
            ->assertStatus(429);
    }

    public function test_api_sin_token_devuelve_401(): void
    {
        $this->getJson('/api/estudiantes')->assertUnauthorized();
    }

    public function test_logout_api_revoca_el_token(): void
    {
        $user  = User::factory()->create();
        $token = $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password'])
            ->json('access_token');

        $this->withToken($token)->postJson('/api/logout')->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_la_ruta_publica_ya_no_existe(): void
    {
        $this->getJson('/api/v1/estudiantes-publico')->assertNotFound();
    }
}