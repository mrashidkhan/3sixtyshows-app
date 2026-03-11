<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Show;
use App\Models\ShowCategory;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ShowController extends Controller
{
    public function index(Request $request)
    {
        // Get all venues and categories for filter dropdowns
        $venues = Venue::all();
        $categories = ShowCategory::all();

        // Start with base query
        $query = Show::with(['category', 'venue']);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by active/inactive
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        // Filter by redirect
        if ($request->filled('redirect')) {
            $query->where('redirect', $request->redirect == '1');
        }

        // Filter by venue
        if ($request->filled('venue')) {
            $query->where('venue_id', $request->venue);
        }

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('short_description', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('performers', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        // Order by most recent first
        $query->orderBy('created_at', 'desc');

        $shows = $query->get();

        return view('admin.show.index', compact('venues', 'categories', 'shows'));
    }

    public function create()
    {
        $categories = ShowCategory::where('is_active', 1)->orderBy('name')->get();
        $venues = Venue::all();
        return view('admin.show.add', compact('venues', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'category_id'       => 'required|exists:show_categories,id',
            'venue_id'          => 'required|exists:venues,id',
            'description'       => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'featured_image'    => 'required|image|mimes:jpeg,png,webp,jpg|max:2048',
            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'price'             => 'nullable|numeric|min:0',
            'available_tickets' => 'nullable|integer|min:0',
            'is_featured'       => 'boolean',
            'status'            => 'required|in:upcoming,ongoing,past,cancelled',
            'performers'        => 'nullable|string',
            'duration'          => 'nullable|string|max:50',
            'age_restriction'   => 'nullable|string|max:50',
            'is_active'         => 'boolean',
            'redirect'          => 'boolean',
            'redirect_url'      => 'nullable|url|required_if:redirect,1',
        ]);

        // Store image in storage/app/private/shows
        if ($request->hasFile('featured_image')) {
            $file     = $request->file('featured_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs('shows', $filename, 'public');
            $validated['featured_image'] = $path;
        }

        $show = Show::create($validated);

        return redirect()->route('show.index')
                         ->with('success', 'Show created successfully!');
    }

    public function show($id)
    {
        $show = Show::with(['category', 'venue', 'gallery'])
                    ->findOrFail($id);

        return view('admin.show.show', compact('show'));
    }

    public function edit($id)
    {
        $show       = Show::findOrFail($id);
        $categories = ShowCategory::where('is_active', 1)->orderBy('name')->get();
        $venues     = Venue::all();

        return view('admin.show.edit', compact('show', 'categories', 'venues'));
    }

    public function update(Request $request, $id)
    {
        $show = Show::findOrFail($id);

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'slug'              => 'required|string|max:255|unique:shows,slug,' . $id,
            'category_id'       => 'required|exists:show_categories,id',
            'venue_id'          => 'required|exists:venues,id',
            'description'       => 'required|string',
            'short_description' => 'required|string|max:500',
            'featured_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after_or_equal:start_date',
            'price'             => 'required|numeric|min:0',
            'available_tickets' => 'nullable|integer|min:0',
            'is_featured'       => 'required|boolean',
            'status'            => 'required|in:upcoming,ongoing,past,cancelled',
            'performers'        => 'nullable|string',
            'duration'          => 'nullable|string|max:50',
            'age_restriction'   => 'nullable|string|max:50',
            'is_active'         => 'required|boolean',
            'redirect'          => 'boolean',
            'redirect_url'      => 'nullable|url|required_if:redirect,1',
        ]);

        // Handle additional_info JSON field
        if ($request->has('additional_info_json')) {
            $validated['additional_info'] = json_decode($request->additional_info_json, true);
        } elseif ($request->has('additional_info')) {
            $lines          = explode("\n", $request->additional_info);
            $additionalInfo = [];

            foreach ($lines as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $key   = trim($parts[0]);
                    $value = trim($parts[1]);
                    if (!empty($key) && !empty($value)) {
                        $additionalInfo[$key] = $value;
                    }
                }
            }

            $validated['additional_info'] = $additionalInfo;
        }

        // Handle image upload — store in storage/app/private/shows
        if ($request->hasFile('featured_image')) {
            // Delete old image from private storage
            if ($show->featured_image) {
                Storage::disk('local')->delete($show->featured_image);
            }

            $image    = $request->file('featured_image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path     = $image->storeAs('shows', $filename, 'public');

            $validated['featured_image'] = $path;
        }

        // Clear redirect URL if redirect is disabled
        if (!$request->has('redirect') || !$request->redirect) {
            $validated['redirect_url'] = null;
        }

        $show->update($validated);

        // Update show status based on dates
        $show->updateStatus();

        return redirect()->route('show.index')
                         ->with('success', 'Show updated successfully!');
    }

    public function destroy($id)
    {
        $show = Show::findOrFail($id);

        // Delete featured image from private storage
        if ($show->featured_image) {
            Storage::disk('local')->delete($show->featured_image);
        }

        // Delete associated gallery images
        foreach ($show->gallery as $galleryItem) {
            Storage::disk('local')->delete($galleryItem->image);
            $galleryItem->delete();
        }

        $show->delete();

        return redirect()->route('show.index')
                         ->with('success', 'Show deleted successfully!');
    }
}
