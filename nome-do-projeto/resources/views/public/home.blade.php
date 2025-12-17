@extends('layouts.app')

@section('title', 'Sistema de Gestão de Explicações')

@section('content')

<style>
    /* Importação de fontes */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');


.alert-success,
.alert-success-custom,
#contactFormMessage.alert-success,
#contactFormMessage.alert-success-custom {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    height: 0 !important;
    overflow: hidden !important;
}

/* Reset e Base */
* {
    font-family: 'Inter', sans-serif;
}

:root {
    --primary-color: #2a8fe1;
    --success-color: #198754;
    --info-color: #0dcaf0;
    --dark-color: #212529;
    --purple-gradient: linear-gradient(135deg, #1e88e5 0%, #FFD700 100%);
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
    background: linear-gradient(135deg, #1e88e5 0%, #FFD700 100%) !important;
    color: white !important;
    padding: 120px 0 100px !important;
    position: relative !important;
    overflow: hidden !important;
    margin: 0 !important;
     margin-top: -40px !important;
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
    background: linear-gradient(45deg, #ffffff, #FFF9E6);
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
    color: #1e88e5 !important;
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
    background: rgba(30, 136, 229, 0.5);
    box-shadow: 0 0 60px rgba(30, 136, 229, 0.8);
}

.floating-element:nth-child(2) {
    width: 100px;
    height: 100px;
    top: 60%;
    left: 20%;
    background: rgba(255, 215, 0, 0.6);
    box-shadow: 0 0 60px rgba(255, 215, 0, 0.9);
    animation: float 6s infinite ease-in-out;
}

.floating-element:nth-child(3) {
    width: 200px;
    height: 200px;
    top: 30%;
    right: 15%;
    background: rgba(66, 165, 245, 0.5);
    box-shadow: 0 0 60px rgba(66, 165, 245, 0.8);
    animation: float 10s infinite ease-in-out;
}

.floating-element:nth-child(4) {
    width: 80px;
    height: 80px;
    bottom: 20%;
    right: 25%;
    background: rgba(255, 215, 0, 0.6);
    box-shadow: 0 0 60px rgba(255, 215, 0, 0.9);
    animation: float 7s infinite ease-in-out;
}

.floating-element:nth-child(5) {
    width: 120px;
    height: 120px;
    bottom: 40%;
    left: 40%;
    background: rgba(41, 182, 246, 0.5);
    box-shadow: 0 0 60px rgba(41, 182, 246, 0.8);
    animation: float 9s infinite ease-in-out;
}

/* Números Flutuantes */
.floating-number {
    position: absolute;
    font-size: 2.5rem;
    font-weight: 800;
    color: rgba(255, 255, 255, 0.4);
    animation: float 8s infinite ease-in-out;
    text-shadow: 0 0 30px rgba(30, 136, 229, 0.6);
}

.floating-number:nth-child(6) {
    top: 15%;
    right: 8%;
    animation: float 7s infinite ease-in-out;
    color: rgba(30, 136, 229, 0.5);
    text-shadow: 0 0 30px rgba(30, 136, 229, 0.8);
}

.floating-number:nth-child(7) {
    top: 50%;
    right: 5%;
    animation: float 9s infinite ease-in-out;
    color: rgba(255, 215, 0, 0.6);
    text-shadow: 0 0 30px rgba(255, 215, 0, 0.9);
}

.floating-number:nth-child(8) {
    bottom: 15%;
    left: 8%;
    animation: float 6s infinite ease-in-out;
    color: rgba(66, 165, 245, 0.5);
    text-shadow: 0 0 30px rgba(66, 165, 245, 0.8);
}

.floating-number:nth-child(9) {
    top: 35%;
    left: 5%;
    animation: float 11s infinite ease-in-out;
    color: rgba(30, 136, 229, 0.5);
    text-shadow: 0 0 30px rgba(30, 136, 229, 0.8);
}

.floating-number:nth-child(10) {
    bottom: 30%;
    right: 10%;
    animation: float 8s infinite ease-in-out;
    color: rgba(255, 215, 0, 0.6);
    text-shadow: 0 0 30px rgba(255, 215, 0, 0.9);
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
    animation: pulse 3s ease-in-out infinite;
    position: relative;
    overflow: hidden;
}

.rotating-logo-large::before {
    content: '';
    position: absolute;
    width: 170px;
    height: 170px;
    border-radius: 50%;
    border: 2px dashed rgba(255, 255, 255, 0.3);
    animation: rotate360 15s linear infinite;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.rotating-logo-large img {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    position: relative;
    z-index: 2;
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
    
    .rotating-logo-large img {
        width: 100px;
        height: 100px;
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
                <!-- Logo Rotativo com Imagem -->
                <div class="logo-container mb-4 animate-fade-in-up">
                    <div class="rotating-logo-large">
                        <img src="{{ asset('images/fotop1.jpg') }}" alt="Logo Eureka">
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
<!-- Explicadoras Section -->
<section class="explicadoras-section py-5 mb-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">As nossas Explicadoras</h2>
            <p class="section-subtitle">Conheça as profissionais que fazem a diferença no percurso académico dos nossos alunos</p>
            <div class="section-divider"></div>
        </div>
        
        <div class="row">
            <!-- Joana -->
            <div class="col-md-6 mb-4"> 
                <div class="card explicadora-card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="explicadora-nome fw-bold">📚 Explicadora Joana</h3>
                        <h6 class="explicadora-disciplina text-muted">Apoio ao Estudo | Matemática e Ciências - Ensino Básico</h6>
                        <p class="explicadora-texto text-muted mb-3">
                            <strong>Apoio ao estudo até ao 8º ano:</strong><br>
                            Especialista em acompanhamento escolar personalizado para alunos do 1º e 2º ciclo. 
                            Ajuda os alunos a desenvolver métodos de estudo eficazes, a consolidar conhecimentos 
                            e a ganhar autonomia na aprendizagem, com atenção especial à Matemática e Português.
                        </p>
                        <p class="explicadora-texto text-muted">
                            <strong>Explicações de Matemática e Ciências:</strong><br>
                            Dedicada ao ensino da Matemática e Ciências Naturais para alunos do 2º e 3º ciclo 
                            (5º ao 9º ano). Combina rigor científico com métodos pedagógicos dinâmicos, tornando 
                            conceitos complexos mais acessíveis e interessantes.
                        </p>
                        <div class="mt-3">
                            <span class="badge bg-primary me-2">1º-8º ano</span>
                            <span class="badge bg-success me-2">Matemática</span>
                            <span class="badge bg-success me-2">Ciências</span>
                            <span class="badge bg-info">Apoio ao Estudo</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sofia -->
            <div class="col-md-6 mb-4">
                <div class="card explicadora-card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="explicadora-nome fw-bold">🎓 Explicadora Sofia</h3>
                        <h6 class="explicadora-disciplina text-muted">Matemática - Ensino Secundário | Preparação para Exames</h6>
                        <p class="explicadora-texto text-muted mb-3">
                            <strong>Explicações de Matemática - Ensino Secundário:</strong><br>
                            Professora especializada em Matemática A e MACS para o Ensino Secundário (10º, 11º e 12º anos). 
                            Com formação académica sólida, oferece explicações focadas na compreensão profunda dos 
                            conteúdos programáticos e resolução de exercícios-tipo de exame.
                        </p>
                        <p class="explicadora-texto text-muted">
                            <strong>Preparação para Exames de Matemática:</strong><br>
                            Especialista na preparação intensiva para exames nacionais de Matemática do 9º ano 
                            (prova final) e 12º ano (exame nacional). Metodologia focada em treino intensivo, 
                            identificação de dificuldades e estratégias de resolução eficazes.
                        </p>
                        <div class="mt-3">
                            <span class="badge bg-warning text-dark me-2">Secundário</span>
                            <span class="badge bg-danger me-2">Exame 9º ano</span>
                            <span class="badge bg-danger me-2">Exame 12º ano</span>
                            <span class="badge bg-success">Matemática A/MACS</span>
                        </div>
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
                    <img src="{{ asset('images/sala1.jpg') }}" class="card-img-top" alt="sala1">
                    <div class="card-body">
                        <h5 class="card-title">Sala 1</h5>
                        <p class="card-text">Espaço amplo e iluminado, projetado para proporcionar o ambiente ideal para o aprendizado.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card centro-photo-card border-0 shadow-sm h-100">
                    <img src="{{ asset('images/sala2.jpg') }}" class="card-img-top" alt="sala2">
                    <div class="card-body">
                        <h5 class="card-title">Sala 2</h5>
                        <p class="card-text">Minimalista e simples mas aconchegadora e eficaz, ideal para um local de estudo.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card centro-photo-card border-0 shadow-sm h-100">
                    <img src="{{ asset('images/sala3.jpg') }}" class="card-img-top" alt="entrada">
                    <div class="card-body">
                        <h5 class="card-title">Centro</h5>
                        <p class="card-text">Elaborado ao longo de diversos anos, imensos alunos passaram por cá.</p>
                    </div>
                </div>
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


<!-- Contact CTA Section -->
<section class="contact-cta-section py-5 mb-5 position-relative overflow-hidden">
    <div class="cta-background"></div>
    <div class="cta-particles">
        <div class="particle">π</div>
        <div class="particle">∑</div>
        <div class="particle">∫</div>
        <div class="particle">√</div>
        <div class="particle">∞</div>
        <div class="particle">α</div>
        <div class="particle">β</div>
        <div class="particle">θ</div>
        <div class="particle">Δ</div>
        <div class="particle">±</div>
    </div>
    
    <div class="container position-relative" style="z-index: 2;">
        <div class="text-center mb-5">
            <div class="cta-icon-wrapper mx-auto mb-4">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h2 class="section-title text-white mb-3">Tem Dúvidas?</h2>
            <p class="section-subtitle text-white opacity-90 mb-0">Entre em contacto connosco e esclarecemos tudo</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="cta-card">
                    <div class="cta-card-glow"></div>
                    <div class="card-body p-4 p-md-5">
                        
                        
                        @if($errors->any())
                        <div class="alert alert-danger-custom alert-dismissible fade show mb-4" role="alert">
                            <div class="d-flex align-items-start">
                                <div class="alert-icon me-3">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong class="d-block mb-2">Atenção!</strong>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button type="button" class="btn-close btn-close-white ms-3" data-bs-dismiss="alert" aria-label="Fechar"></button>
                            </div>
                        </div>
                        @endif
                        
                        <div id="contactFormMessage" class="alert d-none mb-4"></div>
                        
                        <form id="contactForm" action="{{ route('contact.send') }}" method="POST">
                            @csrf
                            
                            <!-- Nome -->
                            <div class="mb-4">
                                <label for="contactNome" class="form-label text-white fw-semibold">
                                    <i class="fas fa-user me-2"></i>Nome Completo
                                </label>
                                <input type="text" 
                                       id="contactNome"
                                       name="nome" 
                                       class="form-control form-control-lg contact-input @error('nome') is-invalid @enderror" 
                                       placeholder="Escreva o seu nome"
                                       value="{{ old('nome') }}"
                                       required
                                       autocomplete="name">
                                @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="contactEmail" class="form-label text-white fw-semibold">
                                    <i class="fas fa-envelope me-2"></i>Endereço de Email
                                </label>
                                <input type="email" 
                                       id="contactEmail"
                                       name="email" 
                                       class="form-control form-control-lg contact-input @error('email') is-invalid @enderror" 
                                       placeholder="seuemail@exemplo.com"
                                       value="{{ old('email') }}"
                                       required
                                       autocomplete="email">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Mensagem -->
                            <div class="mb-4">
                                <label for="contactMensagem" class="form-label text-white fw-semibold">
                                    <i class="fas fa-comment-dots me-2"></i>A sua Mensagem
                                </label>
                                <textarea id="contactMensagem"
                                          name="mensagem" 
                                          rows="6" 
                                          class="form-control form-control-lg contact-input @error('mensagem') is-invalid @enderror" 
                                          placeholder="Escreva aqui a sua mensagem ou questão..."
                                          required
                                          maxlength="5000">{{ old('mensagem') }}</textarea>
                                <small class="text-white-50 d-block mt-2">
                                    <span id="charCount">0</span>/5000 caracteres
                                </small>
                                @error('mensagem')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Botão de Envio -->
                            <button type="submit" class="btn btn-light btn-lg w-100 cta-button" id="submitBtn">
                                <span class="btn-text">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Enviar Mensagem
                                </span>
                                <span class="btn-loading d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Enviando...
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-white small opacity-75 mb-0">
                <i class="fas fa-clock me-2"></i>
                Respondemos normalmente em até 24 horas
            </p>
        </div>
    </div>
</section>
<style>

   /* Melhorias nos Inputs do Formulário */
.contact-input {
    background: rgba(255, 255, 255, 0.95) !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    color: #212529 !important;
    border-radius: 12px !important;
    padding: 0.875rem 1.25rem !important;
    font-size: 1rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    position: relative;
    z-index: 100; /* Garante que inputs ficam acima de tudo */
}

.contact-input:focus {
    background: rgba(255, 255, 255, 1) !important;
    border-color: #FFD700 !important;
    box-shadow: 0 4px 16px rgba(255, 215, 0, 0.3) !important;
    outline: none !important;
}

.contact-input::placeholder {
    color: #6c757d !important;
    opacity: 0.7;
}

.contact-input:hover {
    border-color: rgba(255, 255, 255, 0.5) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

/* Textarea específico */
textarea.contact-input {
    resize: vertical;
    min-height: 150px;
    font-family: inherit;
    line-height: 1.6;
}

/* Labels melhorados */
.form-label {
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    letter-spacing: 0.3px;
}

/* Estados de Validação */
.contact-input.is-invalid {
    border-color: #dc3545 !important;
    background: rgba(255, 255, 255, 0.95) !important;
}

.contact-input.is-invalid:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 4px 16px rgba(220, 53, 69, 0.3) !important;
}

.invalid-feedback {
    display: block;
    color: #ffcccc !important;
    font-weight: 500;
    margin-top: 0.5rem;
    font-size: 0.9rem;
}

/* Botão de Loading */
.btn-loading {
    display: inline-block;
}

/* Alertas */
.alert {
    border-radius: 12px !important;
    border: none !important;
    padding: 1rem 1.25rem !important;
}

.alert-success {
    background: rgba(40, 167, 69, 0.95) !important;
    color: white !important;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.95) !important;
    color: white !important;
}

.alert ul {
    margin-bottom: 0;
    padding-left: 1.5rem;
}

.btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.btn-close:hover {
    opacity: 1;
}

/* Contador de caracteres */
.text-white-50 {
    color: rgba(255, 255, 255, 0.7) !important;
}

/* Contact CTA Section - Full Width */
.contact-cta-section {
    width: 100vw;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
}

/* CTA Background Animation */
.cta-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    background: linear-gradient(135deg, #1e88e5 0%, #FFD700 100%);
    z-index: 0;
}

.cta-background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(30, 136, 229, 0.2) 0%, transparent 50%);
    animation: gradientShift 8s ease-in-out infinite;
}

@keyframes gradientShift {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.1); }
}

/* Floating Math Symbols */
.cta-particles {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    overflow: hidden;
    z-index: 1;
}

.particle {
    position: absolute;
    color: rgba(255, 255, 255, 0.4);
    font-size: 2.5rem;
    font-weight: 700;
    animation: floatParticle 15s infinite ease-in-out;
    text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
}

.particle:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; animation-duration: 12s; font-size: 3rem; }
.particle:nth-child(2) { top: 60%; left: 85%; animation-delay: 2s; animation-duration: 10s; font-size: 2rem; }
.particle:nth-child(3) { top: 75%; left: 15%; animation-delay: 4s; animation-duration: 14s; font-size: 2.8rem; }
.particle:nth-child(4) { top: 25%; left: 75%; animation-delay: 1s; animation-duration: 11s; font-size: 2.3rem; }
.particle:nth-child(5) { top: 50%; left: 50%; animation-delay: 3s; animation-duration: 13s; font-size: 2.6rem; }
.particle:nth-child(6) { top: 15%; left: 45%; animation-delay: 5s; animation-duration: 9s; font-size: 2.2rem; }
.particle:nth-child(7) { top: 80%; left: 60%; animation-delay: 1.5s; animation-duration: 11.5s; font-size: 2.4rem; }
.particle:nth-child(8) { top: 35%; left: 20%; animation-delay: 2.5s; animation-duration: 10.5s; font-size: 2.7rem; }
.particle:nth-child(9) { top: 65%; left: 70%; animation-delay: 4.5s; animation-duration: 12.5s; font-size: 2.1rem; }
.particle:nth-child(10) { top: 40%; left: 90%; animation-delay: 3.5s; animation-duration: 13.5s; font-size: 2.9rem; }

@keyframes floatParticle {
    0%, 100% { transform: translate(0, 0) rotate(0deg); opacity: 0.3; }
    25% { transform: translate(50px, -50px) rotate(90deg); opacity: 0.6; }
    50% { transform: translate(-30px, -80px) rotate(180deg); opacity: 0.4; }
    75% { transform: translate(70px, -30px) rotate(270deg); opacity: 0.7; }
}

/* Icon Wrapper */
.cta-icon-wrapper {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid rgba(255, 255, 255, 0.3);
    animation: iconPulse 3s ease-in-out infinite;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.cta-icon-wrapper i {
    font-size: 2.5rem;
    color: white;
}

@keyframes iconPulse {
    0%, 100% { transform: scale(1); box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); }
    50% { transform: scale(1.05); box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3); }
}

/* CTA Card */
.cta-card {
    position: relative;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden; /* MUDADO: de visible para hidden */
    transition: all 0.4s ease;
}

.cta-card .card-body {
    position: relative;
    z-index: 10; /* Garante que o formulário fica acima do glow */
}

.cta-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
    border-color: rgba(255, 255, 255, 0.5);
}

.cta-card-glow {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    animation: rotateGlow 8s linear infinite;
    pointer-events: none; /* FIX: Permite cliques através do glow */
    z-index: 0;
}

@keyframes rotateGlow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* CTA Button */
.cta-button {
    background: white !important;
    color: #1e88e5 !important;
    border: none !important;
    font-weight: 600 !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
    transition: all 0.3s ease !important;
    position: relative;
    overflow: hidden;
    border-radius: 12px !important;
    padding: 1rem 2rem !important;
    font-size: 1.1rem !important;
}

.cta-button::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(30, 136, 229, 0.1);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.cta-button:hover::before {
    width: 400px;
    height: 400px;
}

.cta-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3) !important;
    background: #f8f9fa !important;
}

.cta-button:active {
    transform: translateY(-1px);
}

.cta-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.cta-button i {
    transition: transform 0.3s ease;
}

.cta-button:hover i {
    transform: translateX(5px);
}

/* Responsive */
@media (max-width: 768px) {
    .cta-icon-wrapper {
        width: 80px;
        height: 80px;
    }
    
    .cta-icon-wrapper i {
        font-size: 2rem;
    }
    
    .particle {
        font-size: 1.8rem;
    }
    
    .contact-input {
        font-size: 16px !important; /* Evita zoom no iOS */
    }
    
    .cta-card {
        margin: 0 1rem;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 1.5rem !important;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .section-subtitle {
        font-size: 0.95rem;
    }
}
</style>
@endsection

@push('scripts')
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const messageDiv = document.getElementById('contactFormMessage');
    const mensagemTextarea = document.getElementById('contactMensagem');
    const charCount = document.getElementById('charCount');
    
    // Contador de caracteres
    if (mensagemTextarea && charCount) {
        mensagemTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        
        if (mensagemTextarea.value) {
            charCount.textContent = mensagemTextarea.value.length;
        }
    }
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Desabilitar botão e mostrar loading
            submitBtn.disabled = true;
            submitBtn.querySelector('.btn-text').classList.add('d-none');
            submitBtn.querySelector('.btn-loading').classList.remove('d-none');
            
            // Garantir que messageDiv está escondido
            if (messageDiv) {
                messageDiv.classList.add('d-none');
                messageDiv.style.display = 'none';
            }
            
            // Enviar formulário via AJAX
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // ✅ SEM ALERTA - apenas limpa
                    form.reset();
                    if (charCount) charCount.textContent = '0';
                    
                    // CRÍTICO: Garantir que nada aparece
                    if (messageDiv) {
                        messageDiv.classList.add('d-none');
                        messageDiv.innerHTML = '';
                        messageDiv.style.display = 'none';
                    }
                } else {
                    // Erro
                    if (messageDiv) {
                        messageDiv.className = 'alert alert-danger alert-dismissible fade show mb-4';
                        let errorMessage = data.message || 'Erro ao enviar mensagem';
                        if (data.errors) {
                            errorMessage += '<ul class="mb-0 mt-2">';
                            Object.values(data.errors).forEach(error => {
                                errorMessage += `<li>${error}</li>`;
                            });
                            errorMessage += '</ul>';
                        }
                        messageDiv.innerHTML = `
                            <div class="d-flex align-items-start">
                                <div class="alert-icon me-3">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <strong class="d-block mb-2">Atenção!</strong>
                                    ${errorMessage}
                                </div>
                                <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
                            </div>
                        `;
                        messageDiv.classList.remove('d-none');
                        messageDiv.style.display = 'block';
                        messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                if (messageDiv) {
                    messageDiv.className = 'alert alert-danger alert-dismissible fade show mb-4';
                    messageDiv.innerHTML = `
                        <div class="d-flex align-items-start">
                            <div class="alert-icon me-3">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong class="d-block mb-1">Erro!</strong>
                                Erro ao enviar mensagem. Por favor, tente novamente.
                            </div>
                            <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    messageDiv.classList.remove('d-none');
                    messageDiv.style.display = 'block';
                    messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            })
            .finally(() => {
                // Reabilitar botão
                submitBtn.disabled = false;
                submitBtn.querySelector('.btn-text').classList.remove('d-none');
                submitBtn.querySelector('.btn-loading').classList.add('d-none');
            });
        });
    }
});
</script>
@endpush
