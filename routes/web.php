<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\MailingListController;
use App\Http\Controllers\Admin\ResourceController as AdminResourceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Creator\CourseController as CreatorCourseController;
use App\Http\Controllers\Creator\DashboardController as CreatorDashboardController;
use App\Http\Controllers\Creator\LessonController as CreatorLessonController;
use App\Http\Controllers\Creator\MailingListController as CreatorMailingListController;
use App\Http\Controllers\Creator\ResourceController as CreatorResourceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public signup forms
Route::get('/signup/{slug}', [\App\Http\Controllers\SignupController::class, 'show'])->name('signup.show');
Route::post('/signup/{slug}', [\App\Http\Controllers\SignupController::class, 'store'])->name('signup.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // View As functionality for admins
    Route::get('/view-as/{role}', [\App\Http\Controllers\ViewAsController::class, 'switch'])->name('view-as');

    // Member-facing content
    Route::get('/courses', [\App\Http\Controllers\CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('courses.show');

    Route::get('/resources', [\App\Http\Controllers\ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/{resource}', [\App\Http\Controllers\ResourceController::class, 'show'])->name('resources.show');

    Route::get('/courses/{course}/lessons/{lesson}', [\App\Http\Controllers\LessonController::class, 'show'])->name('lessons.show');
});

// Creator Routes
Route::middleware(['auth', 'creator-or-admin'])->prefix('creator')->name('creator.')->group(function () {
    Route::get('/dashboard', [CreatorDashboardController::class, 'index'])->name('dashboard');

    // Course Management (scoped to creator's own courses)
    Route::resource('courses', CreatorCourseController::class);

    // Lesson Management (nested under courses)
    Route::get('/courses/{course}/lessons/create', [CreatorLessonController::class, 'create'])->name('courses.lessons.create');
    Route::post('/courses/{course}/lessons', [CreatorLessonController::class, 'store'])->name('courses.lessons.store');
    Route::get('/courses/{course}/lessons/{lesson}/edit', [CreatorLessonController::class, 'edit'])->name('courses.lessons.edit');
    Route::patch('/courses/{course}/lessons/{lesson}', [CreatorLessonController::class, 'update'])->name('courses.lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}', [CreatorLessonController::class, 'destroy'])->name('courses.lessons.destroy');
    Route::delete('/courses/{course}/lessons/{lesson}/files/{file}', [CreatorLessonController::class, 'deleteFile'])->name('courses.lessons.files.destroy');

    // Course Tab Management
    Route::post('/courses/{course}/tabs', [CreatorCourseController::class, 'storeTab'])->name('courses.tabs.store');
    Route::delete('/courses/{course}/tabs/{tab}', [CreatorCourseController::class, 'deleteTab'])->name('courses.tabs.destroy');

    // Lesson Tab Management
    Route::post('/courses/{course}/lessons/{lesson}/tabs', [CreatorLessonController::class, 'storeTab'])->name('courses.lessons.tabs.store');
    Route::delete('/courses/{course}/lessons/{lesson}/tabs/{tab}', [CreatorLessonController::class, 'deleteTab'])->name('courses.lessons.tabs.destroy');

    // Resource Management (scoped to creator's own resources)
    Route::resource('resources', CreatorResourceController::class);
    Route::delete('/resources/{resource}/files/{file}', [CreatorResourceController::class, 'deleteFile'])->name('resources.files.destroy');

    // Resource Tab Management
    Route::post('/resources/{resource}/tabs', [CreatorResourceController::class, 'storeTab'])->name('resources.tabs.store');
    Route::delete('/resources/{resource}/tabs/{tab}', [CreatorResourceController::class, 'deleteTab'])->name('resources.tabs.destroy');

    // Mailing List Management (scoped to creator's own lists)
    Route::resource('mailing-lists', CreatorMailingListController::class);
    Route::get('/mailing-lists/{mailing_list}/signup-forms', [CreatorMailingListController::class, 'signupForms'])->name('mailing-lists.signup-forms');
    Route::get('/mailing-lists/{mailing_list}/qr-code', [CreatorMailingListController::class, 'qrCode'])->name('mailing-lists.qr-code');
    Route::get('/mailing-lists/{mailing_list}/import', [CreatorMailingListController::class, 'import'])->name('mailing-lists.import');
    Route::get('/mailing-lists/download-template', [CreatorMailingListController::class, 'downloadTemplate'])->name('mailing-lists.download-template');
    Route::post('/mailing-lists/{mailing_list}/parse-import', [CreatorMailingListController::class, 'parseImport'])->name('mailing-lists.parse-import');
    Route::post('/mailing-lists/{mailing_list}/process-import', [CreatorMailingListController::class, 'processImport'])->name('mailing-lists.process-import');
    Route::post('/mailing-lists/{mailing_list}/members', [CreatorMailingListController::class, 'addMember'])->name('mailing-lists.members.add');
    Route::delete('/mailing-lists/{mailing_list}/members/{user}', [CreatorMailingListController::class, 'removeMember'])->name('mailing-lists.members.remove');
    Route::post('/mailing-lists/{mailing_list}/signup-form/template', [CreatorMailingListController::class, 'updateSignupFormTemplate'])->name('mailing-lists.signup-form.template');
    Route::post('/mailing-lists/{mailing_list}/signup-form/data', [CreatorMailingListController::class, 'updateSignupFormData'])->name('mailing-lists.signup-form.data');
    Route::post('/mailing-lists/{mailing_list}/signup-form/upload-image', [CreatorMailingListController::class, 'uploadSignupFormImage'])->name('mailing-lists.signup-form.upload-image');
    Route::post('/mailing-lists/{mailing_list}/assign-courses', [CreatorMailingListController::class, 'assignCourses'])->name('mailing-lists.assign-courses');
    Route::post('/mailing-lists/{mailing_list}/assign-resources', [CreatorMailingListController::class, 'assignResources'])->name('mailing-lists.assign-resources');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Course Management
    Route::resource('courses', CourseController::class);

    // Lesson Management (nested under courses)
    Route::get('/courses/{course}/lessons/create', [LessonController::class, 'create'])->name('courses.lessons.create');
    Route::post('/courses/{course}/lessons', [LessonController::class, 'store'])->name('courses.lessons.store');
    Route::get('/courses/{course}/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('courses.lessons.edit');
    Route::patch('/courses/{course}/lessons/{lesson}', [LessonController::class, 'update'])->name('courses.lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}', [LessonController::class, 'destroy'])->name('courses.lessons.destroy');
    Route::delete('/courses/{course}/lessons/{lesson}/files/{file}', [LessonController::class, 'deleteFile'])->name('courses.lessons.files.destroy');

    // Course Tab Management
    Route::post('/courses/{course}/tabs', [CourseController::class, 'storeTab'])->name('courses.tabs.store');
    Route::delete('/courses/{course}/tabs/{tab}', [CourseController::class, 'deleteTab'])->name('courses.tabs.destroy');

    // Lesson Tab Management
    Route::post('/courses/{course}/lessons/{lesson}/tabs', [LessonController::class, 'storeTab'])->name('courses.lessons.tabs.store');
    Route::delete('/courses/{course}/lessons/{lesson}/tabs/{tab}', [LessonController::class, 'deleteTab'])->name('courses.lessons.tabs.destroy');

    // Resource Management
    Route::resource('resources', AdminResourceController::class);
    Route::delete('/resources/{resource}/files/{file}', [AdminResourceController::class, 'deleteFile'])->name('resources.files.destroy');

    // Resource Tab Management
    Route::post('/resources/{resource}/tabs', [AdminResourceController::class, 'storeTab'])->name('resources.tabs.store');
    Route::delete('/resources/{resource}/tabs/{tab}', [AdminResourceController::class, 'deleteTab'])->name('resources.tabs.destroy');

    // Mailing List Management
    Route::resource('mailing-lists', MailingListController::class);
    Route::post('/mailing-lists/{mailing_list}/members', [MailingListController::class, 'addMember'])->name('mailing-lists.members.add');
    Route::delete('/mailing-lists/{mailing_list}/members/{user}', [MailingListController::class, 'removeMember'])->name('mailing-lists.members.remove');
});

require __DIR__.'/auth.php';
