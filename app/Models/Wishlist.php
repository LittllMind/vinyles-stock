<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vinyle_id',
    ];

    /**
     * Relation : Une wishlist appartient à un utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Une wishlist appartient à un vinyle
     */
    public function vinyle(): BelongsTo
    {
        return $this->belongsTo(Vinyle::class);
    }

    /**
     * Vérifie si un vinyle est dans les favoris d'un utilisateur
     */
    public static function isInWishlist(int $userId, int $vinyleId): bool
    {
        return self::where('user_id', $userId)
            ->where('vinyle_id', $vinyleId)
            ->exists();
    }

    /**
     * Ajoute un vinyle aux favoris d'un utilisateur
     */
    public static function addToWishlist(int $userId, int $vinyleId): self|false
    {
        if (self::isInWishlist($userId, $vinyleId)) {
            return false;
        }

        return self::create([
            'user_id' => $userId,
            'vinyle_id' => $vinyleId,
        ]);
    }

    /**
     * Retire un vinyle des favoris d'un utilisateur
     */
    public static function removeFromWishlist(int $userId, int $vinyleId): bool
    {
        return self::where('user_id', $userId)
            ->where('vinyle_id', $vinyleId)
            ->delete() > 0;
    }

    /**
     * Récupère les favoris d'un utilisateur avec les vinyles
     */
    public static function getUserWishlist(int $userId)
    {
        return self::with('vinyle')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }
}
