<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Garage Pro') - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @php
        $links = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'grid'],
            ['label' => 'Clients', 'route' => 'clients.index', 'icon' => 'people'],
            ['label' => 'Véhicules', 'route' => 'vehicules.index', 'icon' => 'car-front'],
            ['label' => 'Mécaniciens', 'route' => 'mecaniciens.index', 'icon' => 'wrench'],
            ['label' => 'Réparations', 'route' => 'reparations.index', 'icon' => 'tools'],
        ];
    @endphp

    <div class="app-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark">GG</span>
                <span>
                    <strong>Gestion Garage</strong>
                    <small>Système de gestion</small>
                </span>
            </a>

            <nav class="nav-list">
                @foreach ($links as $link)
                    <a class="nav-link {{ request()->routeIs($link['route']) || request()->routeIs(str_replace('.index', '.*', $link['route'])) ? 'active' : '' }}"
                        href="{{ route($link['route']) }}">
                        <span class="nav-icon">{{ strtoupper(substr($link['label'], 0, 1)) }}</span>
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <!-- <div class="sidebar-card">
                <span class="eyebrow">Statut atelier</span>
                <strong>Ouvert</strong>
                <p>Suivez les depots, reparations et livraisons depuis un seul espace.</p>
            </div> -->
        </aside>

        <main class="main-area">
            <header class="topbar">
                <div>
                    <span class="eyebrow">@yield('eyebrow', 'Gestion')</span>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="topbar-actions">
                    <a class="btn secondary" href="{{ route('clients.create') }}">Nouveau client</a>
                    <a class="btn primary" href="{{ route('reparations.create') }}">Nouvelle reparation</a>
                </div>
            </header>

            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert-error">
                    <strong>Veuillez corriger les champs suivants.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>