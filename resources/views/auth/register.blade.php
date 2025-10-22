<x-guest-layout>
    <h2 class="text-center mb-3 fw-bold">Opret konto</h2>
    <p class="text-center text-muted mb-4">Tilmeld dig Complicero og kom i gang</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Navn</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                   name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Adgangskode</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Bekræft adgangskode</label>
            <input id="password_confirmation" type="password" class="form-control"
                   name="password_confirmation" required autocomplete="new-password">
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">Opret konto</button>
        </div>

        <div class="text-center">
            <span class="text-muted">Har du allerede en konto?</span>
            <a href="{{ route('login') }}" class="text-decoration-none">Log ind</a>
        </div>
    </form>
</x-guest-layout>
