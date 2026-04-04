<?php

namespace Tests\Feature\Wishlist;

use App\Models\User;
use App\Models\Vinyle;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    // =====================================================
    // TESTS: Ajout aux favoris
    // =====================================================

    /**
     * Test: Un utilisateur connecté peut ajouter un vinyle à ses favoris
     */
    public function test_authenticated_user_can_add_vinyle_to_wishlist()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();

        $response = $this->actingAs($user)->post(route('wishlist.add'), [
            'vinyle_id' => $vinyle->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'vinyle_id' => $vinyle->id,
        ]);
    }

    /**
     * Test: Un visiteur non connecté ne peut pas ajouter aux favoris
     */
    public function test_guest_cannot_add_to_wishlist()
    {
        $vinyle = Vinyle::factory()->create();

        $response = $this->post(route('wishlist.add'), [
            'vinyle_id' => $vinyle->id,
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('wishlists', 0);
    }

    /**
     * Test: Impossible d'ajouter deux fois le même vinyle aux favoris
     */
    public function test_cannot_add_same_vinyle_twice_to_wishlist()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();

        // Premier ajout
        $this->actingAs($user)->post(route('wishlist.add'), [
            'vinyle_id' => $vinyle->id,
        ]);

        // Deuxième ajout
        $response = $this->actingAs($user)->post(route('wishlist.add'), [
            'vinyle_id' => $vinyle->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('wishlists', 1);
    }

    /**
     * Test: Le vinyle_id est requis pour ajouter aux favoris
     */
    public function test_vinyle_id_is_required_to_add_wishlist()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('wishlist.add'), []);

        $response->assertSessionHasErrors('vinyle_id');
    }

    /**
     * Test: Le vinyle_id doit exister dans la table vinyles
     */
    public function test_vinyle_id_must_exist_in_database()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('wishlist.add'), [
            'vinyle_id' => 99999,
        ]);

        $response->assertSessionHasErrors('vinyle_id');
    }

    // =====================================================
    // TESTS: Suppression des favoris
    // =====================================================

    /**
     * Test: Un utilisateur peut retirer un vinyle de ses favoris
     */
    public function test_user_can_remove_vinyle_from_wishlist()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();
        
        Wishlist::factory()->create([
            'user_id' => $user->id,
            'vinyle_id' => $vinyle->id,
        ]);

        $response = $this->actingAs($user)->delete(route('wishlist.remove', $vinyle));

        $response->assertRedirect();
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'vinyle_id' => $vinyle->id,
        ]);
    }

    /**
     * Test: Un utilisateur ne peut pas supprimer un favori d'un autre
     */
    public function test_user_cannot_remove_other_user_wishlist_item()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $vinyle = Vinyle::factory()->create();
        
        Wishlist::factory()->create([
            'user_id' => $user1->id,
            'vinyle_id' => $vinyle->id,
        ]);

        $response = $this->actingAs($user2)->delete(route('wishlist.remove', $vinyle));

        $response->assertForbidden();
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user1->id,
            'vinyle_id' => $vinyle->id,
        ]);
    }

    // =====================================================
    // TESTS: Liste des favoris
    // =====================================================

    /**
     * Test: Un utilisateur peut voir sa liste de favoris
     */
    public function test_user_can_view_wishlist_page()
    {
        $user = User::factory()->create();
        $vinyle1 = Vinyle::factory()->create();
        $vinyle2 = Vinyle::factory()->create();
        
        Wishlist::factory()->create([
            'user_id' => $user->id,
            'vinyle_id' => $vinyle1->id,
        ]);
        Wishlist::factory()->create([
            'user_id' => $user->id,
            'vinyle_id' => $vinyle2->id,
        ]);

        $response = $this->actingAs($user)->get(route('wishlist.index'));

        $response->assertStatus(200);
        $response->assertViewIs('wishlist.index');
        $response->assertSee($vinyle1->nom_complet);
        $response->assertSee($vinyle2->nom_complet);
    }

    /**
     * Test: Un visiteur non connecté est redirigé vers login
     */
    public function test_guest_is_redirected_to_login_when_accessing_wishlist()
    {
        $response = $this->get(route('wishlist.index'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test: La wishlist affiche un message quand elle est vide
     */
    public function test_wishlist_shows_empty_message_when_no_items()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('wishlist.index'));

        $response->assertStatus(200);
        $response->assertSee('Aucun favori');
    }

    /**
     * Test: Un utilisateur ne voit que ses propres favoris
     */
    public function test_user_only_sees_own_wishlist_items()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $vinyle1 = Vinyle::factory()->create();
        $vinyle2 = Vinyle::factory()->create();
        
        Wishlist::factory()->create([
            'user_id' => $user1->id,
            'vinyle_id' => $vinyle1->id,
        ]);
        Wishlist::factory()->create([
            'user_id' => $user2->id,
            'vinyle_id' => $vinyle2->id,
        ]);

        $response = $this->actingAs($user1)->get(route('wishlist.index'));

        $response->assertSee($vinyle1->nom_complet);
        $response->assertDontSee($vinyle2->nom_complet);
    }

    // =====================================================
    // TESTS: Déplacer vers le panier
    // =====================================================

    /**
     * Test: Un utilisateur peut déplacer un favori vers le panier
     */
    public function test_user_can_move_wishlist_item_to_cart()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create(['quantite' => 10, 'prix' => 25.99]);
        
        Wishlist::factory()->create([
            'user_id' => $user->id,
            'vinyle_id' => $vinyle->id,
        ]);

        $response = $this->actingAs($user)->post(route('wishlist.to-cart', $vinyle));

        $response->assertRedirect();
        
        // Vérifie que le vinyle est dans le panier
        $this->assertDatabaseHas('cart_items', [
            'vinyle_id' => $vinyle->id,
        ]);
        
        // Vérifie que le favori est supprimé
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'vinyle_id' => $vinyle->id,
        ]);
    }

    /**
     * Test: Message de confirmation après déplacement vers le panier
     */
    public function test_user_sees_confirmation_after_moving_to_cart()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create(['quantite' => 10]);
        
        Wishlist::factory()->create([
            'user_id' => $user->id,
            'vinyle_id' => $vinyle->id,
        ]);

        $response = $this->actingAs($user)->followingRedirects()->post(route('wishlist.to-cart', $vinyle));

        $response->assertSee('ajouté à votre panier');
    }

    /**
     * Test: Ne peut pas déplacer vers panier si rupture de stock
     */
    public function test_cannot_move_to_cart_if_out_of_stock()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create(['quantite' => 0]);
        
        Wishlist::factory()->create([
            'user_id' => $user->id,
            'vinyle_id' => $vinyle->id,
        ]);

        $response = $this->actingAs($user)->post(route('wishlist.to-cart', $vinyle));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        
        // Le favori doit toujours exister
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'vinyle_id' => $vinyle->id,
        ]);
    }

    /**
     * Test: Ne peut pas déplacer le favori d'un autre vers son panier
     */
    public function test_user_cannot_move_other_user_wishlist_to_cart()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $vinyle = Vinyle::factory()->create(['quantite' => 10]);
        
        Wishlist::factory()->create([
            'user_id' => $user1->id,
            'vinyle_id' => $vinyle->id,
        ]);

        $response = $this->actingAs($user2)->post(route('wishlist.to-cart', $vinyle));

        $response->assertForbidden();
    }

    // =====================================================
    // TESTS: Modèle Wishlist
    // =====================================================

    /**
     * Test: Une wishlist appartient à un utilisateur
     */
    public function test_wishlist_belongs_to_user()
    {
        $user = User::factory()->create();
        $wishlist = Wishlist::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $wishlist->user);
        $this->assertEquals($user->id, $wishlist->user->id);
    }

    /**
     * Test: Une wishlist appartient à un vinyle
     */
    public function test_wishlist_belongs_to_vinyle()
    {
        $vinyle = Vinyle::factory()->create();
        $wishlist = Wishlist::factory()->create(['vinyle_id' => $vinyle->id]);

        $this->assertInstanceOf(Vinyle::class, $wishlist->vinyle);
        $this->assertEquals($vinyle->id, $wishlist->vinyle->id);
    }

    /**
     * Test: La méthode isInWishlist retourne true si le vinyle est en favori
     */
    public function test_is_in_wishlist_returns_true_when_vinyle_is_wishlisted()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();
        
        Wishlist::factory()->create([
            'user_id' => $user->id,
            'vinyle_id' => $vinyle->id,
        ]);

        $this->assertTrue(Wishlist::isInWishlist($user->id, $vinyle->id));
    }

    /**
     * Test: La méthode isInWishlist retourne false si le vinyle n'est pas en favori
     */
    public function test_is_in_wishlist_returns_false_when_vinyle_is_not_wishlisted()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();

        $this->assertFalse(Wishlist::isInWishlist($user->id, $vinyle->id));
    }
}