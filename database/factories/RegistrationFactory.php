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
            'harga'         => $kategori === 'regu' ? 1000000 : 400000,
            'status'        => $this->faker->randomElement(['pending', 'pending_verification', 'paid', 'paid', 'paid', 'failed', 'expired']),
            'payment_verified_at' => $this->faker->optional()->dateTimeBetween('-30 days', 'now'),
            'payment_verified_by' => $this->faker->optional()->numberBetween(1, 10),
            'payment_note' => $this->faker->optional()->sentence(),
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