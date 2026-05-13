<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Mail\NewRegistrationMail;
use App\Models\PhotoGallery;
use App\Mail\RegistrationConfirmationMail;
use App\Services\SeatsioService;

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

        $isSearching = false;
        $location    = '';
        $date        = '';
        $query       = '';

        return view('pages.index', compact('shows', 'isSearching', 'location', 'date', 'query'));
     }

    public function aboutus(){ return view('pages.aboutus'); }
    public function registration(){ return view('pages.registration'); }

    public function registrationStore(Request $request)
    {
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

        $duplicateEmail = Registration::where('email', $validated['email'])->where('event', $validated['event'])->exists();
        $duplicatePhone = Registration::where('phone', $validated['phone'])->where('event', $validated['event'])->exists();

        if ($duplicateEmail || $duplicatePhone) {
            return response()->json([
                'success' => false,
                'message' => 'A registration with this ' . ($duplicateEmail ? 'email' : 'phone number') . ' already exists for this event.',
            ], 422);
        }

        $registration = Registration::create([
            'full_name' => $validated['full_name'],
            'email'     => strtolower(trim($validated['email'])),
            'phone'     => $validated['phone'],
            'city'      => $validated['city'],
            'event'     => $validated['event'],
            'source'    => $validated['source'] ?? null,
            'status'    => 'pending',
        ]);

        $emailError = null;
        try {
            // Mail::to('mrashid2000@gmail.com')->send(new NewRegistrationMail($registration));
            Mail::to('info@3sixtyshows.com')->send(new NewRegistrationMail($registration));
            Mail::to($registration->email)->send(new RegistrationConfirmationMail($registration));
        } catch (\Exception $e) {
            $emailError = $e->getMessage();
            Log::error('Registration email failed: ' . $emailError, ['registration_id' => $registration->id]);
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Registration successful! Confirmation will be sent to your email within 24 hours.',
            'email_error' => $emailError,
        ]);
    }

    public function events(Request $request)
    {
        $location = trim($request->get('location', ''));
        $date     = trim($request->get('date', ''));
        $query    = trim($request->get('query', ''));

        $showsQuery = \App\Models\Show::with(['venue', 'category'])
            ->where('is_active', 1)
            ->where(function($q) { $q->where('status', 'upcoming')->orWhere('status', 'ongoing'); })
            ->where('start_date', '>=', now()->startOfDay());

        if ($location !== '') {
            $showsQuery->whereHas('venue', function($q) use ($location) {
                $q->where('city', 'like', '%' . $location . '%')
                  ->orWhere('country', 'like', '%' . $location . '%')
                  ->orWhere('state', 'like', '%' . $location . '%');
            });
        }
        if ($date !== '') {
            try { $showsQuery->whereDate('start_date', \Carbon\Carbon::parse($date)->toDateString()); } catch (\Exception $e) {}
        }
        if ($query !== '') {
            $showsQuery->where(function($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                  ->orWhere('short_description', 'like', '%' . $query . '%')
                  ->orWhere('performers', 'like', '%' . $query . '%')
                  ->orWhereHas('venue', function($vq) use ($query) { $vq->where('name', 'like', '%' . $query . '%'); })
                  ->orWhereHas('category', function($cq) use ($query) { $cq->where('name', 'like', '%' . $query . '%'); });
            });
        }

        $shows = $showsQuery->orderBy('start_date', 'asc')->get();
        $isSearching = ($location !== '' || $date !== '' || $query !== '');
        return view('pages.events', compact('shows', 'isSearching', 'location', 'date', 'query'));
    }

    public function contactus(){ return view('pages.contactus'); }
    public function artists(){ return view('pages.artists'); }
    public function faq(){ return view('pages.faq'); }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['required', 'string', 'max:50'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $body =
            "New contact form submission from 3SixtyShows website.\n" .
            str_repeat('-', 50) . "\n\n" .
            "Name:    {$validated['name']}\n" .
            "Email:   {$validated['email']}\n" .
            "Phone:   {$validated['phone']}\n" .
            "Subject: {$validated['subject']}\n\n" .
            "Message:\n{$validated['message']}\n";

        try {
            // Send to admin inboxes
            Mail::raw($body, function ($mail) use ($validated) {
                $mail->to('info@3sixtyshows.com')
                     ->cc('3sixtyshows@gmail.com')
                     ->subject('Contact Form: ' . $validated['subject'])
                     ->replyTo($validated['email'], $validated['name']);
            });

            // Auto-reply to the sender
            Mail::raw(
                "Dear {$validated['name']},\n\n" .
                "Thank you for contacting 3SixtyShows!\n\n" .
                "We have received your message and will get back to you soon.\n\n" .
                "Best regards,\n3SixtyShows Team\nwww.3sixtyshows.com",
                function ($mail) use ($validated) {
                    $mail->to($validated['email'], $validated['name'])
                         ->subject('We received your message - 3SixtyShows');
                }
            );

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent! We\'ll get back to you soon.',
            ]);

        } catch (\Throwable $e) {
            Log::error('Contact form mail failed', [
                'error' => $e->getMessage(),
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sorry, we could not send your message right now. Please try again or email us directly at mrashid2000@gmail.com',
            ], 500);
        }
    }

    public function pastEvents(Request $request)
{
    $search = trim($request->get('search', ''));

    $query = \App\Models\Show::with(['venue'])
        // ✅ REMOVED: ->where('is_active', true)
        // Past events should show regardless of is_active status,
        // since admins often deactivate shows after they're done.
        ->where(function($q) {
            $q->where('status', 'past')
              ->orWhere('status', 'completed')
              ->orWhere(function($q2) {
                  $q2->where('status', 'upcoming')
                     ->where('start_date', '<', now());
              });
        })
        ->whereNotNull('slug')   // guard against detail-page 404s
        ->where('slug', '!=', '');

    if ($search !== '') {
        $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhereHas('venue', function($vq) use ($search) {
                  $vq->where('name', 'like', "%{$search}%");
              });
        });
    }

    $pastEvents = $query
        ->orderBy('start_date', 'desc')   // ✅ most recent past events first
        ->paginate(12)
        ->withQueryString();

    return view('pages.pastevents', compact('pastEvents'));
}

    public function galleries()
    {
        $galleries = PhotoGallery::with('show')->where('is_active', true)->get();
        $galleryYears = $galleries
            ->groupBy(function ($g) { return $g->show ? Carbon::parse($g->show->start_date)->year : $g->created_at->year; })
            ->sortKeysDesc()
            ->map(function ($items, $year) {
                $cover = $items->filter(fn($g) => $g->image)->sortBy(fn($g) => $g->show?->start_date ?? $g->created_at)->first();
                $earliest = $items->filter(fn($g) => $g->show?->start_date)->sortBy(fn($g) => $g->show->start_date)->first()?->show?->start_date;
                return ['year' => $year, 'count' => $items->count(), 'cover' => $cover?->image_url, 'earliest_show_date' => $earliest];
            })->values();
        return view('gallery.index', compact('galleryYears'));
    }

    public function galleriesByYear(int $year)
    {
        abort_if($year < 2000 || $year > now()->year + 1, 404);
        $galleries = PhotoGallery::with(['show', 'photos' => function ($q) { $q->where('photosin_galleries.is_active', true)->orderBy('photosin_galleries.display_order'); }])
                                 ->where('photo_galleries.is_active', true)
                                 ->whereHas('show', function ($q) use ($year) { $q->whereYear('start_date', $year); })
                                 ->join('shows', 'photo_galleries.show_id', '=', 'shows.id')
                                 ->orderBy('shows.start_date', 'desc')
                                 ->select('photo_galleries.*')
                                 ->paginate(12)->withQueryString();
        abort_if($galleries->total() === 0, 404);
        $availableYears = PhotoGallery::with('show')->where('is_active', true)->whereHas('show')->get()
                                      ->map(fn($g) => Carbon::parse($g->show->start_date)->year)->unique()->sortDesc()->values();
        return view('gallery.year', compact('galleries', 'year', 'availableYears'));
    }

    public function galleryPhotos(int $year, int $galleryId)
    {
        $gallery = PhotoGallery::with('show')->where('is_active', true)->findOrFail($galleryId);
        $galleryYear = $gallery->show ? Carbon::parse($gallery->show->start_date)->year : $gallery->created_at->year;
        abort_if($galleryYear !== $year, 404);
        $photos = $gallery->photos()->where('photosin_galleries.is_active', true)->orderBy('photosin_galleries.display_order')->paginate(24)->withQueryString();
        $siblingGalleries = PhotoGallery::with('show')->where('photo_galleries.is_active', true)->where('photo_galleries.id', '!=', $gallery->id)
                                        ->whereHas('show', function ($q) use ($year) { $q->whereYear('start_date', $year); })
                                        ->join('shows', 'photo_galleries.show_id', '=', 'shows.id')
                                        ->orderBy('shows.start_date', 'desc')->select('photo_galleries.*')->get();
        return view('gallery.show', compact('gallery', 'photos', 'year', 'siblingGalleries'));
    }

    /**
     * GET /shows/{slug}
     * Public show detail page.
     */
    public function showDetails(string $slug)
    {
        if (empty(trim($slug))) { abort(404); }

        $show = \App\Models\Show::with(['venue', 'category', 'activeTicketTypes', 'photos', 'videos', 'posters'])
            ->where('slug', $slug)
            ->firstOrFail();

        $formattedStartDate = $show->start_date ? \Carbon\Carbon::parse($show->start_date)->format('l, F j, Y') : 'TBA';
        $formattedStartTime = $show->start_date ? \Carbon\Carbon::parse($show->start_date)->format('g:i A') : '';
        $formattedEndDate   = $show->end_date   ? \Carbon\Carbon::parse($show->end_date)->format('l, F j, Y') : 'TBA';
        $formattedEndTime   = $show->end_date   ? \Carbon\Carbon::parse($show->end_date)->format('g:i A') : '';

        $eventPassed = $show->start_date && \Carbon\Carbon::parse($show->start_date)->isPast();
        $isSoldOut   = $show->sold_out ?? false;

        $relatedShows = \App\Models\Show::with(['venue'])
            ->where('is_active', true)
            ->where('id', '!=', $show->id)
            ->when($show->category_id, fn($q) => $q->where('category_id', $show->category_id))
            ->orderBy('start_date', 'desc')
            ->limit(4)
            ->get();

        // ── Generate seats.io hold token (needed for the seating map) ──
        // This reserves the user's seats while they browse and choose.
        $holdToken = null;
        if ($show->isSeatsIoReady() && $show->isSaleOpen()) {
            try {
                $holdToken = app(SeatsioService::class)->createHoldToken($show, 15);
                session(['seatsio_hold_token_' . $show->id => $holdToken]);
            } catch (\Throwable $e) {
                Log::warning('seats.io hold token failed: ' . $e->getMessage());
            }
        }

        return view('pages.show-details', compact(
            'show',
            'formattedStartDate', 'formattedStartTime',
            'formattedEndDate',   'formattedEndTime',
            'eventPassed',
            'isSoldOut',
            'relatedShows',
            'holdToken'
        ));
    }
}
