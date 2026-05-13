<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\BookingItem;
use App\Models\TicketHold;


class Show extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'category_id', 'venue_id', 'description',
        'short_description', 'featured_image', 'start_date', 'end_date',
        'price', 'available_tickets', 'is_featured', 'status',
        'performers', 'additional_info', 'duration', 'age_restriction', 'is_active',
        'redirect', 'redirect_url',
        // seats.io fields (Migration 1)
        'ticketing_mode', 'seatsio_chart_key', 'seatsio_event_key',
        'seatsio_public_key', 'tickets_on_sale', 'sale_starts_at',
    ];

    protected $casts = [
        // 'performers' => 'array',
        'additional_info'  => 'array',
        'start_date'       => 'datetime',
        'end_date'         => 'datetime',
        'is_featured'      => 'boolean',
        'is_active'        => 'boolean',
        'redirect'         => 'boolean',
        // seats.io casts — ADDED
        'tickets_on_sale'  => 'boolean',
        'sale_starts_at'   => 'datetime',
    ];

    // ------------------------------------------------------------------
    // Ticketing mode constants (matches Migration 1 enum values)
    // ------------------------------------------------------------------
    const TICKETING_NONE               = 'none';
    const TICKETING_GENERAL_ADMISSION  = 'general_admission';
    const TICKETING_RESERVED           = 'reserved';
    const TICKETING_MIXED              = 'mixed';

    // ------------------------------------------------------------------
    // Relationships
    // ------------------------------------------------------------------

    public function category()
    {
        return $this->belongsTo(ShowCategory::class, 'category_id');
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function posters()
    {
        return $this->hasMany(Poster::class);
    }

    public function photos()
    {
        return $this->hasMany(PhotoGallery::class);
    }

    public function videos()
    {
        return $this->hasMany(VideoGallery::class);
    }

    public function seatReservations()
    {
        return $this->hasMany(SeatReservation::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class)
                    ->orderBy('display_order', 'asc')
                    ->orderBy('name', 'asc');
    }

    public function activeTicketTypes()
    {
        return $this->hasMany(TicketType::class)
                    ->where('is_active', true)
                    ->orderBy('display_order', 'asc')
                    ->orderBy('name', 'asc');
    }

    // ------------------------------------------------------------------
    // seats.io helper methods
    // ------------------------------------------------------------------

    /**
     * Returns true if this show uses seats.io for any ticketing mode
     * (reserved or mixed). GA-only shows do NOT need the seats.io widget.
     */
    public function usesSeatsIo(): bool
    {
        return in_array($this->ticketing_mode, [
            self::TICKETING_RESERVED,
            self::TICKETING_MIXED,
        ]);
    }

    /**
     * Returns the effective seats.io public key for this show.
     * Falls back to the global .env key if no per-show override is set.
     */
    public function getSeatsioPublicKeyAttribute($value): string
    {
        return $value ?: config('services.seatsio.public_key', '');
    }

    /**
     * Whether ticket sales are currently open for this show.
     */
    public function isSaleOpen(): bool
    {
        if (!$this->tickets_on_sale) {
            return false;
        }
        if ($this->sale_starts_at && $this->sale_starts_at->isFuture()) {
            return false;
        }
        return true;
    }

    /**
     * Whether the show is fully configured for seats.io reserved seating.
     */
    public function isSeatsIoReady(): bool
    {
        return $this->usesSeatsIo()
            && !empty($this->seatsio_chart_key)
            && !empty($this->seatsio_event_key);
    }

    // ------------------------------------------------------------------
    // Existing methods (unchanged)
    // ------------------------------------------------------------------

    public function hasTicketTypes(): bool
    {
        return $this->ticketTypes()->count() > 0;
    }

    public function hasActiveTicketTypes(): bool
    {
        return $this->activeTicketTypes()->count() > 0;
    }

    public function getSoldTicketsAttribute()
    {
        return $this->bookings()->where('status', 'confirmed')->sum('number_of_tickets');
    }

    public function getSoldOutAttribute()
    {
        if ($this->available_tickets === null) {
            return false;
        }
        return $this->sold_tickets >= $this->available_tickets;
    }

    public function getFormattedPriceAttribute()
    {
        if ($this->price === null || $this->price == 0) {
            return 'Free';
        }
        return '$' . number_format($this->price, 2);
    }

    public function updateStatus()
    {
        $now = Carbon::now();

        if ($this->status === 'cancelled') {
            return;
        }

        if ($this->end_date && $now > $this->end_date) {
            $this->status = 'past';
        } elseif ($now > $this->start_date) {
            $this->status = 'ongoing';
        } else {
            $this->status = 'upcoming';
        }

        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($show) {
            $show->slug = $show->slug ?: Str::slug($show->title);
        });

        static::updating(function ($show) {
            if ($show->isDirty('title') && !$show->isDirty('slug')) {
                $show->slug = Str::slug($show->title);
            }
        });
    }

    // Scopes
    public function scopeActive($query)       { return $query->where('is_active', true); }
    public function scopeFeatured($query)     { return $query->where('is_featured', true); }
    public function scopeUpcoming($query)     { return $query->where('status', 'upcoming'); }
    public function scopePast($query)         { return $query->where('status', 'past'); }
    public function scopeOngoing($query)      { return $query->where('status', 'ongoing'); }

    public function getAvailableSeatsAttribute()
    {
        $reservedSeatIds = $this->seatReservations()
            ->whereIn('status', ['booked', 'reserved'])
            ->pluck('seat_id');

        return Seat::where('venue_id', $this->venue_id)
            ->where('is_active', true)
            ->whereNotIn('id', $reservedSeatIds)
            ->get();
    }

    public function getAvailableTicketTypes()
    {
        return $this->ticketTypes()->where('is_active', true)->orderBy('display_order')->get();
    }

    public function getAvailableCapacityForTicketType($ticketTypeId)
    {
        $ticketType = $this->ticketTypes()->find($ticketTypeId);
        if (!$ticketType) return 0;
        if (!$ticketType->capacity) return 999;

        $sold = BookingItem::whereHas('booking', function ($query) {
            $query->where('show_id', $this->id)->where('status', 'confirmed');
        })->where('ticket_type_id', $ticketTypeId)->sum('quantity');

        $held = TicketHold::where('show_id', $this->id)
            ->where('ticket_type_id', $ticketTypeId)
            ->where('expires_at', '>', now())
            ->sum('quantity');

        return max(0, $ticketType->capacity - $sold - $held);
    }

    public function getTotalSoldTickets()
    {
        return BookingItem::whereHas('booking', function ($query) {
            $query->where('show_id', $this->id)->where('status', 'confirmed');
        })->sum('quantity');
    }

    public function hasAvailableTickets()
    {
        foreach ($this->getAvailableTicketTypes() as $ticketType) {
            if ($this->getAvailableCapacityForTicketType($ticketType->id) > 0) {
                return true;
            }
        }
        return false;
    }

    public function getTotalCapacityAttribute()
    {
        return $this->ticketTypes()->sum('capacity') ?: null;
    }

    public function getTotalSoldTicketsAttribute()
    {
        return $this->ticketTypes()->withCount('tickets')->get()->sum('tickets_count');
    }

    public function hasAnySoldOutTicketTypes()
    {
        return $this->ticketTypes()->get()->contains(function ($ticketType) {
            return $ticketType->capacity && $ticketType->tickets()->count() >= $ticketType->capacity;
        });
    }
}
