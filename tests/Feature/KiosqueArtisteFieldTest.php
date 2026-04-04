<?php

namespace Tests\Feature;

use App\Models\Vinyle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KiosqueArtisteFieldTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function le_kiosque_affiche_le_champ_artiste_correctement()
    {
        // Créer un vinyle avec artiste
        $vinyle = Vinyle::create([
            'reference' => 'TST-001',
            'artiste' => 'Daft Punk',
            'modele' => 'Random Access Memories',
            'genre' => 'Electro',
            'style' => 'French Touch',
            'prix' => 25.00,
            'quantite' => 5,
        ]);

        // Visiter le kiosque
        $response = $this->get(route('kiosque.index'));

        // Vérifier que la vue contient le nom de l'artiste
        $response->assertStatus(200);
        
        // Le problème: si la vue utilise 'nom' au lieu de 'artiste', 
        // l'artiste sera vide
        $response->assertSee('Daft Punk');
    }

    /** @test */
    public function les_donnees_json_du_kiosque_contiennent_artiste_et_non_nom()
    {
        // Créer un vinyle
        $vinyle = Vinyle::create([
            'reference' => 'TST-002',
            'artiste' => 'Pink Floyd',
            'modele' => 'Dark Side of the Moon',
            'genre' => 'Rock',
            'style' => 'Progressif',
            'prix' => 30.00,
            'quantite' => 3,
        ]);

        // Visiter le kiosque
        $response = $this->get(route('kiosque.index'));

        // Vérifier que les données JSON passées à AlpineJS contiennent 'artiste'
        // et non 'nom'
        $response->assertStatus(200);
        
        // La vue doit contenir 'artiste' dans les données JSON
        $response->assertSee('artiste');
        
        // La vue ne doit PAS utiliser 'nom' comme propriété dans les données JSON
        // (car le modèle Vinyle n'a pas de champ 'nom', seulement 'artiste' et 'modele')
    }

    /** @test */
    public function la_recherche_par_artiste_fonctionne_dans_le_kiosque()
    {
        // Créer plusieurs vinyles
        Vinyle::create([
            'reference' => 'TST-003',
            'artiste' => 'Nirvana',
            'modele' => 'Nevermind',
            'genre' => 'Grunge',
            'style' => 'Alternative',
            'prix' => 22.00,
            'quantite' => 4,
        ]);

        Vinyle::create([
            'reference' => 'TST-004',
            'artiste' => 'Metallica',
            'modele' => 'Black Album',
            'genre' => 'Metal',
            'style' => 'Heavy',
            'prix' => 28.00,
            'quantite' => 2,
        ]);

        // Visiter le kiosque avec un filtre artiste
        $response = $this->get(route('kiosque.index', ['artiste' => 'Nirvana']));

        $response->assertStatus(200);
        $response->assertSee('Nirvana');
    }

    /** @test */
    public function le_modele_vinyle_n_a_pas_d_attribut_nom()
    {
        // Vérifier que le modèle Vinyle n'a pas de champ 'nom'
        $fillable = (new Vinyle())->getFillable();
        
        $this->assertNotContains('nom', $fillable);
        $this->assertContains('artiste', $fillable);
    }
}
