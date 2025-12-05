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
<style>
    <!-- Custom CSS -->
    /* Layout com Sidebar (autenticado) */
body.sidebar-mini {
    font-family: 'Nunito', sans-serif;
}

/* Sidebar styling moderna e elegante */
.main-sidebar {
    background: linear-gradient(180deg, #1a5490 0%, #0d3a6b 50%, #062340 100%);
    box-shadow: 4px 0 20px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
}

/* Efeito de luz de fundo suave */
.main-sidebar::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(74, 166, 255, 0.1) 0%, transparent 70%);
    opacity: 0.5;
}

.brand-link {
    background: linear-gradient(135deg, rgba(255,193,7,0.15) 0%, rgba(255,255,255,0.05) 100%);
    border-bottom: 2px solid rgba(255,193,7,0.3);
    padding: 1.2rem 1rem;
    position: relative;
    transition: all 0.3s ease;
}

.brand-link:hover {
    background: linear-gradient(135deg, rgba(255,193,7,0.25) 0%, rgba(255,255,255,0.1) 100%);
}

.brand-link .brand-image {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    object-fit: contain;
    background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
    padding: 5px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    border: 2px solid rgba(255,193,7,0.4);
    transition: all 0.3s ease;
}

.brand-link:hover .brand-image {
    box-shadow: 0 6px 15px rgba(255,193,7,0.4);
}

.brand-link .brand-text {
    color: white !important;
    font-weight: 800;
    font-size: 1.4rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    letter-spacing: 1px;
}

/* Sidebar menu moderna */
.nav-sidebar {
    padding-top: 0.5rem;
}

.nav-sidebar .nav-item {
    margin-bottom: 4px;
}

.nav-sidebar .nav-item .nav-link {
    color: rgba(255,255,255,0.85);
    border-radius: 12px;
    margin: 4px 10px;
    padding: 14px 18px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    font-weight: 500;
    border: 1px solid transparent;
}

.nav-sidebar .nav-item .nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background: #ffc107;
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.nav-sidebar .nav-item .nav-link:hover {
    background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(74, 166, 255, 0.2) 100%);
    color: white;
    border-color: rgba(255,255,255,0.2);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.nav-sidebar .nav-item .nav-link:hover::before {
    transform: scaleY(1);
}

.nav-sidebar .nav-item .nav-link.active {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: #0d3a6b;
    font-weight: 700;
    border-color: rgba(255,193,7,0.5);
    box-shadow: 0 6px 20px rgba(255,193,7,0.4), inset 0 1px 3px rgba(255,255,255,0.3);
}

.nav-sidebar .nav-item .nav-link.active::before {
    transform: scaleY(1);
    background: #0d3a6b;
}

.nav-sidebar .nav-item .nav-link i {
    margin-right: 12px;
    width: 24px;
    text-align: center;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

/* User panel na sidebar  */
.user-panel {
    padding: 1.2rem 1rem;
    border-bottom: 2px solid rgba(255,255,255,0.15);
    background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(74, 166, 255, 0.1) 100%);
    margin: 0.5rem 0.5rem 1rem 0.5rem;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.user-panel:hover {
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(74, 166, 255, 0.15) 100%);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.user-panel .image i {
    color: #ffc107;
    filter: drop-shadow(0 2px 4px rgba(255,193,7,0.4));
    transition: all 0.3s ease;
}

.user-panel:hover .image i {
    color: #ffdc7f;
}

.user-panel .info {
    color: white;
    padding: 0.5rem 0 0.5rem 0.5rem;
}

.user-panel .info a {
    color: white;
    font-weight: 700;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
}

.user-panel .info a:hover {
    color: #ffc107;
}

/* Header na sidebar para seções */
.nav-header {
    color: rgba(255,193,7,0.8) !important;
    margin-top: 15px;
    padding: 8px 18px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    border-left: 3px solid #ffc107;
    margin-left: 10px;
    background: linear-gradient(90deg, rgba(255,193,7,0.1) 0%, transparent 100%);
}

/* Mobile responsive */
@media (max-width: 767px) {
    .main-sidebar {
        margin-left: -250px;
    }
    
    .sidebar-open .main-sidebar {
        margin-left: 0;
    }
}
        /* Footer Styles */
        .modern-footer, .main-footer {
            background: linear-gradient(135deg, #1a5490 0%, #4da6ff 100%);
            color: white;
            position: relative;
            overflow: hidden;
            margin: 0;
            padding: 2.5rem 0 1rem 0;
        }

        .modern-footer::before, .main-footer::before {
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

        @media (max-width: 768px) {
            .modern-footer, .main-footer {
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
    </style>
</head>

@guest
    <!-- Layout original para não autenticados -->
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
                        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                            <div class="footer-brand">
                                <h4><i class="fas fa-graduation-cap"></i> EUREKA</h4>
                                <p>Centro de Explicações</p>
                                <div class="social-links">
                                    <a href="#" class="social-link" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 mb-3 mb-lg-0">
                            <h6 class="footer-title">Links Rápidos</h6>
                            <ul class="footer-links">
                                <li><a href="{{ route('login') }}"><i class="fas fa-angle-right"></i> Login</a></li>
                                <li><a href="{{ route('register') }}"><i class="fas fa-angle-right"></i> Registro</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                            <h6 class="footer-title">Contacto</h6>
                            <ul class="contact-info">
                                <li><i class="fas fa-map-marker-alt"></i> Largo das Neves, Vila de Punhe<br>4905-665 Viana do Castelo</li>
                                <li><i class="fas fa-envelope"></i> contato@eureka.pt</li>
                                <li><i class="fas fa-clock"></i> Seg-Sex: 9h-18h</li>
                            </ul>
                        </div>
                        <div class="col-12">
                            <div class="footer-bottom">
                                <span>&copy; {{ date('Y') }} <strong>Sistema EUREKA</strong>. Todos os direitos reservados.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
@else
    <!-- Layout com sidebar para autenticados -->
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <!-- Navbar superior (apenas toggle e user) -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light logged-navbar">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('password.change') }}">
                                <i class="fas fa-key"></i> Alterar Senha
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                <i class="fas fa-sign-out-alt"></i> Terminar Sessão
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="{{ url('/') }}" class="brand-link">
                    <img src="{{ asset('images/fotop2.jpg') }}" alt="Eureka Logo" class="brand-image">
                    <span class="brand-text">EUREKA</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <i class="fas fa-user-circle fa-2x" style="color: white;"></i>
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                            
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                                    <i class="fas fa-lightbulb"></i>
                                    <p>Eureka</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('explicacoes.index') }}" class="nav-link {{ request()->routeIs('explicacoes.*') ? 'active' : '' }}">
                                    <i class="fas fa-graduation-cap"></i>
                                    <p>Explicações</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('mensagens.index') }}" class="nav-link {{ request()->routeIs('mensagens.*') ? 'active' : '' }}">
                                    <i class="fas fa-comments"></i>
                                    <p>
                                        Mensagens
                                        @php
                                            $totalNaoLidas = auth()->user()->grupos()
                                                ->where('ativo', true)
                                                ->get()
                                                ->sum(function($grupo) {
                                                    return $grupo->mensagensNaoLidas(auth()->id());
                                                });
                                        @endphp
                                        @if($totalNaoLidas > 0)
                                            <span class="badge badge-danger right">{{ $totalNaoLidas }}</span>
                                        @endif
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('estatisticas') }}" class="nav-link {{ request()->routeIs('estatisticas') ? 'active' : '' }}">
                                    <i class="fas fa-chart-bar"></i>
                                    <p>Estatísticas</p>
                                </a>
                            </li>

                            @if(auth()->user()->role === 'admin')
                                <li class="nav-header" style="color: rgba(255,255,255,0.6); margin-top: 10px;">ADMINISTRAÇÃO</li>
                                
                                <li class="nav-item">
                                    <a href="{{ url('/admin/grupos') }}" class="nav-link {{ request()->is('admin/grupos*') ? 'active' : '' }}">
                                        <i class="fas fa-users"></i>
                                        <p>Gerir Grupos</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ url('/admin') }}" class="nav-link {{ request()->is('admin') && !request()->is('admin/*') ? 'active' : '' }}">
                                        <i class="fas fa-cog"></i>
                                        <p>Dashboard Admin</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                        <i class="fas fa-users-cog"></i>
                                        <p>Gestão de Utilizadores</p>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </nav>
                </div>
            </aside>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Content Header -->
                <div class="content-header">
                    <div class="container-fluid">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Main content -->
                <section class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </section>
            </div>

            <!-- Footer -->
            <footer class="main-footer">
                <div class="footer-content">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                <div class="footer-brand">
                                    <h4><i class="fas fa-graduation-cap"></i> EUREKA</h4>
                                    <p>Centro de Explicações</p>
                                    <div class="social-links">
                                        <a href="#" class="social-link" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3 mb-lg-0">
                                <h6 class="footer-title">Links Rápidos</h6>
                                <ul class="footer-links">
                                    <li><a href="{{ route('home') }}"><i class="fas fa-angle-right"></i> Início</a></li>
                                    <li><a href="{{ route('explicacoes.index') }}"><i class="fas fa-angle-right"></i> Explicações</a></li>
                                    <li><a href="{{ route('estatisticas') }}"><i class="fas fa-angle-right"></i> Estatísticas</a></li>
                                    <li><a href="{{ route('mensagens.index') }}"><i class="fas fa-angle-right"></i> Mensagens</a></li>
                                </ul>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
                                <h6 class="footer-title">Contacto</h6>
                                <ul class="contact-info">
                                    <li><i class="fas fa-map-marker-alt"></i> Largo das Neves, Vila de Punhe<br>4905-665 Viana do Castelo</li>
                                    <li><i class="fas fa-envelope"></i> contato@eureka.pt</li>
                                    <li><i class="fas fa-clock"></i> Seg-Sex: 9h-18h</li>
                                </ul>
                            </div>
                            <div class="col-12">
                                <div class="footer-bottom">
                                    <span>&copy; {{ date('Y') }} <strong>Sistema EUREKA</strong>. Todos os direitos reservados.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
@endguest

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @yield('scripts')

</body>
</html>