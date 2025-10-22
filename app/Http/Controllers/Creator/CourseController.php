<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseTab;
use App\Models\MailingList;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        // Only show courses created by this user
        $courses = Course::where('creator_id', auth()->id())
            ->with('creator')
            ->latest()
            ->paginate(20);

        return view('creator.courses.index', compact('courses'));
    }

    public function create()
    {
        // Only show mailing lists owned by this creator
        $mailingLists = MailingList::where('creator_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('creator.courses.create', compact('mailingLists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'mailing_list_id' => ['nullable', 'exists:mailing_lists,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['boolean'],
            'is_published' => ['boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Ensure the mailing list belongs to this creator if specified
        if ($validated['mailing_list_id'] ?? null) {
            $mailingList = MailingList::where('id', $validated['mailing_list_id'])
                ->where('creator_id', auth()->id())
                ->firstOrFail();
        }

        $validated['creator_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['mailing_list_id'] = $request->input('mailing_list_id') ?: null;
        $validated['is_free'] = $request->has('is_free');
        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $validated['image_url'] = $path;
        }

        $course = Course::create($validated);

        return redirect()->route('creator.courses.show', $course)
            ->with('success', 'Kursus oprettet succesfuldt');
    }

    public function show(Course $course)
    {
        // Verify ownership
        if ($course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til dette kursus');
        }

        $course->load('lessons', 'creator', 'tabs');
        return view('creator.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        // Verify ownership
        if ($course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere dette kursus');
        }

        $course->load('tabs');

        // Only show mailing lists owned by this creator
        $mailingLists = MailingList::where('creator_id', auth()->id())
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('creator.courses.edit', compact('course', 'mailingLists'));
    }

    public function update(Request $request, Course $course)
    {
        // Verify ownership
        if ($course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere dette kursus');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'mailing_list_id' => ['nullable', 'exists:mailing_lists,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'is_free' => ['boolean'],
            'is_published' => ['boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        // Ensure the mailing list belongs to this creator if specified
        if ($validated['mailing_list_id'] ?? null) {
            $mailingList = MailingList::where('id', $validated['mailing_list_id'])
                ->where('creator_id', auth()->id())
                ->firstOrFail();
        }

        $validated['mailing_list_id'] = $request->input('mailing_list_id') ?: null;
        $validated['is_free'] = $request->has('is_free');
        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $validated['image_url'] = $path;
        }

        $course->update($validated);

        return redirect()->route('creator.courses.show', $course)
            ->with('success', 'Kursus opdateret succesfuldt');
    }

    public function destroy(Course $course)
    {
        // Verify ownership
        if ($course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at slette dette kursus');
        }

        $course->delete();

        return redirect()->route('creator.courses.index')
            ->with('success', 'Kursus slettet succesfuldt');
    }

    public function storeTab(Request $request, Course $course)
    {
        // Verify ownership
        if ($course->creator_id !== auth()->id()) {
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

    public function deleteTab(Course $course, CourseTab $tab)
    {
        // Verify ownership
        if ($course->creator_id !== auth()->id() || $tab->course_id !== $course->id) {
            abort(403);
        }

        $tab->delete();

        return redirect()->route('creator.courses.edit', $course)
            ->with('success', 'Tab slettet succesfuldt');
    }
}
