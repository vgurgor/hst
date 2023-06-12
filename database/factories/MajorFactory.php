<?php

namespace Database\Factories;

use App\Models\Major;
use Illuminate\Database\Eloquent\Factories\Factory;

class MajorFactory extends Factory
{
    protected $model = Major::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'created_by' => 1, // Örnek olarak oluşturan kullanıcıyı belirtebilirsiniz
            'updated_by' => 1, // Örnek olarak güncelleyen kullanıcıyı belirtebilirsiniz
        ];
    }
}
