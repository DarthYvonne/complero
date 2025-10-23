@props(['course', 'showActions' => false])

@php
    $courseColor = $course->primary_color ?? '#be185d';
    $rgb = sscanf($courseColor, "#%02x%02x%02x");
    $r = max(0, $rgb[0] - 30);
    $g = max(0, $rgb[1] - 30);
    $b = max(0, $rgb[2] - 30);
    $hoverColor = sprintf("#%02x%02x%02x", $r, $g, $b);
@endphp

<div class="col-md-6 col-lg-4">
    <div class="card clickable-card" data-href="{{ route('creator.courses.show', $course) }}" style="cursor: pointer;">
        <img src="{{ $course->image }}" class="card-img-top" alt="{{ $course->title }}" style="height: 200px; object-fit: cover;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                    <i class="fa-solid fa-circle-play" style="color: {{ $courseColor }};"></i> {{ $course->title }}
                </h5>
                @if($course->is_published)
                    <span class="badge bg-success">Publiceret</span>
                @else
                    <span class="badge bg-secondary">Kladde</span>
                @endif
            </div>
            <div class="card-text" style="font-size: 14px; font-weight: 300; color: #666; margin-bottom: 15px;">
                {!! Str::limit(strip_tags($course->description), 200) !!}
                @if(strlen(strip_tags($course->description)) > 200)
                    <a href="{{ route('creator.courses.show', $course) }}" style="color: {{ $courseColor }}; text-decoration: underline;">læs resten</a>
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span style="font-size: 13px; font-weight: 300; color: #999;">
                    <span style="color: {{ $courseColor }}; font-weight: 600;">Af:</span> {{ $course->creator->organization_name ?: $course->creator->name }}
                </span>
                <span style="font-size: 16px; font-weight: 600; color: #333;">
                    @if($course->is_free)
                        Gratis for medlemmer
                    @else
                        €{{ number_format($course->price, 2) }}
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>
