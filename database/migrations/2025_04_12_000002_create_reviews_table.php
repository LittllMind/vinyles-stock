<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vinyle_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('rating')->unsigned(); // 1-5
            $table->text('comment')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_response')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('moderated_at')->nullable();
            $table->timestamps();

            // Index pour performance
            $table->index(['vinyle_id', 'status']);
            $table->index(['user_id', 'created_at']);
            $table->unique(['vinyle_id', 'user_id']); // Un avis par utilisateur/vinyle
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};