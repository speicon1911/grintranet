<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gestor de Usuarios')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @auth
        @php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp
        <div class="app-container">
            <aside class="sidebar">
                <div class="sidebar-title">
                    Laravel<span style="color: #fff">Admin</span>
                </div>

                <div class="user-sidebar-info"
                    style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding: 1rem; background: rgba(255,255,255,0.03); border-radius: 0.75rem;">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div
                            style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div style="overflow: hidden;">
                        <div
                            style="font-weight: 600; font-size: 0.9rem; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $user->name }}
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">
                            {{ ucfirst($user->getRoleNames()[0] ?? '') }}
                        </div>
                    </div>
                </div>

                <ul class="nav-links">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profile.edit') }}"
                            class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            Mi Perfil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('personal-schedules.index') }}"
                            class="nav-link {{ request()->routeIs('personal-schedules.*') ? 'active' : '' }}">
                            Mi Horario
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('calendar.index') }}"
                            class="nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                            Calendario Escolar
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('schedule-templates.index') }}"
                            class="nav-link {{ request()->routeIs('schedule-templates.*') ? 'active' : '' }}">
                            Plantillas Horario
                        </a>
                    </li>
                    @role('profesor')
                    <li>
                        <a href="{{ route('groups.index') }}"
                            class="nav-link {{ request()->routeIs('groups.*') ? 'active' : '' }}">
                            Mis Grupos
                        </a>
                    </li>
                    @endrole

                    @role('admin')
                    <li>
                        <a href="{{ route('groups.index') }}"
                            class="nav-link {{ request()->routeIs('groups.*') ? 'active' : '' }}">
                            Grupos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            Usuarios
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('roles.index') }}"
                            class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            Roles
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('zonas.index') }}"
                            class="nav-link {{ request()->routeIs('zonas.*') ? 'active' : '' }}">
                            Zonas
                        </a>
                    </li>
                    @endrole
                    <li>
                        <a href="{{ route('ausencias.index') }}"
                            class="nav-link {{ request()->routeIs('ausencias.*') ? 'active' : '' }}">
                            Ausencias
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('aula.index') }}"
                            class="nav-link {{ request()->routeIs('aula.*') ? 'active' : '' }}">
                            AulaPass
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-toggle nav-link {{ request()->routeIs(['documentos.*', 'categorias.*', 'tipo-recursos.*', 'etiquetas.*']) ? 'active' : '' }}" onclick="toggleDropdown(this)">
                            <span>Gestor Documental</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 dropdown-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <ul class="nav-dropdown {{ request()->routeIs(['documentos.*', 'categorias.*', 'tipo-recursos.*', 'etiquetas.*']) ? 'show' : '' }}">
                            <li>
                                <a href="{{ route('documentos.index') }}"
                                    class="nav-link {{ request()->routeIs('documentos.*') ? 'active' : '' }}">
                                    <span style="margin-left: 1rem;">Documentos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('categorias.index') }}"
                                    class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                                    <span style="margin-left: 1rem;">Carpetas</span>
                                </a>
                            </li>

                            @hasanyrole('admin|directiva')
                            <li>
                                <a href="{{ route('etiquetas.index') }}"
                                    class="nav-link {{ request()->routeIs('etiquetas.*') ? 'active' : '' }}">
                                    <span style="margin-left: 1rem;">Etiquetas</span>
                                </a>
                            </li>
                            @endhasanyrole
                        </ul>
                    </li>

                    <li style="margin-top: auto;">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link"
                                style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                                Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </aside>

            <main class="main-content">
                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endauth
</body>

</html>