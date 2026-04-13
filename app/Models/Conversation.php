<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'order_id',
        'sujet',
        'statut',
        'dernier_message_at',
        'fermee_at',
        'fermee_par',
    ];

    protected $casts = [
        'dernier_message_at' => 'datetime',
        'fermee_at' => 'datetime',
    ];

    // Relations
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'id', 'conversation_id')
            ->whereColumn('messages.id', '!=', 'conversations.id')
            ->latestOfMany();
    }

    public function fermeePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fermee_par');
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('statut', 'active');
    }

    public function scopeNonLuesParAdmin($query)
    {
        return $query->whereHas('messages', function ($q) {
            $q->where('lu_at', null)->where('type', 'client');
        });
    }

    // Méthodes
    public function estActive(): bool
    {
        return $this->statut === 'active';
    }

    public function mettreAJourDernierMessage(): void
    {
        $this->update(['dernier_message_at' => now()]);
    }

    public function statutBadge(): string
    {
        return match($this->statut) {
            'active' => '<span class="badge badge-success">Active</span>',
            'fermee' => '<span class="badge badge-warning">Fermée</span>',
            'archive' => '<span class="badge badge-ghost">Archivée</span>',
            default => $this->statut,
        };
    }
}