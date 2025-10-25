<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Indhold</strong>
@endsection

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                <i class="fa-solid fa-book-open" style="color: var(--primary-color);"></i> Indhold
            </h1>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" style="margin-bottom: 44px;" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab === 'courses' ? 'active' : '' }}" href="{{ route('content.index', ['tab' => 'courses']) }}">
                    <i class="fa-solid fa-circle-play me-1"></i> Forløb
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link {{ $tab === 'resources' ? 'active' : '' }}" href="{{ route('content.index', ['tab' => 'resources']) }}">
                    <i class="fa-solid fa-photo-film me-1"></i> Materialer
                </a>
            </li>
        </ul>

        <!-- Content Area -->
        @if($tab === 'courses')
            <!-- Courses Tab -->
            <div class="row g-3">
                @forelse($courses as $course)
                    @php
                        $courseColor = $course->primary_color ?? '#be185d';
                        $rgb = sscanf($courseColor, "#%02x%02x%02x");
                        $r = max(0, $rgb[0] - 30);
                        $g = max(0, $rgb[1] - 30);
                        $b = max(0, $rgb[2] - 30);
                        $hoverColor = sprintf("#%02x%02x%02x", $r, $g, $b);
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="card clickable-card" data-href="{{ route('courses.show', $course) }}" style="cursor: pointer;">
                            <img src="{{ $course->image }}" class="card-img-top" alt="{{ $course->title }}" style="aspect-ratio: 16/9; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title" style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 10px;">
                                    <i class="fa-solid fa-circle-play" style="color: {{ $courseColor }};"></i> {{ $course->title }}
                                </h5>
                                <div class="card-text" style="font-size: 14px; font-weight: 300; color: #666; margin-bottom: 15px;">
                                    @php
                                        $displayText = $course->short_description ?: strip_tags($course->description);
                                    @endphp
                                    {!! Str::limit($displayText, 200) !!}
                                    @if(strlen($displayText) > 200)
                                        <a href="{{ route('courses.show', $course) }}" style="color: {{ $courseColor }}; text-decoration: underline;">læs resten</a>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span style="font-size: 13px; font-weight: 300; color: #999;">
                                        <span style="color: {{ $courseColor }}; font-weight: 600;">Af:</span> {{ $course->creator->organization_name ?: $course->creator->name }}
                                    </span>
                                    <span style="font-size: 16px; font-weight: 600; color: #333;">
                                        @if($course->is_free)
                                            Gratis for dig
                                        @else
                                            €{{ number_format($course->price, 2) }}
                                        @endif
                                    </span>
                                </div>
                                <a href="{{ route('courses.show', $course) }}" class="btn w-100 course-btn" style="background: {{ $courseColor }}; border-color: {{ $courseColor }}; color: {{ $courseColor === '#F2CC21' ? '#000000' : '#ffffff' }};"
                                   onmouseover="this.style.background='{{ $hoverColor }}'; this.style.borderColor='{{ $hoverColor }}';"
                                   onmouseout="this.style.background='{{ $courseColor }}'; this.style.borderColor='{{ $courseColor }}';">
                                    <i class="fa-solid fa-arrow-right me-1"></i> Se forløb
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card text-center py-5">
                            <div class="card-body">
                                <i class="fa-solid fa-circle-play" style="font-size: 4rem; color: #d1d5db;"></i>
                                <h5 class="mt-3 mb-2">Ingen forløb tilgængelige endnu</h5>
                                <p class="text-muted mb-0" style="font-weight: 300;">Forløb vil blive tilgængelige snart</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        @else
            <!-- Resources Tab -->
            <div class="row g-3">
                @forelse($resources as $resource)
                    @php
                        $resourceColor = '#be185d';
                        $rgb = sscanf($resourceColor, "#%02x%02x%02x");
                        $r = max(0, $rgb[0] - 30);
                        $g = max(0, $rgb[1] - 30);
                        $b = max(0, $rgb[2] - 30);
                        $hoverColor = sprintf("#%02x%02x%02x", $r, $g, $b);
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="card clickable-card" data-href="{{ route('resources.show', $resource) }}" style="cursor: pointer;">
                            <img src="{{ $resource->image }}" class="card-img-top" alt="{{ $resource->title }}" style="aspect-ratio: 16/9; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title" style="font-size: 18px; font-weight: 600; color: #333; margin-bottom: 10px;">
                                    <i class="fa-solid fa-photo-film" style="color: {{ $resourceColor }};"></i> {{ $resource->title }}
                                </h5>
                                <div class="card-text" style="font-size: 14px; font-weight: 300; color: #666; margin-bottom: 15px;">
                                    {!! Str::limit(strip_tags($resource->description), 200) !!}
                                    @if(strlen(strip_tags($resource->description)) > 200)
                                        <a href="{{ route('resources.show', $resource) }}" style="color: {{ $resourceColor }}; text-decoration: underline;">læs resten</a>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span style="font-size: 13px; font-weight: 300; color: #999;">
                                        <span style="color: {{ $resourceColor }}; font-weight: 600;">Af:</span> {{ $resource->creator->organization_name ?: $resource->creator->name }}
                                    </span>
                                    <span style="font-size: 16px; font-weight: 600; color: #333;">
                                        @if($resource->is_free)
                                            Gratis for dig
                                        @else
                                            €{{ number_format($resource->price, 2) }}
                                        @endif
                                    </span>
                                </div>
                                <a href="{{ route('resources.show', $resource) }}" class="btn w-100 resource-btn" style="background: {{ $resourceColor }}; border-color: {{ $resourceColor }}; color: #ffffff;"
                                   onmouseover="this.style.background='{{ $hoverColor }}'; this.style.borderColor='{{ $hoverColor }}';"
                                   onmouseout="this.style.background='{{ $resourceColor }}'; this.style.borderColor='{{ $resourceColor }}';">
                                    <i class="fa-solid fa-arrow-right me-1"></i> Se materiale
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card text-center py-5">
                            <div class="card-body">
                                <i class="fa-solid fa-photo-film" style="font-size: 4rem; color: #d1d5db;"></i>
                                <h5 class="mt-3 mb-2">Intet materiale tilgængeligt endnu</h5>
                                <p class="text-muted mb-0" style="font-weight: 300;">Materialer vil blive tilgængeligt snart</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    <style>
        .nav-tabs {
            border-bottom: none;
        }
        .nav-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
            background: none;
        }
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        .card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: box-shadow 0.2s;
        }
        .card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Make cards clickable except when clicking buttons
            document.querySelectorAll('.clickable-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Don't navigate if clicking a button or form element
                    if (!e.target.closest('button, a, form')) {
                        window.location.href = this.dataset.href;
                    }
                });
            });
        });
    </script>
</x-app-layout>
