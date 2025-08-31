@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="container-fluid">
    <!-- Header do Dashboard -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard Administrativo
                </h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="{{ route('admin.explicacoes.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list"></i> Todas as Explicações
                        </a>
                        <a href="{{ route('admin.relatorio-aprovacoes') }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-chart-bar"></i> Relatórios
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartões de Estatísticas -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Explicações
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_explicacoes'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pendentes de Aprovação
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pendentes_aprovacao'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Aprovadas Hoje
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['aprovadas_hoje'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Professores Ativos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_professores'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="row">
        <!-- Explicações Pendentes de Aprovação -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock mr-2"></i>
                        Explicações Pendentes de Aprovação
                    </h6>
                    @if($stats['pendentes_aprovacao'] > 0)
                        <span class="badge badge-warning">{{ $stats['pendentes_aprovacao'] }} pendentes</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($explicacoesPendentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Professor</th>
                                        <th>Disciplina</th>
                                        <th>Data/Hora</th>
                                        <th>Aluno</th>
                                        <th>Criada em</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($explicacoesPendentes as $explicacao)
                                        <tr>
                                            <td>
                                                <strong>{{ $explicacao->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $explicacao->user->email }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-light">{{ $explicacao->disciplina }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ date('d/m/Y', strtotime($explicacao->data_explicacao)) }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $explicacao->hora_inicio }} - {{ $explicacao->hora_fim }}</small>
                                            </td>
                                            <td>
                                                {{ $explicacao->nome_aluno }}
                                                <br>
                                                <small class="text-muted">{{ $explicacao->contacto_aluno }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $explicacao->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.explicacoes.show', $explicacao->id) }}" 
                                                       class="btn btn-outline-info btn-sm" title="Ver detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.explicacoes.aprovar', $explicacao->id) }}" 
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-outline-success btn-sm" 
                                                                title="Aprovar" onclick="return confirm('Aprovar esta explicação?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                            title="Rejeitar" onclick="mostrarModalRejeicao({{ $explicacao->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($stats['pendentes_aprovacao'] > 10)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.explicacoes.index') }}?status_aprovacao=pendente" 
                                   class="btn btn-primary">
                                    Ver todas as {{ $stats['pendentes_aprovacao'] }} explicações pendentes
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-muted">Não há explicações pendentes de aprovação</h5>
                            <p class="text-muted">Todas as explicações foram processadas!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Atividade Recente -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>
                        Atividade Recente
                    </h6>
                </div>
                <div class="card-body">
                    @if($explicacoesRecentes->count() > 0)
                        @foreach($explicacoesRecentes as $explicacao)
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    @if($explicacao->aprovacao_admin === 'aprovada')
                                        <i class="fas fa-check-circle text-success"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small">
                                        <strong>{{ $explicacao->user->name }}</strong>
                                        <br>
                                        {{ $explicacao->disciplina }} - {{ $explicacao->nome_aluno }}
                                        <br>
                                        <span class="badge badge-{{ $explicacao->aprovacao_admin === 'aprovada' ? 'success' : 'danger' }}">
                                            {{ $explicacao->aprovacao_admin === 'aprovada' ? 'Aprovada' : 'Rejeitada' }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $explicacao->data_aprovacao->diffForHumans() }}</small>
                                        @if($explicacao->aprovadoPor)
                                            <br>
                                            <small class="text-muted">por {{ $explicacao->aprovadoPor->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="my-2">
                            @endif
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Nenhuma atividade recente</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>
                        Ações Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.explicacoes.index') }}?status_aprovacao=pendente" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-clock text-warning mr-2"></i>
                                Explicações Pendentes
                            </div>
                            @if($stats['pendentes_aprovacao'] > 0)
                                <span class="badge badge-warning badge-pill">{{ $stats['pendentes_aprovacao'] }}</span>
                            @endif
                        </a>
                        
                        <a href="{{ route('admin.explicacoes.index') }}?status_aprovacao=aprovada" 
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-check text-success mr-2"></i>
                            Explicações Aprovadas
                        </a>
                        
                        <a href="{{ route('admin.explicacoes.index') }}?status_aprovacao=rejeitada" 
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-times text-danger mr-2"></i>
                            Explicações Rejeitadas
                        </a>
                        
                        <a href="{{ route('admin.relatorio-aprovacoes') }}" 
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-bar text-info mr-2"></i>
                            Relatório de Aprovações
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de Atividade (placeholder para implementação futura) -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>
                        Resumo de Atividades dos Últimos 30 Dias
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Gráfico de Atividades</h5>
                        <p class="text-muted">Esta funcionalidade será implementada em breve para mostrar estatísticas detalhadas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Rejeitar Explicação -->
<div class="modal fade" id="modalRejeicao" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle text-danger mr-2"></i>
                    Rejeitar Explicação
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formRejeicao" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="motivo_rejeicao">Motivo da Rejeição *</label>
                        <textarea class="form-control" id="motivo_rejeicao" name="motivo_rejeicao" 
                                  rows="4" required placeholder="Descreva o motivo da rejeição..."></textarea>
                        <small class="form-text text-muted">
                            Este motivo será enviado ao professor para que possa corrigir e reenviar.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times mr-1"></i>
                        Rejeitar Explicação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function mostrarModalRejeicao(explicacaoId) {
    const form = document.getElementById('formRejeicao');
    form.action = `/admin/explicacoes/${explicacaoId}/rejeitar`;
    document.getElementById('motivo_rejeicao').value = '';
    $('#modalRejeicao').modal('show');
}

// Auto-refresh para contar explicações pendentes
function atualizarContadorPendentes() {
    fetch('/admin/api/explicacoes-pendentes')
        .then(response => response.json())
        .then(data => {
            // Atualizar badge se existir
            const badge = document.querySelector('.badge-warning');
            if (badge && data.count > 0) {
                badge.textContent = data.count + ' pendentes';
            }
        })
        .catch(error => console.log('Erro ao atualizar contador:', error));
}

// Atualizar a cada 30 segundos
setInterval(atualizarContadorPendentes, 30000);

// Mostrar notificações de sucesso/erro
@if(session('success'))
    toastr.success('{{ session('success') }}');
@endif

@if(session('error'))
    toastr.error('{{ session('error') }}');
@endif

@if(session('info'))
    toastr.info('{{ session('info') }}');
@endif
</script>
@endpush

@push('styles')
<style>
.border-left-primary {
    border-left: .25rem solid #4e73df!important;
}

.border-left-success {
    border-left: .25rem solid #1cc88a!important;
}

.border-left-info {
    border-left: .25rem solid #36b9cc!important;
}

.border-left-warning {
    border-left: .25rem solid #f6c23e!important;
}

.text-xs {
    font-size: .7rem;
}

.shadow {
    box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15)!important;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.list-group-item-action:hover {
    background-color: #f8f9fc;
}

.badge-pill {
    border-radius: 10rem;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.badge-warning.badge-pill {
    animation: pulse 2s infinite;
}
</style>
@endpush