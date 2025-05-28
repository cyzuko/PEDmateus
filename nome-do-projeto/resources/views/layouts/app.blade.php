<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema de Faturas') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

    <!-- FontAwesome CDN oficial para garantir ícones -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS para mobile -->
    <style>
        /* Remove o efeito cinzento de tap no mobile */
        * {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        /* Permitir seleção de texto apenas onde necessário */
        input, textarea, [contenteditable] {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }
        
        /* Ajustes para mobile */
        @media (max-width: 767px) {
            .navbar-nav {
                padding: 10px 0;
                background-color: #f8f9fa;
                border-radius: 8px;
                margin-top: 10px;
            }
            
            .navbar-nav .nav-item {
                margin: 2px 0;
            }
            
            .navbar-nav .nav-link {
                padding: 12px 20px !important;
                color: #495057 !important;
                border-radius: 6px;
                margin: 2px 8px;
                transition: all 0.2s ease;
            }
            
            .navbar-nav .nav-link:hover,
            .navbar-nav .nav-link:active,
            .navbar-nav .nav-link:focus {
                background-color: #e9ecef !important;
                color: #343a40 !important;
                transform: none !important;
            }
            
            .dropdown-menu {
                position: static !important;
                float: none !important;
                width: calc(100% - 16px) !important;
                margin: 8px !important;
                background-color: #f8f9fa !important;
                border: 1px solid #dee2e6 !important;
                border-radius: 6px !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            }
            
            .dropdown-item {
                padding: 10px 20px !important;
                color: #495057 !important;
                transition: all 0.2s ease;
            }
            
            .dropdown-item:hover,
            .dropdown-item:active,
            .dropdown-item:focus {
                background-color: #e9ecef !important;
                color: #343a40 !important;
                transform: none !important;
            }
            
            .navbar-collapse {
                border: none;
                margin-top: 5px;
                padding: 0;
            }
        }
        
        /* Hamburger menu customizado */
        .navbar-toggler {
            border: none !important;
            padding: 6px 10px !important;
            background-color: transparent !important;
            outline: none !important;
        }
        
        .navbar-toggler:focus,
        .navbar-toggler:active {
            box-shadow: none !important;
            outline: none !important;
            background-color: transparent !important;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%280, 0, 0, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
            width: 1.2em;
            height: 1.2em;
        }
        
        /* Remove o outline azul dos links */
        .nav-link:focus,
        .dropdown-item:focus,
        .navbar-toggler:focus {
            outline: none !important;
            box-shadow: none !important;
        }
        
        /* Remove o highlight cinzento do Bootstrap */
        .nav-link:active,
        .dropdown-item:active,
        .btn:active {
            background-color: transparent !important;
        }
    </style>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-white navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Sistema de Faturas') }}
                </a>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <!-- Add left navbar items here if needed -->
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus"></i> Registro
                                </a>
                            </li>
                       @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">
                                    <i class="fas fa-home"></i> Casa
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('faturas.index') }}">
                                    <i class="fas fa-file-invoice"></i> Faturas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('estatisticas') }}">
                                    <i class="fas fa-chart-bar"></i> Estatísticas
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                                </a>
                               <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                  <a class="dropdown-item" href="{{ route('password.change') }}">
                                      <i class="fas fa-key"></i> Alterar Senha
                                  </a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="{{ route('logout') }}">
                                     <i class="fas fa-sign-out-alt"></i> Terminar Sessão
                                  </a>
                               </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ url('/') }}" class="brand-link">
                <span class="brand-text font-weight-light">{{ config('app.name', 'Sistema de Faturas') }}</span>
            </a>
            
            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Painel do Utilizador com dropdown -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info w-100">

                        <!-- Botão que mostra/esconde ações -->
                        <a href="#" class="d-flex align-items-center text-white ps-2 pe-2" id="user-toggle" style="text-decoration: none;">
                            <i class="fas fa-user nav-icon" style="margin-left: 14px; margin-right: 9px;"></i>
                            <span class="flex-grow-1">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ms-auto" id="chevron-icon"></i>
                        </a>

                        <!-- Dropdown com links -->
                        <div id="user-actions" class="ps-4 pt-2" style="display: none;">
                            <a href="{{ route('password.change') }}" class="d-block text-white mb-1">
                                <i class="fas fa-key me-2"></i> Alterar Senha
                            </a>
                            <a href="{{ route('logout') }}" class="d-block text-white">
                                <i class="fas fa-sign-out-alt me-2"></i> Terminar Sessão
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Casa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('faturas.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>Faturas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('estatisticas') }}" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Estatísticas</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">{{ $title ?? 'Dashboard' }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                <li class="breadcrumb-item active">{{ $title ?? 'Dashboard' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="container-fluid">
                <div class="float-right d-none d-sm-block">
                    <b>Version</b> 3.2.0
                </div>
                <strong>&copy; {{ date('Y') }} <a href="#">Sistema de Faturas</a>.</strong> Todos os direitos reservados.
            </div>
        </footer>

    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Script para o dropdown do usuário na sidebar
            const toggle = document.getElementById("user-toggle");
            const actions = document.getElementById("user-actions");
            const chevron = document.getElementById("chevron-icon");

            if (toggle) {
                toggle.addEventListener("click", function (e) {
                    e.preventDefault();
                    const isVisible = actions.style.display === "block";
                    actions.style.display = isVisible ? "none" : "block";
                    chevron.classList.toggle("fa-chevron-down", isVisible);
                    chevron.classList.toggle("fa-chevron-up", !isVisible);
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @yield('scripts')

</body>
</html>