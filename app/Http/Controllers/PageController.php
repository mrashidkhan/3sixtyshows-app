<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Mail\NewRegistrationMail;
use App\Models\PhotoGallery;
use App\Mail\RegistrationConfirmationMail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->where(function ($q) {
            $q->where('start_date', '<', now()->startOfDay())
              ->orWhere('status', 'completed');
        })
        ->with(['venue', 'ticketTypes'])
        ->orderBy('start_date', 'desc');

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
/**
 * GET /gallery
 *
 * Show one card per year, grouped by the linked show's start_date year.
 * Sorted newest year first. Each card carries:
 *   - year              : int
 *   - count             : number of galleries in that year
 *   - cover             : image URL of the first gallery that has an image
 *   - earliest_show_date: Carbon instance of the earliest show in that year
 *                         (used by index.blade.php to display "From MMM D, YYYY")
 */
public function galleries()
{
    $galleries = PhotoGallery::with('show')
                             ->where('is_active', true)
                             ->get();

    $galleryYears = $galleries
        // Group by the show's start_date year; fall back to created_at year
        // if the show relationship is missing.
        ->groupBy(function ($g) {
            return $g->show
                ? Carbon::parse($g->show->start_date)->year
                : $g->created_at->year;
        })
        ->sortKeysDesc()
        ->map(function ($items, $year) {
            // Cover: first gallery (sorted by show start_date asc) that has an image
            $cover = $items
                ->filter(fn($g) => $g->image)
                ->sortBy(fn($g) => $g->show?->start_date ?? $g->created_at)
                ->first();

            // Earliest show date in this year — displayed in the card body
            $earliest = $items
                ->filter(fn($g) => $g->show?->start_date)
                ->sortBy(fn($g) => $g->show->start_date)
                ->first()?->show?->start_date;

            return [
                'year'               => $year,
                'count'              => $items->count(),
                'cover'              => $cover?->image_url,
                'earliest_show_date' => $earliest,
            ];
        })
        ->values();

    return view('gallery.index', compact('galleryYears'));
}

/**
 * GET /gallery/{year}
 *
 * All active galleries whose linked show's start_date falls in $year,
 * ordered by that show's start_date descending (most-recent show first).
 */
public function galleriesByYear(int $year)
{
    abort_if($year < 2000 || $year > now()->year + 1, 404);

    $galleries = PhotoGallery::with(['show', 'photos' => function ($q) {
                                    $q->where('photosin_galleries.is_active', true)
                                      ->orderBy('photosin_galleries.display_order');
                                }])
                             ->where('photo_galleries.is_active', true)
                             ->whereHas('show', function ($q) use ($year) {
                                 $q->whereYear('start_date', $year);
                             })
                             ->join('shows', 'photo_galleries.show_id', '=', 'shows.id')
                             ->orderBy('shows.start_date', 'desc')
                             ->select('photo_galleries.*')   // prevent column collision
                             ->paginate(12)
                             ->withQueryString();

    abort_if($galleries->total() === 0, 404);

    // Year-nav pills: distinct years that have at least one active gallery
    $availableYears = PhotoGallery::with('show')
                                  ->where('is_active', true)
                                  ->whereHas('show')
                                  ->get()
                                  ->map(fn($g) => Carbon::parse($g->show->start_date)->year)
                                  ->unique()
                                  ->sortDesc()
                                  ->values();

    return view('gallery.year', compact('galleries', 'year', 'availableYears'));
}

/**
 * GET /gallery/{year}/{gallery}
 *
 * All active photos inside a single gallery, with lightbox support.
 * Year is validated against the show's start_date (not created_at).
 */
public function galleryPhotos(int $year, int $galleryId)
{
    $gallery = PhotoGallery::with('show')
                           ->where('is_active', true)
                           ->findOrFail($galleryId);

    // Validate that this gallery's show actually belongs to $year
    $galleryYear = $gallery->show
        ? Carbon::parse($gallery->show->start_date)->year
        : $gallery->created_at->year;

    abort_if($galleryYear !== $year, 404);

    $photos = $gallery->photos()
                      ->where('photosin_galleries.is_active', true)
                      ->orderBy('photosin_galleries.display_order')
                      ->paginate(24)
                      ->withQueryString();

    // Sibling galleries: same year via show->start_date, ordered by show start_date desc
    $siblingGalleries = PhotoGallery::with('show')
                                    ->where('photo_galleries.is_active', true)
                                    ->where('photo_galleries.id', '!=', $gallery->id)
                                    ->whereHas('show', function ($q) use ($year) {
                                        $q->whereYear('start_date', $year);
                                    })
                                    ->join('shows', 'photo_galleries.show_id', '=', 'shows.id')
                                    ->orderBy('shows.start_date', 'desc')
                                    ->select('photo_galleries.*')
                                    ->get();

    return view('gallery.show', compact('gallery', 'photos', 'year', 'siblingGalleries'));
}

/**
 * GET /shows/{slug}
 *
 * Public show detail page — loads the show by slug with all relations
 * needed to render tickets, venue, poster, photo gallery, and videos.
 */
/**
 * GET /shows/{slug}
 *
 * Public show detail page — always renders show-details.blade.php.
 * Never redirects to an external URL, regardless of the redirect flag.
 */
public function showDetails(string $slug)
{
    // Guard: empty slug would match any show via a broken WHERE clause
    if (empty(trim($slug))) {
        abort(404);
    }

    $show = \App\Models\Show::with([
            'venue',
            'category',
            'activeTicketTypes',
            'photos',
            'videos',
            'posters',
        ])
        ->where('slug', $slug)
        // ->where('is_active', true)
        ->firstOrFail();

    // ── Formatted date/time strings for the schedule block ───────
    $formattedStartDate = $show->start_date
        ? \Carbon\Carbon::parse($show->start_date)->format('l, F j, Y')
        : 'TBA';
    $formattedStartTime = $show->start_date
        ? \Carbon\Carbon::parse($show->start_date)->format('g:i A')
        : '';
    $formattedEndDate = $show->end_date
        ? \Carbon\Carbon::parse($show->end_date)->format('l, F j, Y')
        : 'TBA';
    $formattedEndTime = $show->end_date
        ? \Carbon\Carbon::parse($show->end_date)->format('g:i A')
        : '';

    // ── Boolean flags used by the booking widget ─────────────────
    $eventPassed = $show->start_date && \Carbon\Carbon::parse($show->start_date)->isPast();
    $isSoldOut   = $show->sold_out ?? false;

    // ── Related shows: same category, active, not this show ──────
    $relatedShows = \App\Models\Show::with(['venue'])
        ->where('is_active', true)
        ->where('id', '!=', $show->id)
        ->when($show->category_id, fn($q) => $q->where('category_id', $show->category_id))
        ->orderBy('start_date', 'desc')
        ->limit(4)
        ->get();

    return view('pages.show-details', compact(
        'show',
        'formattedStartDate', 'formattedStartTime',
        'formattedEndDate',   'formattedEndTime',
        'eventPassed',
        'isSoldOut',
        'relatedShows'
    ));
}
}
