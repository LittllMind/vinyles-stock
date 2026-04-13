<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email');
            $table->string('sujet')->nullable();
            $table->text('message');
            $table->string('telephone')->nullable();
            $table->string('statut')->default('non_lu'); // non_lu, lu, repondu, archive
            $table->text('reponse')->nullable();
            $table->timestamp('repondu_at')->nullable();
            $table->foreignId('repondu_par')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['statut', 'created_at']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
