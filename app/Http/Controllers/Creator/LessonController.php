<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonFile;
use App\Models\LessonTab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Show the form for creating a new lesson.
     */
    public function create(Course $course)
    {
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til dette forløb');
        }

        return view('creator.lessons.create', compact('course'));
    }

    /**
     * Store a newly created lesson in storage.
     */
    public function store(Request $request, Course $course)
    {
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til dette forløb');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,avi,webm', 'max:512000'], // 500MB max
            'files.*' => ['nullable', 'file', 'max:10240'], // 10MB max per file
        ]);

        $lesson = $course->lessons()->create([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
        ]);

        // Handle video upload
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $path = $video->store('videos', 'public');
            $lesson->update(['video_path' => $path]);
        }

        // Handle file attachments
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('lesson-files', 'public');

                $lesson->files()->create([
                    'filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()
            ->route('creator.courses.show', $course)
            ->with('success', 'Lektion oprettet succesfuldt');
    }

    /**
     * Show the form for editing the specified lesson.
     */
    public function edit(Course $course, Lesson $lesson)
    {
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til dette forløb');
        }

        $lesson->load('tabs');
        return view('creator.lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Update the specified lesson in storage.
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til dette forløb');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'order' => ['nullable', 'integer', 'min:1'],
            'video' => ['nullable', 'file', 'mimes:mp4,mov,avi,webm', 'max:512000'],
            'files.*' => ['nullable', 'file', 'max:10240'],
        ]);

        $lesson->update([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'order' => $validated['order'] ?? $lesson->order,
        ]);

        // Handle video upload (replace existing)
        if ($request->hasFile('video')) {
            // Delete old video if exists
            if ($lesson->video_path) {
                Storage::disk('public')->delete($lesson->video_path);
            }

            $video = $request->file('video');
            $path = $video->store('videos', 'public');
            $lesson->update(['video_path' => $path]);
        }

        // Handle new file attachments
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('lesson-files', 'public');

                $lesson->files()->create([
                    'filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()
            ->route('creator.courses.show', $course)
            ->with('success', 'Lektion opdateret succesfuldt');
    }

    /**
     * Remove the specified lesson from storage.
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til dette forløb');
        }

        // Delete video file if exists
        if ($lesson->video_path) {
            Storage::disk('public')->delete($lesson->video_path);
        }

        // Delete all lesson files
        foreach ($lesson->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }

        $lesson->delete();

        return redirect()
            ->route('creator.courses.show', $course)
            ->with('success', 'Lektion slettet succesfuldt');
    }

    /**
     * Delete a specific file attachment.
     */
    public function deleteFile(Course $course, Lesson $lesson, LessonFile $file)
    {
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til dette forløb');
        }

        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return back()->with('success', 'Fil slettet succesfuldt');
    }

    /**
     * Store a new tab for the lesson.
     */
    public function storeTab(Request $request, Course $course, Lesson $lesson)
    {
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til dette forløb');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $validated['lesson_id'] = $lesson->id;
        $validated['order'] = $lesson->tabs()->max('order') + 1;

        LessonTab::create($validated);

        return redirect()->route('creator.courses.lessons.edit', [$course, $lesson])
            ->with('success', 'Tab tilføjet succesfuldt');
    }

    /**
     * Delete a tab from the lesson.
     */
    public function deleteTab(Course $course, Lesson $lesson, LessonTab $tab)
    {
        if (auth()->user()->role !== 'admin' && $course->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til dette forløb');
        }

        if ($tab->lesson_id !== $lesson->id) {
            abort(404);
        }

        $tab->delete();

        return redirect()->route('creator.courses.lessons.edit', [$course, $lesson])
            ->with('success', 'Tab slettet succesfuldt');
    }
}
