<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\CourseTab;
use App\Models\MailingList;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('creator')->latest();

        // Only scope to own content if NOT admin
        if (auth()->user()->role !== 'admin') {
            $query->where('creator_id', auth()->id());
        } else {
            // Admin can filter by creator
            if ($request->filled('creator_id')) {
                $query->where('creator_id', $request->creator_id);
            }
        }

        $courses = $query->paginate(20);

        // Get all creators for filter dropdown (only for admins)
        $creators = auth()->user()->role === 'admin'
            ? \App\Models\User::whereIn('role', ['creator', 'admin'])->orderBy('name')->get()
            : collect();

        return view('creator.courses.index', compact('courses', 'creators'));
    }

    public function create()
    {
        $query = MailingList::where('is_active', true)->orderBy('name');

        // Only show own mailing lists if NOT admin
        if (auth()->user()->role !== 'admin') {
            $query->where('creator_id', auth()->id());
        }

        $mailingLists = $query->get();

        return view('creator.courses.create', compact('mailingLists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string'],
            'intro_title' => ['nullable', 'string', 'max:255'],
            'mailing_list_id' => ['nullable', 'exists:mailing_lists,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'primary_color' => ['nullable', 'string', 'max:7'],
            'is_free' => ['boolean'],
            'is_published' => ['boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Ensure the mailing list belongs to this creator if specified (unless admin)
        if ($validated['mailing_list_id'] ?? null) {
            $query = MailingList::where('id', $validated['mailing_list_id']);

            if (auth()->user()->role !== 'admin') {
                $query->where('creator_id', auth()->id());
            }

            $mailingList = $query->firstOrFail();
        }

        $validated['creator_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['mailing_list_id'] = $request->input('mailing_list_id') ?: null;
        $validated['is_free'] = $request->has('is_free');
        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'files');
            $validated['image_url'] = $path;
        }

        $course = Course::create($validated);

        // Log activity
        ActivityLog::log(
            'course_created',
            auth()->user()->name . ' oprettede forløbet "' . $course->title . '"',
            $course,
            auth()->user(),
            auth()->user(),
            ['course_title' => $course->title]
        );

        return redirect()->route('creator.courses.show', $course)
            ->with('success', 'Kursus oprettet succesfuldt');
    }

    public function show(Course $course)
    {
        // Verify ownership (unless admin)
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til dette kursus');
        }

        $course->load('lessons', 'creator', 'tabs');
        return view('creator.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        // Verify ownership (unless admin)
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere dette kursus');
        }

        $course->load('tabs');

        $query = MailingList::where('is_active', true)->orderBy('name');

        // Only show own mailing lists if NOT admin
        if (auth()->user()->role !== 'admin') {
            $query->where('creator_id', auth()->id());
        }

        $mailingLists = $query->get();

        return view('creator.courses.edit', compact('course', 'mailingLists'));
    }

    public function update(Request $request, Course $course)
    {
        // Verify ownership (unless admin)
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere dette kursus');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string'],
            'intro_title' => ['nullable', 'string', 'max:255'],
            'mailing_list_id' => ['nullable', 'exists:mailing_lists,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'primary_color' => ['nullable', 'string', 'max:7'],
            'is_free' => ['boolean'],
            'is_published' => ['boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
            'remove_image' => ['boolean'],
        ]);

        // Ensure the mailing list belongs to this creator if specified (unless admin)
        if ($validated['mailing_list_id'] ?? null) {
            $query = MailingList::where('id', $validated['mailing_list_id']);

            if (auth()->user()->role !== 'admin') {
                $query->where('creator_id', auth()->id());
            }

            $mailingList = $query->firstOrFail();
        }

        $validated['mailing_list_id'] = $request->input('mailing_list_id') ?: null;
        $validated['is_free'] = $request->has('is_free');
        $validated['is_published'] = $request->has('is_published');

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            $validated['image_url'] = null;
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'files');
            $validated['image_url'] = $path;
        }

        $course->update($validated);

        // Log activity
        ActivityLog::log(
            'course_updated',
            auth()->user()->name . ' opdaterede forløbet "' . $course->title . '"',
            $course,
            auth()->user(),
            auth()->user(),
            ['course_title' => $course->title]
        );

        return redirect()->route('creator.courses.show', $course)
            ->with('success', 'Kursus opdateret succesfuldt');
    }

    public function destroy(Course $course)
    {
        // Verify ownership (unless admin)
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at slette dette kursus');
        }

        $courseTitle = $course->title;

        $course->delete();

        // Log activity
        ActivityLog::log(
            'course_deleted',
            auth()->user()->name . ' slettede forløbet "' . $courseTitle . '"',
            null,
            auth()->user(),
            auth()->user(),
            ['course_title' => $courseTitle]
        );

        return redirect()->route('creator.courses.index')
            ->with('success', 'Kursus slettet succesfuldt');
    }

    public function storeTab(Request $request, Course $course)
    {
        // Verify ownership (unless admin)
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $validated['course_id'] = $course->id;
        $validated['order'] = $course->tabs()->max('order') + 1;

        CourseTab::create($validated);

        return redirect()->route('creator.courses.edit', $course)
            ->with('success', 'Tab tilføjet succesfuldt');
    }

    public function updateTab(Request $request, Course $course, CourseTab $tab)
    {
        // Verify ownership (unless admin)
        if (auth()->user()->role !== 'admin' && ($course->creator_id !== auth()->id() || $tab->course_id !== $course->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $tab->update($validated);

        return redirect()->route('creator.courses.edit', $course)
            ->with('success', 'Tab opdateret succesfuldt');
    }

    public function deleteTab(Course $course, CourseTab $tab)
    {
        // Verify ownership (unless admin)
        if (auth()->user()->role !== 'admin' && ($course->creator_id !== auth()->id() || $tab->course_id !== $course->id)) {
            abort(403);
        }

        $tab->delete();

        return redirect()->route('creator.courses.edit', $course)
            ->with('success', 'Tab slettet succesfuldt');
    }
}
