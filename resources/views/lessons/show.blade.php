<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('courses.index') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">Forløb</a>
    <span style="margin: 0 8px;">/</span>
    <a href="{{ route('courses.show', $course) }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $course->title }}</a>
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">{{ $lesson->title }}</strong>
@endsection

@php
    $effectiveRole = session('view_as', Auth::user()->role);
@endphp

    <div class="container-fluid">
        <!-- Lesson Title -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 style="font-size: 28px; font-weight: 700; color: #333; margin-bottom: 0;">
                        <i class="fa-solid fa-circle-play" style="color: var(--primary-color);"></i> {{ $course->title }}: <span style="font-weight: 100;">{{ $lesson->title }}</span>
                    </h1>
                </div>
                @if($effectiveRole === 'admin' || $effectiveRole === 'creator')
                    <div class="d-flex gap-2">
                        <a href="{{ $effectiveRole === 'admin' ? route('admin.courses.lessons.edit', [$course, $lesson]) : route('creator.courses.lessons.edit', [$course, $lesson]) }}" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-pen me-1"></i> Rediger
                        </a>
                        <a href="{{ $effectiveRole === 'admin' ? route('admin.courses.lessons.create', $course) : route('creator.courses.lessons.create', $course) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-circle-plus me-1"></i> Tilføj lektion
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="row g-4 align-items-start">
            <!-- Main Content -->
            <div class="col-lg-8">

                <!-- Video Player -->
                @if($lesson->video_path)
                    <div class="card mb-4">
                        <div class="card-body p-0">
                            <video controls style="width: 100%; max-height: 600px; background: #000;">
                                <source src="{{ $lesson->getVideoUrl() }}" type="video/mp4">
                                Din browser understøtter ikke videoafspilning.
                            </video>
                        </div>
                    </div>
                @endif

                <!-- Lesson Content / Tabs -->
                <div class="card mb-4">
                    @if($lesson->tabs->count() > 0)
                        <!-- Tabs Navigation -->
                        <div class="card-header bg-white border-bottom" style="padding: 0;">
                            <ul class="nav nav-tabs card-header-tabs" id="lessonTabs" role="tablist" style="border-bottom: none; margin-bottom: 0;">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="intro-tab" data-bs-toggle="tab" data-bs-target="#intro" type="button" role="tab" aria-controls="intro" aria-selected="true">
                                        Introduktion
                                    </button>
                                </li>
                                @foreach($lesson->tabs as $index => $tab)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-{{ $tab->id }}-tab" data-bs-toggle="tab" data-bs-target="#tab-{{ $tab->id }}" type="button" role="tab" aria-controls="tab-{{ $tab->id }}" aria-selected="false">
                                            {{ $tab->title }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Tabs Content -->
                        <div class="card-body">
                            <div class="tab-content" id="lessonTabsContent">
                                <div class="tab-pane fade show active" id="intro" role="tabpanel" aria-labelledby="intro-tab">
                                    @if($lesson->content)
                                        {!! $lesson->content !!}
                                    @else
                                        <p class="text-muted">Ingen indhold tilgængeligt.</p>
                                    @endif
                                </div>
                                @foreach($lesson->tabs as $tab)
                                    <div class="tab-pane fade" id="tab-{{ $tab->id }}" role="tabpanel" aria-labelledby="tab-{{ $tab->id }}-tab">
                                        {!! $tab->content !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- No tabs, just show content -->
                        @if($lesson->content)
                            <div class="card-body">
                                <h5 style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 15px;">
                                    <i class="fa-solid fa-file-lines" style="color: #2563eb;"></i> Lektionsindhold
                                </h5>
                                <div style="font-size: 14px; font-weight: 300; color: #666;">
                                    {!! $lesson->content !!}
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Downloadable Files -->
                @if($lesson->files->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                                <i class="fa-solid fa-download" style="color: #2563eb;"></i> Filer til download
                                <span class="badge bg-secondary ms-2">{{ $lesson->files->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach($lesson->files as $file)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-solid fa-file me-2" style="color: #2563eb; font-size: 1.2rem;"></i>
                                                    <div>
                                                        <strong style="font-size: 16px; font-weight: 600; color: #333;">{{ $file->filename }}</strong>
                                                        <div style="font-size: 13px; font-weight: 300; color: #999;">{{ number_format($file->file_size / 1024, 2) }} KB</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ Storage::url($file->file_path) }}" download class="btn btn-primary">
                                                    <i class="fa-solid fa-download me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Lesson Navigation -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            @php
                                $currentIndex = $lessons->search(function($l) use ($lesson) {
                                    return $l->id === $lesson->id;
                                });
                                $prevLesson = $currentIndex > 0 ? $lessons[$currentIndex - 1] : null;
                                $nextLesson = $currentIndex < $lessons->count() - 1 ? $lessons[$currentIndex + 1] : null;
                            @endphp

                            <div>
                                @if($prevLesson)
                                    <a href="{{ route('lessons.show', [$course, $prevLesson]) }}" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-chevron-left me-1"></i> {{ $prevLesson->title }}
                                    </a>
                                @else
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-arrow-left me-1"></i> Tilbage til forløbet
                                    </a>
                                @endif
                            </div>

                            <div>
                                @if($nextLesson)
                                    <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="btn btn-primary">
                                        Fortsæt til {{ $nextLesson->title }} <i class="fa-solid fa-chevron-right ms-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Course Lessons -->
                <div class="card">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0" style="font-size: 16px; font-weight: 600; color: #333;">
                            Lektioner
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($lessons as $index => $l)
                                <a href="{{ route('lessons.show', [$course, $l]) }}"
                                   class="list-group-item list-group-item-action {{ $l->id === $lesson->id ? 'active' : '' }}"
                                   style="border-left: none; border-right: none;">
                                    <div class="d-flex align-items-start">
                                        <div class="me-2" style="min-width: 24px;">
                                            @if($l->id === $lesson->id)
                                                <i class="fa-solid fa-tv" style="color: #fff;"></i>
                                            @else
                                                <i class="fa-solid fa-tv" style="color: #999;"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div style="font-size: 14px; font-weight: {{ $l->id === $lesson->id ? '600' : '400' }}; color: {{ $l->id === $lesson->id ? '#fff' : '#333' }};">
                                                {{ $l->title }}
                                            </div>
                                            @if($l->duration_minutes)
                                                <small style="font-size: 12px; font-weight: 300; color: {{ $l->id === $lesson->id ? 'rgba(255,255,255,0.8)' : '#999' }};">
                                                    {{ $l->duration_minutes }} min
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        .btn-outline-secondary {
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
        }
        .list-group-item {
            border-color: #f0f0f0;
            padding: 12px 16px;
        }
        .list-group-item:first-child {
            border-top: none;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        .list-group-item.active {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .list-group-item-action:hover:not(.active) {
            background-color: #f9fafb;
        }
        .card-header-tabs {
            margin-left: 0;
            margin-right: 0;
        }
        .card-header-tabs .nav-item:first-child .nav-link {
            padding-left: 1rem;
        }
    </style>
</x-app-layout>
