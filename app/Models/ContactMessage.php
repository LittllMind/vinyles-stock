<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'sujet',
        'message',
        'telephone',
        'statut',
        'reponse',
        'repondu_at',
        'repondu_par',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'repondu_at' => 'datetime',
    ];

    public function userRepondu(): BelongsTo
    {
        return $this->belongsTo(User::class, 'repondu_par');
    }

    /**
     * Badge du statut pour affichage admin
     */
    public function statutBadge(): string
    {
        return match($this->statut) {
            'non_lu' => '<span class="badge badge-error">Nouveau</span>',
            'lu' => '<span class="badge badge-warning">Lu</span>',
            'repondu' => '<span class="badge badge-success">Répondu</span>',
            'archive' => '<span class="badge badge-ghost">Archivé</span>',
            default => $this->statut,
        };
    }

    public function scopeNonLus($query)
    {
        return $query->where('statut', 'non_lu');
    }

    public function scopeEnAttente($query)
    {
        return $query->whereIn('statut', ['non_lu', 'lu']);
    }

    /**
     * Extraire un aperçu du message
     */
    public function apercu(int $longueur = 100): string
    {
        return str($this->message)->limit($longueur, '...');
    }

    /**
     * Marquer comme lu
     */
    public function marquerLu(): void
    {
        if ($this->statut === 'non_lu') {
            $this->update(['statut' => 'lu']);
        }
    }

    /**
     * Ajouter une réponse
     */
    public function repondre(string $reponse, int $userId): void
    {
        $this->update([
            'reponse' => $reponse,
            'repondu_at' => now(),
            'repondu_par' => $userId,
            'statut' => 'repondu',
        ]);
    }
}
