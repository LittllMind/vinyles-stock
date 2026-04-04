<?php

namespace Tests\Feature\Newsletter;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\NewsletterSubscriber;

class NewsletterSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST T3.1.1: Un visiteur peut s'inscrire à la newsletter
     */
    public function test_visitor_can_subscribe_to_newsletter(): void
    {
        $response = $this->postJson('/api/newsletter/subscribe', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Inscription initiée. Veuillez confirmer votre email.',
            ]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'test@example.com',
            'confirmed' => false,
        ]);
    }

    /**
     * TEST T3.1.2: L'email doit être valide
     */
    public function test_subscription_requires_valid_email(): void
    {
        $response = $this->postJson('/api/newsletter/subscribe', [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * TEST T3.1.3: L'email est requis
     */
    public function test_subscription_requires_email(): void
    {
        $response = $this->postJson('/api/newsletter/subscribe', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * TEST T3.1.4: Un token de confirmation est généré à l'inscription
     */
    public function test_subscription_generates_confirmation_token(): void
    {
        $this->postJson('/api/newsletter/subscribe', [
            'email' => 'test@example.com',
        ]);

        $subscriber = NewsletterSubscriber::where('email', 'test@example.com')->first();

        $this->assertNotNull($subscriber->confirmation_token);
        $this->assertEquals(64, strlen($subscriber->confirmation_token));
    }

    /**
     * TEST T3.1.5: Un visiteur peut confirmer son inscription avec le token
     */
    public function test_visitor_can_confirm_subscription_with_token(): void
    {
        $subscriber = NewsletterSubscriber::factory()->unconfirmed()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->get("/newsletter/confirm/{$subscriber->confirmation_token}");

        $response->assertRedirect('/')
            ->assertSessionHas('success', 'Votre inscription à la newsletter est confirmée !');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'test@example.com',
            'confirmed' => true,
        ]);
    }

    /**
     * TEST T3.1.6: Un token invalide redirige avec erreur
     */
    public function test_invalid_token_redirects_with_error(): void
    {
        $response = $this->get('/newsletter/confirm/invalid-token');

        $response->assertRedirect('/')
            ->assertSessionHas('error', 'Lien de confirmation invalide ou expiré.');
    }

    /**
     * TEST T3.1.7: Un visiteur peut se désinscrire
     */
    public function test_visitor_can_unsubscribe(): void
    {
        $subscriber = NewsletterSubscriber::factory()->confirmed()->create([
            'email' => 'test@example.com',
            'unsubscribe_token' => 'unsubscribe-token-123',
        ]);

        $response = $this->get("/newsletter/unsubscribe/{$subscriber->unsubscribe_token}");

        $response->assertRedirect('/')
            ->assertSessionHas('info', 'Vous avez été désinscrit de la newsletter.');

        $this->assertDatabaseMissing('newsletter_subscribers', [
            'email' => 'test@example.com',
        ]);
    }

    /**
     * TEST T3.1.8: Un email déjà confirmé ne peut pas s'inscrire à nouveau
     */
    public function test_confirmed_email_cannot_subscribe_again(): void
    {
        NewsletterSubscriber::factory()->confirmed()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->postJson('/api/newsletter/subscribe', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Cet email est déjà inscrit à la newsletter.',
            ]);
    }

    /**
     * TEST T3.1.9: Un email non confirmé peut relancer la confirmation
     */
    public function test_unconfirmed_email_can_resend_confirmation(): void
    {
        $subscriber = NewsletterSubscriber::factory()->unconfirmed()->create([
            'email' => 'test@example.com',
        ]);
        $oldToken = $subscriber->confirmation_token;

        $response = $this->postJson('/api/newsletter/subscribe', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Un nouvel email de confirmation a été envoyé.',
            ]);

        $subscriber->refresh();
        $this->assertNotEquals($oldToken, $subscriber->confirmation_token);
    }
}
