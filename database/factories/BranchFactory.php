<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Campus;
use Illuminate\Database\Eloquent\Factories\Factory;

class BranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'type' => $this->faker->randomElement(['Type 1', 'Type 2']),
            'campus_id' => function () {
                return Campus::factory()->create()->id;
            },
            'status' => 'active',
            'created_by' => null,
            'updated_by' => null,
        ];
    }
}
