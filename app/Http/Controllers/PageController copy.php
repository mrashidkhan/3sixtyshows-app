<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Mail\NewRegistrationMail;
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

        return view('pages.index', compact('shows'));
     }

    public function sendContact(Request $request)
{
    $validated = $request->validate([
        'name'    => 'required|string|max:255',
        'email'   => 'required|email|max:255',
        'phone'   => 'required|string|max:50',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    $data = $validated;

    // 1. Send confirmation email to the user
    Mail::send('emails.contact_user', $data, function ($mail) use ($data) {
        $mail->to($data['email'], $data['name'])
             ->subject('We received your message – ' . $data['subject']);
    });

    // 2. Send notification email to admin
    Mail::send('emails.contact_admin', $data, function ($mail) use ($data) {
        $mail->to('3sixtyshows@gmail.com', '3Sixty Shows')
             ->replyTo($data['email'], $data['name'])
             ->subject('New Contact Form: ' . $data['subject']);
    });

    return response()->json([
        'success' => true,
        'message' => 'Your message has been sent successfully!'
    ]);
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

    public function events()
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

        return view('pages.events', compact('shows'));
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
}
