<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Bougie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BougieControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un utilisateur admin
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->user);
    }

    public function test_admin_peut_voir_liste_bougies()
    {
        $bougies = Bougie::factory()->count(3)->create();

        $response = $this->get(route('admin.bougies.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.index');
        $response->assertViewHas('bougies');
    }

    public function test_admin_peut_voir_formulaire_creation()
    {
        $response = $this->get(route('admin.bougies.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.create');
    }

    public function test_admin_peut_creer_bougie()
    {
        $data = [
            'reference' => 'BOUG-TEST-001',
            'nom' => 'Bougie Test',
            'parfum' => 'Vanille',
            'prix' => 29.99,
            'quantite' => 10,
            'seuil_alerte' => 5,
        ];

        $response = $this->post(route('admin.bougies.store'), $data);

        $response->assertRedirect(route('admin.bougies.index'));
        $this->assertDatabaseHas('bougies', ['reference' => 'BOUG-TEST-001']);
    }

    public function test_admin_peut_voir_detail_bougie()
    {
        $bougie = Bougie::factory()->create();

        $response = $this->get(route('admin.bougies.show', $bougie));

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.show');
        $response->assertViewHas('bougie', $bougie);
    }

    public function test_admin_peut_voir_formulaire_edition()
    {
        $bougie = Bougie::factory()->create();

        $response = $this->get(route('admin.bougies.edit', $bougie));

        $response->assertStatus(200);
        $response->assertViewIs('admin.bougies.edit');
        $response->assertViewHas('bougie', $bougie);
    }

    public function test_admin_peut_modifier_bougie()
    {
        $bougie = Bougie::factory()->create();

        $response = $this->put(route('admin.bougies.update', $bougie), [
            'reference' => $bougie->reference,
            'nom' => 'Bougie Modifiée',
            'parfum' => $bougie->parfum,
            'prix' => 39.99,
            'quantite' => $bougie->quantite,
            'seuil_alerte' => $bougie->seuil_alerte,
        ]);

        $response->assertRedirect(route('admin.bougies.index'));
        $this->assertDatabaseHas('bougies', ['nom' => 'Bougie Modifiée', 'prix' => 39.99]);
    }

    public function test_admin_peut_supprimer_bougie()
    {
        $bougie = Bougie::factory()->create();

        $response = $this->delete(route('admin.bougies.destroy', $bougie));

        $response->assertRedirect(route('admin.bougies.index'));
        $this->assertDatabaseMissing('bougies', ['id' => $bougie->id]);
    }

    public function test_validation_requise_pour_creation_bougie()
    {
        $response = $this->post(route('admin.bougies.store'), []);

        $response->assertSessionHasErrors(['reference', 'nom', 'parfum', 'prix']);
    }

    public function test_reference_doit_etre_unique()
    {
        $existing = Bougie::factory()->create(['reference' => 'BOUG-DUPLICATE']);

        $response = $this->post(route('admin.bougies.store'), [
            'reference' => 'BOUG-DUPLICATE',
            'nom' => 'Test',
            'parfum' => 'Test',
            'prix' => 10,
        ]);

        $response->assertSessionHasErrors('reference');
    }
}
