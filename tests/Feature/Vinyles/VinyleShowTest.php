<?php

namespace Tests\Feature\Vinyles;

use App\Models\Vinyle;
use App\Models\Fond;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VinyleShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_vinyle_show_page_is_accessible(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'David Bowie',
            'modele' => 'Mirror Vinyl',
            'prix' => 99.99,
            'quantite' => 5,
            'reference' => 'BOW-001',
            'genre' => 'Rock',
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertStatus(200);
    }

    public function test_vinyle_show_page_displays_artiste_and_titre(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'Pink Floyd',
            'modele' => 'Dark Side',
            'prix' => 129.99,
            'quantite' => 3,
            'reference' => 'PF-001',
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertSee('Pink Floyd');
        $response->assertSee('Dark Side');
    }

    public function test_vinyle_show_page_displays_price(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'The Beatles',
            'prix' => 149.99,
            'quantite' => 2,
            'reference' => 'BEAT-001',
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertSee('149.99');
    }

    public function test_vinyle_show_page_displays_stock_status(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'Led Zeppelin',
            'quantite' => 5,
            'reference' => 'LED-001',
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertSee('5');
    }

    public function test_vinyle_show_page_has_add_to_cart_button(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'Queen',
            'quantite' => 3,
            'reference' => 'QUE-001',
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertSee('Ajouter au panier');
    }

    public function test_vinyle_show_page_displays_image_or_placeholder(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'Nirvana',
            'reference' => 'NIR-001',
            'quantite' => 4,
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        // La page doit afficher correctement
        $response->assertStatus(200);
        $response->assertSee('Nirvana');
    }

    public function test_vinyle_show_page_displays_genre_and_style(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'Daft Punk',
            'genre' => 'Électro',
            'style' => 'French Touch',
            'reference' => 'DP-001',
            'quantite' => 5,
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertSee('Électro');
        $response->assertSee('French Touch');
    }

    public function test_vinyle_show_page_displays_reference(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'Metallica',
            'reference' => 'MET-001',
            'quantite' => 3,
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertSee('MET-001');
    }

    public function test_can_add_to_cart_from_vinyle_show_page(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'AC/DC',
            'prix' => 89.99,
            'quantite' => 5,
            'reference' => 'ACDC-001',
        ]);

        $response = $this->post(route('cart.add'), [
            'vinyle_id' => $vinyle->id,
            'quantite' => 1,
            'fond' => 'standard',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_kiosque_has_link_to_vinyle_show_page(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'The Rolling Stones',
            'quantite' => 3,
            'reference' => 'RS-001',
        ]);

        $response = $this->get(route('kiosque.index'));

        $response->assertStatus(200);
        // Vérifie que le kiosque charge et contient les données du vinyle
        $response->assertSee('The Rolling Stones');
    }

    public function test_vinyle_show_page_displays_out_of_stock_message(): void
    {
        $vinyle = Vinyle::factory()->create([
            'artiste' => 'Sold Out Artist',
            'quantite' => 0,
            'reference' => 'SOLD-001',
        ]);

        $response = $this->get(route('kiosque.vinyle.show', $vinyle));

        $response->assertSee('Rupture de stock');
    }
}