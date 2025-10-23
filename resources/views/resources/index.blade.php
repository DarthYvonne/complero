<x-app-layout>
@section('breadcrumbs')
    <span style="margin: 0 8px;">/</span>
    <strong style="color: #333; font-weight: 600;">Downloads</strong>
@endsection
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #333; margin-bottom: 0;">
                        <i class="fa-solid fa-photo-film" style="color: var(--primary-color);"></i> Downloads
                    </h1>
                </div>
                @if(Auth::user() && in_array(Auth::user()->role, ['admin', 'creator']))
                    <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-circle-plus me-1"></i> Tilføj download
                    </a>
                @endif
            </div>
        </div>

        <!-- Resources Grid -->
        <div class="row g-3">
            @forelse($resources as $resource)
                @php
                    // Resources use magenta by default
                    $resourceColor = '#be185d';
                    $rgb = sscanf($resourceColor, "#%02x%02x%02x");
                    $r = max(0, $rgb[0] - 30);
                    $g = max(0, $rgb[1] - 30);
                    $b = max(0, $rgb[2] - 30);
                    $hoverColor = sprintf("#%02x%02x%02x", $r, $g, $b);
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card clickable-card" data-href="{{ route('resources.show', $resource) }}" style="cursor: pointer;">
                        <img src="{{ $resource->image }}" class="card-img-top" alt="{{ $resource->title }}" style="height: 200px; object-fit: cover;">
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
                            <div class="d-flex justify-content-between align-items-center">
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
                        </div>
                    </div>
                    <a href="{{ route('resources.show', $resource) }}" class="btn w-100" style="margin-top: 15px; background: {{ $resourceColor }}; border-color: {{ $resourceColor }}; color: #ffffff; font-size: 14px; font-weight: 500; border-radius: 6px;"
                       onmouseover="this.style.background='{{ $hoverColor }}'; this.style.borderColor='{{ $hoverColor }}';"
                       onmouseout="this.style.background='{{ $resourceColor }}'; this.style.borderColor='{{ $resourceColor }}';">
                        <i class="fa-solid fa-arrow-right me-1"></i> Se download
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="card text-center py-5">
                        <div class="card-body">
                            <i class="fa-solid fa-glasses" style="font-size: 4rem; color: #d1d5db;"></i>
                            <h5 class="mt-3 mb-2">Intet download tilgængeligt endnu</h5>
                            <p class="text-muted mb-0" style="font-weight: 300;">Downloads vil blive tilgængeligt snart</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <style>
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
