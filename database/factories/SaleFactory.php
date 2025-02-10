<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition()
    {
        return [
            'invoice_number' => 'INV-' . $this->faker->unique()->randomNumber(8),
            'total_amount' => $this->faker->randomFloat(2, 100, 1000),
            'payment_method' => $this->faker->randomElement(['cash', 'card', 'transfer']),
            'payment_status' => 'paid',
            'customer_name' => $this->faker->name()
        ];
    }
}
