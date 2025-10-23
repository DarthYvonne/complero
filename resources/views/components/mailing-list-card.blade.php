@props(['mailingList', 'showActions' => false])

<div class="col-md-6 col-lg-4">
    <div class="card clickable-card" data-href="{{ route('creator.mailing-lists.show', $mailingList) }}" style="cursor: pointer;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="card-title mb-0" style="font-size: 18px; font-weight: 600; color: #333;">
                    <i class="fa-solid fa-users" style="color: var(--primary-color);"></i> {{ $mailingList->name }}
                </h5>
            </div>
            <div class="card-text" style="font-size: 14px; font-weight: 300; color: #666; margin-bottom: 15px;">
                {{ Str::limit($mailingList->description, 150) }}
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span style="font-size: 13px; font-weight: 300; color: #999;">
                    <i class="fa-solid fa-user-group me-1" style="color: var(--primary-color);"></i>
                    {{ $mailingList->members->where('pivot.status', 'active')->count() }} medlemmer
                </span>
                <span style="font-size: 13px; font-weight: 300; color: #999;">
                    <span style="color: var(--primary-color); font-weight: 600;">Af:</span> {{ $mailingList->creator->organization_name ?: $mailingList->creator->name }}
                </span>
            </div>
        </div>
    </div>
</div>
