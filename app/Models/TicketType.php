<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'show_id',
        'price',
        'capacity',
        'is_active',
        'display_order',
        'seatsio_category_key',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'price'         => 'decimal:2',
        'capacity'      => 'integer',
        'display_order' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    public function getFormattedPriceAttribute()
    {
        if ($this->price == 0) {
            return 'Free';
        }
        return '$' . number_format($this->price, 2);
    }

    public function getAvailableTicketsAttribute()
    {
        if (!$this->capacity) {
            return null; // Unlimited
        }
        return max(0, $this->capacity - $this->tickets()->count());
    }

    public function getSoldTicketsAttribute()
    {
        return $this->tickets()->count();
    }

    public function getIsSoldOutAttribute()
    {
        if (!$this->capacity) {
            return false;
        }
        return $this->sold_tickets >= $this->capacity;
    }

    /**
     * Whether this ticket type has a seats.io category key configured.
     */
    public function getHasSeatsioCategoryAttribute(): bool
    {
        return !empty($this->seatsio_category_key);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Only ticket types that are linked to a seats.io category.
     */
    public function scopeWithSeatsioCategory($query)
    {
        return $query->whereNotNull('seatsio_category_key')->where('seatsio_category_key', '!=', '');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function hasAvailableTickets($quantity = 1)
    {
        if (!$this->capacity) {
            return true;
        }
        return $this->available_tickets >= $quantity;
    }
}
