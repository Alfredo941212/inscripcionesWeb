<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Inscripciones')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @stack('styles')
</head>
<body class="bg-light min-vh-100 d-flex flex-column">
    <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ route('home') }}">
                <i class="bi bi-trophy-fill me-1"></i> Evento Deportivo y Cultural
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    @auth
                        @if(auth()->user()->role === 'participant')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('participant.dashboard') }}">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('participant.profile.edit') }}">Mi perfil</a>
                            </li>
                            @if(optional(auth()->user()->participantProfile)->status === 'accepted')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('participant.disciplines.index') }}">Disciplinas</a>
                                </li>
                            @endif
                        @elseif(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Panel administrativo</a>
                            </li>
                        @elseif(auth()->user()->role === 'supervisor')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('supervisor.dashboard') }}">Panel supervisor</a>
                            </li>
                        @endif
                    @endauth
                </ul>

                @php
                    $currentUser = auth()->user();
                    $recentNotifications = $currentUser?->notifications()->latest()->limit(5)->get() ?? collect();
                    $unreadNotificationsCount = $currentUser?->unreadNotifications()->count() ?? 0;
                @endphp

                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative" href="#" id="navbarNotifications" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell{{ $unreadNotificationsCount ? '-fill' : '' }}"></i>
                                @if($unreadNotificationsCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                                        {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" aria-labelledby="navbarNotifications" style="min-width: 320px;">
                                <div class="list-group list-group-flush">
                                    @forelse($recentNotifications as $notification)
                                        <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                                            <p class="fw-semibold mb-1">{{ $notification->data['title'] ?? 'Notificación' }}</p>
                                            <p class="mb-1 small">{{ $notification->data['message'] ?? '' }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </div>
                                    @empty
                                        <div class="list-group-item text-center text-muted small">
                                            Sin notificaciones recientes.
                                        </div>
                                    @endforelse
                                </div>
                                <div class="border-top px-3 py-2 text-center">
                                    <a href="{{ route('notifications.index') }}" class="small text-decoration-none">
                                        Ver todas las notificaciones
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item me-2 text-white-50 small">
                            Bienvenido, <strong>{{ auth()->user()->name }}</strong>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="btn btn-outline-light btn-sm">
                                    <i class="bi bi-box-arrow-right me-1"></i>Salir
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Ingresar</a>
                        </li>
                        <li class="nav-item ms-md-2">
                            <a class="btn btn-outline-light btn-sm" href="{{ route('register') }}">
                                Crear cuenta
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 py-4">
        @yield('content')
    </main>

    <footer class="bg-white border-top py-3 mt-auto">
        <div class="container text-center text-muted small">
            © {{ date('Y') }} Universidad - Evento Deportivo y Cultural
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
