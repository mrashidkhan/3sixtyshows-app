<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Mail\NewRegistrationMail;
use App\Models\PhotoGallery;
use App\Mail\RegistrationConfirmationMail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function index()
    {
        $shows = \App\Models\Show::with(['venue', 'category'])
        ->where('is_active', 1)
        ->where(function($query) {
            $query->where('status', 'upcoming')
                  ->orWhere('status', 'ongoing');
        })
        ->where('start_date', '>=', now()->startOfDay())
        ->orderBy('start_date', 'asc')
        ->get();

        // Pass search defaults so partials/events.blade.php never throws undefined variable
        $isSearching = false;
        $location    = '';
        $date        = '';
        $query       = '';

        return view('pages.index', compact('shows', 'isSearching', 'location', 'date', 'query'));
     }

    public function aboutus(){
        return view('pages.aboutus');
    }

    public function registration(){
        return view('pages.registration');
    }

    public function registrationStore(Request $request)
    {
        // 1. Validate
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email',  'max:150'],
            'phone'     => ['required', 'string', 'max:20'],
            'city'      => ['required', 'string', 'max:100'],
            'event'     => ['required', 'string', 'max:100'],
            'source'    => ['nullable', 'string', 'max:50'],
            'terms'     => ['required', 'accepted'],
        ], [
            'full_name.required' => 'Please enter your full name.',
            'email.required'     => 'Please enter a valid email address.',
            'email.email'        => 'Please enter a valid email address.',
            'phone.required'     => 'Please enter your phone number.',
            'city.required'      => 'Please enter your city.',
            'event.required'     => 'Please select an event.',
            'terms.required'     => 'Please accept the terms to continue.',
            'terms.accepted'     => 'Please accept the terms to continue.',
        ]);

        // 2. Check duplicates
        $duplicateEmail = Registration::where('email', $validated['email'])
                                      ->where('event', $validated['event'])
                                      ->exists();
        $duplicatePhone = Registration::where('phone', $validated['phone'])
                                      ->where('event', $validated['event'])
                                      ->exists();

        if ($duplicateEmail || $duplicatePhone) {
            return response()->json([
                'success' => false,
                'message' => 'A registration with this ' . ($duplicateEmail ? 'email' : 'phone number') . ' already exists for this event.',
            ], 422);
        }

        // 3. Save to database
        $registration = Registration::create([
            'full_name' => $validated['full_name'],
            'email'     => strtolower(trim($validated['email'])),
            'phone'     => $validated['phone'],
            'city'      => $validated['city'],
            'event'     => $validated['event'],
            'source'    => $validated['source'] ?? null,
            'status'    => 'pending',
        ]);

        // 4. Send emails — debug mode: error exposed in JSON response
        $emailError = null;
        try {
            Mail::to('mrashid2000@gmail.com')
                ->send(new NewRegistrationMail($registration));

            Mail::to('3sixtyshow@gmail.com')
                ->send(new NewRegistrationMail($registration));

            Mail::to($registration->email)
                ->send(new RegistrationConfirmationMail($registration));

        } catch (\Exception $e) {
            $emailError = $e->getMessage();
            Log::error('Registration email failed: ' . $emailError, [
                'registration_id' => $registration->id,
                'client_email'    => $registration->email,
            ]);
        }

        // 5. Return JSON — email_error will be null if sent OK, or show exact error
        // TODO: remove 'email_error' from response once email is confirmed working
        return response()->json([
            'success'     => true,
            'message'     => 'Registration successful! Confirmation will be sent to your email within 24 hours.',
            'email_error' => $emailError,
        ]);
    }

    public function events(Request $request)
    {
        // ── Search parameters from header search bar ─────────────────────
        $location = trim($request->get('location', ''));
        $date     = trim($request->get('date', ''));
        $query    = trim($request->get('query', ''));

        // ── Base query ────────────────────────────────────────────────────
        $showsQuery = \App\Models\Show::with(['venue', 'category'])
            ->where('is_active', 1)
            ->where(function($q) {
                $q->where('status', 'upcoming')
                  ->orWhere('status', 'ongoing');
            })
            ->where('start_date', '>=', now()->startOfDay());

        // ── Filter: Location (city or zip against venue) ──────────────────
        if ($location !== '') {
            $showsQuery->whereHas('venue', function($q) use ($location) {
                $q->where('city', 'like', '%' . $location . '%')
                  ->orWhere('country', 'like', '%' . $location . '%')
                  ->orWhere('state', 'like', '%' . $location . '%');
            });
        }

        // ── Filter: Date (exact day match) ────────────────────────────────
        if ($date !== '') {
            try {
                $parsed = \Carbon\Carbon::parse($date);
                $showsQuery->whereDate('start_date', $parsed->toDateString());
            } catch (\Exception $e) {
                // Invalid date — ignore filter
            }
        }

        // ── Filter: Keyword (title, description, performers, venue name) ──
        if ($query !== '') {
            $showsQuery->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('short_description', 'like', '%' . $query . '%')
                  ->orWhere('performers', 'like', '%' . $query . '%')
                  ->orWhereHas('venue', function($vq) use ($query) {
                      $vq->where('name', 'like', '%' . $query . '%');
                  })
                  ->orWhereHas('category', function($cq) use ($query) {
                      $cq->where('name', 'like', '%' . $query . '%');
                  });
            });
        }

        $shows = $showsQuery->orderBy('start_date', 'asc')->get();

        // ── Was a search actually performed? ──────────────────────────────
        $isSearching = ($location !== '' || $date !== '' || $query !== '');

        return view('pages.events', compact('shows', 'isSearching', 'location', 'date', 'query'));
    }

    public function contactus(){
        return view('pages.contactus');
    }

    public function artists(){
        return view('pages.artists');
    }

    public function faq(){
        return view('pages.faq');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['required', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        // Rename 'message' → 'user_message' because $message is a reserved
        // variable in Laravel mail views (it refers to the Mailer object).
        $data = [
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'phone'        => $validated['phone'],
            'subject'      => $validated['subject'],
            'user_message' => $validated['message'],
        ];

        $emailError = null;
        try {
            // Send confirmation to the user
            Mail::send('emails.contact_user', $data, function ($mail) use ($data) {
                $mail->to($data['email'], $data['name'])
                     ->subject('We received your message – ' . $data['subject']);
            });

            // Send notification to admin
            Mail::send('emails.contact_admin', $data, function ($mail) use ($data) {
                $mail->to('3sixtyshows@gmail.com', '3Sixty Shows')
                     ->replyTo($data['email'], $data['name'])
                     ->subject('New Contact Form: ' . $data['subject']);
            });

        } catch (\Exception $e) {
            $emailError = $e->getMessage();
            Log::error('Contact email failed: ' . $emailError);
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Your message has been sent successfully! We will get back to you soon.',
            'email_error' => $emailError, // Remove this line once email is confirmed working
        ]);
    }

    /**
 * Past Events page
 * Shows all shows whose end date/start date is in the past,
 * with optional search filters (location, date, query).
 */
public function pastEvents(Request $request)
{
    $query = \App\Models\Show::query()
        ->where(function ($q) {
            // A show is "past" when its start_date is before today
            $q->where('start_date', '<', now()->startOfDay())
              ->orWhere('status', 'completed');
        })
        ->with(['venue', 'ticketTypes'])
        ->orderBy('start_date', 'desc'); // Most recent past events first

    // ── Search filters (mirrors events() method) ─────────────
    if ($request->filled('location')) {
        $location = $request->input('location');
        $query->whereHas('venue', function ($q) use ($location) {
            $q->where('city', 'like', "%{$location}%")
              ->orWhere('state', 'like', "%{$location}%")
              ->orWhere('zip_code', 'like', "%{$location}%")
              ->orWhere('name', 'like', "%{$location}%");
        });
    }

    if ($request->filled('date')) {
        $query->whereDate('start_date', $request->input('date'));
    }

    if ($request->filled('query')) {
        $search = $request->input('query');
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhereHas('venue', function ($vq) use ($search) {
                  $vq->where('name', 'like', "%{$search}%");
              });
        });
    }

    $pastEvents = $query->paginate(12)->withQueryString();

    return view('pages.pastevents', compact('pastEvents'));
}

// ═══════════════════════════════════════════════════════════════
//  ADD THESE METHODS TO App\Http\Controllers\PageController
// ═══════════════════════════════════════════════════════════════
//
//  Also add this use-statement at the top of PageController.php
//  (if not already present):
//
//  use App\Models\PhotoGallery;
//  use App\Models\PhotosinGallery;
// ═══════════════════════════════════════════════════════════════

/**
 * GET /gallery
 *
 * Show all distinct years that have at least one active gallery,
 * along with a cover image and gallery count for each year.
 */
public function galleries()
{
    // Pull every active gallery with its photos eager-loaded (just one photo
    // per gallery is needed for the year-card cover, but we load the relation
    // so Blade can access it without N+1 queries).
    $galleries = PhotoGallery::with(['show', 'photos' => function ($q) {
                                    $q->where('is_active', true)
                                      ->orderBy('display_order')
                                      ->limit(1);
                                }])
                             ->where('is_active', true)
                             ->orderByDesc('created_at')
                             ->get();

    // Group by the year extracted from created_at (or use show->start_date if preferred)
    $galleryYears = $galleries
        ->groupBy(fn($g) => $g->created_at->year)
        ->sortKeysDesc()                // newest year first
        ->map(function ($yearGalleries, $year) {
            return [
                'year'       => $year,
                'count'      => $yearGalleries->count(),
                'cover'      => $yearGalleries->first()?->image_with_fallback,
                'galleries'  => $yearGalleries,
            ];
        });

    return view('gallery.index', compact('galleryYears'));
}

/**
 * GET /gallery/{year}
 *
 * Show every active gallery created in the given year,
 * with thumbnail, title, photo count and description.
 */
public function galleriesByYear(int $year)
{
    abort_if($year < 2000 || $year > now()->year + 1, 404);

    $galleries = PhotoGallery::with(['show', 'photos' => function ($q) {
                                    $q->where('is_active', true)
                                      ->orderBy('display_order');
                                }])
                             ->where('is_active', true)
                             ->whereYear('created_at', $year)
                             ->orderBy('display_order')
                             ->orderByDesc('created_at')
                             ->paginate(12)
                             ->withQueryString();

    abort_if($galleries->total() === 0, 404);

    // Surrounding years that also have galleries (for prev/next nav)
    $availableYears = PhotoGallery::selectRaw('YEAR(created_at) as yr')
                                  ->where('is_active', true)
                                  ->groupBy('yr')
                                  ->orderByDesc('yr')
                                  ->pluck('yr')
                                  ->map(fn($y) => (int) $y);

    return view('gallery.year', compact('galleries', 'year', 'availableYears'));
}

/**
 * GET /gallery/{year}/{gallery}
 *
 * Show all active photos inside a single gallery, with lightbox support.
 * Uses gallery ID for routing (no slug column required).
 */
public function galleryPhotos(int $year, int $galleryId)
{
    $gallery = PhotoGallery::where('is_active', true)->findOrFail($galleryId);

    // Make sure the gallery actually belongs to the requested year
    abort_if((int) $gallery->created_at->year !== $year, 404);

    $photos = $gallery->photos()
                      ->where('is_active', true)
                      ->orderBy('display_order')
                      ->paginate(24)
                      ->withQueryString();

    // Sibling galleries in the same year for the bottom scroll strip
    $siblingGalleries = PhotoGallery::where('is_active', true)
                                    ->whereYear('created_at', $year)
                                    ->where('id', '!=', $gallery->id)
                                    ->orderBy('display_order')
                                    ->get(['id', 'title', 'image', 'created_at']);

    return view('gallery.show', compact('gallery', 'photos', 'year', 'siblingGalleries'));
}
}
