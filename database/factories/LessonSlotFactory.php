<?php

namespace Database\Factories;

use App\Models\Campus;
use App\Models\LessonSlot;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonSlotFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LessonSlot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'campus_id' => function () {
                return Campus::factory()->create()->id;
            },
            'day' => $this->faker->dayOfWeek,
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i'),
        ];
    }
}
