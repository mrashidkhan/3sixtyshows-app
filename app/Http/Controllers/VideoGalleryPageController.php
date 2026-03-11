<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\VideoGallery;

/**
 * Public-facing Video Gallery controller.
 * Mirrors PageController::galleries / galleriesByYear / galleryPhotos
 * but for VideoGallery → VideosinGallery (videos, youtubelink).
 *
 * Routes (defined in routes/web.php):
 *   GET /video-gallery                      → index()      (video-gallery.index)
 *   GET /video-gallery/{year}               → byYear()     (video-gallery.year)
 *   GET /video-gallery/{year}/{gallery_id}  → show()       (video-gallery.show)
 */
class VideoGalleryPageController extends Controller
{
    /**
     * GET /video-gallery
     *
     * One card per year, grouped by the linked show's start_date year.
     * Sorted newest year first. Passes $galleryYears to the view, each entry:
     *   year               => int
     *   count              => number of video galleries in that year
     *   cover              => thumbnail URL (first gallery with a thumbnail)
     *   earliest_show_date => Carbon of earliest show start_date in that year
     */
    public function index()
    {
        $galleries = VideoGallery::with('show')
                                 ->where('is_active', true)
                                 ->get();

        $galleryYears = $galleries
            ->groupBy(function ($g) {
                return $g->show
                    ? Carbon::parse($g->show->start_date)->year
                    : $g->created_at->year;
            })
            ->sortKeysDesc()
            ->map(function ($items, $year) {
                // Cover: first gallery sorted by show start_date asc that has a thumbnail
                $cover = $items
                    ->filter(fn($g) => $g->thumbnail)
                    ->sortBy(fn($g) => $g->show?->start_date ?? $g->created_at)
                    ->first();

                // Earliest show date in the year — shown in the card body
                $earliest = $items
                    ->filter(fn($g) => $g->show?->start_date)
                    ->sortBy(fn($g) => $g->show->start_date)
                    ->first()?->show?->start_date;

                return [
                    'year'               => $year,
                    'count'              => $items->count(),
                    'cover'              => $cover ? $this->thumbnailUrl($cover->thumbnail) : null,
                    'earliest_show_date' => $earliest,
                ];
            })
            ->values();

        return view('videogallery.index', compact('galleryYears'));
    }

    /**
     * GET /video-gallery/{year}
     *
     * All active video galleries whose show's start_date falls in $year,
     * ordered by show start_date descending (most-recent show first).
     */
    public function byYear(int $year)
    {
        abort_if($year < 2000 || $year > now()->year + 1, 404);

        $galleries = VideoGallery::with(['show', 'videos' => function ($q) {
                                        $q->where('videosin_galleries.is_active', true)
                                          ->orderBy('videosin_galleries.display_order');
                                    }])
                                 ->where('video_galleries.is_active', true)
                                 ->whereHas('show', function ($q) use ($year) {
                                     $q->whereYear('start_date', $year);
                                 })
                                 ->join('shows', 'video_galleries.show_id', '=', 'shows.id')
                                 ->orderBy('shows.start_date', 'desc')
                                 ->select('video_galleries.*')
                                 ->paginate(12)
                                 ->withQueryString();

        abort_if($galleries->total() === 0, 404);

        // Year-nav pills
        $availableYears = VideoGallery::with('show')
                                      ->where('is_active', true)
                                      ->whereHas('show')
                                      ->get()
                                      ->map(fn($g) => Carbon::parse($g->show->start_date)->year)
                                      ->unique()
                                      ->sortDesc()
                                      ->values();

        return view('videogallery.year', compact('galleries', 'year', 'availableYears'));
    }

    /**
     * GET /video-gallery/{year}/{gallery_id}
     *
     * All active videos inside a single VideoGallery.
     * Year validated against the show's start_date.
     */
    public function show(int $year, int $galleryId)
    {
        $gallery = VideoGallery::with('show')
                               ->where('is_active', true)
                               ->findOrFail($galleryId);

        $galleryYear = $gallery->show
            ? Carbon::parse($gallery->show->start_date)->year
            : $gallery->created_at->year;

        abort_if($galleryYear !== $year, 404);

        $videos = $gallery->videos()
                          ->where('videosin_galleries.is_active', true)
                          ->orderBy('videosin_galleries.display_order')
                          ->paginate(24)
                          ->withQueryString();

        // Sibling galleries: same year, ordered by show start_date desc
        $siblingGalleries = VideoGallery::with('show')
                                        ->where('video_galleries.is_active', true)
                                        ->where('video_galleries.id', '!=', $gallery->id)
                                        ->whereHas('show', function ($q) use ($year) {
                                            $q->whereYear('start_date', $year);
                                        })
                                        ->join('shows', 'video_galleries.show_id', '=', 'shows.id')
                                        ->orderBy('shows.start_date', 'desc')
                                        ->select('video_galleries.*')
                                        ->get();

        return view('videogallery.show', compact('gallery', 'videos', 'year', 'siblingGalleries'));
    }

    // ── Helpers ───────────────────────────────────────────────────

    /**
     * Resolve a stored thumbnail path to a public URL,
     * mirroring PhotoGallery::getImageUrlAttribute().
     */
    private function thumbnailUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        if (str_starts_with($path, 'storage/')) return asset($path);
        return asset('storage/' . $path);
    }

    /**
     * Extract an 11-character YouTube video ID from any YouTube URL,
     * or return null if the string isn't a recognised YouTube link.
     *
     * Handles:
     *   https://www.youtube.com/watch?v=XXXXXXXXXXX
     *   https://youtu.be/XXXXXXXXXXX
     *   https://www.youtube.com/embed/XXXXXXXXXXX
     *   https://www.youtube.com/shorts/XXXXXXXXXXX
     */
    public static function youtubeId(?string $url): ?string
    {
        if (!$url) return null;

        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([A-Za-z0-9_\-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $m)) {
                return $m[1];
            }
        }

        return null;
    }
}
