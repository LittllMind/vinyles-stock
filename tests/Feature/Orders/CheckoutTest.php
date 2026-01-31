<?php

namespace Tests\Feature\Orders;

use App\Mail\OrderConfirmationToCustomer;
use App\Mail\OrderRecapToAdmin;
use App\Models\Vinyle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_create_order_and_mails_are_sent()
    {
        Mail::fake();

        $vinyle = Vinyle::factory()->create(['quantite' => 10, 'prix' => 12]);

        // Add to cart
        $response = $this->post('/cart/add', [
            '_token' => csrf_token(),
            'vinyle_id' => $vinyle->id,
            'quantite' => 2,
            'fond' => 'standard',
        ]);

        $response->assertStatus(302);
        // The anonymous cart id cookie should be set so the cart can be merged after login
        $response->assertCookie('anon_cart_id');

        // There should be an anonymous cart with items in DB
        $anonCart = \App\Models\Cart::whereNull('user_id')->whereHas('items')->first();
        $this->assertNotNull($anonCart, 'Anonymous cart with items should exist after adding to cart');
        $this->assertEquals(1, $anonCart->items()->count(), 'Anonymous cart should have 1 item');

        // Visit checkout page as guest => should be redirected to login
        $response = $this->get('/orders/create');
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        // Authenticate via the real login flow so anonymous cart merges into user cart
        $password = 'secret123';
        $user = \App\Models\User::factory()->create(['password' => bcrypt($password)]);
        // Post to login and assert the merge cookies are queued
        $loginResponse = $this->post('/login', ['email' => $user->email, 'password' => $password]);
        $loginResponse->assertStatus(302);
        $loginResponse->assertCookie('cart_merge_pending');
        $loginResponse->assertCookie('cart_merge_source_id');

        // Visiting a page after login should trigger the merge middleware
        $response = $this->get('/orders/create');
        $response->assertStatus(200);

        // After visiting a page post-login the cart merge middleware should have run
        $cartService = app(\App\Services\CartService::class);

        // Check user cart exists and contains the expected items
        $userCart = \App\Models\Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($userCart, 'User cart should exist after login/merge');
        $this->assertEquals(1, $userCart->items()->count(), 'User cart should contain merged items');

        $this->assertEquals(2, $cartService->count(), 'Cart was not merged after login');

        // Submit order
        $form = [
            'prenom' => 'Jean',
            'nom' => 'Dupont',
            'email' => 'jean@example.com',
            'telephone' => '0123456789',
            'adresse' => '1 Rue Exemple',
            'code_postal' => '75001',
            'ville' => 'Paris',
            'notes_client' => 'Merci',
        ];

        // Ensure admin email configured for the test
        config(['app.admin_email' => 'admin@example.com']);

        $response = $this->post('/orders', $form);

        $response->assertStatus(302);

        // Mails
        Mail::assertSent(OrderConfirmationToCustomer::class, function ($mail) use ($form) {
            return $mail->hasTo($form['email']);
        });

        Mail::assertSent(OrderRecapToAdmin::class, function ($mail) {
            return $mail->hasTo('admin@example.com');
        });

        // Check stock decremented
        $vinyle->refresh();
        $this->assertEquals(8, $vinyle->quantite);
    }
}
