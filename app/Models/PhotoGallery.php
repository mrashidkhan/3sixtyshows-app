<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoGallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_id', 'title', 'image', 'description',
        'display_order', 'is_featured', 'is_active'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // A photo belongs to a show
    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    // A photo gallery has many photos
    public function photos()
    {
        return $this->hasMany(PhotosinGallery::class, 'photo_gallery_id');
    }

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null; // Return null instead of placeholder to avoid infinite loops
        }

        // If image already contains full URL, return as is
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        // If image path starts with 'storage/', use asset() directly
        if (str_starts_with($this->image, 'storage/')) {
            return asset($this->image);
        }

        // Otherwise, assume it's in storage folder
        return asset('storage/' . $this->image);
    }

    // Check if image exists
    public function getHasImageAttribute()
    {
        return !empty($this->image);
    }

    // Get image with fallback
    public function getImageWithFallbackAttribute()
    {
        if ($this->has_image) {
            return $this->image_url;
        }

        // Return a simple data URL for a placeholder instead of file path
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300">
                <rect width="400" height="300" fill="#f8f9fa"/>
                <text x="200" y="150" text-anchor="middle" fill="#6c757d" font-family="Arial" font-size="16">No Gallery Cover</text>
            </svg>
        ');
    }
}
