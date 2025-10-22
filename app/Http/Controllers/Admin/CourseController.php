<?php

namespace App\Http\Controllers\Admin;

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
        $courses = Course::with('creator')->latest()->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $mailingLists = MailingList::where('is_active', true)->orderBy('name')->get();
        return view('admin.courses.create', compact('mailingLists'));
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

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Kursus oprettet succesfuldt');
    }

    public function show(Course $course)
    {
        $course->load('lessons', 'creator', 'tabs');
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $course->load('tabs');
        $mailingLists = MailingList::where('is_active', true)->orderBy('name')->get();
        return view('admin.courses.edit', compact('course', 'mailingLists'));
    }

    public function update(Request $request, Course $course)
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

        $validated['mailing_list_id'] = $request->input('mailing_list_id') ?: null;
        $validated['is_free'] = $request->has('is_free');
        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('courses', 'public');
            $validated['image_url'] = $path;
        }

        $course->update($validated);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Kursus opdateret succesfuldt');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Kursus slettet succesfuldt');
    }

    public function storeTab(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $validated['course_id'] = $course->id;
        $validated['order'] = $course->tabs()->max('order') + 1;

        CourseTab::create($validated);

        return redirect()->route('admin.courses.edit', $course)
            ->with('success', 'Tab tilføjet succesfuldt');
    }

    public function deleteTab(Course $course, CourseTab $tab)
    {
        if ($tab->course_id !== $course->id) {
            abort(404);
        }

        $tab->delete();

        return redirect()->route('admin.courses.edit', $course)
            ->with('success', 'Tab slettet succesfuldt');
    }
}
