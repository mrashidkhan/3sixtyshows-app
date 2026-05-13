<?php

/*
|--------------------------------------------------------------------------
| routes/web.php — 3Sixtyshows
|--------------------------------------------------------------------------
|
| Controllers in use:
|   Public/User:
|     PageController              — public pages & show details
|     ContactController           — contact form
|     BaseController              — user auth (login/register/logout)
|     VideoGalleryPageController  — public video gallery pages
|     GalleryController           — gallery index & photos API
|     GeneralAdmissionController  — GA ticket flow + PayPal redirect
|     BookingController           — seats.io reserved + GA unified entry
|     PaymentController           — PayPal capture, success, cancel, failed
|     TicketController            — ticket download / QR / PDF
|
|   Admin:
|     AdminController             — admin auth & dashboard
|     CategoryController          — show categories
|     ShowController              — show CRUD
|     VenueController             — venue CRUD
|     PhotoGalleryController      — photo gallery CRUD
|     VideoGalleryController      — video gallery CRUD
|     PhotosinGalleryController   — photos inside gallery CRUD
|     VideosinGalleryController   — videos inside gallery CRUD
|     CustomerController          — customer management
|     AdminBookingController      — booking management, reports, scanning
|     TicketTypeController        — ticket type CRUD
|
|   Webhooks (no CSRF):
|     GeneralAdmissionController  — PayPal webhook
|     SeatsioWebhookController    — seats.io webhook
|
| Also add to app/Http/Middleware/VerifyCsrfToken.php:
|   protected $except = [
|       'webhooks/seatsio',
|       'webhooks/payment/paypal',
|   ];
|
*/

use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
// Imports — public & user controllers
// ─────────────────────────────────────────────────────────────────────────────
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\VideoGalleryPageController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GeneralAdmissionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SeatsioWebhookController;

// ─────────────────────────────────────────────────────────────────────────────
// Imports — admin controllers
// ─────────────────────────────────────────────────────────────────────────────
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BaseController as UserBaseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\ShowController;
use App\Http\Controllers\Admin\VenueController;
use App\Http\Controllers\Admin\PhotoGalleryController;
use App\Http\Controllers\Admin\VideoGalleryController;
use App\Http\Controllers\Admin\PhotosinGalleryController;
use App\Http\Controllers\Admin\VideosinGalleryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\TicketTypeController;

// =============================================================================
// PUBLIC ROUTES
// =============================================================================

Route::get('/', [PageController::class, 'index'])->name('index');
Route::get('/aboutus', [PageController::class, 'aboutus'])->name('aboutus');
Route::get('/contactus', [PageController::class, 'contactus'])->name('contactus');
Route::get('/events', [PageController::class, 'events'])->name('events');
Route::get('/artists', [PageController::class, 'artists'])->name('artists');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/registration', [PageController::class, 'registration'])->name('registration');
Route::post('/registration', [PageController::class, 'registrationStore'])->name('register.store');
Route::get('/past-events', [PageController::class, 'pastEvents'])->name('pastevents');
Route::get('/activeevents', [PageController::class, 'activeevents'])->name('activeevents');
Route::get('/posters', [PageController::class, 'posters'])->name('posters');
Route::get('/upcomingposters', [PageController::class, 'upcomingposters'])->name('upcomingposters');
Route::get('/service', [PageController::class, 'service'])->name('service');
Route::get('/team', [PageController::class, 'team'])->name('team');
Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/seatselection', [PageController::class, 'selection'])->name('seatselection');

// Contact
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/contact/send', [PageController::class, 'sendContact'])->name('contact.send');

// Public Show Pages
Route::get('/shows/{slug}', [PageController::class, 'showDetails'])->name('show.details');
Route::get('/shows/{slug}/booking', [PageController::class, 'showBooking'])->name('show.booking');

// Photo Gallery (public)
Route::prefix('gallery')->name('gallery.')->group(function () {
    Route::get('/', [PageController::class, 'galleries'])->name('index');
    Route::get('/{year}', [PageController::class, 'galleriesByYear'])->name('year')->where('year', '[0-9]{4}');
    Route::get('/{year}/{gallery}', [PageController::class, 'galleryPhotos'])->name('show')->where('gallery', '[0-9]+');
});

// Video Gallery (public)
Route::prefix('video-gallery')->name('video-gallery.')->group(function () {
    Route::get('/', [VideoGalleryPageController::class, 'index'])->name('index');
    Route::get('/{year}', [VideoGalleryPageController::class, 'byYear'])->name('year')->where('year', '[0-9]{4}');
    Route::get('/{year}/{gallery}', [VideoGalleryPageController::class, 'show'])->name('show')
        ->where('year', '[0-9]{4}')
        ->where('gallery', '[0-9]+');
});

// Gallery API (used by public gallery pages)
Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/gallery/{galleryId}/photos', [GalleryController::class, 'getGalleryPhotos'])->name('gallery.photos');

// Admin-side gallery indexes (public read)
Route::get('/photo-galleries', [PhotoGalleryController::class, 'clientIndex'])->name('photo-galleries');
Route::get('/video-galleries', [VideoGalleryController::class, 'clientIndex'])->name('video-galleries');

// =============================================================================
// USER AUTHENTICATION
// =============================================================================

Route::get('user/login', [BaseController::class, 'loginCheck'])->name('user_login');
Route::post('user/login', [BaseController::class, 'loginCheck'])->name('logincheck');
Route::post('user/register', [BaseController::class, 'user_store'])->name('user_store');
Route::get('user/logout', [BaseController::class, 'logout'])->name('user_logout');

// =============================================================================
// ADMIN AUTHENTICATION
// =============================================================================

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'makeLogin'])->name('admin.makeLogin');
Route::get('/login', fn() => redirect()->route('admin.login'))->name('login');

// =============================================================================
// ADMIN PANEL (auth required)
// =============================================================================

Route::middleware('auth')->group(function () {

    // Dashboard & logout
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // ── Show Categories ───────────────────────────────────────────────────────
    Route::get('/showcategories', [CategoryController::class, 'index'])->name('showcategory.list');
    Route::get('/showcategory/add', [CategoryController::class, 'create'])->name('showcategory.create');
    Route::post('/showcategory/add', [CategoryController::class, 'store'])->name('showcategory.store');
    Route::get('/showcategories/edit/{id}', [CategoryController::class, 'edit'])->name('showcategory.edit');
    Route::put('/showcategories/update/{id}', [CategoryController::class, 'update'])->name('showcategory.update');
    Route::post('/showcategory/delete/{id}', [CategoryController::class, 'destroy'])->name('showcategory.delete');

    // ── Shows ─────────────────────────────────────────────────────────────────
    Route::get('/shows', [ShowController::class, 'index'])->name('show.index');
    Route::get('/show/add', [ShowController::class, 'create'])->name('show.create');
    Route::post('/show/add', [ShowController::class, 'store'])->name('show.store');
    Route::get('/show/edit/{id}', [ShowController::class, 'edit'])->name('show.edit');
    Route::put('/show/update/{id}', [ShowController::class, 'update'])->name('show.update');
    Route::post('/show/delete/{id}', [ShowController::class, 'destroy'])->name('show.delete');
    Route::get('/admin/show/{id}', [ShowController::class, 'show'])->name('shows.show');

    // ── Venues ────────────────────────────────────────────────────────────────
    Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
    Route::get('/venue/add', [VenueController::class, 'create'])->name('venue.create');
    Route::post('/venue/add', [VenueController::class, 'store'])->name('venue.store');
    Route::get('/venue/edit/{id}', [VenueController::class, 'edit'])->name('venue.edit');
    Route::put('/venue/update/{id}', [VenueController::class, 'update'])->name('venue.update');
    Route::post('/venue/delete/{id}', [VenueController::class, 'destroy'])->name('venue.delete');

    // ── Photo Gallery ─────────────────────────────────────────────────────────
    Route::get('/photogallery/list', [PhotoGalleryController::class, 'index'])->name('photogallery.list');
    Route::get('/photogallery/create', [PhotoGalleryController::class, 'create'])->name('photogallery.create');
    Route::post('/photogallery/create', [PhotoGalleryController::class, 'store'])->name('photogallery.store');
    Route::get('/photogallery/edit/{id}', [PhotoGalleryController::class, 'edit'])->name('photogallery.edit');
    Route::post('/photogallery/edit/{id}', [PhotoGalleryController::class, 'update'])->name('photogallery.update');
    Route::get('/photogallery/{id}', [PhotoGalleryController::class, 'show'])->name('photogallery.show');
    Route::post('/photogallery/delete/{id}', [PhotoGalleryController::class, 'destroy'])->name('photogallery.delete');

    // ── Photos in Gallery ─────────────────────────────────────────────────────
    Route::get('/photosingallery/list', [PhotosinGalleryController::class, 'index'])->name('photosingallery.list');
    Route::get('/photosingallery/create', [PhotosinGalleryController::class, 'create'])->name('photosingallery.create');
    Route::post('/photosingallery/create', [PhotosinGalleryController::class, 'store'])->name('photosingallery.store');
    Route::get('/photosingallery/edit/{id}', [PhotosinGalleryController::class, 'edit'])->name('photosingallery.edit');
    Route::post('/photosingallery/edit/{id}', [PhotosinGalleryController::class, 'update'])->name('photosingallery.update');
    Route::get('/photosingallery/{id}', [PhotosinGalleryController::class, 'show'])->name('photosingallery.show');
    Route::post('/photosingallery/delete/{id}', [PhotosinGalleryController::class, 'destroy'])->name('photosingallery.delete');

    // ── Videos in Gallery ─────────────────────────────────────────────────────
    Route::get('/videosingallery/list', [VideosinGalleryController::class, 'index'])->name('videosingallery.list');
    Route::get('/videosingallery/create', [VideosinGalleryController::class, 'create'])->name('videosingallery.create');
    Route::post('/videosingallery/create', [VideosinGalleryController::class, 'store'])->name('videosingallery.store');
    Route::get('/videosingallery/edit/{id}', [VideosinGalleryController::class, 'edit'])->name('videosingallery.edit');
    Route::post('/videosingallery/edit/{id}', [VideosinGalleryController::class, 'update'])->name('videosingallery.update');
    Route::get('/videosingallery/{id}', [VideosinGalleryController::class, 'show'])->name('videosingallery.show');
    Route::delete('/videosingallery/delete/{id}', [VideosinGalleryController::class, 'destroy'])->name('videosingallery.delete');

    // ── Video Gallery ─────────────────────────────────────────────────────────
    Route::get('/videogallery/list', [VideoGalleryController::class, 'index'])->name('videogallery.list');
    Route::get('/videogallery/create', [VideoGalleryController::class, 'create'])->name('videogallery.create');
    Route::post('/videogallery/create', [VideoGalleryController::class, 'store'])->name('videogallery.store');
    Route::get('/videogallery/edit/{id}', [VideoGalleryController::class, 'edit'])->name('videogallery.edit');
    Route::put('/videogallery/edit/{id}', [VideoGalleryController::class, 'update'])->name('videogallery.update');
    Route::get('/videogallery/{id}', [VideoGalleryController::class, 'show'])->name('videogallery.show');
    Route::post('/videogallery/delete/{id}', [VideoGalleryController::class, 'destroy'])->name('videogallery.delete');

    // ── Ticket Types ──────────────────────────────────────────────────────────
    Route::get('/admin/ticket-types', [TicketTypeController::class, 'all'])->name('admin.ticket-types.all');
    Route::get('/admin/ticket-types/create', [TicketTypeController::class, 'create'])->name('admin.ticket-types.create');
    Route::post('/admin/ticket-types', [TicketTypeController::class, 'store'])->name('admin.ticket-types.store');
    Route::get('/admin/ticket-types/search-shows', [TicketTypeController::class, 'searchShows'])->name('admin.ticket-types.search-shows');
    Route::get('/admin/ticket-types/{ticketType}/edit', [TicketTypeController::class, 'edit'])->name('admin.ticket-types.edit');
    Route::put('/admin/ticket-types/{ticketType}', [TicketTypeController::class, 'update'])->name('admin.ticket-types.update');
    Route::delete('/admin/ticket-types/{ticketType}', [TicketTypeController::class, 'destroy'])->name('admin.ticket-types.delete');
    Route::get('/admin/shows/{show}/ticket-types', [TicketTypeController::class, 'index'])->name('admin.ticket-types.index');
    Route::get('/admin/shows/{show}/ticket-types/create', [TicketTypeController::class, 'createForShow'])->name('admin.ticket-types.create-for-show');
    Route::post('/admin/shows/{show}/ticket-types', [TicketTypeController::class, 'storeForShow'])->name('admin.ticket-types.store-for-show');

    // ── Bookings Management ───────────────────────────────────────────────────
    Route::get('/admin/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::get('/admin/bookings/{booking}', [AdminBookingController::class, 'show'])->name('admin.bookings.show');
    Route::patch('/admin/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('admin.bookings.update-status');
    Route::post('/admin/bookings/{booking}/refund', [AdminBookingController::class, 'refund'])->name('admin.bookings.refund');
    Route::post('/admin/bookings/{booking}/resend-confirmation', [AdminBookingController::class, 'resendConfirmation'])->name('admin.bookings.resend-confirmation');
    Route::post('/admin/bookings/bulk-cancel', [AdminBookingController::class, 'bulkCancel'])->name('admin.bookings.bulk-cancel');
    Route::post('/admin/bookings/bulk-confirm', [AdminBookingController::class, 'bulkConfirm'])->name('admin.bookings.bulk-confirm');

    // ── Booking Exports ───────────────────────────────────────────────────────
    Route::get('/admin/bookings/export/csv', [AdminBookingController::class, 'exportCsv'])->name('admin.bookings.export.csv');
    Route::get('/admin/bookings/export/excel', [AdminBookingController::class, 'exportExcel'])->name('admin.bookings.export.excel');
    Route::get('/admin/shows/{show}/bookings/export', [AdminBookingController::class, 'exportShowBookings'])->name('admin.show-bookings.export');

    // ── Reports ───────────────────────────────────────────────────────────────
    Route::get('/admin/reports/sales', [AdminBookingController::class, 'salesReport'])->name('admin.reports.sales');
    Route::get('/admin/reports/attendance', [AdminBookingController::class, 'attendanceReport'])->name('admin.reports.attendance');
    Route::get('/admin/reports/revenue', [AdminBookingController::class, 'revenueReport'])->name('admin.reports.revenue');

    // ── Ticket Scanning ───────────────────────────────────────────────────────
    Route::get('/admin/scan', [AdminBookingController::class, 'scanTicket'])->name('admin.scan');
    Route::post('/admin/scan/validate', [AdminBookingController::class, 'validateTicket'])->name('admin.scan.validate');
    Route::get('/admin/shows/{show}/scan', [AdminBookingController::class, 'showScanPage'])->name('admin.show.scan');

    // ── Show Reservations (admin view) ────────────────────────────────────────
    Route::get('/admin/shows/{show}/reservations', [AdminBookingController::class, 'showReservations'])->name('admin.show.reservations');
    Route::post('/admin/reservations/{reservation}/release', [AdminBookingController::class, 'releaseReservation'])->name('admin.reservations.release');
    Route::post('/admin/reservations/cleanup-expired', [AdminBookingController::class, 'cleanupExpiredReservations'])->name('admin.reservations.cleanup');

    // ── seats.io Event Management (per show) ──────────────────────────────────
    // Creates a seats.io event key for a show and saves it to the shows table.
    // Requires the SeatsioService and a seatsio_event_key column on shows.
    Route::post('/admin/shows/{show}/seatsio/create-event', function (App\Models\Show $show) {
        try {
            $updatedShow = app(App\Services\SeatsioService::class)->createEventForShow($show);
            return back()->with('success', 'seats.io event created. Key: ' . $updatedShow->seatsio_event_key);
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'seats.io error: ' . $e->getMessage()]);
        }
    })->name('admin.show.seatsio.create-event');

});

// ── Customers (admin prefix) ──────────────────────────────────────────────────
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customer.update');
    Route::get('/customers/{customer}/delete', [CustomerController::class, 'delete'])->name('customer.delete');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy');
});

// ── Maintenance (auth + admin middleware) ─────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin/maintenance')->name('admin.maintenance.')->group(function () {
    Route::post('/cleanup-expired-reservations', [AdminBookingController::class, 'cleanupExpiredReservations'])->name('cleanup-reservations');
    Route::post('/cleanup-expired-bookings', [AdminBookingController::class, 'cleanupExpiredBookings'])->name('cleanup-bookings');
    Route::post('/update-show-statuses', [AdminBookingController::class, 'updateShowStatuses'])->name('update-show-statuses');
});

// =============================================================================
// GENERAL ADMISSION BOOKING FLOW
// =============================================================================
//
// User journey:
//   /ga-booking/{slug}/tickets           → pick ticket quantities
//   /ga-booking/{slug}/customer-details  → name / email / phone
//   /ga-booking/{slug}/payment           → login gate only (auth required)
//   → redirect to PayPal approval URL
//   /ga-booking/{slug}/paypal/success    → capture payment, generate tickets
//   /ga-booking/{slug}/paypal/cancel     → mark cancelled, return to site
//   /ga-booking/{slug}/booking-success   → confirmation page
//   /ga-booking/{slug}/booking-failed    → failure page

Route::prefix('ga-booking')->group(function () {

    // Public steps (no login required yet)
    Route::get('/{slug}/tickets', [GeneralAdmissionController::class, 'showTicketSelection'])->name('ga-booking.tickets');
    Route::post('/{slug}/select-tickets', [GeneralAdmissionController::class, 'selectTickets'])->name('ga-booking.select-tickets');
    Route::get('/{slug}/customer-details', [GeneralAdmissionController::class, 'showCustomerDetails'])->name('ga-booking.customer-details');
    Route::post('/{slug}/customer-details', [GeneralAdmissionController::class, 'processCustomerDetails'])->name('ga-booking.process-customer-details');

    // PayPal return URLs — no auth required (PayPal redirects here after payment)
    Route::get('/{slug}/paypal/success', [GeneralAdmissionController::class, 'paypalSuccess'])->name('ga-booking.paypal-success');
    Route::get('/{slug}/paypal/cancel', [GeneralAdmissionController::class, 'paypalCancel'])->name('ga-booking.paypal-cancel');

    // Auth-required steps
    Route::middleware('auth')->group(function () {
        Route::get('/{slug}/payment', [GeneralAdmissionController::class, 'showPayment'])->name('ga-booking.payment');
        Route::post('/{slug}/payment', [GeneralAdmissionController::class, 'processPayment'])->name('ga-booking.process-payment');
        Route::get('/{slug}/booking-success/{bookingNumber}', [GeneralAdmissionController::class, 'bookingSuccess'])->name('ga-booking.success');
        Route::get('/{slug}/booking-failed', [GeneralAdmissionController::class, 'bookingFailed'])->name('ga-booking.failed');
    });

});

// =============================================================================
// SEATS.IO RESERVED SEATING BOOKING FLOW
// =============================================================================
//
// User journey:
//   GET  /shows/{show}/book              → render seats.io widget (BookingController::show)
//   POST /shows/{show}/book              → create pending booking  (BookingController::initiate)
//   POST /shows/{show}/hold-token/refresh → AJAX: extend hold token before 15-min expiry
//   GET  /booking/{bookingNumber}/payment → PayPal checkout page
//   → PayPal routes below handle capture

Route::get('/shows/{show}/book', [BookingController::class, 'show'])->name('booking.show');
Route::post('/shows/{show}/book', [BookingController::class, 'initiate'])->name('booking.initiate');
Route::post('/shows/{show}/hold-token/refresh', [BookingController::class, 'refreshHoldToken'])->name('booking.refresh-hold');

// Payment page — the single checkout page used by both GA and reserved flows
// BookingController::confirmBooking() is called internally after PayPal capture,
// not via a public route — it is invoked from within paypalSuccess().
Route::middleware('auth')->group(function () {
    Route::get('/booking/{bookingNumber}/payment', [PaymentController::class, 'show'])->name('booking.payment');
});

// =============================================================================
// PAYMENT ROUTES (PayPal — auth required)
// =============================================================================

Route::middleware('auth')->prefix('payment')->name('payment.')->group(function () {
    Route::get('/success/{booking}', [PaymentController::class, 'success'])->name('success');
    Route::get('/cancel/{booking}', [PaymentController::class, 'cancel'])->name('cancel');
    Route::get('/failed/{booking}', [PaymentController::class, 'failed'])->name('failed');
});

// =============================================================================
// TICKET MANAGEMENT (auth required)
// =============================================================================

Route::middleware('auth')->group(function () {
    Route::get('/my-bookings', [PaymentController::class, 'myBookings'])->name('bookings.my');
    Route::get('/bookings/{booking}', [PaymentController::class, 'showBooking'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [PaymentController::class, 'cancelBooking'])->name('bookings.cancel');

    Route::get('/bookings/{booking}/tickets', [TicketController::class, 'download'])->name('tickets.download');
    Route::get('/bookings/{booking}/tickets/pdf', [TicketController::class, 'downloadPdf'])->name('tickets.pdf');
    Route::get('/tickets/{ticket}/qr', [TicketController::class, 'qrCode'])->name('tickets.qr');
    Route::get('/tickets/{ticket}/view', [TicketController::class, 'view'])->name('tickets.view');
});

// =============================================================================
// WEBHOOKS — excluded from CSRF (add paths to VerifyCsrfToken::$except)
// =============================================================================

// PayPal webhook — handled by GeneralAdmissionController
// Add 'webhooks/payment/paypal' to VerifyCsrfToken::$except
Route::post('/webhooks/payment/paypal', [GeneralAdmissionController::class, 'paypalWebhook'])->name('webhooks.paypal');

// seats.io webhook — handles object.booked, object.released, order.confirmed
// Add 'webhooks/seatsio' to VerifyCsrfToken::$except
// Configure endpoint in seats.io Dashboard → Manage → Webhooks
// Set SEATSIO_WEBHOOK_SECRET in .env after copying the secret from the dashboard
Route::post('/webhooks/seatsio', [SeatsioWebhookController::class, 'handle'])->name('webhooks.seatsio');

// =============================================================================
// DEBUG / SANDBOX ROUTES — REMOVE BEFORE GOING TO PRODUCTION
// =============================================================================

if (app()->environment('local', 'staging')) {

    // Verify a show and its ticket types exist
    Route::get('/debug/show/{slug}', function ($slug) {
        $show = App\Models\Show::where('slug', $slug)->with('ticketTypes')->first();
        if (!$show) {
            return response()->json(['error' => "No show found for slug: {$slug}"], 404);
        }
        return response()->json([
            'id'              => $show->id,
            'title'           => $show->title,
            'slug'            => $show->slug,
            'ticketing_mode'  => $show->ticketing_mode,
            'seatsio_event_key' => $show->seatsio_event_key ?? null,
            'is_active'       => $show->is_active,
            'ticket_types'    => $show->ticketTypes->map(fn($t) => [
                'id'       => $t->id,
                'name'     => $t->name,
                'price'    => $t->price,
                'capacity' => $t->capacity,
                'is_active'=> $t->is_active,
            ]),
        ]);
    })->name('debug.show')->middleware('auth');

    // Verify PayPal credentials and connectivity
    Route::get('/debug/paypal', function () {
        try {
            $paypal  = new \App\Services\PayPalService();
            $token   = $paypal->getAccessToken();
            $order   = $paypal->createOrder(1.00, 'Debug test order');
            return response()->json([
                'token_received' => !empty($token),
                'order_created'  => ($order['status'] ?? '') === 'CREATED',
                'order_id'       => $order['id'] ?? null,
                'approval_url'   => collect($order['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    })->name('debug.paypal')->middleware('auth');

}
