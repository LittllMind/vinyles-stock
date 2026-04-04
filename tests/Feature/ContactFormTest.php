<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Mail\ContactFormMail;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function la_route_post_contact_existe_et_repond()
    {
        $response = $this->postJson('/contact', [
            'nom' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'sujet' => 'Test',
            'message' => 'Ceci est un message de test.',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function le_formulaire_envoie_un_email()
    {
        Mail::fake();

        $response = $this->postJson('/contact', [
            'nom' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'sujet' => 'Demande d\'information',
            'message' => 'Bonjour, j\'aimerais en savoir plus sur vos produits.',
        ]);

        $response->assertStatus(200);
        Mail::assertSent(ContactFormMail::class, function ($mail) {
            return $mail->hasTo(config('mail.from.address'));
        });
    }

    /** @test */
    public function le_nom_est_requis()
    {
        $response = $this->postJson('/contact', [
            'email' => 'jean@example.com',
            'sujet' => 'Test',
            'message' => 'Message',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nom']);
    }

    /** @test */
    public function l_email_est_requis_et_doit_etre_valide()
    {
        // Email manquant
        $response = $this->postJson('/contact', [
            'nom' => 'Jean Dupont',
            'sujet' => 'Test',
            'message' => 'Message',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);

        // Email invalide
        $response = $this->postJson('/contact', [
            'nom' => 'Jean Dupont',
            'email' => 'email-invalide',
            'sujet' => 'Test',
            'message' => 'Message',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function le_sujet_est_requis()
    {
        $response = $this->postJson('/contact', [
            'nom' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'message' => 'Message',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['sujet']);
    }

    /** @test */
    public function le_message_est_requis_et_a_un_minimum_de_10_caracteres()
    {
        // Message manquant
        $response = $this->postJson('/contact', [
            'nom' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'sujet' => 'Test',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);

        // Message trop court
        $response = $this->postJson('/contact', [
            'nom' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'sujet' => 'Test',
            'message' => 'Court',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }

    /** @test */
    public function le_message_contient_les_bonnes_informations()
    {
        Mail::fake();

        $this->postJson('/contact', [
            'nom' => 'Marie Martin',
            'email' => 'marie@test.com',
            'sujet' => 'Question produit',
            'message' => 'Bonjour, j\'aimerais savoir si vous faites des commandes personnalisées.',
        ]);

        Mail::assertSent(ContactFormMail::class, function ($mail) {
            return $mail->contactData['nom'] === 'Marie Martin'
                && $mail->contactData['email'] === 'marie@test.com'
                && $mail->contactData['sujet'] === 'Question produit'
                && $mail->contactData['message'] === 'Bonjour, j\'aimerais savoir si vous faites des commandes personnalisées.';
        });
    }

    /** @test */
    public function la_reponse_json_contient_un_message_de_succes()
    {
        $response = $this->postJson('/contact', [
            'nom' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'sujet' => 'Test',
            'message' => 'Ceci est un message de test suffisamment long.',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Votre message a été envoyé avec succès.',
        ]);
    }

    /** @test */
    public function les_champs_ont_une_taille_maximale()
    {
        $response = $this->postJson('/contact', [
            'nom' => str_repeat('a', 256),
            'email' => 'jean@example.com',
            'sujet' => str_repeat('a', 256),
            'message' => str_repeat('a', 5001),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['nom', 'sujet', 'message']);
    }
}
