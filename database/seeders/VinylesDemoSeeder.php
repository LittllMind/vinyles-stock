<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vinyle;
use App\Models\Fond;

class VinylesDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les 3 fonds
        $fonds = [
            [
                'type' => 'sans',
                'prix_vente' => 0,
                'prix_achat' => 0,
                'quantite' => 999,
                'actif' => true,
            ],
            [
                'type' => 'miroir',
                'prix_vente' => 8,
                'prix_achat' => 3.50,
                'quantite' => 50,
                'actif' => true,
            ],
            [
                'type' => 'doré',
                'prix_vente' => 13,
                'prix_achat' => 5.50,
                'quantite' => 30,
                'actif' => true,
            ],
        ];

        foreach ($fonds as $fond) {
            Fond::updateOrCreate(
                ['type' => $fond['type']],
                $fond
            );
        }

        $this->command->info('✅ 3 fonds créés');

        // Créer 5 vinyles d'exemples
        $vinyles = [
            [
                'reference' => 'VD001',
                'artiste' => 'Pink Floyd',
                'modele' => 'The Dark Side of the Moon',
                'genre' => 'Rock Progressif',
                'style' => 'art-print',
                'prix' => 8900,
                'quantite' => 3,
                'seuil_alerte' => 2,
            ],
            [
                'reference' => 'VD002',
                'artiste' => 'Daft Punk',
                'modele' => 'Random Access Memories',
                'genre' => 'Electro',
                'style' => 'art-print',
                'prix' => 7500,
                'quantite' => 5,
                'seuil_alerte' => 2,
            ],
            [
                'reference' => 'VD003',
                'artiste' => 'David Bowie',
                'modele' => 'Aladdin Sane',
                'genre' => 'Rock',
                'style' => 'art-print',
                'prix' => 9500,
                'quantite' => 2,
                'seuil_alerte' => 1,
            ],
            [
                'reference' => 'VD004',
                'artiste' => 'AC/DC',
                'modele' => 'Back in Black',
                'genre' => 'Hard Rock',
                'style' => 'art-print',
                'prix' => 7900,
                'quantite' => 4,
                'seuil_alerte' => 2,
            ],
            [
                'reference' => 'VD005',
                'artiste' => 'Édith Piaf',
                'modele' => 'La Vie en Rose',
                'genre' => 'Chanson',
                'style' => 'art-print',
                'prix' => 6900,
                'quantite' => 6,
                'seuil_alerte' => 3,
            ],
        ];

        foreach ($vinyles as $vinyle) {
            Vinyle::updateOrCreate(
                ['reference' => $vinyle['reference']],
                $vinyle
            );
        }

        $this->command->info('✅ 5 vinyles créés');
        $this->command->info('');
        $this->command->info('🎉 Données de démo prêtes !');
        $this->command->info('   - Vinyles: ' . Vinyle::count());
        $this->command->info('   - Fonds: ' . Fond::count());
    }
}
