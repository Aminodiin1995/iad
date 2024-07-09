<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'invoice_id' => Invoice::factory(),
            'payment_type' => $this->faker->randomElement(['cash', 'D-Money', 'waafi', 'cac', 'cheque']),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'remaining' => 0,
            'status_id' => $this->faker->numberBetween(2, 3),
            'user_id' => 1,
        ];
    }
}
