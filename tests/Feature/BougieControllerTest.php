<?php

namespace Tests\Feature;

use App\Models\Bougie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BougieControllerTest extends TestCase
{
    use RefreshDatabase;

    // === T2.3.1 : Accès liste ===
    public function test_admin_peut_voir_liste_bougies(): void
    {
        Bougie::factory()->count(15)->create();

        $response = $this->get('/bougies');

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.index');
        $response->assertViewHas('bougies');
    }

    // === T2.3.2 : Accès formulaire création ===
    public function test_admin_peut_voir_formulaire_creation(): void
    {
        $response = $this->get('/bougies/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.create');
    }

    // === T2.3.3 : Création d'une bougie ===
    public function test_admin_peut_creer_bougie(): void
    {
        $data = [
            'reference' => 'BOUG-001',
            'parfum' => 'Vanille',
            'nom' => 'Bougie Vanille Douce',
            'collection' => 'Classique',
            'format' => '200g',
            'type_cire' => 'soja',
            'temps_brulure' => 45,
            'notes' => 'Notes gourmandes de vanille',
            'prix' => 24.90,
            'quantite' => 100,
            'seuil_alerte' => 10,
        ];

        $response = $this->post('/bougies', $data);

        $response->assertRedirect('/bougies');
        $this->assertDatabaseHas('bougies', ['reference' => 'BOUG-001']);
    }

    // === T2.3.4 : Affichage d'une bougie ===
    public function test_admin_peut_voir_detail_bougie(): void
    {
        $bougie = Bougie::factory()->create();

        $response = $this->get("/bougies/{$bougie->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.show');
        $response->assertViewHas('bougie');
    }

    // === T2.3.5 : Accès formulaire édition ===
    public function test_admin_peut_voir_formulaire_edition(): void
    {
        $bougie = Bougie::factory()->create();

        $response = $this->get("/bougies/{$bougie->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.edit');
        $response->assertViewHas('bougie');
    }

    // === T2.3.6 : Mise à jour d'une bougie ===
    public function test_admin_peut_modifier_bougie(): void
    {
        $bougie = Bougie::factory()->create(['parfum' => 'Ancien Parfum']);

        $response = $this->put("/bougies/{$bougie->id}", [
            'reference' => $bougie->reference,
            'parfum' => 'Lavande',
            'nom' => $bougie->nom,
            'prix' => $bougie->prix,
        ]);

        $response->assertRedirect('/bougies');
        $this->assertDatabaseHas('bougies', ['id' => $bougie->id, 'parfum' => 'Lavande']);
    }

    // === T2.3.7 : Suppression d'une bougie ===
    public function test_admin_peut_supprimer_bougie(): void
    {
        $bougie = Bougie::factory()->create();

        $response = $this->delete("/bougies/{$bougie->id}");

        $response->assertRedirect('/bougies');
        $this->assertDatabaseMissing('bougies', ['id' => $bougie->id]);
    }

    // === T2.3.8 : Validation création ===
    public function test_validation_requise_pour_creation(): void
    {
        $response = $this->post('/bougies', []);

        $response->assertSessionHasErrors(['reference', 'parfum', 'nom', 'prix']);
    }

    // === T2.3.9 : Unicité référence ===
    public function test_reference_doit_etre_unique(): void
    {
        $bougie = Bougie::factory()->create(['reference' => 'BOUG-001']);

        $response = $this->post('/bougies', [
            'reference' => 'BOUG-001',
            'parfum' => 'Lavande',
            'nom' => 'Nom Test',
            'prix' => 20.00,
        ]);

        $response->assertSessionHasErrors(['reference']);
    }
}