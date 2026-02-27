<?php

namespace Database\Factories;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        $kategori = $this->faker->randomElement(['regu', 'open']);

        return [
            'uuid'          => Str::uuid(),
            'nama'          => $this->faker->name(),
            'tim_pb'        => 'PB ' . $this->faker->city(),
            'kategori'      => $kategori,
            'harga'         => $kategori === 'regu' ? 200000 : 150000,
            'status'        => $this->faker->randomElement(['pending', 'paid', 'paid', 'paid', 'failed', 'expired']),
            'midtrans_order_id' => 'BO2026-' . strtoupper(Str::random(8)),
            'midtrans_transaction_id' => $this->faker->optional()->uuid(),
            'payment_type'  => $this->faker->optional()->randomElement(['bank_transfer', 'gopay', 'qris', 'credit_card']),
            'payment_time'  => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn() => [
            'status'       => 'paid',
            'payment_type' => $this->faker->randomElement(['bank_transfer', 'gopay', 'qris']),
            'payment_time' => $this->faker->dateTimeBetween('-7 days', 'now'),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn() => ['status' => 'pending']);
    }
}