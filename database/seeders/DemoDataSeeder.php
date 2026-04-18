<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vinyle;
use App\Models\Fond;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ContactMessage;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed the application's database with demo data.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            EmployeSeeder::class,
            FondSeeder::class,
            VenteSeeder::class,
        ]);

        // Create additional demo users
        $this->createDemoUsers();
        
        // Create demo orders
        $this->createDemoOrders();
        
        // Create demo contact messages
        $this->createDemoContactMessages();
        
        // Create demo reviews
        $this->createDemoReviews();
        
        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Admin user: admin@test.com / pass');
        $this->command->info('Employe user: employe@test.com / pass');
        $this->command->info('Client user: client@test.com / pass');
    }

    private function createDemoUsers(): void
    {
        // Additional admin
        User::updateOrCreate(
            ['email' => 'manager@test.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('pass'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Additional employes
        for ($i = 1; $i <= 3; $i++) {
            User::updateOrCreate(
                ['email' => "employe{$i}@test.com"],
                [
                    'name' => "Employé {$i}",
                    'password' => Hash::make('pass'),
                    'role' => 'employe',
                    'email_verified_at' => now(),
                ]
            );
        }

        // Additional clients
        $clientNames = ['Jean Dupont', 'Marie Martin', 'Pierre Bernard', 'Sophie Petit', 'Lucas Moreau'];
        foreach ($clientNames as $index => $name) {
            User::updateOrCreate(
                ['email' => "client{$index}@test.com"],
                [
                    'name' => $name,
                    'password' => Hash::make('pass'),
                    'role' => 'client',
                    'email_verified_at' => now(),
                ]
            );
        }
    }

    private function createDemoOrders(): void
    {
        $clients = User::where('role', 'client')->get();
        $vinyles = Vinyle::all();

        if ($clients->isEmpty() || $vinyles->isEmpty()) {
            return;
        }

        $statuses = ['en_attente', 'payee', 'preparation', 'expediee', 'livree', 'annulee'];
        
        foreach ($clients as $client) {
            // Create 1-3 orders per client
            $orderCount = rand(1, 3);
            
            for ($i = 0; $i < $orderCount; $i++) {
                $order = Order::create([
                    'user_id' => $client->id,
                    'total_amount' => 0,
                    'status' => $statuses[array_rand($statuses)],
                    'mode_livraison' => rand(0, 1) ? 'retrait' : 'livraison',
                    'shipping_address' => json_encode([
                        'street' => rand(1, 100) . ' Rue de Paris',
                        'city' => 'Paris',
                        'postal_code' => '7500' . rand(1, 9),
                        'country' => 'France',
                    ]),
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                // Add 1-4 items per order
                $total = 0;
                $itemCount = rand(1, 4);
                $selectedVinyles = $vinyles->random(min($itemCount, $vinyles->count()));

                foreach ($selectedVinyles as $vinyle) {
                    $quantity = rand(1, 3);
                    $price = $vinyle->prix ?? rand(15, 50);
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'vinyle_id' => $vinyle->id,
                        'quantity' => $quantity,
                        'price' => $price,
                    ]);
                    
                    $total += $price * $quantity;
                }

                $order->update(['total_amount' => $total]);
            }
        }
    }

    private function createDemoContactMessages(): void
    {
        $clients = User::where('role', 'client')->take(3)->get();
        
        $subjects = [
            'Question sur une commande',
            'Problème de paiement',
            'Demande de remboursement',
            'Question sur un vinyle',
            'Suggestion',
        ];

        $messages = [
            'Bonjour, j\'ai une question concernant ma commande récente...',
            'Impossible de finaliser mon paiement, pouvez-vous m\'aider?',
            'Je souhaiterais annuler ma commande et être remboursé.',
            'Est-ce que ce vinyle est encore disponible?',
            'Serait-il possible d\'ajouter plus de vinyles jazz?',
        ];

        foreach ($clients as $index => $client) {
            // Create conversation (using French column names)
            $conversation = Conversation::create([
                'client_id' => $client->id,
                'sujet' => $subjects[$index % count($subjects)],
                'statut' => rand(0, 1) ? 'active' : 'fermee',
                'created_at' => now()->subDays(rand(1, 20)),
            ]);

            // Initial client message
            Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => $client->id,
                'type' => 'client',
                'contenu' => $messages[$index % count($messages)],
                'created_at' => $conversation->created_at,
            ]);

            // Maybe add a reply
            if (rand(0, 1)) {
                $admin = User::where('role', 'admin')->first();
                if ($admin) {
                    Message::create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $admin->id,
                        'type' => 'admin',
                        'contenu' => 'Bonjour, merci pour votre message. Je regarde cela de suite.',
                        'created_at' => $conversation->created_at->addHours(2),
                    ]);
                }
            }

            // Also create legacy ContactMessage
            ContactMessage::create([
                'nom' => $client->name,
                'email' => $client->email,
                'sujet' => $subjects[$index % count($subjects)],
                'message' => $messages[$index % count($messages)],
                'reponse' => rand(0, 1) ? 'Merci pour votre message, nous traitons votre demande.' : null,
                'statut' => rand(0, 1) ? 'repondu' : 'non_lu',
                'created_at' => now()->subDays(rand(1, 20)),
            ]);
        }
    }

    private function createDemoReviews(): void
    {
        $clients = User::where('role', 'client')->take(4)->get();
        $vinyles = Vinyle::all();

        if ($clients->isEmpty() || $vinyles->isEmpty()) {
            return;
        }

        $comments = [
            'Excellent vinyle, qualité sonore parfaite!',
            'Très satisfait de mon achat, livraison rapide.',
            'Good pressing, worth the price.',
            'Un peu déçu par l\'emballage mais le disque est nickel.',
            'Mastering de qualité, je recommande!',
        ];

        foreach ($clients as $client) {
            $selectedVinyles = $vinyles->random(min(2, $vinyles->count()));
            
            foreach ($selectedVinyles as $vinyle) {
                Review::create([
                    'vinyle_id' => $vinyle->id,
                    'user_id' => $client->id,
                    'rating' => rand(3, 5),
                    'comment' => $comments[array_rand($comments)],
                    'is_approved' => rand(0, 1),
                    'created_at' => now()->subDays(rand(1, 15)),
                ]);
            }
        }
    }
}
