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
            </div>
        </div>
    </div>

    <!-- Cartões de Estatísticas -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
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

        <div class="col-xl-4 col-md-6 mb-4">
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

        <div class="col-xl-4 col-md-6 mb-4">
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
                        <div>
                            <span class="badge badge-warning">{{ $stats['pendentes_aprovacao'] }} pendentes</span>
                            <button class="btn btn-success btn-sm ml-2" onclick="aprovarSelecionadas()" id="btnAprovarLote" style="display: none;">
                                <i class="fas fa-check-double"></i> Aprovar Selecionadas
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if($explicacoesPendentes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="30">
                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                        </th>
                                        <th>Encarregado de Educação/Aluno</th>
                                        <th>Disciplina</th>
                                        <th>Data/Hora</th>
                                        <th>Aluno</th>
                                        <th>Preço</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($explicacoesPendentes as $explicacao)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="select-item" value="{{ $explicacao->id }}" onchange="updateSelection()">
                                            </td>
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
                                                <strong class="text-success">€{{ number_format($explicacao->preco, 2) }}</strong>
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
                            <a href="{{ route('admin.explicacoes.show', $explicacao->id) }}" 
                               class="d-flex align-items-center mb-3 p-2 rounded text-decoration-none atividade-item">
                                <div class="mr-3">
                                    @if($explicacao->aprovacao_admin === 'aprovada')
                                        <i class="fas fa-check-circle text-success"></i>
                                    @else
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small">
                                        <strong class="text-dark">{{ $explicacao->user->name }}</strong>
                                        <br>
                                        <span class="text-muted">{{ $explicacao->disciplina }} - {{ $explicacao->nome_aluno }}</span>
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
                                <div class="ml-2">
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </a>
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
        </div>
    </div>
</div>

<!-- Modal para Rejeitar Explicação -->
<div class="modal fade" id="modalRejeicao" tabindex="-1" role="dialog" aria-labelledby="modalRejeicaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalRejeicaoLabel">
                    <i class="fas fa-times-circle mr-2"></i>
                    Rejeitar Explicação
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form id="formRejeicao" method="POST" action="">
                @csrf
                @method('PATCH')
                
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Atenção:</strong> Esta ação irá rejeitar a explicação e notificar o professor sobre os motivos.
                    </div>
                    
                    <div class="form-group">
                        <label for="motivo_rejeicao">
                            <strong>Motivo da Rejeição <span class="text-danger">*</span></strong>
                        </label>
                        <textarea class="form-control" 
                                  id="motivo_rejeicao" 
                                  name="motivo_rejeicao" 
                                  rows="5" 
                                  required 
                                  minlength="10"
                                  maxlength="500"
                                  placeholder="Descreva detalhadamente o motivo da rejeição para que o professor possa corrigir..."></textarea>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Seja específico nos motivos para ajudar o professor a corrigir os problemas identificados.
                            <br>
                            <span id="contador-caracteres">0/500 caracteres</span>
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="text-muted">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Clique num motivo comum para pré-preencher:
                        </label>
                        <div class="d-flex flex-wrap">
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Horário incompatível com disponibilidade estabelecida.">
                                <i class="fas fa-clock mr-1"></i>Horário incompatível
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Preço não está de acordo com a tabela de valores estabelecida.">
                                <i class="fas fa-euro-sign mr-1"></i>Preço incorreto
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Informações do aluno incompletas ou incorretas.">
                                <i class="fas fa-user mr-1"></i>Info. aluno incompletas
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Local da explicação não especificado adequadamente.">
                                <i class="fas fa-map-marker-alt mr-1"></i>Local inadequado
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Disciplina não corresponde às habilitações do professor.">
                                <i class="fas fa-book mr-1"></i>Disciplina incorreta
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mr-2 mb-2 motivo-exemplo" 
                                    data-motivo="Dados de contacto do aluno em falta ou inválidos.">
                                <i class="fas fa-phone mr-1"></i>Contacto inválido
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger" id="btnConfirmarRejeicao">
                        <i class="fas fa-times-circle mr-1"></i>
                        Confirmar Rejeição
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<!-- Script inline direto no HTML -->
<script>
// Variáveis globais
let selectedItems = [];

// Funções globais - disponíveis imediatamente
function mostrarModalRejeicao(explicacaoId) {
    console.log('Abrindo modal de rejeição para explicação:', explicacaoId);
    
    const modal = document.getElementById('modalRejeicao');
    const form = document.getElementById('formRejeicao');
    
    if (!modal || !form) {
        console.error('Modal ou form não encontrado');
        alert('Erro: Modal não encontrado');
        return;
    }
    
    // Atualizar a action do form
    form.action = `/admin/explicacoes/${explicacaoId}/rejeitar`;
    
    // Limpar textarea
    const textarea = document.getElementById('motivo_rejeicao');
    if (textarea) {
        textarea.value = '';
        updateCharacterCount();
    }
    
    // Mostrar modal (compatível com Bootstrap 4 e 5)
    if (typeof $ !== 'undefined' && $.fn.modal) {
        $('#modalRejeicao').modal('show');
    } else if (typeof bootstrap !== 'undefined') {
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    } else {
        // Fallback manual
        modal.style.display = 'block';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Criar backdrop
        const backdrop = document.createElement('div');
        backdrop.classList.add('modal-backdrop', 'fade', 'show');
        backdrop.id = 'modal-backdrop-manual';
        document.body.appendChild(backdrop);
    }
}

function fecharModal() {
    const modal = document.getElementById('modalRejeicao');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
        
        const backdrop = document.getElementById('modal-backdrop-manual');
        if (backdrop) {
            backdrop.remove();
        }
    }
}

function updateCharacterCount() {
    const textarea = document.getElementById('motivo_rejeicao');
    const contador = document.getElementById('contador-caracteres');
    
    if (textarea && contador) {
        const length = textarea.value.length;
        contador.textContent = `${length}/500 caracteres`;
        
        if (length > 450) {
            contador.classList.add('text-warning');
            contador.classList.remove('text-danger');
        } else if (length >= 500) {
            contador.classList.remove('text-warning');
            contador.classList.add('text-danger');
        } else {
            contador.classList.remove('text-warning', 'text-danger');
        }
    }
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.select-item');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    updateSelection();
}

function updateSelection() {
    const checkboxes = document.querySelectorAll('.select-item:checked');
    selectedItems = Array.from(checkboxes).map(cb => cb.value);
    
    const btnAprovar = document.getElementById('btnAprovarLote');
    if (btnAprovar) {
        if (selectedItems.length > 0) {
            btnAprovar.style.display = 'inline-block';
            btnAprovar.innerHTML = `<i class="fas fa-check-double"></i> Aprovar Selecionadas (${selectedItems.length})`;
        } else {
            btnAprovar.style.display = 'none';
        }
    }
}

function aprovarSelecionadas() {
    if (selectedItems.length === 0) {
        alert('Selecione pelo menos uma explicação');
        return;
    }
    
    if (!confirm(`Aprovar ${selectedItems.length} explicação(ões) selecionada(s)?`)) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/explicacoes/aprovar-multiplas';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrfToken);
    
    selectedItems.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'explicacoes[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}

function atualizarContadorPendentes() {
    fetch('/admin/api/explicacoes-pendentes')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.badge-warning');
            if (badge && data.count > 0) {
                badge.textContent = data.count + ' pendentes';
            }
        })
        .catch(error => console.log('Erro ao atualizar contador:', error));
}

// Quando o documento carregar
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado - inicializando funcionalidades admin');
    
    // Event listeners para fechar modal
    document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', fecharModal);
    });
    
    // Fechar modal ao clicar fora
    const modal = document.getElementById('modalRejeicao');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                fecharModal();
            }
        });
    }
    
    // Contador de caracteres
    const textarea = document.getElementById('motivo_rejeicao');
    if (textarea) {
        textarea.addEventListener('input', updateCharacterCount);
    }
    
    // Preencher motivo com exemplos
    document.querySelectorAll('.motivo-exemplo').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const motivo = this.getAttribute('data-motivo');
            const textarea = document.getElementById('motivo_rejeicao');
            
            if (textarea) {
                if (textarea.value.trim()) {
                    textarea.value += '\n\n' + motivo;
                } else {
                    textarea.value = motivo;
                }
                textarea.focus();
                updateCharacterCount();
            }
        });
    });
    
    // Validação do formulário
    const formRejeicao = document.getElementById('formRejeicao');
    if (formRejeicao) {
        formRejeicao.addEventListener('submit', function(e) {
            const textarea = document.getElementById('motivo_rejeicao');
            
            if (!textarea || textarea.value.trim().length < 10) {
                e.preventDefault();
                alert('Por favor, forneça um motivo detalhado para a rejeição (mínimo 10 caracteres).');
                if (textarea) textarea.focus();
                return false;
            }
            
            if (!confirm('Tem certeza que deseja rejeitar esta explicação?')) {
                e.preventDefault();
                return false;
            }
            
            const btn = document.getElementById('btnConfirmarRejeicao');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>A rejeitar...';
                btn.disabled = true;
            }
        });
    }
    
    // Auto-refresh a cada 30 segundos
    setInterval(atualizarContadorPendentes, 30000);
    
    // Mostrar notificações
    @if(session('success'))
        if (typeof toastr !== 'undefined') {
            toastr.success('{{ session('success') }}');
        } else {
            alert('{{ session('success') }}');
        }
    @endif

    @if(session('error'))
        if (typeof toastr !== 'undefined') {
            toastr.error('{{ session('error') }}');
        } else {
            alert('{{ session('error') }}');
        }
    @endif
});
</script>

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

.motivo-exemplo:hover {
    background-color: #e9ecef;
}
</style>
@endpush