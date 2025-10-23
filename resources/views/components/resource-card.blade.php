@props(['resource', 'showActions' => false])

<div class="col-md-6 col-lg-4">
    <div class="card clickable-card" data-href="{{ route('creator.resources.show', $resource) }}" style="cursor: pointer;">
        <img src="{{ $resource->image }}" class="card-img-top" alt="{{ $resource->title }}" style="height: 200px; object-fit: cover;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                    <i class="fa-solid fa-download" style="color: var(--primary-color);"></i> {{ $resource->title }}
                </h5>
                @if($resource->is_published)
                    <span class="badge bg-success">Publiceret</span>
                @else
                    <span class="badge bg-secondary">Kladde</span>
                @endif
            </div>
            <div class="card-text" style="font-size: 14px; font-weight: 300; color: #666; margin-bottom: 15px;">
                {!! Str::limit(strip_tags($resource->description), 200) !!}
                @if(strlen(strip_tags($resource->description)) > 200)
                    <a href="{{ route('creator.resources.show', $resource) }}" style="color: var(--primary-color); text-decoration: underline;">læs resten</a>
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span style="font-size: 13px; font-weight: 300; color: #999;">
                    <span style="color: var(--primary-color); font-weight: 600;">Af:</span> {{ $resource->creator->organization_name ?: $resource->creator->name }}
                </span>
                <span style="font-size: 16px; font-weight: 600; color: #333;">
                    @if($resource->is_free)
                        Gratis for medlemmer
                    @else
                        €{{ number_format($resource->price, 2) }}
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>
