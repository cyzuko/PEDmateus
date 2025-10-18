@extends('layouts.app')

@section('title', 'Sistema de Gestão de Explicações')

@section('content')

<style>
    /* Importação de fontes */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

/* Reset e Base */
* {
    font-family: 'Inter', sans-serif;
}

:root {
    --primary-color: #2a8fe1;
    --success-color: #198754;
    --info-color: #0dcaf0;
    --dark-color: #212529;
    --purple-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

html {
    scroll-behavior: smooth;
}

h1, h2, h3, h4, h5, h6 {
    font-weight: 700;
    letter-spacing: -0.5px;
}

p {
    font-weight: 400;
    font-size: 1.05rem;
}

/* Hero Section - OVERRIDE AdminLTE */
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    padding: 120px 0 100px !important;
    position: relative !important;
    overflow: hidden !important;
    margin: 0 !important;
    margin-left: -15px !important;
    margin-right: -15px !important;
    width: calc(100% + 30px) !important;
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
    color: white !important;
}

.hero-subtitle {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    opacity: 0.95;
    line-height: 1.6;
    color: white !important;
}

.hero-cta {
    margin-bottom: 2rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
}

.btn-hero {
    padding: 12px 30px;
    font-weight: 600;
    border-radius: 30px;
    transition: all 0.3s ease;
    text-decoration: none;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    border: none;
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
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
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.btn-hero-primary {
    background: #fff !important;
    color: #4a6bff !important;
}

.btn-hero-secondary {
    background: rgba(255, 255, 255, 0.2) !important;
    color: #fff !important;
    border: 2px solid #fff !important;
}

.btn-hero-secondary:hover {
    background: rgba(255, 255, 255, 0.3) !important;
}

.hero-scroll-indicator {
    margin-top: 2rem;
}

.hero-scroll-indicator a {
    color: white !important;
    font-size: 1.5rem;
    animation: bounce 2s infinite;
    display: inline-block;
    text-decoration: none !important;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-20px); }
    60% { transform: translateY(-10px); }
}

/* Floating Elements */
.floating-elements {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 1;
    pointer-events: none;
}

.floating-element {
    position: absolute;
    border-radius: 50%;
    animation: float 8s infinite ease-in-out;
    border: 2px solid rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(10px);
}

.floating-element:nth-child(1) {
    width: 150px;
    height: 150px;
    top: 20%;
    left: 10%;
    background: rgba(100, 200, 255, 0.6);
    box-shadow: 0 0 60px rgba(100, 200, 255, 0.9);
}

.floating-element:nth-child(2) {
    width: 100px;
    height: 100px;
    top: 60%;
    left: 20%;
    background: rgba(150, 100, 255, 0.6);
    box-shadow: 0 0 60px rgba(150, 100, 255, 0.9);
    animation: float 6s infinite ease-in-out;
}

.floating-element:nth-child(3) {
    width: 200px;
    height: 200px;
    top: 30%;
    right: 15%;
    background: rgba(100, 200, 255, 0.6);
    box-shadow: 0 0 60px rgba(100, 200, 255, 0.9);
    animation: float 10s infinite ease-in-out;
}

.floating-element:nth-child(4) {
    width: 80px;
    height: 80px;
    bottom: 20%;
    right: 25%;
    background: rgba(150, 100, 255, 0.6);
    box-shadow: 0 0 60px rgba(150, 100, 255, 0.9);
    animation: float 7s infinite ease-in-out;
}

.floating-element:nth-child(5) {
    width: 120px;
    height: 120px;
    bottom: 40%;
    left: 40%;
    background: rgba(200, 150, 255, 0.6);
    box-shadow: 0 0 60px rgba(200, 150, 255, 0.9);
    animation: float 9s infinite ease-in-out;
}

/* Números Flutuantes */
.floating-number {
    position: absolute;
    font-size: 2.5rem;
    font-weight: 800;
    color: rgba(255, 255, 255, 0.4);
    animation: float 8s infinite ease-in-out;
    text-shadow: 0 0 30px rgba(100, 200, 255, 0.6);
}

.floating-number:nth-child(6) {
    top: 15%;
    right: 8%;
    animation: float 7s infinite ease-in-out;
    color: rgba(100, 200, 255, 0.5);
    text-shadow: 0 0 30px rgba(100, 200, 255, 0.8);
}

.floating-number:nth-child(7) {
    top: 50%;
    right: 5%;
    animation: float 9s infinite ease-in-out;
    color: rgba(150, 100, 255, 0.5);
    text-shadow: 0 0 30px rgba(150, 100, 255, 0.8);
}

.floating-number:nth-child(8) {
    bottom: 15%;
    left: 8%;
    animation: float 6s infinite ease-in-out;
    color: rgba(200, 150, 255, 0.5);
    text-shadow: 0 0 30px rgba(200, 150, 255, 0.8);
}

.floating-number:nth-child(9) {
    top: 35%;
    left: 5%;
    animation: float 11s infinite ease-in-out;
    color: rgba(100, 200, 255, 0.5);
    text-shadow: 0 0 30px rgba(100, 200, 255, 0.8);
}

.floating-number:nth-child(10) {
    bottom: 30%;
    right: 10%;
    animation: float 8s infinite ease-in-out;
    color: rgba(150, 100, 255, 0.5);
    text-shadow: 0 0 30px rgba(150, 100, 255, 0.8);
}

@keyframes float {
    0% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
    100% { transform: translateY(0) rotate(0deg); }
}

/* Logo Rotativo */
.logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 2rem !important;
    position: relative;
    z-index: 2;
    perspective: 1000px;
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
    color: white !important;
    text-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    z-index: 2;
    position: relative;
}

@keyframes rotateY360 {
    from { transform: rotateY(0deg); }
    to { transform: rotateY(360deg); }
}

@keyframes rotate360 {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3); }
    50% { box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4); }
}

.rotating-logo-large:hover {
    animation: rotateY360 2s linear infinite, pulse 1s ease-in-out infinite;
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

/* Section Styles */
.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    color: #333;
    text-align: center;
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
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 1.5rem;
    text-align: center;
}

.section-divider {
    height: 4px;
    width: 70px;
    background: #4a6bff;
    margin: 0 auto 2rem;
    border-radius: 2px;
}

/* Location Section */
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

/* Explicadoras Section */
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

/* Centro Photos Section */
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

/* Animations */
.animate-fade-in-up {
    animation: fadeInUp 0.8s ease forwards;
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

/* Responsive */
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
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-number">π</div>
         <div class="floating-number">+</div>
         <div class="floating-number">x</div>
        <div class="floating-number">√2</div>
        <div class="floating-number">79</div>
        <div class="floating-number">5</div>
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
                    Gerir horários e explicações nunca foi tão simples, eficiente e intuitivo.
                </p>
                
                <div class="hero-cta animate-fade-in-up">
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

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
@endpush