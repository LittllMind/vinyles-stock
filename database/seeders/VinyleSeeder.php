<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Vinyle;

class VinyleSeeder extends Seeder
{
    /**
     * 40 albums vinyles uniques - sans doublons d'artistes
     * Les fonds (standard, miroir, doré) sont des accessoires séparés
     * 
     * REF format : VIN-### (VINyle)
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Vinyle::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $vinyles = [
            // ═══════════════════════════════════════════════════════════════
            // CLASSIQUES ROCK/POP INTERNATIONAUX
            // ═══════════════════════════════════════════════════════════════
            ['reference' => 'VIN-001', 'artiste' => 'David Bowie', 'modele' => 'The Rise and Fall of Ziggy Stardust', 'genre' => 'Rock', 'style' => 'Glam Rock', 'prix' => 2700, 'quantite' => 15],
            ['reference' => 'VIN-002', 'artiste' => 'Daft Punk', 'modele' => 'Random Access Memories', 'genre' => 'Électro', 'style' => 'French Touch', 'prix' => 2700, 'quantite' => 20],
            ['reference' => 'VIN-003', 'artiste' => 'AC/DC', 'modele' => 'Back in Black', 'genre' => 'Rock', 'style' => 'Hard Rock', 'prix' => 2700, 'quantite' => 18],
            ['reference' => 'VIN-004', 'artiste' => 'Queen', 'modele' => 'A Night at the Opera', 'genre' => 'Rock', 'style' => 'Classic Rock', 'prix' => 2700, 'quantite' => 16],
            ['reference' => 'VIN-005', 'artiste' => 'Pink Floyd', 'modele' => 'The Dark Side of the Moon', 'genre' => 'Rock', 'style' => 'Progressif', 'prix' => 2700, 'quantite' => 12],
            ['reference' => 'VIN-006', 'artiste' => 'Metallica', 'modele' => 'Master of Puppets', 'genre' => 'Metal', 'style' => 'Thrash', 'prix' => 2700, 'quantite' => 10],
            ['reference' => 'VIN-007', 'artiste' => 'Bob Marley', 'modele' => 'Legend', 'genre' => 'Reggae', 'style' => 'Roots', 'prix' => 2700, 'quantite' => 20],
            ['reference' => 'VIN-008', 'artiste' => 'Nirvana', 'modele' => 'Nevermind', 'genre' => 'Grunge', 'style' => 'Alternative', 'prix' => 2700, 'quantite' => 14],
            ['reference' => 'VIN-009', 'artiste' => 'Gorillaz', 'modele' => 'Demon Days', 'genre' => 'Électro', 'style' => 'Trip-Hop', 'prix' => 2700, 'quantite' => 15],
            ['reference' => 'VIN-010', 'artiste' => 'The Beatles', 'modele' => 'Abbey Road', 'genre' => 'Rock', 'style' => 'British Invasion', 'prix' => 2700, 'quantite' => 8],
            ['reference' => 'VIN-011', 'artiste' => 'Led Zeppelin', 'modele' => 'Led Zeppelin IV', 'genre' => 'Rock', 'style' => 'Hard Rock', 'prix' => 2700, 'quantite' => 12],
            ['reference' => 'VIN-012', 'artiste' => 'Guns N\' Roses', 'modele' => 'Appetite for Destruction', 'genre' => 'Rock', 'style' => 'Hard Rock', 'prix' => 2700, 'quantite' => 14],
            ['reference' => 'VIN-013', 'artiste' => 'Prince', 'modele' => 'Purple Rain', 'genre' => 'Pop', 'style' => 'Funk Rock', 'prix' => 2700, 'quantite' => 11],
            ['reference' => 'VIN-014', 'artiste' => 'Elvis Presley', 'modele' => 'The King', 'genre' => 'Rock', 'style' => 'Rockabilly', 'prix' => 2700, 'quantite' => 15],
            ['reference' => 'VIN-015', 'artiste' => 'The Rolling Stones', 'modele' => 'Sticky Fingers', 'genre' => 'Rock', 'style' => 'Classic Rock', 'prix' => 2700, 'quantite' => 13],
            ['reference' => 'VIN-016', 'artiste' => 'Red Hot Chili Peppers', 'modele' => 'Californication', 'genre' => 'Rock', 'style' => 'Funk Rock', 'prix' => 2700, 'quantite' => 16],
            ['reference' => 'VIN-017', 'artiste' => 'Iron Maiden', 'modele' => 'The Number of the Beast', 'genre' => 'Metal', 'style' => 'Heavy Metal', 'prix' => 2700, 'quantite' => 9],
            ['reference' => 'VIN-018', 'artiste' => 'Rammstein', 'modele' => 'Rosenrot', 'genre' => 'Metal', 'style' => 'Industrial Metal', 'prix' => 2700, 'quantite' => 12],
            ['reference' => 'VIN-019', 'artiste' => 'Black Sabbath', 'modele' => 'Paranoid', 'genre' => 'Metal', 'style' => 'Doom Metal', 'prix' => 2700, 'quantite' => 10],
            ['reference' => 'VIN-020', 'artiste' => 'Pink Floyd', 'modele' => 'The Wall', 'genre' => 'Rock', 'style' => 'Progressif', 'prix' => 2700, 'quantite' => 8],
            
            // ═══════════════════════════════════════════════════════════════
            // VARIÉTÉS FRANÇAISES
            // ═══════════════════════════════════════════════════════════════
            ['reference' => 'VIN-021', 'artiste' => 'Mylène Farmer', 'modele' => 'Bleu Noir', 'genre' => 'Pop', 'style' => 'Chanson Française', 'prix' => 2700, 'quantite' => 15],
            ['reference' => 'VIN-022', 'artiste' => 'Renaud', 'modele' => 'Mistral Gagnant', 'genre' => 'Chanson', 'style' => 'Chanson Française', 'prix' => 2700, 'quantite' => 18],
            ['reference' => 'VIN-023', 'artiste' => 'Johnny Hallyday', 'modele' => 'La Fête Foraine', 'genre' => 'Rock', 'style' => 'Rock Français', 'prix' => 2700, 'quantite' => 20],
            ['reference' => 'VIN-024', 'artiste' => 'Indochine', 'modele' => 'Dancetaria', 'genre' => 'Rock', 'style' => 'Rock Français', 'prix' => 2700, 'quantite' => 14],
            ['reference' => 'VIN-025', 'artiste' => 'Stromae', 'modele' => 'Racine Carrée', 'genre' => 'Électro', 'style' => 'Chanson Française', 'prix' => 2700, 'quantite' => 18],
            ['reference' => 'VIN-026', 'artiste' => 'Angèle', 'modele' => 'Brol', 'genre' => 'Pop', 'style' => 'Chanson Française', 'prix' => 2700, 'quantite' => 22],
            ['reference' => 'VIN-027', 'artiste' => 'Aya Nakamura', 'modele' => 'Nakamura', 'genre' => 'R&B', 'style' => 'Afropop', 'prix' => 2700, 'quantite' => 20],
            
            // ═══════════════════════════════════════════════════════════════
            // RAP & HIP-HOP
            // ═══════════════════════════════════════════════════════════════
            ['reference' => 'VIN-028', 'artiste' => 'Eminem', 'modele' => 'The Marshall Mathers LP', 'genre' => 'Rap', 'style' => 'Hip-Hop US', 'prix' => 2700, 'quantite' => 20],
            ['reference' => 'VIN-029', 'artiste' => '2Pac', 'modele' => 'All Eyez on Me', 'genre' => 'Rap', 'style' => 'West Coast Rap', 'prix' => 2700, 'quantite' => 12],
            ['reference' => 'VIN-030', 'artiste' => 'NTM', 'modele' => 'Anthologie', 'genre' => 'Rap', 'style' => 'Rap Français', 'prix' => 2700, 'quantite' => 10],
            ['reference' => 'VIN-031', 'artiste' => 'IAM', 'modele' => "L'École du Micro d'Argent", 'genre' => 'Rap', 'style' => 'Rap Français', 'prix' => 2700, 'quantite' => 12],
            ['reference' => 'VIN-032', 'artiste' => 'Wu-Tang Clan', 'modele' => 'Enter the Wu-Tang', 'genre' => 'Rap', 'style' => 'East Coast Rap', 'prix' => 2700, 'quantite' => 9],
            ['reference' => 'VIN-033', 'artiste' => 'Orelsan', 'modele' => 'Civilisation', 'genre' => 'Rap', 'style' => 'Rap Français', 'prix' => 2700, 'quantite' => 18],
            ['reference' => 'VIN-034', 'artiste' => 'Booba', 'modele' => 'Lunatic', 'genre' => 'Rap', 'style' => 'Rap Français', 'prix' => 2700, 'quantite' => 16],
            
            // ═══════════════════════════════════════════════════════════════
            // CONTEMPORAINS & DIVERS
            // ═══════════════════════════════════════════════════════════════
            ['reference' => 'VIN-035', 'artiste' => 'Billie Eilish', 'modele' => 'When We All Fall Asleep', 'genre' => 'Pop', 'style' => 'Electropop', 'prix' => 2700, 'quantite' => 25],
            ['reference' => 'VIN-036', 'artiste' => 'Taylor Swift', 'modele' => '1989', 'genre' => 'Pop', 'style' => 'Country Pop', 'prix' => 2700, 'quantite' => 30],
            ['reference' => 'VIN-037', 'artiste' => 'The Weeknd', 'modele' => 'After Hours', 'genre' => 'R&B', 'style' => 'Pop', 'prix' => 2700, 'quantite' => 20],
            ['reference' => 'VIN-038', 'artiste' => 'Lana Del Rey', 'modele' => 'Born to Die', 'genre' => 'Pop', 'style' => 'Dream Pop', 'prix' => 2700, 'quantite' => 22],
            ['reference' => 'VIN-039', 'artiste' => 'Arctic Monkeys', 'modele' => 'AM', 'genre' => 'Rock', 'style' => 'Indie Rock', 'prix' => 2700, 'quantite' => 18],
            ['reference' => 'VIN-040', 'artiste' => 'Tame Impala', 'modele' => 'Currents', 'genre' => 'Rock', 'style' => 'Psychédélique', 'prix' => 2700, 'quantite' => 17],
        ];

        foreach ($vinyles as $data) {
            Vinyle::create($data);
        }
    }
}
