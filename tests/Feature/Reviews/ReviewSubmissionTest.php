<?php

namespace Tests\Feature\Reviews;

use App\Models\User;
use App\Models\Vinyle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Un utilisateur connecté peut soumettre un avis
     */
    public function test_authenticated_user_can_submit_review()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();

        $response = $this->actingAs($user)->post(route('kiosque.reviews.store', $vinyle), [
            'rating' => 5,
            'comment' => 'Très bon vinyle, je recommande !',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'vinyle_id' => $vinyle->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Très bon vinyle, je recommande !',
            'status' => 'pending',
        ]);
    }

    /**
     * Test: Un visiteur non connecté ne peut pas soumettre d'avis
     */
    public function test_guest_cannot_submit_review()
    {
        $vinyle = Vinyle::factory()->create();

        $response = $this->post(route('kiosque.reviews.store', $vinyle), [
            'rating' => 5,
            'comment' => 'Test comment',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test: La note est obligatoire
     */
    public function test_rating_is_required()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();

        $response = $this->actingAs($user)->post(route('kiosque.reviews.store', $vinyle), [
            'comment' => 'Test sans note',
        ]);

        $response->assertSessionHasErrors('rating');
    }

    /**
     * Test: La note doit être entre 1 et 5
     */
    public function test_rating_must_be_between_1_and_5()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();

        $response = $this->actingAs($user)->post(route('kiosque.reviews.store', $vinyle), [
            'rating' => 6,
            'comment' => 'Test note invalide',
        ]);

        $response->assertSessionHasErrors('rating');
    }

    /**
     * Test: Un utilisateur ne peut pas commenter deux fois le même vinyle
     */
    public function test_user_cannot_review_same_vinyle_twice()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();
        
        // Premier avis
        $this->actingAs($user)->post(route('kiosque.reviews.store', $vinyle), [
            'rating' => 5,
            'comment' => 'Premier avis',
        ]);

        // Deuxième avis (devrait échouer et rediriger avec message d'erreur)
        $response = $this->actingAs($user)->followingRedirects()->post(route('kiosque.reviews.store', $vinyle), [
            'rating' => 4,
            'comment' => 'Deuxième avis',
        ]);

        $response->assertSee('Vous avez déjà laissé un avis');
        $this->assertDatabaseCount('reviews', 1);
    }

    /**
     * Test: Le commentaire est optionnel mais limité à 1000 caractères
     */
    public function test_comment_is_optional_but_limited_to_1000_chars()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();

        // Sans commentaire (OK)
        $response = $this->actingAs($user)->post(route('kiosque.reviews.store', $vinyle), [
            'rating' => 4,
        ]);

        $response->assertRedirect();

        // Avec commentaire trop long
        $vinyle2 = Vinyle::factory()->create();
        $response2 = $this->actingAs($user)->post(route('kiosque.reviews.store', $vinyle2), [
            'rating' => 4,
            'comment' => str_repeat('a', 1001),
        ]);

        $response2->assertSessionHasErrors('comment');
    }

    /**
     * Test: L'avis est créé avec le statut 'pending' par défaut
     */
    public function test_review_is_created_with_pending_status()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();

        $this->actingAs($user)->post(route('kiosque.reviews.store', $vinyle), [
            'rating' => 5,
            'comment' => 'En attente de modération',
        ]);

        $this->assertDatabaseHas('reviews', [
            'vinyle_id' => $vinyle->id,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test: Message de confirmation après soumission
     */
    public function test_user_sees_confirmation_after_submitting_review()
    {
        $user = User::factory()->create();
        $vinyle = Vinyle::factory()->create();

        $response = $this->actingAs($user)->followingRedirects()->post(route('kiosque.reviews.store', $vinyle), [
            'rating' => 5,
            'comment' => 'Mon avis',
        ]);

        $response->assertSee('Votre avis a été soumis');
    }
}
