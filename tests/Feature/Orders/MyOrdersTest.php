<?php

namespace Tests\Feature\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_my_orders()
    {
        $response = $this->get(route('account.orders.index'));
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_their_orders_list()
    {
        $user = User::factory()->create(['role' => 'client']);

        $order1 = Order::create([
            'numero_commande' => Order::generateNumero(),
            'user_id' => $user->id,
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'j.dupont@test.com',
            'telephone' => '0101010101',
            'total' => 10.00,
            'statut' => 'en_attente',
        ]);

        $order2 = Order::create([
            'numero_commande' => Order::generateNumero(),
            'user_id' => $user->id,
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'j.dupont@test.com',
            'telephone' => '0101010102',
            'total' => 20.00,
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($user)->get(route('account.orders.index'));

        $response->assertStatus(200);
        $response->assertSee($order1->numero_commande);
        $response->assertSee($order2->numero_commande);
    }

    public function test_user_cannot_view_other_users_order()
    {
        $userA = User::factory()->create(['role' => 'client']);
        $userB = User::factory()->create(['role' => 'client']);

        $order = Order::create([
            'numero_commande' => Order::generateNumero(),
            'user_id' => $userB->id,
            'nom' => 'Smith',
            'prenom' => 'Bob',
            'email' => 'bob@test.com',
            'telephone' => '0202020202',
            'total' => 15.00,
            'statut' => 'en_attente',
        ]);

        $response = $this->actingAs($userA)->get(route('account.orders.show', $order->id));
        $response->assertStatus(403);
    }
}
