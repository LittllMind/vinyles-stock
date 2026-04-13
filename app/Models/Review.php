<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'vinyle_id',
        'user_id',
        'rating',
        'comment',
        'status',
        'admin_response',
        'moderated_by',
        'moderated_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'moderated_at' => 'datetime',
    ];

    // Relations
    public function vinyle(): BelongsTo
    {
        return $this->belongsTo(Vinyle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForVinyle($query, $vinyleId)
    {
        return $query->where('vinyle_id', $vinyleId);
    }

    // Méthodes
    public function approve(int $moderatorId, ?string $response = null): void
    {
        $this->update([
            'status' => 'approved',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
            'admin_response' => $response,
        ]);
    }

    public function reject(int $moderatorId): void
    {
        $this->update([
            'status' => 'rejected',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
        ]);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function getRatingStars(): string
    {
        return str_repeat('⭐', $this->rating);
    }

    public function statusBadge(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge badge-warning">En attente</span>',
            'approved' => '<span class="badge badge-success">Approuvé</span>',
            'rejected' => '<span class="badge badge-error">Rejeté</span>',
            default => $this->status,
        };
    }
}