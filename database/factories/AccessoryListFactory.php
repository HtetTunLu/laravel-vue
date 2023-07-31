<?php

namespace Database\Factories;

use App\Models\Accessory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccessoryList>
 */
class AccessoryListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'accessory_id' => Accessory::factory()->create()->id,
            'floor' => rand(1, 4),
            'quantity' => 100,
            'remind_limit' => 20
        ];
    }
}
