@extends('layouts.app')

@section('title', 'Sistema de Gestão de Explicações')

@section('styles')
<style>
    /* Importação de fontes */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    /* Estilos personalizados para a página home */
    body {
        font-family: 'Poppins', sans-serif;
        line-height: 1.7;
        color: #333;
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    
    p {
        font-weight: 400;
        font-size: 1.05rem;
    }
    
    .hero-section {
        background: linear-gradient(135deg, #4a6bff 0%, #2541b2 100%);
        color: white;
        padding: 120px 0 100px;
        position: relative;
        overflow: hidden;
    }
    
    .floating-elements {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }
    
    .floating-element {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .floating-element:nth-child(1) {
        width: 150px;
        height: 150px;
        top: 20%;
        left: 10%;
        animation: float 8s infinite ease-in-out;
    }
    
    .floating-element:nth-child(2) {
        width: 100px;
        height: 100px;
        top: 60%;
        left: 20%;
        animation: float 6s infinite ease-in-out;
    }
    
    .floating-element:nth-child(3) {
        width: 200px;
        height: 200px;
        top: 30%;
        right: 15%;
        animation: float 10s infinite ease-in-out;
    }
    
    .floating-element:nth-child(4) {
        width: 80px;
        height: 80px;
        bottom: 20%;
        right: 25%;
        animation: float 7s infinite ease-in-out;
    }
    
    .floating-element:nth-child(5) {
        width: 120px;
        height: 120px;
        bottom: 40%;
        left: 40%;
        animation: float 9s infinite ease-in-out;
    }
    
    @keyframes float {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
        100% { transform: translateY(0) rotate(0deg); }
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .hero-cta {
        margin-bottom: 2rem;
    }
    
    .btn-hero {
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 30px;
        transition: all 0.3s ease;
    }
    
    .btn-hero-primary {
        background: #fff;
        color: #4a6bff;
    }
    
    .btn-hero-primary:hover {
        background: #f0f0f0;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .btn-hero-secondary {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        border: 2px solid #fff;
    }
    
    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .hero-scroll-indicator {
        margin-top: 2rem;
    }
    
    .hero-scroll-indicator a {
        color: white;
        font-size: 1.5rem;
        animation: bounce 2s infinite;
        display: inline-block;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-20px); }
        60% { transform: translateY(-10px); }
    }
    
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #333;
    }
    
    .section-subtitle {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 1.5rem;
    }
    
    .section-divider {
        height: 4px;
        width: 70px;
        background: #4a6bff;
        margin: 0 auto 2rem;
        border-radius: 2px;
    }
    
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 30px 20px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .stats-icon {
        font-size: 2.5rem;
        color: #4a6bff;
        margin-bottom: 1rem;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .stats-label {
        color: #666;
        font-size: 1rem;
    }
    
    .feature-card {
        background: white;
        border-radius: 10px;
        padding: 30px;
        height: 100%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .hover-effect:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .feature-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
    }
    
    .feature-icon.primary {
        background: rgba(74, 107, 255, 0.1);
        color: #4a6bff;
    }
    
    .feature-icon.success {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .feature-icon.info {
        background: rgba(23, 162, 184, 0.1);
        color: #17a2b8;
    }
    
    .feature-icon.warning {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    
    .feature-title {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }
    
    .feature-description {
        color: #666;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .feature-badges {
        margin-bottom: 1rem;
    }
    
    .feature-badge {
        margin-right: 5px;
        margin-bottom: 5px;
        font-weight: 500;
        padding: 5px 10px;
    }
    
    .btn-link {
        color: #4a6bff;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-link:hover {
        color: #2541b2;
        text-decoration: none;
    }
    
    .benefit-item {
        text-align: center;
        padding: 30px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    
    .benefit-icon {
        font-size: 2.5rem;
        color: #4a6bff;
        margin-bottom: 1.5rem;
    }
    
    .benefit-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }
    
    .benefit-description {
        color: #666;
        line-height: 1.6;
    }
    

    
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease forwards;
        opacity: 0;
    }
    
    .animate-fade-in-left {
        animation: fadeInLeft 0.8s ease forwards;
        opacity: 0;
    }
    
    .animate-fade-in-right {
        animation: fadeInRight 0.8s ease forwards;
        opacity: 0;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    
    <div class="container hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <!-- Logo Rotativo -->
                <div class="logo-container mb-4 animate-fade-in-up">
                    <div class="rotating-logo-large">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
                
                <h1 class="hero-title animate-fade-in-up">
                    EUREKA
                </h1>
                
                <p class="hero-subtitle animate-fade-in-up">
                    Gerir horários e alunos nunca foi tão simples, eficiente e intuitivo.
                </p>
                
                <div class="hero-cta animate-fade-in-up">
                    <a href="#funcionalidades" class="btn btn-hero btn-hero-primary">
                        <i class="fas fa-rocket me-2"></i>
                        Descobrir Funcionalidades
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-hero btn-hero-secondary ms-3">
                        <i class="fas fa-user-plus me-2"></i>
                        Começar Agora
                    </a>
                </div>
                
                <div class="hero-scroll-indicator animate-fade-in-up">
                    <a href="#funcionalidades">
                        <i class="fas fa-chevron-down"></i>
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
        <section id="funcionalidades" class="mb-5 py-5">
            <div class="text-center mb-5">
                <h2 class="section-title">Funcionalidades Principais</h2>
                <p class="section-subtitle">Descubra como podemos transformar a gestão do seu centro de explicações</p>
                <div class="section-divider"></div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="feature-card animate-fade-in-left hover-effect">
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
                    <div class="feature-card animate-fade-in-right hover-effect">
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
                    <div class="feature-card animate-fade-in-left hover-effect">
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
                    <div class="feature-card animate-fade-in-right hover-effect">
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
        <section class="mb-5 py-5 bg-light">
            <div class="text-center mb-5">
                <h2 class="section-title">Vantagens Competitivas</h2>
                <p class="section-subtitle">Por que escolher o nosso sistema</p>
                <div class="section-divider"></div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="benefit-item animate-fade-in-up hover-lift">
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
                    <div class="benefit-item animate-fade-in-up hover-lift" style="animation-delay: 0.1s;">
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
                    <div class="benefit-item animate-fade-in-up hover-lift" style="animation-delay: 0.2s;">
                        <div class="benefit-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="benefit-title">Segurança & RGPD</h4>
                        <p class="benefit-description">
                            Dados protegidos com encriptação de nível bancário. 
                            Sistema totalmente compatível com o RGPD e outras regulamentações.
                        </p>
                    </div>
                </div>
            </div>
        </section>
         
            
<!-- Location Map Section -->
<section class="location-section py-5 mb-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Onde Estamos</h2>
            <p class="section-subtitle">Visite-nos no nosso centro em Vila de Punhe, Viana do Castelo</p>
            <div class="section-divider"></div>
        </div>
        
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="location-info">
                    <div class="info-card p-4 mb-3 bg-white rounded-4 shadow-sm">
                        <div class="d-flex align-items-start">
                            <div class="icon-wrapper-location me-3">
                                <i class="fas fa-map-marker-alt text-primary fs-3"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-2 fw-bold">Morada</h5>
                                <p class="text-muted mb-0">Largo das Neves, Vila de Punhe<br>4905-665 Viana do Castelo<br>Portugal</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-card p-4 mb-3 bg-white rounded-4 shadow-sm">
                        <div class="d-flex align-items-start">
                            <div class="icon-wrapper-location me-3">
                                <i class="fas fa-phone text-success fs-3"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-2 fw-bold">Telemóvel</h5>
                                <p class="text-muted mb-0">+351 966 952 680</p>
                                <p class="text-muted mb-0">+351 926 149 970</p>
                                <p class="text-muted mb-0 small">Chamada para rede móvel nacional</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-card p-4 mb-3 bg-white rounded-4 shadow-sm">
                        <div class="d-flex align-items-start">
                            <div class="icon-wrapper-location me-3">
                                <i class="fas fa-envelope text-info fs-3"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-2 fw-bold">Email</h5>
                                <p class="text-muted mb-0">geral@eureka.pt</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <a href="https://www.google.com/maps/dir//Largo+das+Neves,+4905-665+Vila+de+Punhe,+Viana+do+Castelo" target="_blank" class="btn btn-primary w-100">
                            <i class="fas fa-directions me-2"></i>
                            Como Chegar
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="map-container rounded-4 shadow-lg overflow-hidden">
                    <!-- Mapa do Google Maps - Largo das Neves, Vila de Punhe 4905-665 -->
                    <iframe 
                        src="https://www.google.com/maps?q=Largo+das+Neves,+4905-665+Vila+de+Punhe,+Viana+do+Castelo,+Portugal&output=embed" 
                        width="100%" 
                        height="500" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                
                <!-- Informação adicional sobre a localização -->
                <div class="mt-3 p-3 bg-light rounded-3">
                    <p class="mb-2 text-muted small">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        <strong>Localização central</strong> no Largo das Neves em Vila de Punhe
                    </p>
                    <p class="mb-2 text-muted small">
                        <i class="fas fa-car me-2 text-primary"></i>
                        Fácil acesso e estacionamento disponível nas proximidades
                    </p>
                    <p class="mb-0 text-muted small">
                        <i class="fas fa-bus me-2 text-primary"></i>
                        Bem servido de transportes públicos
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Explicadoras Section -->
<section class="explicadoras-section py-5 mb-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">As nossas Explicadoras</h2>
            <p class="section-subtitle">Conheça Sofia e Joana, as profissionais que fazem a diferença</p>
            <div class="section-divider"></div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card explicadora-card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="explicadora-nome fw-bold">Sofia Oliveira</h3>
                        <h6 class="explicadora-disciplina text-muted">Matemática e Física</h6>
                        <p class="explicadora-texto text-muted">
                            Licenciada em Matemática com especialização em ensino. Com mais de 10 anos de experiência, a Sofia tem ajudado centenas de alunos a alcançar excelentes resultados nos exames nacionais.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card explicadora-card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="explicadora-nome fw-bold">Joana Santos</h3>
                        <h6 class="explicadora-disciplina text-muted">Português e Inglês</h6>
                        <p class="explicadora-texto text-muted">
                            Mestre em Linguística e certificada em ensino de inglês como língua estrangeira. A Joana é conhecida pela sua abordagem dinâmica e pela capacidade de tornar o aprendizado de línguas divertido e eficaz.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

        <!-- Centro Photos Section -->
        <section class="centro-photos-section py-5 mb-5">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title">O nosso Centro</h2>
                    <p class="section-subtitle">Conheça o espaço onde acontece a magia do aprendizado</p>
                    <div class="section-divider"></div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card centro-photo-card border-0 shadow-sm h-100">
                            <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="card-img-top" alt="Sala de Estudo">
                            <div class="card-body">
                                <h5 class="card-title">Sala de Estudo Principal</h5>
                                <p class="card-text">Espaço amplo e iluminado, projetado para proporcionar o ambiente ideal para o aprendizado.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card centro-photo-card border-0 shadow-sm h-100">
                            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="card-img-top" alt="Biblioteca">
                            <div class="card-body">
                                <h5 class="card-title">Biblioteca e Recursos</h5>
                                <p class="card-text">Nossa biblioteca conta com uma vasta coleção de livros e materiais didáticos para todas as disciplinas.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-4">
                        <div class="card centro-photo-card border-0 shadow-sm h-100">
                            <img src="https://images.unsplash.com/photo-1581078426770-6d336e5de7bf?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" class="card-img-top" alt="Sala de Informática">
                            <div class="card-body">
                                <h5 class="card-title">Sala de Informática</h5>
                                <p class="card-text">Equipada com computadores modernos e softwares educacionais para aulas de programação e pesquisa.</p>
                            </div>
                        </div>
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
    /* Explicadoras Section Styles */
.explicadoras-section {
    background: #f8f9fa !important;
}

.explicadora-card {
    transition: all 0.3s ease;
    border-radius: 10px;
    background: white;
}

.explicadora-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.12) !important;
}

.explicadora-card .card-body {
    padding: 2rem !important;
}

.explicadora-nome {
    font-size: 1.4rem;
    font-weight: 700 !important;
    color: #212529;
    margin-bottom: 0.75rem !important;
    line-height: 1.3;
    display: block;
}

.explicadora-disciplina {
    font-size: 0.95rem;
    font-weight: 400;
    color: #6c757d !important;
    margin-top: 0;
    margin-bottom: 1.5rem !important;
    display: block;
}

.explicadora-texto {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #6c757d !important;
    margin-bottom: 0 !important;
}
        /* Estilos para a seção de fotos do centro */
        .centro-photo-card {
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .centro-photo-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }
        
        .centro-photo-card img {
            height: 200px;
            object-fit: cover;
            transition: all 0.5s ease;
        }
        
        .centro-photo-card:hover img {
            transform: scale(1.05);
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

/* Location Section Styles */
.location-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.location-info .info-card {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}

.location-info .info-card:hover {
    transform: translateX(10px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.location-info .icon-wrapper {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(42, 143, 225, 0.1);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.location-info .info-card:hover .icon-wrapper {
    transform: scale(1.1);
    background: rgba(42, 143, 225, 0.2);
}

.map-container {
    position: relative;
    animation: fadeIn 1s ease-in;
    border: 3px solid rgba(42, 143, 225, 0.1);
}

.map-container iframe {
    display: block;
    transition: all 0.3s ease;
}

.map-container:hover {
    border-color: rgba(42, 143, 225, 0.3);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@media (max-width: 991px) {
    .map-container {
        height: 400px;
    }
    
    .map-container iframe {
        height: 400px;
    }
    
    .location-info .info-card:hover {
        transform: translateY(-5px);
    }
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
/* Location Section - Extra spacing for icons */
.icon-wrapper-location {
    width: 60px;
    height: 60px;
    min-width: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(42, 143, 225, 0.1);
    border-radius: 12px;
    transition: all 0.3s ease;
    margin-right: 20px !important;
}

.location-info .info-card:hover .icon-wrapper-location {
    transform: scale(1.1);
    background: rgba(42, 143, 225, 0.2);
}

.location-info .info-card .d-flex {
    gap: 20px;
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

        /* botaorodar */
/* Logo Rotativo no Hero - Rotação de Lado (3D) */
.logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 2rem !important;
    position: relative;
    z-index: 2;
    perspective: 1000px; /* Adiciona perspectiva 3D */
}

.rotating-logo-large {
    width: 150px;
    height: 150px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
    border: 4px solid rgba(255, 255, 255, 0.4);
    animation: rotateY360 4s linear infinite, pulse 3s ease-in-out infinite;
    position: relative;
    transform-style: preserve-3d;
}

.rotating-logo-large::before {
    content: '';
    position: absolute;
    width: 170px;
    height: 170px;
    border-radius: 50%;
    border: 2px dashed rgba(255, 255, 255, 0.3);
    animation: rotate360 15s linear infinite;
}

.rotating-logo-large i {
    font-size: 4.5rem;
    color: white;
    text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    z-index: 2;
    position: relative;
}

/* Animação de Rotação de Lado (eixo Y) */
@keyframes rotateY360 {
    from {
        transform: rotateY(0deg);
    }
    to {
        transform: rotateY(360deg);
    }
}

/* Animação do anel externo */
@keyframes rotate360 {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
    }
    50% {
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }
}

/* Hover para acelerar */
.rotating-logo-large:hover {
    animation: rotateY360 2s linear infinite, pulse 1s ease-in-out infinite;
}

/* Responsive */
@media (max-width: 768px) {
    .rotating-logo-large {
        width: 120px;
        height: 120px;
    }
    
    .rotating-logo-large::before {
        width: 140px;
        height: 140px;
    }
    
    .rotating-logo-large i {
        font-size: 3.5rem;
    }
}

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
