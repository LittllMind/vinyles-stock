<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refactoring complet : structure REF | ARTISTE | MODELE | GENRE
     */
    public function up(): void
    {
        // Backup données si besoin (skip en dev/mock)
        
        Schema::dropIfExists('vinyles');
        
        Schema::create('vinyles', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();        // Ex: VIN-001
            $table->string('artiste');                   // Ex: David Bowie
            $table->string('modele');                    // Ex: Album title
            $table->string('genre')->nullable();         // Ex: Rock, Électro, Rap
            $table->string('style')->nullable();         // Ex: Glam Rock, French Touch
            $table->integer('prix');                      // Prix en CENTIMES
            $table->integer('quantite')->default(0);
            $table->integer('seuil_alerte')->default(5);
            // Les fonds sont des accessoires séparés, pas des variantes de vinyles
            // Le lien s'effectue via les lignes de commande (cart_items/order_items)
            $table->timestamps();
            
            $table->index('reference');
            $table->index('artiste');
            $table->index('genre');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vinyles');
        
        // Restore ancienne structure si rollback
        Schema::create('vinyles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('modele');
            $table->decimal('prix', 8, 2);
            $table->integer('quantite')->default(0);
            $table->timestamps();
        });
    }
};