<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence(),
            'priority' => $this->faker->numberBetween(1, 9),
            'complexity' => $this->faker->numberBetween(1, 5),
        ];
    }

    public function highestPriority()
    {
        return $this->state(function (array $attributes) {
            return [
                'priority' => 9,
            ];
        });
    }

    public function lowestPriority()
    {
        return $this->state(function (array $attributes) {
            return [
                'priority' => 1,
            ];
        });
    }

    public function highestComplexity()
    {
        return $this->state(function (array $attributes) {
            return [
                'priority' => 5,
            ];
        });
    }

    public function lowestComplexity()
    {
        return $this->state(function (array $attributes) {
            return [
                'priority' => 1,
            ];
        });
    }
}
