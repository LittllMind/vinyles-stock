<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'confirmed',
        'confirmed_at',
        'confirmation_token',
        'unsubscribe_token',
    ];

    protected $casts = [
        'confirmed' => 'boolean',
        'confirmed_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (empty($subscriber->confirmation_token)) {
                $subscriber->confirmation_token = bin2hex(random_bytes(32));
            }
            if (empty($subscriber->unsubscribe_token)) {
                $subscriber->unsubscribe_token = bin2hex(random_bytes(32));
            }
        });
    }

    public function scopeConfirmed($query)
    {
        return $query->where('confirmed', true);
    }

    public function scopeUnconfirmed($query)
    {
        return $query->where('confirmed', false);
    }

    public function confirm(): void
    {
        $this->update([
            'confirmed' => true,
            'confirmed_at' => now(),
        ]);
    }

    public function regenerateConfirmationToken(): void
    {
        $this->update([
            'confirmation_token' => bin2hex(random_bytes(32)),
        ]);
    }
}
