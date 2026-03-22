<?php

namespace Database\Factories;

use App\Models\Bougie;
use App\Models\MouvementStock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MouvementStockFactory extends Factory
{
    protected $model = MouvementStock::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['entree', 'sortie']);
        $stockable = Bougie::inRandomOrder()->first() ?? Bougie::factory()->create();

        return [
            'stockable_type' => Bougie::class,
            'stockable_id' => $stockable->id,
            'type' => $type,
            'quantite' => $this->faker->numberBetween(1, 50),
            'raison' => $this->faker->optional()->sentence(),
            'user_id' => User::inRandomOrder()->first() ?? User::factory(),
        ];
    }

    public function entree(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'entree',
        ]);
    }

    public function sortie(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'sortie',
        ]);
    }
}