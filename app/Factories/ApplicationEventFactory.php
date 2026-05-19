<?php

namespace App\Factories;

use App\Models\ApplicationEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ApplicationEventFactory extends Factory
{
    protected $model = ApplicationEvent::class;

    public function definition(): array
    {
        return [
            'message' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
