<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vinyle;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    protected $model = Wishlist::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'vinyle_id' => Vinyle::factory(),
        ];
    }
}
