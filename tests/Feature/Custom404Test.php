<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Custom404Test extends TestCase
{
    /**
     * Test qu'une URL inexistante retourne 404 custom
     */
    public function test_unknown_url_returns_custom_404_page(): void
    {
        $response = $this->get('/page-qui-n-existe-pas');

        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('morceau');
    }

    /**
     * Test que la page 404 affiche un message personnalisé
     */
    public function test_404_page_displays_custom_message(): void
    {
        $response = $this->get('/page-qui-n-existe-pas');

        $response->assertSee('Vinyl');
        $response->assertSee('404');
    }

    /**
     * Test que la page 404 contient des suggestions de navigation
     */
    public function test_404_page_contains_navigation_suggestions(): void
    {
        $response = $this->get('/page-qui-n-existe-pas');

        $response->assertSee('Accueil');
        $response->assertSee('Kiosque');
        $response->assertSee('Contact');
    }

    /**
     * Test que la page 404 contient des liens vers les sections principales
     */
    public function test_404_page_contains_links_to_main_sections(): void
    {
        $response = $this->get('/page-qui-n-existe-pas');

        $response->assertSee(route('landing'));
        $response->assertSee(route('kiosque.index'));
    }

    /**
     * Test que la page 404 utilise le design du thème vinyle
     */
    public function test_404_page_has_vinyl_theme_design(): void
    {
        $response = $this->get('/page-qui-n-existe-pas');

        // Style vinyl/dark theme indicators
        $response->assertSee('bg-slate');
    }
}
