<?php

namespace Tests\Feature\Newsletter;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\NewsletterSubscriber;

class NewsletterAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST T3.1.10: L'admin peut voir la liste des inscrits
     */
    public function test_admin_can_view_subscribers_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        NewsletterSubscriber::factory()->count(5)->confirmed()->create();
        NewsletterSubscriber::factory()->count(3)->unconfirmed()->create();

        $response = $this->actingAs($admin)->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->getJson('/api/admin/newsletter/subscribers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'email', 'confirmed', 'confirmed_at', 'created_at']
                ]
            ]);
    }

    /**
     * TEST T3.1.11: Un non-admin ne peut pas voir la liste
     */
    public function test_non_admin_cannot_view_subscribers_list(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->getJson('/api/admin/newsletter/subscribers');

        $response->assertStatus(403)
            ->assertJson(['error' => 'Accès refusé. Vous n\'avez pas les permissions nécessaires.']);
    }

    /**
     * TEST T3.1.12: L'admin peut exporter les inscrits en CSV
     */
    public function test_admin_can_export_confirmed_subscribers_csv(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        NewsletterSubscriber::factory()->count(3)->confirmed()->create();
        NewsletterSubscriber::factory()->count(2)->unconfirmed()->create();

        $response = $this->actingAs($admin)->get('/api/admin/newsletter/export');

        $response->assertStatus(200)
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->assertHeader('Content-Disposition', 'attachment; filename="newsletter-subscribers.csv"');

        $content = $response->streamedContent();
        $this->assertStringContainsString('Email', $content);
        $this->assertStringContainsString('Confirmé le', $content);
    }

    /**
     * TEST T3.1.13: L'admin peut supprimer un inscrit
     */
    public function test_admin_can_delete_subscriber(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $subscriber = NewsletterSubscriber::factory()->create(['email' => 'to-delete@example.com']);

        $response = $this->actingAs($admin)->actingAs($admin)->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->deleteJson("/api/admin/newsletter/subscribers/{$subscriber->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Inscrit supprimé.']);

        $this->assertDatabaseMissing('newsletter_subscribers', [
            'email' => 'to-delete@example.com',
        ]);
    }

    /**
     * TEST T3.1.14: L'export CSV contient uniquement les inscrits confirmés
     */
    public function test_csv_export_contains_only_confirmed_subscribers(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $confirmed = NewsletterSubscriber::factory()->create([
            'email' => 'confirmed@example.com',
            'confirmed' => true,
        ]);
        $unconfirmed = NewsletterSubscriber::factory()->create([
            'email' => 'unconfirmed@example.com',
            'confirmed' => false,
        ]);

        $response = $this->actingAs($admin)->get('/api/admin/newsletter/export');
        $content = $response->streamedContent();

        $this->assertStringContainsString('confirmed@example.com', $content);
        $this->assertStringNotContainsString('unconfirmed@example.com', $content);
    }
}
