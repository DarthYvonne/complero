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
                <i class="fa-solid fa-heart" style="color: #be185d;"></i> {{ $organizationName }}
            </h1>
            <p style="font-size: 14px; font-weight: 300; color: #999; margin: 0;">
                Velkommen tilbage, {{ Auth::user()->name }}
            </p>
        </div>

        <!-- Continue Learning Section -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 style="font-size: 20px; font-weight: 600; color: #333; margin: 0;">Fortsæt læring</h2>
                <a href="#" class="text-decoration-none" style="color: #be185d; font-size: 14px; font-weight: 400;">Se alle</a>
            </div>

            <!-- Placeholder for courses in progress - FULL WIDTH -->
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fa-solid fa-circle-play" style="font-size: 4rem; color: #d1d5db;"></i>
                    <h5 class="mt-3 mb-2">Ingen igangværende kurser</h5>
                    <p class="text-muted mb-3" style="font-weight: 300;">Start med at udforske vores tilgængelige indhold</p>
                    <a href="#" class="btn btn-primary">Udforsk indhold</a>
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
            background: #be185d;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            font-size: 14px;
        }

        .btn-primary:hover {
            background: #9f1239;
        }
    </style>
</x-app-layout>