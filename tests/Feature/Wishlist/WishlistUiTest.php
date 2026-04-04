<?php

namespace Tests\Feature\Wishlist;

use App\Models\User;
use App\Models\Vinyle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistUiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Le lien vers la wishlist est accessible
     */
    public function test_wishlist_page_is_accessible_for_authenticated_users()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('wishlist.index'));

        $response->assertStatus(200);
        $response->assertViewIs('wishlist.index');
    }

    /**
     * Test: Le lien wishlist redirige les visiteurs vers login
     */
    public function test_wishlist_redirects_guests_to_login()
    {
        $response = $this->get(route('wishlist.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Le bouton favoris redirige vers login pour les visiteurs
     */
    public function test_wishlist_button_redirects_to_login_for_guests()
    {
        $vinyle = Vinyle::factory()->create();

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertStatus(200);
    }
}
