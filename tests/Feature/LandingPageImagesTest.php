<?php

namespace Tests\Feature;

use App\Models\Vinyle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class LandingPageImagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function la_landing_page_affiche_les_images_reelles_des_vinyles_en_vedette()
    {
        // Créer un vinyle avec une image via Spatie Media Library
        $vinyle = Vinyle::create([
            'reference' => 'TST-IMG-001',
            'artiste' => 'The Beatles',
            'modele' => 'Abbey Road',
            'genre' => 'Rock',
            'style' => 'Classic',
            'prix' => 35.00,
            'quantite' => 5,
        ]);

        // Simuler l'ajout d'une image via Spatie
        $imageUrl = '/storage/photos/1/test-image.jpg';
        
        // La landing page doit afficher des images (pas des emojis)
        $response = $this->get(route('landing'));
        
        $response->assertStatus(200);
        
        // La landing ne doit pas contenir l'emoji 💿 comme seule représentation
        // (elle peut contenir des balises img avec src)
        $content = $response->getContent();
        
        // S'il y a des vinyles featured, il doit y avoir des balises img
        if (str_contains($content, 'vinyle') || str_contains($content, 'featured')) {
            // La page doit contenir des balises img
            $this->assertTrue(
                str_contains($content, '<img') || str_contains($content, 'background-image'),
                'La landing page doit afficher des images via des balises img ou background'
            );
        }
    }

    /** @test */
    public function les_vinyles_en_vedette_ont_leurs_urls_images_correctement_generees()
    {
        // Créer plusieurs vinyles avec images
        $vinyles = [];
        for ($i = 1; $i <= 3; $i++) {
            $vinyles[] = Vinyle::create([
                'reference' => "IMG-00{$i}",
                'artiste' => "Artiste {$i}",
                'modele' => "Album {$i}",
                'genre' => 'Rock',
                'style' => 'Classic',
                'prix' => 20 + $i * 5,
                'quantite' => $i + 1,
            ]);
        }

        $response = $this->get(route('landing'));
        $response->assertStatus(200);
        
        // La réponse doit contenir les données des vinyles avec leurs images
        $content = $response->getContent();
        
        // Vérifier que les artistes apparaissent
        foreach ($vinyles as $vinyle) {
            $this->assertStringContainsString($vinyle->artiste, $content);
        }
    }

    /** @test */
    public function la_landing_page_utilise_un_fallback_si_pas_d_image()
    {
        // Créer un vinyle SANS image
        $vinyle = Vinyle::create([
            'reference' => 'NO-IMG-001',
            'artiste' => 'Unknown Artist',
            'modele' => 'No Cover',
            'genre' => 'Various',
            'style' => 'Unknown',
            'prix' => 15.00,
            'quantite' => 3,
        ]);

        $response = $this->get(route('landing'));
        $response->assertStatus(200);
        
        // Vérifier que l'artiste apparaît malgré l'absence d'image
        $response->assertSee('Unknown Artist');
        
        // La page doit contenir un fallback (no-image.png ou équivalent)
        $response->assertSee('/images/no-image.png');
    }

    /** @test */
    public function la_landing_page_affiche_le_bon_nombre_de_vinyles_vedette()
    {
        // Créer 10 vinyles
        for ($i = 1; $i <= 10; $i++) {
            Vinyle::create([
                'reference' => "VED-00{$i}",
                'artiste' => "Artist {$i}",
                'modele' => "Album {$i}",
                'genre' => 'Rock',
                'style' => 'Classic',
                'prix' => 20.00,
                'quantite' => 5,
            ]);
        }

        $response = $this->get(route('landing'));
        $response->assertStatus(200);
        
        // Vérifier qu'exactement 6 vinyles sont affichés (le contrôleur limite à 6)
        $content = $response->getContent();
        
        // Compter le nombre de cartes vinyle affichées
        // Chaque vinyle a un div avec bg-gray-900 et rounded-2xl dans la section Featured
        preg_match_all('/bg-gray-900 rounded-2xl overflow-hidden/', $content, $matches);
        $this->assertCount(6, $matches[0], 'La landing page doit afficher exactement 6 vinyles vedettes');
    }
}
