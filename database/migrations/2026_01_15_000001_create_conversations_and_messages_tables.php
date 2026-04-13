<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table des conversations (threads)
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->string('sujet')->nullable();
            $table->enum('statut', ['active', 'fermee', 'archive'])->default('active');
            $table->timestamp('dernier_message_at')->nullable();
            $table->timestamp('fermee_at')->nullable();
            $table->foreignId('fermee_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['client_id', 'statut']);
            $table->index('dernier_message_at');
        });

        // Table des messages dans les conversations
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['client', 'admin', 'systeme'])->default('client');
            $table->text('contenu');
            $table->timestamp('lu_at')->nullable();
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
            $table->index(['user_id', 'lu_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};