<?php

namespace Tests\Feature\Reviews;

use App\Models\User;
use App\Models\Vinyle;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewDisplayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Un visiteur peut voir les avis approuvés sur une fiche vinyle
     */
    public function test_visitor_can_see_approved_reviews_on_vinyle_page()
    {
        $vinyle = Vinyle::factory()->create();
        $user = User::factory()->create();
        
        Review::factory()->approved()->create([
            'vinyle_id' => $vinyle->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Excellent vinyle, qualité top !',
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertStatus(200);
        $response->assertSee('Excellent vinyle, qualité top !');
        $response->assertSee('5');
    }

    /**
     * Test: Les avis en attente de modération ne sont pas visibles
     */
    public function test_pending_reviews_are_not_visible_to_visitors()
    {
        $vinyle = Vinyle::factory()->create();
        $user = User::factory()->create();
        
        Review::factory()->pending()->create([
            'vinyle_id' => $vinyle->id,
            'user_id' => $user->id,
            'comment' => 'Ce commentaire est en attente',
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertDontSee('Ce commentaire est en attente');
    }

    /**
     * Test: Les avis rejetés ne sont pas visibles
     */
    public function test_rejected_reviews_are_not_visible()
    {
        $vinyle = Vinyle::factory()->create();
        $user = User::factory()->create();
        
        Review::factory()->rejected()->create([
            'vinyle_id' => $vinyle->id,
            'user_id' => $user->id,
            'comment' => 'Ce commentaire est rejeté',
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertDontSee('Ce commentaire est rejeté');
    }

    /**
     * Test: La note moyenne est affichée sur la fiche
     */
    public function test_average_rating_is_displayed_on_vinyle_page()
    {
        $vinyle = Vinyle::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Review::factory()->approved()->create([
            'vinyle_id' => $vinyle->id,
            'user_id' => $user1->id,
            'rating' => 5,
        ]);
        
        Review::factory()->approved()->create([
            'vinyle_id' => $vinyle->id,
            'user_id' => $user2->id,
            'rating' => 3,
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertStatus(200);
        $response->assertSee('4'); // Note moyenne (5+3)/2
    }

    /**
     * Test: Le nombre total d'avis est affiché
     */
    public function test_total_reviews_count_is_displayed()
    {
        $vinyle = Vinyle::factory()->create();
        $user1 = User::factory()->create();
        
        Review::factory()->approved()->create([
            'vinyle_id' => $vinyle->id,
            'user_id' => $user1->id,
        ]);
        
        Review::factory()->approved()->create([
            'vinyle_id' => $vinyle->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertStatus(200);
        // On vérifie qu'il y a une section "Avis clients" avec 2 avis affichés
        $response->assertSee('Avis clients');
    }

    /**
     * Test: Les avis sont triés par date décroissante
     */
    public function test_reviews_are_sorted_by_date_descending()
    {
        $vinyle = Vinyle::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $review1 = Review::factory()->approved()->create([
            'vinyle_id' => $vinyle->id,
            'user_id' => $user1->id,
            'comment' => 'Ancien commentaire',
            'created_at' => now()->subDays(2),
        ]);
        
        $review2 = Review::factory()->approved()->create([
            'vinyle_id' => $vinyle->id,
            'user_id' => $user2->id,
            'comment' => 'Commentaire récent',
            'created_at' => now()->subDay(),
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        // Le plus récent en premier
        $response->assertSeeInOrder(['Commentaire récent', 'Ancien commentaire']);
    }
}
