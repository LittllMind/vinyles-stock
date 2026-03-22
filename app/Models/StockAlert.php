<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockAlert extends Model
{
    use HasFactory;

    public const TYPE_STOCK_BAS = 'stock_bas';

    protected $fillable = [
        'alertable_type',
        'alertable_id',
        'type',
        'message',
        'resolu',
    ];

    protected $casts = [
        'resolu' => 'boolean',
    ];

    /** @return MorphTo */
    public function alertable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Marque l'alerte comme résolue.
     */
    public function resoudre(): void
    {
        $this->update(['resolu' => true]);
    }

    /**
     * Scope pour les alertes actives (non résolues).
     */
    public function scopeActives($query)
    {
        return $query->where('resolu', false);
    }
}
