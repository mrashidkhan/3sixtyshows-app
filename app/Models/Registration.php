<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'city',
        'event',
        'source',
        'status',
    ];

    protected $casts = [
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    /**
     * Scope: filter by event
     * Usage: Registration::forEvent('bismil_ki_mehfil_houston')->get();
     */
    public function scopeForEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    /**
     * Scope: filter by status
     * Usage: Registration::pending()->get();
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeDisqualified($query)
    {
        return $query->where('status', 'disqualified');
    }
}
