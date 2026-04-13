<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'type',
        'contenu',
        'lu_at',
    ];

    protected $casts = [
        'lu_at' => 'datetime',
    ];

    // Relations
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeNonLus($query)
    {
        return $query->whereNull('lu_at');
    }

    public function scopeDuClient($query)
    {
        return $query->where('type', 'client');
    }

    public function scopeDeLAdmin($query)
    {
        return $query->where('type', 'admin');
    }

    // Méthodes
    public function marquerLu(): void
    {
        if ($this->lu_at === null) {
            $this->update(['lu_at' => now()]);
        }
    }

    public function estLu(): bool
    {
        return $this->lu_at !== null;
    }

    public function estDuClient(): bool
    {
        return $this->type === 'client';
    }

    public function estDeLAdmin(): bool
    {
        return $this->type === 'admin';
    }

    public function typeBadge(): string
    {
        return match($this->type) {
            'client' => '<span class="badge badge-info">Client</span>',
            'admin' => '<span class="badge badge-warning">Admin</span>',
            'systeme' => '<span class="badge badge-ghost">Système</span>',
            default => $this->type,
        };
    }
}