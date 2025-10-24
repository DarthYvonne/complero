<x-app-layout>
    <div class="container-fluid">
        @php
            $user = Auth::user();
            $organizationName = 'Complicero';

            if ($user && in_array($user->role, ['admin', 'creator'])) {
                $organizationName = $user->organization_name ?: $user->name;
            }
        @endphp

        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                <i class="fa-solid fa-heart" style="color: var(--primary-color);"></i> {{ $organizationName }}
            </h1>
            <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                Velkommen tilbage, {{ Auth::user()->name }}
            </p>
        </div>

        <!-- Continue Learning Section -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="font-size: 20px; font-weight: 600; color: #333; margin: 0;">Fortsæt læring</h2>
                @if($enrolledCourses->count() > 0)
                    <a href="{{ route('courses.index') }}" class="text-decoration-none" style="color: var(--primary-color); font-size: 14px; font-weight: 400;">Se alle forløb</a>
                @endif
            </div>

            @if($enrolledCourses->count() > 0)
                <!-- Enrolled Courses Grid -->
                <div class="row g-3">
                    @foreach($enrolledCourses as $enrollment)
                        @php
                            $course = $enrollment->course;
                            $courseColor = $course->primary_color ?? '#be185d';
                            $rgb = sscanf($courseColor, "#%02x%02x%02x");
                            $r = max(0, $rgb[0] - 30);
                            $g = max(0, $rgb[1] - 30);
                            $b = max(0, $rgb[2] - 30);
                            $hoverColor = sprintf("#%02x%02x%02x", $r, $g, $b);
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="card">
                                <img src="{{ $course->image }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title" style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 10px;">
                                        <i class="fa-solid fa-circle-play" style="color: {{ $courseColor }};"></i> {{ $course->title }}
                                    </h5>
                                    <p class="text-muted small mb-3">
                                        {{ $course->lessons->count() }} {{ $course->lessons->count() === 1 ? 'lektion' : 'lektioner' }}
                                    </p>
                                    @php
                                        $targetLesson = $enrollment->lastAccessedLesson ?? $course->lessons->first();
                                    @endphp
                                    @if($targetLesson)
                                        <a href="{{ route('lessons.show', [$course, $targetLesson]) }}"
                                           class="btn w-100"
                                           style="background: {{ $courseColor }}; border-color: {{ $courseColor }}; color: #ffffff;"
                                           onmouseover="this.style.background='{{ $hoverColor }}'; this.style.borderColor='{{ $hoverColor }}';"
                                           onmouseout="this.style.background='{{ $courseColor }}'; this.style.borderColor='{{ $courseColor }}';">
                                            <i class="fa-solid fa-play me-1"></i> Fortsæt
                                        </a>
                                    @else
                                        <button class="btn w-100" disabled
                                                style="background: #e0e0e0; border-color: #e0e0e0; color: #999;">
                                            <i class="fa-solid fa-circle-info me-1"></i> Ingen lektioner endnu
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Placeholder for no enrolled courses -->
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fa-solid fa-circle-play" style="font-size: 4rem; color: #d1d5db;"></i>
                        <h5 class="mt-3 mb-2">Ingen igangværende kurser</h5>
                        <p class="text-muted mb-3" style="font-weight: 300;">Start med at udforske vores tilgængelige indhold</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-primary">Udforsk indhold</a>
                    </div>
                </div>
            @endif
        </div>




    </div>

    <style>
        .card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            font-size: 14px;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }
    </style>
</x-app-layout>