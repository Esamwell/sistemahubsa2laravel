<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Agência Solicita</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <a href="/dashboard" class="navbar-brand">Agência Solicita</a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="/clients" class="nav-link">Clientes</a>
                </li>
                <li class="nav-item">
                    <a href="/requests" class="nav-link">Solicitações</a>
                </li>
                <li class="nav-item">
                    <a href="/calendar" class="nav-link">Calendário</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="api.logout()">Sair</a>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html> 