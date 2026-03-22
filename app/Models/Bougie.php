<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Bougie extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'parfum',
        'nom',
        'collection',
        'format',
        'type_cire',
        'temps_brulure',
        'notes',
        'prix',
        'quantite',
        'seuil_alerte',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'quantite' => 'integer',
        'seuil_alerte' => 'integer',
        'temps_brulure' => 'integer',
    ];

    public function isEnAlerte(): bool
    {
        return $this->quantite < $this->seuil_alerte;
    }

    public function stockAlerts(): MorphMany
    {
        return $this->morphMany(StockAlert::class, 'alertable');
    }

    /** @return MorphMany */
    public function mouvementsStock(): MorphMany
    {
        return $this->morphMany(MouvementStock::class, 'stockable');
    }
}
