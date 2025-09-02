@extends('layouts.app')

@section('title', 'Sistema de Gestão de Explicações')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    
    <div class="container hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <h1 class="hero-title animate-fade-in-up">
                    <i class="fas fa-graduation-cap me-3"></i>
                    Sistema de Gestão de Explicações teste
                </h1>
                <p class="hero-subtitle animate-fade-in-up">
                    A plataforma mais completa para centros de explicações em Portugal. 
                    Gerir horários, alunos, professores, pagamentos e relatórios nunca foi tão simples e eficiente.
                </p>
                <div class="hero-cta animate-fade-in-up">
                    <a href="#funcionalidades" class="btn btn-hero btn-hero-primary">
                        <i class="fas fa-rocket me-2"></i>
                        Descobrir Funcionalidades
                    </a>
                   
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <!-- Stats Section -->
        <section class="mb-5">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card animate-fade-in-up">
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stats-number">1500+</div>
                        <div class="stats-label">Alunos Ativos</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card animate-fade-in-up" style="animation-delay: 0.1s;">
                        <div class="stats-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="stats-number">150+</div>
                        <div class="stats-label">Professores Registados</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card animate-fade-in-up" style="animation-delay: 0.2s;">
                        <div class="stats-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stats-number">8000+</div>
                        <div class="stats-label">Explicações Realizadas</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card animate-fade-in-up" style="animation-delay: 0.3s;">
                        <div class="stats-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stats-number">4.9/5</div>
                        <div class="stats-label">Avaliação Média</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="funcionalidades" class="mb-5">
            <div class="text-center mb-5">
                <h2 class="section-title">Funcionalidades Principais</h2>
               
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="feature-card animate-fade-in-left">
                        <div class="feature-icon primary">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="feature-title">Gestão Inteligente de Horários</h3>
                        <p class="feature-description">
                            Sistema avançado de agendamento com calendário interativo, deteção automática de conflitos, 
                            sincronização com Google Calendar e notificações push em tempo real.
                        </p>
                        <div class="feature-badges">
                            <span class="badge bg-primary feature-badge">Calendário Interativo</span>
                            <span class="badge bg-primary feature-badge">Deteção de Conflitos</span>
                            <span class="badge bg-primary feature-badge">Notificações Push</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="feature-card animate-fade-in-right">
                        <div class="feature-icon success">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3 class="feature-title">Base de Dados de Alunos</h3>
                        <p class="feature-description">
                            Perfis detalhados com histórico académico, progressão personalizada, 
                            comunicação com encarregados de educação e relatórios automáticos.
                        </p>
                        <div class="feature-badges">
                            <span class="badge bg-success feature-badge">Perfis Detalhados</span>
                            <span class="badge bg-success feature-badge">Histórico Académico</span>
                            <span class="badge bg-success feature-badge">Portal dos Pais</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="feature-card animate-fade-in-left">
                        <div class="feature-icon info">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                        <h3 class="feature-title">Sistema Financeiro</h3>
                        <p class="feature-description">
                            Controlo total sobre faturação automática, pagamentos online, 
                            gestão de mensalidades e relatórios fiscais detalhados.
                        </p>
                        <div class="feature-badges">
                            <span class="badge bg-info feature-badge">Faturação Automática</span>
                            <span class="badge bg-info feature-badge">Pagamentos Online</span>
                            <span class="badge bg-info feature-badge">Relatórios Fiscais</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="feature-card animate-fade-in-right">
                        <div class="feature-icon warning">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Dashboard e Analytics</h3>
                        <p class="feature-description">
                            Dashboard executivo com métricas em tempo real, relatórios de desempenho 
                            e indicadores KPI do centro.
                        </p>
                        <div class="feature-badges">
                            <span class="badge bg-warning feature-badge text-dark">Dashboard Executivo</span>
                            <span class="badge bg-warning feature-badge text-dark">Métricas em Tempo Real</span>
                            <span class="badge bg-warning feature-badge text-dark">KPIs Personalizados</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section class="mb-5">
            <div class="text-center mb-5">
                <h2 class="section-title">Vantagens Competitivas</h2>
                
            <div class="row">
                <div class="col-md-4">
                    <div class="benefit-item animate-fade-in-up">
                        <div class="benefit-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4 class="benefit-title">Poupança de Tempo</h4>
                        <p class="benefit-description">
                            Automatize tarefas repetitivas como agendamentos, lembretes e relatórios. 
                            Concentre-se no ensino e no crescimento do negócio.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="benefit-item animate-fade-in-up" style="animation-delay: 0.1s;">
                        <div class="benefit-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4 class="benefit-title">100% Mobile & Cloud</h4>
                        <p class="benefit-description">
                            Aceda ao sistema em qualquer lugar. Interface otimizada para todos os 
                            dispositivos com sincronização instantânea.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="benefit-item animate-fade-in-up" style="animation-delay: 0.2s;">
                        <div class="benefit-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="benefit-title">Segurança & RGPD</h4>
                        <p class="benefit-description">
                            Conformidade total com RGPD, encriptação de dados e backups automáticos. 
                            Os seus dados estão sempre protegidos.
                        </p>
                    </div>
                </div>
            </div>
        </section>



@endsection

@push('styles')
<style>
        * {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary-color: #2a8fe1;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --purple-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Hero Section */
        .hero-section {
            background: var(--purple-gradient);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
            margin: -1.5rem -15px 0 -15px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.1) 0%, transparent 50%),
                       radial-gradient(circle at 70% 70%, rgba(255,255,255,0.08) 0%, transparent 50%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.4rem;
            font-weight: 300;
            margin-bottom: 2.5rem;
            opacity: 0.95;
            line-height: 1.6;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-hero {
            padding: 1rem 2.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
            text-decoration: none;
        }

        .btn-hero-primary {
            background: linear-gradient(45deg, #ffffff, #f8f9fa);
            color: var(--primary-color);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border: 2px solid rgba(255,255,255,0.8);
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.1);
            border-color: white;
            color: white;
        }

        /* Main Content */
        .main-content {
            background: white;
            border-radius: 30px 30px 0 0;
            margin: -3rem -15px 0 -15px;
            position: relative;
            z-index: 10;
            padding: 4rem 15px 2rem 15px;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.4s ease;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .stats-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, var(--primary-color), var(--info-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .stats-label {
            font-size: 1.1rem;
            font-weight: 500;
            color: #6c757d;
        }

        /* Feature Cards */
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.4s ease;
            height: 100%;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 90px;
            height: 90px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }

        .feature-icon.primary {
            background: linear-gradient(135deg, var(--primary-color), #4da6ff);
            color: white;
            box-shadow: 0 8px 20px rgba(42,143,225,0.3);
        }

        .feature-icon.success {
            background: linear-gradient(135deg, var(--success-color), #20c997);
            color: white;
            box-shadow: 0 8px 20px rgba(25,135,84,0.3);
        }

        .feature-icon.info {
            background: linear-gradient(135deg, var(--info-color), #54d3f0);
            color: white;
            box-shadow: 0 8px 20px rgba(13,202,240,0.3);
        }

        .feature-icon.warning {
            background: linear-gradient(135deg, var(--warning-color), #ffd43b);
            color: #495057;
            box-shadow: 0 8px 20px rgba(255,193,7,0.3);
        }

        .feature-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark-color);
            position: relative;
            z-index: 2;
        }

        .feature-description {
            color: #6c757d;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .feature-badges {
            position: relative;
            z-index: 2;
        }

        .feature-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            margin: 0.2rem;
            font-weight: 500;
        }

        /* Section Titles */
        .section-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: var(--dark-color);
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
            border-radius: 2px;
        }

        .section-subtitle {
            font-size: 1.3rem;
            color: #6c757d;
            margin-bottom: 4rem;
            text-align: center;
            font-weight: 300;
            line-height: 1.6;
        }

        /* Benefits Section */
        .benefit-item {
            text-align: center;
            margin-bottom: 3rem;
        }

        .benefit-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), #4da6ff);
            color: white;
            box-shadow: 0 8px 25px rgba(42,143,225,0.3);
        }

        .benefit-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--dark-color);
        }

        .benefit-description {
            color: #6c757d;
            line-height: 1.6;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #4da6ff 100%);
            color: white;
            padding: 5rem 0;
            text-align: center;
            margin: 4rem -15px 0 -15px;
            position: relative;
            overflow: hidden;
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        /* Floating Elements */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            top: 0;
            left: 0;
            z-index: 1;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 120px;
            height: 120px;
            top: 15%;
            left: 8%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 80px;
            height: 80px;
            top: 50%;
            right: 10%;
            animation-delay: 3s;
        }

        .floating-element:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 25%;
            left: 15%;
            animation-delay: 6s;
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg); 
                opacity: 0.7;
            }
            50% { 
                transform: translateY(-30px) rotate(180deg); 
                opacity: 1;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-fade-in-left {
            animation: fadeInLeft 0.8s ease-out;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .btn-hero {
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
            
            .main-content {
                padding: 3rem 15px 1rem 15px;
            }
            
            .feature-card {
                padding: 2rem;
            }
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Additional hover effects */
        .bg-light.rounded-4:hover {
            background-color: #e9ecef !important;
            transition: background-color 0.3s ease;
        }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll para links internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    console.log('Homepage scripts carregados com sucesso');
});
</script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary-color: #2a8fe1;
            --success-color: #198754;
            --info-color: #0dcaf0;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --purple-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            background: var(--purple-gradient);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            background: var(--purple-gradient);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.1) 0%, transparent 50%),
                       radial-gradient(circle at 70% 70%, rgba(255,255,255,0.08) 0%, transparent 50%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            background: linear-gradient(45deg, #ffffff, #e0e7ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            font-weight: 300;
            margin-bottom: 2.5rem;
            opacity: 0.95;
            line-height: 1.6;
        }

        .hero-cta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-hero {
            padding: 1rem 2.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-hero:hover::before {
            left: 100%;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.3);
        }

        .btn-hero-primary {
            background: linear-gradient(45deg, #ffffff, #f8f9fa);
            color: var(--primary-color);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border: 2px solid rgba(255,255,255,0.8);
        }

        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.1);
            border-color: white;
        }

        /* Main Content */
        .main-content {
            background: white;
            border-radius: 30px 30px 0 0;
            margin-top: -3rem;
            position: relative;
            z-index: 10;
            padding: 4rem 0 2rem 0;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.4s ease;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .stats-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(45deg, var(--primary-color), var(--info-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .stats-label {
            font-size: 1.1rem;
            font-weight: 500;
            color: #6c757d;
        }

        /* Feature Cards */
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.4s ease;
            height: 100%;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(42,143,225,0.05) 0%, transparent 70%);
            transition: all 0.4s ease;
            transform: scale(0);
        }

        .feature-card:hover::before {
            transform: scale(1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 90px;
            height: 90px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }

        .feature-icon.primary {
            background: linear-gradient(135deg, var(--primary-color), #4da6ff);
            color: white;
            box-shadow: 0 8px 20px rgba(42,143,225,0.3);
        }

        .feature-icon.success {
            background: linear-gradient(135deg, var(--success-color), #20c997);
            color: white;
            box-shadow: 0 8px 20px rgba(25,135,84,0.3);
        }

        .feature-icon.info {
            background: linear-gradient(135deg, var(--info-color), #54d3f0);
            color: white;
            box-shadow: 0 8px 20px rgba(13,202,240,0.3);
        }

        .feature-icon.warning {
            background: linear-gradient(135deg, var(--warning-color), #ffd43b);
            color: #495057;
            box-shadow: 0 8px 20px rgba(255,193,7,0.3);
        }

        .feature-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark-color);
            position: relative;
            z-index: 2;
        }

        .feature-description {
            color: #6c757d;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .feature-badges {
            position: relative;
            z-index: 2;
        }

        .feature-badge {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            margin: 0.2rem;
            font-weight: 500;
        }

        /* Section Titles */
        .section-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: var(--dark-color);
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
            border-radius: 2px;
        }

        .section-subtitle {
            font-size: 1.3rem;
            color: #6c757d;
            margin-bottom: 4rem;
            text-align: center;
            font-weight: 300;
            line-height: 1.6;
        }

        /* Benefits Section */
        .benefit-item {
            text-align: center;
            margin-bottom: 3rem;
        }

        .benefit-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), #4da6ff);
            color: white;
            box-shadow: 0 8px 25px rgba(42,143,225,0.3);
        }

        .benefit-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--dark-color);
        }

        .benefit-description {
            color: #6c757d;
            line-height: 1.6;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, #4da6ff 100%);
            color: white;
            padding: 5rem 0;
            text-align: center;
            margin-top: 4rem;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,0 1000,50 0,100"/></svg>');
            z-index: 1;
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-fade-in-left {
            animation: fadeInLeft 0.8s ease-out;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out;
        }

        /* Floating Elements */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            top: 0;
            left: 0;
            z-index: 1;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 120px;
            height: 120px;
            top: 15%;
            left: 8%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 80px;
            height: 80px;
            top: 50%;
            right: 10%;
            animation-delay: 3s;
        }

        .floating-element:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 25%;
            left: 15%;
            animation-delay: 6s;
        }

        @keyframes float {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg); 
                opacity: 0.7;
            }
            50% { 
                transform: translateY(-30px) rotate(180deg); 
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .btn-hero {
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
            
            .main-content {
                padding: 3rem 0 1rem 0;
            }
            
            .feature-card {
                padding: 2rem;
            }
        }

        /* Loading Animation */
        .btn-hero.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-hero.loading::after {
            content: '';
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
