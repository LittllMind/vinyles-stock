<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    /**
     * Test que la page FAQ est accessible
     */
    public function test_faq_page_is_accessible(): void
    {
        $response = $this->get('/faq');

        $response->assertStatus(200);
    }

    /**
     * Test que la page FAQ affiche le titre correct
     */
    public function test_faq_page_displays_title(): void
    {
        $response = $this->get('/faq');

        $response->assertSee('FAQ');
        $response->assertSee('Questions fréquentes');
    }

    /**
     * Test que la FAQ contient des questions/réponses
     */
    public function test_faq_page_contains_questions_and_answers(): void
    {
        $response = $this->get('/faq');

        // Questions minimum attendues
        $response->assertSee('Comment');
        $response->assertSee('?');
    }

    /**
     * Test que la page FAQ utilise le layout principal
     */
    public function test_faq_page_uses_main_layout(): void
    {
        $response = $this->get('/faq');

        $response->assertViewIs('faq');
    }

    /**
     * Test que la FAQ est accessible depuis la landing
     */
    public function test_faq_link_exists_in_navigation(): void
    {
        $response = $this->get('/');

        $response->assertSee(route('faq'));
    }
}
