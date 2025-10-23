<!DOCTYPE html>
<html lang="da">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Complicero') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('graphics/favicon.png') }}">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome Solid -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Quill Rich Text Editor -->
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

        <style>
            :root {
                @php
                    // Detect course color from context
                    $primaryColor = '#be185d'; // Default magenta
                    $textColor = '#ffffff'; // Default white text

                    if (isset($course) && $course->primary_color) {
                        $primaryColor = $course->primary_color;
                        // Yellow needs black text
                        if ($primaryColor === '#F2CC21') {
                            $textColor = '#000000';
                        }
                    } elseif (isset($lesson) && isset($lesson->course) && $lesson->course->primary_color) {
                        $primaryColor = $lesson->course->primary_color;
                        if ($primaryColor === '#F2CC21') {
                            $textColor = '#000000';
                        }
                    }

                    // Calculate hover color (darker version)
                    $rgb = sscanf($primaryColor, "#%02x%02x%02x");
                    $r = max(0, $rgb[0] - 30);
                    $g = max(0, $rgb[1] - 30);
                    $b = max(0, $rgb[2] - 30);
                    $hoverColor = sprintf("#%02x%02x%02x", $r, $g, $b);
                @endphp
                --primary-color: {{ $primaryColor }};
                --primary-hover: {{ $hoverColor }};
                --primary-text: {{ $textColor }};
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: #f8f9fa;
                margin: 0;
                padding: 0;
            }

            /* Sidebar */
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 220px;
                background-color: #f5f5f7;
                border-right: 1px solid #e0e0e0;
                padding: 0;
                overflow-y: auto;
                z-index: 1000;
            }

            .sidebar-logo {
                padding: 20px;
                border-bottom: 1px solid #e0e0e0;
                background: white;
                height: 70px;
                display: flex;
                align-items: center;
            }

            .sidebar-logo img {
                height: 30px;
                width: auto;
            }

            .sidebar-menu {
                padding: 0;
                margin: 0;
                list-style: none;
            }

            .sidebar-menu li {
                margin: 0;
            }

            .sidebar-menu a {
                display: flex;
                align-items: center;
                padding: 12px 20px;
                color: #333;
                text-decoration: none;
                font-size: 15px;
                font-weight: 400;
                transition: all 0.2s;
            }

            .sidebar-menu a:hover {
                background-color: #e8e8ea;
            }

            .sidebar-menu a.active {
                background-color: var(--primary-color);
                color: var(--primary-text);
                font-weight: 500;
            }

            .sidebar-menu a i {
                width: 20px;
                margin-right: 10px;
                font-size: 15px;
            }

            .sidebar-menu a.active i {
                color: white !important;
            }

            .sidebar-divider {
                margin: 10px 0;
                padding-top: 10px;
                border-top: 1px solid #e0e0e0;
            }

            .sidebar-label {
                padding: 8px 20px;
                font-size: 11px;
                text-transform: uppercase;
                color: #999;
                font-weight: 600;
                letter-spacing: 0.5px;
            }

            /* Main content */
            .main-content {
                margin-left: 220px;
                min-height: 100vh;
                padding: 0;
            }

            .top-bar {
                background: white;
                border-bottom: 1px solid #e0e0e0;
                padding: 15px 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                height: 70px;
            }

            .mobile-header {
                display: none;
            }

            .hamburger {
                background: none;
                border: none;
                font-size: 24px;
                color: #333;
                cursor: pointer;
                padding: 10px;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }

            .sidebar.active {
                transform: translateX(0) !important;
            }

            .user-menu {
                position: relative;
            }

            .user-menu .dropdown-toggle {
                background: none;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
                font-size: 15px;
                color: #333;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .user-menu .dropdown-toggle:hover {
                background-color: #f5f5f7;
                border-radius: 4px;
            }

            .page-content {
                padding: 30px;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-220px);
                    transition: transform 0.3s ease;
                }
                .main-content {
                    margin-left: 0;
                }
                .top-bar {
                    display: none;
                }
                .mobile-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 15px 20px;
                    background: white;
                    border-bottom: 1px solid #e0e0e0;
                    height: 70px;
                }
                .mobile-header img {
                    height: 30px;
                    width: auto;
                }
                .sidebar-overlay.active {
                    display: block;
                }
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <a href="{{ route('dashboard') }}" style="display: block;">
                    <img src="{{ asset('graphics/logo.png') }}" alt="Complicero">
                </a>
            </div>

            <ul class="sidebar-menu">
                @php
                    $effectiveRole = session('view_as', Auth::user()->role);
                @endphp

                <!-- USER MENU - Always shown -->
                <li class="sidebar-label">BRUGER MENU</li>
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-heart" style="color: var(--primary-color);"></i> Start
                    </a>
                </li>
                <li>
                    <a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.index') || request()->routeIs('courses.show') || request()->routeIs('lessons.show') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-play" style="color: var(--primary-color);"></i> Forløb
                    </a>
                </li>
                <li>
                    <a href="{{ route('resources.index') }}" class="{{ request()->routeIs('resources.index') || request()->routeIs('resources.show') ? 'active' : '' }}">
                        <i class="fa-solid fa-photo-film" style="color: var(--primary-color);"></i> Materialer
                    </a>
                </li>

                @if($effectiveRole === 'creator' || $effectiveRole === 'admin')
                    <!-- CREATOR MENU - Shown for creators and admins -->
                    <li class="sidebar-divider"></li>
                    <li class="sidebar-label">CREATOR MENU</li>
                    <li>
                        <a href="{{ route('creator.dashboard') }}" class="{{ request()->routeIs('creator.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-gauge" style="color: #666;"></i> Overblik
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('creator.courses.index') }}" class="{{ request()->routeIs('creator.courses.*') || request()->routeIs('creator.lessons.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-circle-play" style="color: #666;"></i> Mine Forløb (<b>{{ $coursesCount ?? 0 }}</b>)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('creator.resources.index') }}" class="{{ request()->routeIs('creator.resources.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-photo-film" style="color: #666;"></i> Mine Materialer (<b>{{ $resourcesCount ?? 0 }}</b>)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('creator.mailing-lists.index') }}" class="{{ request()->routeIs('creator.mailing-lists.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-envelope" style="color: #666;"></i> Mine Lister (<b>{{ $mailingListsCount ?? 0 }}</b>)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('creator.emails.index') }}" class="{{ request()->routeIs('creator.emails.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-paper-plane" style="color: #666;"></i> Mine Emails
                        </a>
                    </li>
                @endif

                @if($effectiveRole === 'admin')
                    <!-- ADMIN MENU - Shown only for admins -->
                    <li class="sidebar-divider"></li>
                    <li class="sidebar-label">ADMIN MENU</li>
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-gauge" style="color: #666;"></i> Overblik
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.courses.index') }}" class="{{ request()->routeIs('admin.courses.*') || request()->routeIs('admin.courses.lessons.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-circle-play" style="color: #666;"></i> Alle Forløb
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.resources.index') }}" class="{{ request()->routeIs('admin.resources.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-photo-film" style="color: #666;"></i> Alle Materialer
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.mailing-lists.index') }}" class="{{ request()->routeIs('admin.mailing-lists.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-envelope" style="color: #666;"></i> Alle Lister
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users" style="color: #666;"></i> Brugere
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.activity.index') }}" class="{{ request()->routeIs('admin.activity.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-clock-rotate-left" style="color: #666;"></i> Aktivitetslog
                        </a>
                    </li>
                @endif

                <li class="sidebar-divider"></li>
                <li>
                    <a href="{{ route('profile.edit') }}">
                        <i class="fa-solid fa-user" style="color: var(--primary-color);"></i> Min profil
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; text-align: left; background: none; border: none; padding: 0; cursor: pointer;">
                            <a style="display: flex; align-items: center; padding: 12px 20px; color: #333; text-decoration: none; font-size: 15px; font-weight: 400;">
                                <i class="fa-solid fa-right-from-bracket" style="width: 20px; margin-right: 10px; font-size: 15px; color: var(--primary-color);"></i> Log ud
                            </a>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Mobile Header -->
            <div class="mobile-header">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('graphics/logo.png') }}" alt="Complicero">
                </a>
                <button class="hamburger" id="hamburgerBtn">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <!-- Desktop Top Bar -->
            <div class="top-bar">
                <div class="breadcrumb-area" style="font-size: 15px; color: #999; font-weight: 300;">
                    @php
                        $user = Auth::user();
                        $organizationName = 'Complicero'; // Default

                        if ($user && in_array($user->role, ['admin', 'creator'])) {
                            // Use organization name if set, otherwise use user's name
                            $organizationName = $user->organization_name ?: $user->name;
                        }
                    @endphp

                    @hasSection('breadcrumbs')
                        <a href="{{ route('dashboard') }}" style="color: #999; text-decoration: none; transition: color 0.2s;">{{ $organizationName }}</a>
                        @yield('breadcrumbs')
                    @else
                        {{ $organizationName }}
                    @endif
                </div>
                <div class="d-flex align-items-center gap-3">
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'creator')
                        <!-- View As Dropdown -->
                        <div class="view-as-menu dropdown">
                            @php
                                $viewAs = session('view_as', Auth::user()->role);
                                $viewAsLabel = match($viewAs) {
                                    'admin' => 'Administrator',
                                    'creator' => 'Creator',
                                    'member' => 'Medlem',
                                    default => ucfirst($viewAs)
                                };
                            @endphp
                            <button class="dropdown-toggle" data-bs-toggle="dropdown" style="background: none; border: 1px solid #e0e0e0; padding: 6px 12px; cursor: pointer; font-size: 14px; color: #666; border-radius: 4px; display: flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-eye" style="font-size: 13px;"></i>
                                <span>Se som: {{ $viewAsLabel }}</span>
                            </button>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item {{ !session('view_as') || session('view_as') === 'admin' ? 'active' : '' }}" href="{{ route('view-as', 'admin') }}">Administrator</a></li>
                                @endif
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'creator')
                                    <li><a class="dropdown-item {{ session('view_as') === 'creator' ? 'active' : '' }}" href="{{ route('view-as', 'creator') }}">Creator</a></li>
                                @endif
                                <li><a class="dropdown-item {{ session('view_as') === 'member' ? 'active' : '' }}" href="{{ route('view-as', 'member') }}">Medlem</a></li>
                            </ul>
                        </div>
                    @endif

                    <!-- User Menu -->
                    <div class="user-menu dropdown">
                        <button class="dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-circle-user"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Log ud</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="page-content">
                {{ $slot }}
            </div>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Quill Rich Text Editor -->
        <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

        <!-- Mobile Menu Toggle -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const hamburgerBtn = document.getElementById('hamburgerBtn');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');

                if (hamburgerBtn) {
                    hamburgerBtn.addEventListener('click', function() {
                        sidebar.classList.toggle('active');
                        overlay.classList.toggle('active');
                    });

                    overlay.addEventListener('click', function() {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    });
                }
            });
        </script>

        @stack('scripts')
    </body>
</html>
