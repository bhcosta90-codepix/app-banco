<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\PixKey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'uuid' => (string) str()->uuid(),
            'account_from_id' => Account::factory(),
            'pix_key_id' => PixKey::factory(),
            'amount' => rand(50, 100),
            'description' => rand(0, 1) ? $this->faker->paragraph() : null,
        ];
    }
}
