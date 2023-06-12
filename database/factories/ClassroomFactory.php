<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClassroomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Classroom::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['9/A', '9/C', '10/B', '11/D', '12/Z', '9/B']),
            'grade_id' => function () {
                return Grade::factory()->create()->id;
            },
            'branch_id' => function () {
                return Branch::factory()->create()->id;
            },
            'status' => 'active',
            'created_by' => \App\Models\User::first()->id,
            'updated_by' => \App\Models\User::first()->id
        ];
    }
}
