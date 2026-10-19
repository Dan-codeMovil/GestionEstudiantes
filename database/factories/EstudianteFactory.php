<?php

namespace Database\Factories;

use App\Models\Estudiante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Estudiante>
 */
class EstudianteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre'    => fake()->name(),
            'email'     => fake()->unique()->safeEmail(),
            'documento' => fake()->unique()->numerify('##########'),
            'carrera'   => fake()->randomElement(['Ingeniería de Sistemas', 'Derecho', 'Medicina']),
        ];
    }
}
