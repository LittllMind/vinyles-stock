<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajoute reserved_quantity pour gérer les articles dans les paniers actifs.
     */
    public function up(): void
    {
        // Ajouter reserved_quantity sur vinyles
        if (!Schema::hasColumn('vinyles', 'reserved_quantity')) {
            Schema::table('vinyles', function (Blueprint $table) {
                $table->unsignedInteger('reserved_quantity')->default(0)->after('quantite')->comment('Quantité réservée dans des paniers actifs');
            });
        }

        // Ajouter reserved_quantity sur fonds
        if (!Schema::hasColumn('fonds', 'reserved_quantity')) {
            Schema::table('fonds', function (Blueprint $table) {
                $table->unsignedInteger('reserved_quantity')->default(0)->after('quantite')->comment('Quantité réservée dans des paniers actifs');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('vinyles', 'reserved_quantity')) {
            Schema::table('vinyles', function (Blueprint $table) {
                $table->dropColumn('reserved_quantity');
            });
        }

        if (Schema::hasColumn('fonds', 'reserved_quantity')) {
            Schema::table('fonds', function (Blueprint $table) {
                $table->dropColumn('reserved_quantity');
            });
        }
    }
};
