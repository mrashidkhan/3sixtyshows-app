<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoGallery;
use App\Models\Show;
use Illuminate\Http\Request;

class VideoGalleryController extends Controller
{
    public function index(Request $request)
    {
        $galleries = VideoGallery::when($request->search, function ($query) use ($request) {
            return $query->where('title', 'like', '%' . $request->search . '%');
        })->paginate(10);

        return view('admin.video_gallery.index', compact('galleries'));
    }

    public function clientIndex(Request $request)
    {
        $videos = \App\Models\VideosinGallery::with(['videoGallery', 'videoGallery.show'])
            ->whereHas('videoGallery', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('pages.video-galleries', compact('videos'));
    }

    public function create()
    {
        $shows = Show::orderBy('title')->get();
        return view('admin.video_gallery.create', compact('shows'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'show_id'       => 'nullable|exists:shows,id',
            'video_type'    => 'required|in:youtube,vimeo,other',
            'video_url'     => 'required|url',
            // FIX 1: thumbnail is OPTIONAL — matches what the form tells the user
            'thumbnail'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description'   => 'nullable|string',
            'display_order' => 'nullable|integer',
        ]);

        $gallery = new VideoGallery();
        $gallery->show_id       = $request->show_id;
        $gallery->title         = $request->title;
        // FIX 2: video_type and video_url were missing from assignments
        $gallery->video_type    = $request->video_type;
        $gallery->video_url     = $request->video_url;
        $gallery->description   = $request->description;
        $gallery->display_order = $request->display_order ?? 0;
        $gallery->is_featured   = $request->has('is_featured');
        $gallery->is_active     = $request->has('is_active');

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('photos', 'public');
            $gallery->thumbnail = $path;
        }

        $gallery->save();

        return redirect()->route('videogallery.list')
                         ->with('success', 'Video Gallery created successfully.');
    }

    public function show($id)
    {
        $gallery = VideoGallery::with('show')->findOrFail($id);
        return view('admin.video_gallery.show', compact('gallery'));
    }

    public function edit($id)
    {
        $gallery = VideoGallery::findOrFail($id);
        $shows   = Show::orderBy('title')->get();
        return view('admin.video_gallery.edit', compact('gallery', 'shows'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'show_id'       => 'nullable|exists:shows,id',
            'video_type'    => 'required|in:youtube,vimeo,other',
            'video_url'     => 'required|url',
            // FIX 3: thumbnail optional on edit (keep existing if not re-uploaded)
            'thumbnail'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description'   => 'nullable|string',
            'display_order' => 'nullable|integer',
        ]);

        $gallery = VideoGallery::findOrFail($id);

        // FIX 3: save ALL fields, not just title
        $gallery->show_id       = $request->show_id;
        $gallery->title         = $request->title;
        $gallery->video_type    = $request->video_type;
        $gallery->video_url     = $request->video_url;
        $gallery->description   = $request->description;
        $gallery->display_order = $request->display_order ?? 0;
        $gallery->is_featured   = $request->has('is_featured');
        // is_active comes as a select (1/0) not a checkbox, so use input value
        $gallery->is_active     = $request->input('is_active', 0);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('photos', 'public');
            $gallery->thumbnail = $path;
        }

        $gallery->save();

        return redirect()->route('videogallery.list')
                         ->with('success', 'Video Gallery updated successfully.');
    }

    public function destroy($id)
    {
        $gallery = VideoGallery::findOrFail($id);
        $gallery->delete();

        return redirect()->route('videogallery.list')
                         ->with('success', 'Video Gallery deleted successfully.');
    }
}
