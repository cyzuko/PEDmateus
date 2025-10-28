<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema de Explicações') }}</title>

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
        .navbar-brand img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: contain;
    padding: 3px;
    background-color: white;
    margin-right: 10px;
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
            
            /* Botão admin responsivo */
            .admin-btn-mobile {
                margin: 2px 8px;
                width: calc(100% - 16px);
                text-align: left;
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
        .navbar-toggler:focus,
        .btn:focus {
            outline: none !important;
            box-shadow: none !important;
        }
        
        /* Remove o highlight cinzento do Bootstrap */
        .nav-link:active,
        .dropdown-item:active,
        .btn:active {
            background-color: transparent !important;
        }
        .navbar-brand img {
    width: 50px;
    height: 50px;
    object-fit: contain;
    margin-right: 10px;
    padding: 0;
    background-color: transparent;
}
        /* Estilo para o botão admin */
        .admin-btn {
            margin-left: 8px;
            margin-right: 8px;
        }
        
        @media (min-width: 768px) {
            .admin-btn {
                margin-left: 15px;
                margin-right: 0;
            }
        }
    </style>

</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-white navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" style="margin-left: 20px;">
    <img src="{{ asset('images/fotop2.jpg') }}" alt="Eureka Logo" 
        onload="console.log('Imagem carregada com sucesso!')" 
        onerror="console.error('Erro:', this.src)">
    <span>EUREKA</span>
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
                    <!-- Links para visitantes não autenticados -->
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
                    <!-- Links para usuários autenticados -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-lightbulb"></i> Eureka
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('explicacoes.index') }}">
                            <i class="fas fa-graduation-cap"></i> Explicações
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('estatisticas') }}">
                            <i class="fas fa-chart-bar"></i> Estatísticas
                        </a>
                    </li>
                    
                    
                   <li class="nav-item"></li>
                    <!-- Botão Admin - Desktop -->
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <li class="nav-item d-none d-md-block">
                            <a href="{{ url('/admin') }}" class="btn btn-primary admin-btn">
                                <i class="fas fa-cog"></i> Dashboard Admin
                            </a>
                        </li>
                    @endif
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <!-- Botão Admin - Mobile (dentro do dropdown) -->
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <a class="dropdown-item d-md-none" href="{{ url('/admin') }}">
                                    <i class="fas fa-cog"></i> Admin
                                </a>
                                <div class="dropdown-divider d-md-none"></div>
                            @endif
                            
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

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                          
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                
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
                <strong>&copy; {{ date('Y') }} <a href="#">Sistema de Explicações</a>.</strong> Todos os direitos reservados.
            </div>
        </footer>

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @yield('scripts')

</body>
</html>