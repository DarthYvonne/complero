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
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 5px;">
                        <i class="fa-solid fa-heart" style="color: var(--primary-color);"></i> {{ $organizationName }}
                    </h1>
                    <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                        Velkommen tilbage, {{ Auth::user()->name }}
                    </p>
                </div>
                <div class="d-flex gap-2 align-items-end">
                    @if($allGroups && $allGroups->count() > 1)
                        <div>
                            <label for="groupSelector" class="form-label" style="font-size: 14px; font-weight: 500; margin-bottom: 5px;">Gruppe</label>
                            <select id="groupSelector" class="form-select" style="min-width: 200px;" onchange="changeGroup(this.value)">
                                @foreach($allGroups as $group)
                                    <option value="{{ $group->id }}" {{ $selectedGroup && $selectedGroup->id == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if($selectedGroup && in_array($user->role, ['creator', 'admin']))
                        <div>
                            <a href="{{ route('creator.mailing-lists.welcome', $selectedGroup) }}" class="btn btn-outline-primary">
                                <i class="fa-solid fa-pen me-1"></i> REDIGER VELKOMST
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Welcome Content Section -->
        @if($selectedGroup && ($selectedGroup->welcome_text || $selectedGroup->welcome_image))
            <div class="card mb-4" style="border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; margin-top: 30px;">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Left: Text Content -->
                        <div class="col-md-6 p-4">
                            <h2 style="font-size: 28px; font-weight: 700; color: #333; margin-bottom: 10px;">
                                {{ $selectedGroup->name }}
                            </h2>
                            @if($selectedGroup->organization_name)
                                <p style="font-size: 18px; font-weight: 400; color: #666; margin-bottom: 20px;">
                                    Leveret af
                                    @if($selectedGroup->website)
                                        <a href="{{ $selectedGroup->website }}" target="_blank" style="color: var(--primary-color); text-decoration: none;">
                                            {{ $selectedGroup->organization_name }}
                                        </a>
                                    @else
                                        {{ $selectedGroup->organization_name }}
                                    @endif
                                </p>
                            @endif
                            @if($selectedGroup->welcome_text)
                                <div style="font-size: 16px; font-weight: 300; color: #666; line-height: 1.6;">
                                    {!! $selectedGroup->welcome_text !!}
                                </div>
                            @endif
                        </div>
                        <!-- Right: Image -->
                        @if($selectedGroup->welcome_image)
                            <div class="col-md-6">
                                <img src="{{ asset('files/' . $selectedGroup->welcome_image) }}"
                                     alt="{{ $selectedGroup->name }}"
                                     style="width: 100%; height: 100%; object-fit: cover; min-height: 300px;"
                                     onerror="this.style.display='none'">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Two Column Layout -->
        <div class="row g-4">
            <!-- Left Column: Continue Learning -->
            <div class="col-lg-6">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 style="font-size: 20px; font-weight: 600; color: #333; margin: 0;">Fortsæt forløb</h2>
                        @if($enrolledCourses->count() > 0)
                            <a href="{{ route('courses.index') }}" class="text-decoration-none" style="color: var(--primary-color); font-size: 14px; font-weight: 400;">Se alle forløb</a>
                        @endif
                    </div>

                    @if($enrolledCourses->count() > 0)
                        <!-- Enrolled Courses Grid -->
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
                            <div class="card mb-3">
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
                        @endforeach
                    @else
                        <!-- Placeholder for no enrolled courses -->
                        <div class="card text-center py-5">
                            <div class="card-body">
                                <i class="fa-solid fa-circle-play" style="font-size: 3rem; color: #d1d5db;"></i>
                                <h5 class="mt-3 mb-2">Ingen igangværende kurser</h5>
                                <p class="text-muted mb-3" style="font-weight: 300;">Start med at udforske vores tilgængelige indhold</p>
                                <a href="{{ route('courses.index') }}" class="btn btn-primary">Udforsk indhold</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Email Archive -->
            <div class="col-lg-6">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 style="font-size: 20px; font-weight: 600; color: #333; margin: 0;">Nyhedsbreve du har modtaget</h2>
                    </div>

                    @if($selectedGroup && $groupEmails->count() > 0)
                        <!-- Email List -->
                        <div class="d-flex flex-column gap-3">
                            @foreach($groupEmails as $email)
                                <div class="card email-card" style="cursor: pointer;" onclick="openEmailModal({{ json_encode([
                                    'subject' => $email->subject,
                                    'sent_at' => $email->sent_at ? $email->sent_at->format('d/m/Y H:i') : 'Ikke sendt endnu',
                                    'body' => $email->body
                                ]) }})">
                                    <div class="card-body">
                                        <h5 class="card-title" style="font-size: 16px; font-weight: 600; color: #333; margin-bottom: 5px;">
                                            <i class="fa-solid fa-envelope" style="color: var(--primary-color);"></i> {{ $email->subject }}
                                        </h5>
                                        <p class="text-muted small mb-2">
                                            {{ $email->sent_at ? $email->sent_at->format('d/m/Y H:i') : 'Ikke sendt endnu' }}
                                        </p>
                                        @if($email->body)
                                            <p class="mb-0" style="font-size: 14px; color: #666; line-height: 1.5;">
                                                {{ Str::limit(strip_tags($email->body), 100) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Placeholder for no emails -->
                        <div class="card text-center py-5">
                            <div class="card-body">
                                <i class="fa-solid fa-envelope" style="font-size: 3rem; color: #d1d5db;"></i>
                                <h5 class="mt-3 mb-2">Ingen nyhedsbreve endnu</h5>
                                <p class="text-muted mb-0" style="font-weight: 300;">Du har ikke modtaget nogen nyhedsbreve fra denne gruppe</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Email Modal -->
    <div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="emailModalLabel" style="font-size: 20px; font-weight: 600; color: #333;"></h5>
                        <p class="text-muted small mb-0" id="emailModalDate"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="emailModalBody" style="font-size: 16px; line-height: 1.6; color: #333;">
                    <!-- Email content will be inserted here -->
                </div>
            </div>
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

        .btn-outline-primary {
            background: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 6px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        .form-select {
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            padding: 0.5rem 1rem;
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(190, 24, 93, 0.1);
        }

        .email-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
            transition: all 0.2s;
        }

        .modal-header {
            border-bottom: 1px solid #e0e0e0;
        }

        .modal-body {
            padding: 2rem;
        }
    </style>

    <script>
        function changeGroup(groupId) {
            if (groupId) {
                window.location.href = '{{ route('dashboard') }}?group=' + groupId;
            }
        }

        function openEmailModal(emailData) {
            // Set modal content
            document.getElementById('emailModalLabel').textContent = emailData.subject;
            document.getElementById('emailModalDate').textContent = emailData.sent_at;
            document.getElementById('emailModalBody').innerHTML = emailData.body || '<p class="text-muted">Ingen indhold tilgængeligt</p>';

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('emailModal'));
            modal.show();
        }
    </script>
</x-app-layout>