<?php

namespace Tests\Feature;

use App\Models\Bougie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BougieControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_peut_voir_liste_avec_pagination()
    {
        Bougie::factory()->count(15)->create();

        $response = $this->get('/bougies');

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.index');
        $response->assertViewHas('bougies');
    }

    public function test_peut_voir_formulaire_creation()
    {
        $response = $this->get('/bougies/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.create');
    }

    public function test_peut_creer_une_bougie()
    {
        $data = [
            'reference' => 'BOUG-TEST-001',
            'parfum' => 'Vanille',
            'nom' => 'Bougie Test',
            'collection' => 'Luxe',
            'format' => '200g',
            'type_cire' => 'Soja',
            'temps_brulure' => 40,
            'prix' => 25.90,
            'quantite' => 10,
            'seuil_alerte' => 5,
        ];

        $response = $this->post('/bougies', $data);

        $response->assertRedirect('/bougies');
        $this->assertDatabaseHas('bougies', [
            'reference' => 'BOUG-TEST-001',
            'nom' => 'Bougie Test',
        ]);
    }

    public function test_requiert_reference_unique()
    {
        Bougie::factory()->create(['reference' => 'BOUG-DUP-001']);

        $response = $this->post('/bougies', [
            'reference' => 'BOUG-DUP-001',
            'parfum' => 'Rose',
            'nom' => 'Doublon',
            'prix' => 15.00,
        ]);

        $response->assertSessionHasErrors('reference');
    }

    public function test_peut_voir_details_bougie()
    {
        $bougie = Bougie::factory()->create();

        $response = $this->get("/bougies/{$bougie->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.show');
        $response->assertViewHas('bougie');
    }

    public function test_peut_voir_formulaire_edition()
    {
        $bougie = Bougie::factory()->create();

        $response = $this->get("/bougies/{$bougie->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.edit');
        $response->assertViewHas('bougie');
    }

    public function test_peut_modifier_une_bougie()
    {
        $bougie = Bougie::factory()->create(['nom' => 'Ancien Nom']);

        $response = $this->put("/bougies/{$bougie->id}", [
            'reference' => $bougie->reference,
            'parfum' => $bougie->parfum,
            'nom' => 'Nouveau Nom',
            'collection' => $bougie->collection ?? 'Standard',
            'format' => $bougie->format ?? '200g',
            'type_cire' => $bougie->type_cire ?? 'Soja',
            'temps_brulure' => $bougie->temps_brulure ?? 40,
            'prix' => $bougie->prix,
            'quantite' => $bougie->quantite,
            'seuil_alerte' => $bougie->seuil_alerte,
        ]);

        $response->assertRedirect('/bougies');
        $this->assertDatabaseHas('bougies', [
            'id' => $bougie->id,
            'nom' => 'Nouveau Nom',
        ]);
    }

    public function test_peut_supprimer_une_bougie()
    {
        $bougie = Bougie::factory()->create();

        $response = $this->delete("/bougies/{$bougie->id}");

        $response->assertRedirect('/bougies');
        $this->assertDatabaseMissing('bougies', [
            'id' => $bougie->id,
        ]);
    }

    public function test_validation_creation_requiert_champs_obligatoires()
    {
        $response = $this->post('/bougies', []);

        $response->assertSessionHasErrors(['reference', 'parfum', 'nom', 'prix']);
    }
}
