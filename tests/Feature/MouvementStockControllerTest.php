<?php

namespace Tests\Feature;

use App\Models\Bougie;
use App\Models\MouvementStock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MouvementStockControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function admin_peut_voir_liste_mouvements(): void
    {
        $bougie = Bougie::factory()->create(['quantite' => 10]);
        MouvementStock::factory()->count(3)->create([
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.mouvements.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.mouvements.index');
        $response->assertViewHas('mouvements');
    }

    /** @test */
    public function admin_peut_voir_formulaire_creation(): void
    {
        Bougie::factory()->create(['nom' => 'Vanille Douce']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.mouvements.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.mouvements.create');
        $response->assertViewHas('bougies');
    }

    /** @test */
    public function admin_peut_creer_mouvement_entree(): void
    {
        $bougie = Bougie::factory()->create(['quantite' => 10]);

        $response = $this->actingAs($this->user)
            ->post(route('admin.mouvements.store'), [
                'stockable_type' => Bougie::class,
                'stockable_id' => $bougie->id,
                'type' => 'entree',
                'quantite' => 5,
                'raison' => 'Réception fournisseur',
            ]);

        $response->assertRedirect(route('admin.mouvements.index'));
        $response->assertSessionHas('success');

        // Vérifier que le stock a été mis à jour (10 + 5 = 15)
        $this->assertDatabaseHas('bougies', [
            'id' => $bougie->id,
            'quantite' => 15,
        ]);

        $this->assertDatabaseHas('mouvements_stock', [
            'stockable_id' => $bougie->id,
            'type' => 'entree',
            'quantite' => 5,
        ]);
    }

    /** @test */
    public function admin_peut_creer_mouvement_sortie(): void
    {
        $bougie = Bougie::factory()->create(['quantite' => 20]);

        $response = $this->actingAs($this->user)
            ->post(route('admin.mouvements.store'), [
                'stockable_type' => Bougie::class,
                'stockable_id' => $bougie->id,
                'type' => 'sortie',
                'quantite' => 3,
                'raison' => 'Vente client',
            ]);

        $response->assertRedirect(route('admin.mouvements.index'));

        // Vérifier que le stock a été mis à jour (20 - 3 = 17)
        $this->assertDatabaseHas('bougies', [
            'id' => $bougie->id,
            'quantite' => 17,
        ]);
    }

    /** @test */
    public function admin_peut_voir_detail_mouvement(): void
    {
        $bougie = Bougie::factory()->create();
        $mouvement = MouvementStock::factory()->create([
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.mouvements.show', $mouvement));

        $response->assertStatus(200);
        $response->assertViewIs('admin.mouvements.show');
        $response->assertViewHas('mouvement', $mouvement);
    }

    /** @test */
    public function observer_met_a_jour_stock_automatiquement(): void
    {
        $bougie = Bougie::factory()->create(['quantite' => 50]);

        // Création d'une entrée
        MouvementStock::create([
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
            'type' => 'entree',
            'quantite' => 10,
            'user_id' => $this->user->id,
        ]);

        $bougie->refresh();
        $this->assertEquals(60, $bougie->quantite);

        // Création d'une sortie
        MouvementStock::create([
            'stockable_type' => Bougie::class,
            'stockable_id' => $bougie->id,
            'type' => 'sortie',
            'quantite' => 15,
            'user_id' => $this->user->id,
        ]);

        $bougie->refresh();
        $this->assertEquals(45, $bougie->quantite);
    }

    /** @test */
    public function validation_requise_pour_creation(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.mouvements.store'), []);

        $response->assertSessionHasErrors([
            'stockable_id',
            'stockable_type',
            'type',
            'quantite',
        ]);
    }

    /** @test */
    public function quantite_doit_etre_positive(): void
    {
        $bougie = Bougie::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(route('admin.mouvements.store'), [
                'stockable_type' => Bougie::class,
                'stockable_id' => $bougie->id,
                'type' => 'entree',
                'quantite' => -5,
            ]);

        $response->assertSessionHasErrors(['quantite']);
    }

    /** @test */
    public function type_doit_etre_entree_ou_sortie(): void
    {
        $bougie = Bougie::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(route('admin.mouvements.store'), [
                'stockable_type' => Bougie::class,
                'stockable_id' => $bougie->id,
                'type' => 'invalide',
                'quantite' => 5,
            ]);

        $response->assertSessionHasErrors(['type']);
    }
}