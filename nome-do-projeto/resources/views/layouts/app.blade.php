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
    <a class="nav-link" href="{{ route('mensagens.index') }}">
        <i class="fas fa-comments"></i> Mensagens
        @php
            $totalNaoLidas = auth()->user()->grupos()
                ->where('ativo', true)
                ->get()
                ->sum(function($grupo) {
                    return $grupo->mensagensNaoLidas(auth()->id());
                });
        @endphp
        @if($totalNaoLidas > 0)
            <span class="badge badge-danger">{{ $totalNaoLidas }}</span>
        @endif
    </a>
</li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('estatisticas') }}">
                            <i class="fas fa-chart-bar"></i> Estatísticas
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()->role === 'admin')
    <li class="nav-item">
        <a href="{{ url('/admin/grupos') }}" class="btn btn-info admin-btn">
            <i class="fas fa-users"></i> Gerir Grupos
        </a>
    </li>
@endif
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
                <i class="fas fa-cog"></i> Dashboard Admin
            </a>
            <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users"></i> Gestão de Utilizadores
            </a>
            <div class="dropdown-divider"></div>
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

<footer class="modern-footer">
    <div class="footer-content">
        <div class="container-fluid">
            <div class="row align-items-center">
                <!-- Brand -->
                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                    <div class="footer-brand">
                        <h4>
                            <i class="fas fa-graduation-cap"></i>
                            EUREKA
                        </h4>
                        <p>Centro de Explicações</p>
                        <div class="social-links">
                            <a href="#" class="social-link" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            
                        </div>
                    </div>
                </div>

                <!-- Links Rápidos -->
                <div class="col-lg-2 col-md-6 mb-3 mb-lg-0">
                    <h6 class="footer-title">Links Rápidos</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="fas fa-angle-right"></i> Início</a></li>
                        <li><a href="{{ route('explicacoes.index') }}"><i class="fas fa-angle-right"></i> Explicações</a></li>
                        <li><a href="{{ route('estatisticas') }}"><i class="fas fa-angle-right"></i> Estatísticas</a></li>
                        <li><a href="{{ route('mensagens.index') }}"><i class="fas fa-angle-right"></i> Mensagens</a></li>
                    </ul>
                </div>

               

                <!-- Contacto -->
                <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                    <h6 class="footer-title">Contacto</h6>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> Largo das Neves, Vila de Punhe<br>4905-665 Viana do Castelo</li>
                        <li><i class="fas fa-envelope"></i> contato@eureka.pt</li>
                        <li><i class="fas fa-clock"></i> Seg-Sex: 9h-18h</li>
                    </ul>
                </div>

                <!-- Copyright -->
                <div class="col-12">
                    <div class="footer-bottom">
                        <span>&copy; {{ date('Y') }} <strong>Sistema EUREKA</strong>. Todos os direitos reservados.</span>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Footer Moderno Compacto */
    .modern-footer {
        background: linear-gradient(135deg, #2a8fe1 0%, #4da6ff 100%);
        color: white;
        position: relative;
        overflow: hidden;
        margin: 0;
        padding: 2.5rem 0 1rem 0;
    }

    .modern-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(255,193,7,0.15) 0%, transparent 50%),
                   radial-gradient(circle at 70% 50%, rgba(255,255,255,0.1) 0%, transparent 50%);
        z-index: 1;
    }

    .footer-content {
        position: relative;
        z-index: 2;
        padding-left: 2rem;
        padding-right: 2rem;
    }

    .footer-brand h4 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .footer-brand p {
        color: rgba(255,255,255,0.9);
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .footer-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        position: relative;
        padding-bottom: 0.5rem;
        color: #ffc107;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 30px;
        height: 2px;
        background: #ffc107;
        border-radius: 2px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 0.5rem;
    }

    .footer-links a {
        color: rgba(255,255,255,0.85);
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.9rem;
    }

    .footer-links a:hover {
        color: #ffc107;
        transform: translateX(3px);
    }

    .footer-links i {
        font-size: 0.7rem;
    }

    .contact-info {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .contact-info li {
        margin-bottom: 0.6rem;
        display: flex;
        align-items: start;
        gap: 0.6rem;
        color: rgba(255,255,255,0.85);
        font-size: 0.9rem;
    }

    .contact-info i {
        font-size: 0.9rem;
        margin-top: 0.1rem;
        color: #ffc107;
        min-width: 16px;
    }

    .social-links {
        display: flex;
        gap: 0.6rem;
        margin-top: 1rem;
    }

    .social-link {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .social-link:hover {
        background: #ffc107;
        color: #2a8fe1;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(255,193,7,0.4);
    }

    .footer-bottom {
        border-top: 1px solid rgba(255,255,255,0.2);
        margin-top: 2rem;
        padding-top: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: rgba(255,255,255,0.85);
    }

    .footer-bottom strong {
        color: white;
    }

    .footer-bottom .fa-heart {
        color: #ff6b6b;
        animation: heartbeat 1.5s ease infinite;
    }

    @keyframes heartbeat {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.1); }
        50% { transform: scale(1); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-footer {
            padding: 2rem 0 1rem 0;
        }

        .footer-brand h4 {
            font-size: 1.2rem;
        }

        .footer-title {
            font-size: 0.95rem;
            margin-top: 1rem;
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
            gap: 0.3rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
        }

        .social-links {
            justify-content: flex-start;
        }
    }

    /* Smooth transitions */
    .footer-links a,
    .social-link {
        -webkit-tap-highlight-color: transparent;
    }
</style>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @yield('scripts')

</body>
</html>