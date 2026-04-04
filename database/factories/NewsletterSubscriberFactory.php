<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\NewsletterSubscriber;

class NewsletterSubscriberFactory extends Factory
{
    protected $model = NewsletterSubscriber::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'confirmed' => false,
            'confirmed_at' => null,
            'confirmation_token' => bin2hex(random_bytes(32)),
            'unsubscribe_token' => bin2hex(random_bytes(32)),
        ];
    }

    public function confirmed(): self
    {
        return $this->state(fn (array $attributes) => [
            'confirmed' => true,
            'confirmed_at' => now(),
        ]);
    }

    public function unconfirmed(): self
    {
        return $this->state(fn (array $attributes) => [
            'confirmed' => false,
            'confirmed_at' => null,
        ]);
    }
}
