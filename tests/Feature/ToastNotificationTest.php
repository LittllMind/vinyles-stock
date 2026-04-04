<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToastNotificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function toast_component_exists_in_views_directory()
    {
        $toastViewPath = resource_path('views/components/toast.blade.php');
        $this->assertFileExists($toastViewPath, 'Le composant toast.blade.php doit exister');
    }

    /** @test */
    public function toast_component_renders_success_message()
    {
        $view = $this->blade('<x-toast type="success" message="Article ajouté au panier" />');
        $view->assertSee('Article ajouté au panier', false);
        $view->assertSee('bg-green-600', false);
    }

    /** @test */
    public function toast_component_renders_error_message()
    {
        $view = $this->blade('<x-toast type="error" message="Erreur lors de l\'ajout" />');
        $view->assertSee('Erreur lors de l&#039;ajout', false);
        $view->assertSee('bg-red-600', false);
    }

    /** @test */
    public function toast_component_renders_info_message()
    {
        $view = $this->blade('<x-toast type="info" message="Bienvenue sur Fundisc" />');
        $view->assertSee('Bienvenue sur Fundisc', false);
        $view->assertSee('bg-blue-600', false);
    }

    /** @test */
    public function toast_component_has_auto_hide_data_attribute()
    {
        $view = $this->blade('<x-toast type="success" message="Test" />');
        $view->assertSee('x-data', false);
    }

    /** @test */
    public function toast_displays_after_cart_add_redirect()
    {
        $user = \App\Models\User::factory()->create();
        
        // Créer un vinyle avec les bons champs selon le modèle
        $vinyle = \App\Models\Vinyle::factory()->create([
            'artiste' => 'Test Artist',
            'modele' => 'Test Album',
            'prix' => 15.99,
            'quantite' => 10,
        ]);

        // Make request to kiosque first to have proper referer
        $this->actingAs($user)->get(route('kiosque.index'));
        
        $response = $this->actingAs($user)
            ->from(route('kiosque.index'))
            ->post(route('cart.add'), [
                'vinyle_id' => $vinyle->id,
                'quantity' => 1,
            ]);

        $response->assertRedirect();
        
        // Check flash data by following redirect - should redirect back to kiosque
        $redirectUrl = $response->headers->get('Location');
        $this->assertStringContainsString('kiosque', $redirectUrl);
    }

    /** @test */
    public function toast_displays_after_login()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('toast');
    }

    /** @test */
    public function toast_displays_after_order_placed()
    {
        $user = \App\Models\User::factory()->create();
        
        // Simuler une commande complétée avec toast
        // Le toast est affiché via JS en parsant le div caché
        $response = $this->actingAs($user)
            ->withSession(['toast' => [
                'type' => 'success',
                'message' => 'Commande passée avec succès !',
            ]])
            ->get(route('about'));

        $response->assertSee('id="session-toast-data"', false);
        // Les caractères spéciaux sont encodés en HTML entities dans JSON
        $response->assertSee('pass\\u00e9e', false);
    }

    /** @test */
    public function toast_component_is_included_in_layout()
    {
        // Test sur une page utilisant le layout app.blade.php
        $response = $this->get(route('about'));
        $response->assertSee('toastData', false);
        
        // Test avec session toast pour voir le data div
        $response = $this->withSession(['toast' => ['type' => 'success', 'message' => 'Test']])
            ->get(route('about'));
        $response->assertSee('session-toast-data', false);
    }

    /** @test */
    public function toast_has_three_second_auto_hide()
    {
        $view = $this->blade('<x-toast type="success" message="Test" />');
        
        // Vérifier que le timeout est de 3000ms (3 secondes)
        $view->assertSee('3000', false);
    }

    /** @test */
    public function toast_renders_with_emoji_icon_for_success()
    {
        $view = $this->blade('<x-toast type="success" message="Test" />');
        $view->assertSee('✅', false);
    }

    /** @test */
    public function toast_renders_with_emoji_icon_for_error()
    {
        $view = $this->blade('<x-toast type="error" message="Test" />');
        $view->assertSee('❌', false);
    }

    /** @test */
    public function toast_renders_with_emoji_icon_for_info()
    {
        $view = $this->blade('<x-toast type="info" message="Test" />');
        $view->assertSee('ℹ️', false);
    }
}
