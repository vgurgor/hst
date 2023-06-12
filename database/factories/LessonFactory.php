<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'major_id' => 1,
            'grade_id' => 1,
            'name' => $this->faker->word,
            'weekly_frequency' => $this->faker->numberBetween(1, 5),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
