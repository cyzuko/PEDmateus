@extends('layouts.app')

@section('title', 'Gestão de Disciplinas')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3><i class="fas fa-book"></i> Gestão de Disciplinas</h3>
            <button class="btn btn-success" data-toggle="modal" data-target="#modalNovaDisciplina">
                <i class="fas fa-plus"></i> Nova Disciplina
            </button>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <!-- Filtros -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><i class="fas fa-search"></i> Pesquisar:</label>
                        <input type="text" id="filtroNome" class="form-control" placeholder="Nome da disciplina...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt"></i> Ano Letivo:</label>
                        <select id="filtroAnoLetivo" class="form-control">
                            <option value="">Todos</option>
                            <option value="2024/2025">2024/2025</option>
                            <option value="2025/2026" selected>2025/2026</option>
                            <option value="2026/2027">2026/2027</option>
                            <option value="2027/2028">2027/2028</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label><i class="fas fa-graduation-cap"></i> Ano:</label>
                        <select id="filtroAno" class="form-control">
                            <option value="">Todos os anos</option>
                            <option value="1º">1º Ano</option>
                            <option value="2º">2º Ano</option>
                            <option value="3º">3º Ano</option>
                            <option value="4º">4º Ano</option>
                            <option value="5º">5º Ano</option>
                            <option value="6º">6º Ano</option>
                            <option value="7º">7º Ano</option>
                            <option value="8º">8º Ano</option>
                            <option value="9º">9º Ano</option>
                            <option value="10º">10º Ano</option>
                            <option value="11º">11º Ano</option>
                            <option value="12º">12º Ano</option>
                            <option value="🎓">Universidade</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><i class="fas fa-toggle-on"></i> Status:</label>
                        <select id="filtroStatus" class="form-control">
                            <option value="">Todos</option>
                            <option value="ativa">Ativas</option>
                            <option value="inativa">Inativas</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button class="btn btn-secondary btn-block" onclick="limparFiltros()">
                            <i class="fas fa-times"></i> Limpar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contador de resultados -->
            <div class="mb-2">
                <small class="text-muted">
                    Mostrando <strong id="contadorResultados">0</strong> disciplina(s)
                </small>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="60">Ano</th>
                            <th>Nome</th>
                            <th width="100">Ano Letivo</th>
                            <th>Sala</th>
                            <th>Horários</th>
                            <th>Capacidade</th>
                            <th>Cor</th>
                            <th>Status</th>
                            <th width="200">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaDisciplinas">
                        @forelse($disciplinas as $disc)
                        <tr class="disciplina-row" 
                            data-nome="{{ strtolower($disc->nome) }}" 
                            data-ano="{{ $disc->emoji }}" 
                            data-ano-letivo="{{ $disc->ano_letivo }}"
                            data-status="{{ $disc->ativa ? 'ativa' : 'inativa' }}">
                            <td><span class="badge badge-dark" style="font-size: 1.1em;">{{ $disc->emoji }}</span></td>
                            <td><strong>{{ $disc->nome }}</strong></td>
                            <td><span class="badge badge-primary">{{ $disc->ano_letivo }}</span></td>
                            <td><span class="badge badge-secondary">{{ $disc->sala ?? 'N/A' }}</span></td>
                            <td>
                                @php
                                    $horarios = json_decode($disc->horarios_json ?? '{}', true);
                                @endphp
                                @if(!empty($horarios))
                                    <small>
                                        @foreach($horarios as $dia => $blocos)
                                            <div><strong>{{ $dia }}:</strong> 
                                                @if(is_array($blocos) && isset($blocos[0]) && is_array($blocos[0]))
                                                    @foreach($blocos as $bloco)
                                                        {{ $bloco['inicio'] }}-{{ $bloco['fim'] }}@if(!$loop->last), @endif
                                                    @endforeach
                                                @else
                                                    {{ $blocos['inicio'] }} - {{ $blocos['fim'] }}
                                                @endif
                                            </div>
                                        @endforeach
                                    </small>
                                @else
                                    <small>{{ $disc->hora_inicio }} - {{ $disc->hora_fim }}</small>
                                @endif
                            </td>
                            <td><span class="badge badge-info">{{ $disc->capacidade }} vagas</span></td>
                            <td><span class="badge" style="background-color: {{ $disc->cor_badge ?? '#6c757d' }}">■</span></td>
                            <td>
                                <span class="badge badge-{{ $disc->ativa ? 'success' : 'secondary' }}">
                                    {{ $disc->ativa ? 'Ativa' : 'Inativa' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick='editarDisciplina(@json($disc))'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('disciplinas.toggle', $disc) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-{{ $disc->ativa ? 'warning' : 'success' }}" 
                                            title="{{ $disc->ativa ? 'Desativar' : 'Ativar' }}">
                                        <i class="fas fa-{{ $disc->ativa ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('disciplinas.destroy', $disc) }}" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Tem certeza que deseja remover esta disciplina?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr id="semResultados">
                            <td colspan="9" class="text-center text-muted">Nenhuma disciplina cadastrada</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova -->
<div class="modal fade" id="modalNovaDisciplina">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('disciplinas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="horarios_json" id="horariosJson">
                
                <div class="modal-header">
                    <h5>Nova Disciplina</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome: <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Ano Letivo: <span class="text-danger">*</span></label>
                        <select name="ano_letivo" class="form-control" required>
                            <option value="">Selecione o ano letivo...</option>
                            <option value="2024/2025">2024/2025</option>
                            <option value="2025/2026" selected>2025/2026</option>
                            <option value="2026/2027">2026/2027</option>
                            <option value="2027/2028">2027/2028</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Ano: <span class="text-danger">*</span></label>
                        <select name="emoji" class="form-control" required>
                            <option value="">Selecione o ano...</option>
                            <option value="1º">1º Ano</option>
                            <option value="2º">2º Ano</option>
                            <option value="3º">3º Ano</option>
                            <option value="4º">4º Ano</option>
                            <option value="5º">5º Ano</option>
                            <option value="6º">6º Ano</option>
                            <option value="7º">7º Ano</option>
                            <option value="8º">8º Ano</option>
                            <option value="9º">9º Ano</option>
                            <option value="10º">10º Ano</option>
                            <option value="11º">11º Ano</option>
                            <option value="12º">12º Ano</option>
                            <option value="🎓">Universidade</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Sala: <span class="text-danger">*</span></label>
                        <input type="text" name="sala" class="form-control" placeholder="Ex: Sala 1, Lab A, etc." required>
                    </div>

                    <hr>
                    <h6><i class="fas fa-calendar-week"></i> Horários por Dia da Semana</h6>
                    <small class="text-muted">Defina os horários para cada dia. Pode adicionar múltiplos horários por dia.</small>
                    
                    <div class="mt-3" id="horariosContainer">
                        <!-- Segunda-feira -->
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="seg" value="Segunda">
                                <label class="form-check-label font-weight-bold" for="seg">
                                    Segunda-feira
                                </label>
                            </div>
                            <div id="horarios-seg" class="horarios-dia ml-4" style="display: none;">
                                <div class="horario-bloco mb-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-inicio" value="14:00">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-fim" value="18:00">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-success btn-sm" onclick="adicionarHorario('seg')">
                                                <i class="fas fa-plus"></i> Adicionar horário
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terça-feira -->
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="ter" value="Terça">
                                <label class="form-check-label font-weight-bold" for="ter">
                                    Terça-feira
                                </label>
                            </div>
                            <div id="horarios-ter" class="horarios-dia ml-4" style="display: none;">
                                <div class="horario-bloco mb-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-inicio" value="14:00">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-fim" value="18:00">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-success btn-sm" onclick="adicionarHorario('ter')">
                                                <i class="fas fa-plus"></i> Adicionar horário
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quarta-feira -->
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="qua" value="Quarta">
                                <label class="form-check-label font-weight-bold" for="qua">
                                    Quarta-feira
                                </label>
                            </div>
                            <div id="horarios-qua" class="horarios-dia ml-4" style="display: none;">
                                <div class="horario-bloco mb-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-inicio" value="14:00">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-fim" value="18:00">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-success btn-sm" onclick="adicionarHorario('qua')">
                                                <i class="fas fa-plus"></i> Adicionar horário
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quinta-feira -->
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="qui" value="Quinta">
                                <label class="form-check-label font-weight-bold" for="qui">
                                    Quinta-feira
                                </label>
                            </div>
                            <div id="horarios-qui" class="horarios-dia ml-4" style="display: none;">
                                <div class="horario-bloco mb-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-inicio" value="14:00">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-fim" value="18:00">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-success btn-sm" onclick="adicionarHorario('qui')">
                                                <i class="fas fa-plus"></i> Adicionar horário
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sexta-feira -->
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="sex" value="Sexta">
                                <label class="form-check-label font-weight-bold" for="sex">
                                    Sexta-feira
                                </label>
                            </div>
                            <div id="horarios-sex" class="horarios-dia ml-4" style="display: none;">
                                <div class="horario-bloco mb-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-inicio" value="14:00">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-fim" value="18:00">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-success btn-sm" onclick="adicionarHorario('sex')">
                                                <i class="fas fa-plus"></i> Adicionar horário
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sábado -->
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input dia-checkbox" type="checkbox" id="sab" value="Sábado">
                                <label class="form-check-label font-weight-bold" for="sab">
                                    Sábado
                                </label>
                            </div>
                            <div id="horarios-sab" class="horarios-dia ml-4" style="display: none;">
                                <div class="horario-bloco mb-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-inicio" value="09:00">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="time" class="form-control hora-fim" value="13:00">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-success btn-sm" onclick="adicionarHorario('sab')">
                                                <i class="fas fa-plus"></i> Adicionar horário
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="form-group">
                        <label>Capacidade (vagas simultâneas): <span class="text-danger">*</span></label>
                        <input type="number" name="capacidade" class="form-control" value="4" min="1" max="20" required>
                        <small class="text-muted">Número máximo de explicações simultâneas nesta disciplina</small>
                    </div>
                    <div class="form-group">
                        <label>Cor do Badge:</label>
                        <input type="color" name="cor_badge" class="form-control" value="#007bff">
                    </div>

                    <!-- Campos ocultos para compatibilidade -->
                    <input type="hidden" name="hora_inicio" id="horaInicioHidden" value="14:00">
                    <input type="hidden" name="hora_fim" id="horaFimHidden" value="18:00">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" onclick="prepararHorarios()">Criar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditarDisciplina">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditar" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="horarios_json" id="horariosJsonEdit">
                
                <div class="modal-header">
                    <h5>Editar Disciplina</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome: <span class="text-danger">*</span></label>
                        <input type="text" name="nome" id="editNome" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Ano Letivo: <span class="text-danger">*</span></label>
                        <select name="ano_letivo" id="editAnoLetivo" class="form-control" required>
                            <option value="">Selecione o ano letivo...</option>
                            <option value="2024/2025">2024/2025</option>
                            <option value="2025/2026">2025/2026</option>
                            <option value="2026/2027">2026/2027</option>
                            <option value="2027/2028">2027/2028</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Ano: <span class="text-danger">*</span></label>
                        <select name="emoji" id="editEmoji" class="form-control" required>
                            <option value="">Selecione o ano...</option>
                            <option value="1º">1º Ano</option>
                            <option value="2º">2º Ano</option>
                            <option value="3º">3º Ano</option>
                            <option value="4º">4º Ano</option>
                            <option value="5º">5º Ano</option>
                            <option value="6º">6º Ano</option>
                            <option value="7º">7º Ano</option>
                            <option value="8º">8º Ano</option>
                            <option value="9º">9º Ano</option>
                            <option value="10º">10º Ano</option>
                            <option value="11º">11º Ano</option>
                            <option value="12º">12º Ano</option>
                            <option value="🎓">Universidade</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Sala: <span class="text-danger">*</span></label>
                        <input type="text" name="sala" id="editSala" class="form-control" required>
                    </div>

                    <hr>
                    <h6><i class="fas fa-calendar-week"></i> Horários por Dia da Semana</h6>
                    
                    <div class="mt-3" id="editHorariosContainer">
                        <!-- Os horários serão preenchidos via JavaScript -->
                    </div>

                    <hr>

                    <div class="form-group">
                        <label>Capacidade: <span class="text-danger">*</span></label>
                        <input type="number" name="capacidade" id="editCapacidade" class="form-control" min="1" max="20" required>
                    </div>
                    <div class="form-group">
                        <label>Cor do Badge:</label>
                        <input type="color" name="cor_badge" id="editCorBadge" class="form-control">
                    </div>

                    <input type="hidden" name="hora_inicio" id="editHoraInicioHidden" value="14:00">
                    <input type="hidden" name="hora_fim" id="editHoraFimHidden" value="18:00">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" onclick="prepararHorariosEdit()">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Função de filtro
function aplicarFiltros() {
    const filtroNome = document.getElementById('filtroNome').value.toLowerCase();
    const filtroAno = document.getElementById('filtroAno').value;
    const filtroStatus = document.getElementById('filtroStatus').value;
    const filtroAnoLetivo = document.getElementById('filtroAnoLetivo').value;
    
    const rows = document.querySelectorAll('.disciplina-row');
    let contador = 0;
    
    rows.forEach(row => {
        const nome = row.getAttribute('data-nome');
        const ano = row.getAttribute('data-ano');
        const status = row.getAttribute('data-status');
        const anoLetivo = row.getAttribute('data-ano-letivo');
        
        let mostrar = true;
        
        if (filtroNome && !nome.includes(filtroNome)) {
            mostrar = false;
        }
        
        if (filtroAno && ano !== filtroAno) {
            mostrar = false;
        }
        
        if (filtroStatus && status !== filtroStatus) {
            mostrar = false;
        }
        
        if (filtroAnoLetivo && anoLetivo !== filtroAnoLetivo) {
            mostrar = false;
        }
        
        if (mostrar) {
            row.style.display = '';
            contador++;
        } else {
            row.style.display = 'none';
        }
    });
    
    document.getElementById('contadorResultados').textContent = contador;
    
    const semResultados = document.getElementById('semResultados');
    if (semResultados) {
        if (contador === 0 && rows.length > 0) {
            semResultados.style.display = '';
            semResultados.innerHTML = '<td colspan="9" class="text-center text-muted">Nenhuma disciplina encontrada com os filtros aplicados</td>';
        } else {
            semResultados.style.display = 'none';
        }
    }
}

function limparFiltros() {
    document.getElementById('filtroNome').value = '';
    document.getElementById('filtroAno').value = '';
    document.getElementById('filtroStatus').value = '';
    document.getElementById('filtroAnoLetivo').value = '';
    aplicarFiltros();
}

// Event listeners para filtros
document.getElementById('filtroNome').addEventListener('input', aplicarFiltros);
document.getElementById('filtroAno').addEventListener('change', aplicarFiltros);
document.getElementById('filtroStatus').addEventListener('change', aplicarFiltros);
document.getElementById('filtroAnoLetivo').addEventListener('change', aplicarFiltros);

// Aplicar filtros ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    aplicarFiltros();
});

// Modal de criação - checkboxes para mostrar/ocultar horários
document.querySelectorAll('.dia-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const dia = this.value.toLowerCase().substring(0, 3);
        const horariosDiv = document.getElementById('horarios-' + dia);
        if (this.checked) {
            horariosDiv.style.display = 'block';
        } else {
            horariosDiv.style.display = 'none';
        }
    });
});

// Adicionar novo bloco de horário
function adicionarHorario(diaCode) {
    const container = document.getElementById('horarios-' + diaCode);
    const novoBloco = document.createElement('div');
    novoBloco.className = 'horario-bloco mb-2';
    novoBloco.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <input type="time" class="form-control hora-inicio" value="10:00">
            </div>
            <div class="col-md-4">
                <input type="time" class="form-control hora-fim" value="12:00">
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-danger btn-sm" onclick="removerHorario(this)">
                    <i class="fas fa-trash"></i> Remover
                </button>
            </div>
        </div>
    `;
    container.appendChild(novoBloco);
}

function removerHorario(btn) {
    btn.closest('.horario-bloco').remove();
}

function prepararHorarios() {
    const horarios = {};
    const diasMap = {
        'seg': 'Segunda',
        'ter': 'Terça',
        'qua': 'Quarta',
        'qui': 'Quinta',
        'sex': 'Sexta',
        'sab': 'Sábado'
    };
    
    document.querySelectorAll('.dia-checkbox:checked').forEach(checkbox => {
        const diaCompleto = checkbox.value;
        const diaCode = diaCompleto.toLowerCase().substring(0, 3);
        const container = document.getElementById('horarios-' + diaCode);
        const blocos = container.querySelectorAll('.horario-bloco');
        
        const horariosArray = [];
        blocos.forEach(bloco => {
            const inicio = bloco.querySelector('.hora-inicio').value;
            const fim = bloco.querySelector('.hora-fim').value;
            horariosArray.push({ inicio, fim });
        });
        
        horarios[diaCompleto] = horariosArray;
    });
    
    document.getElementById('horariosJson').value = JSON.stringify(horarios);
    
    // Preencher campos ocultos com primeiro horário disponível
    if (Object.keys(horarios).length > 0) {
        const primeiroHorario = Object.values(horarios)[0][0];
        document.getElementById('horaInicioHidden').value = primeiroHorario.inicio;
        document.getElementById('horaFimHidden').value = primeiroHorario.fim;
    }
    
    return true;
}

// Modal de edição
function editarDisciplina(disc) {
    document.getElementById('formEditar').action = '/disciplinas/' + disc.id;
    document.getElementById('editNome').value = disc.nome;
    document.getElementById('editAnoLetivo').value = disc.ano_letivo;
    document.getElementById('editEmoji').value = disc.emoji;
    document.getElementById('editSala').value = disc.sala || '';
    document.getElementById('editCapacidade').value = disc.capacidade;
    document.getElementById('editCorBadge').value = disc.cor_badge || '#007bff';
    
    // Criar estrutura de horários
    const dias = [
        {nome: 'Segunda', code: 'seg'},
        {nome: 'Terça', code: 'ter'},
        {nome: 'Quarta', code: 'qua'},
        {nome: 'Quinta', code: 'qui'},
        {nome: 'Sexta', code: 'sex'},
        {nome: 'Sábado', code: 'sab'}
    ];
    
    const horarios = disc.horarios_json ? JSON.parse(disc.horarios_json) : {};
    
    let html = '';
    dias.forEach(dia => {
        const checked = horarios[dia.nome] ? 'checked' : '';
        const display = horarios[dia.nome] ? 'block' : 'none';
        
        html += `
            <div class="mb-3">
                <div class="form-check mb-2">
                    <input class="form-check-input dia-checkbox-edit" type="checkbox" id="edit-${dia.code}" value="${dia.nome}" ${checked}>
                    <label class="form-check-label font-weight-bold" for="edit-${dia.code}">
                        ${dia.nome}-feira
                    </label>
                </div>
                <div id="edit-horarios-${dia.code}" class="horarios-dia ml-4" style="display: ${display};">
        `;
        
        if (horarios[dia.nome]) {
            const blocos = Array.isArray(horarios[dia.nome]) ? horarios[dia.nome] : [horarios[dia.nome]];
            blocos.forEach((bloco, index) => {
                const btnHtml = index === 0 
                    ? `<button type="button" class="btn btn-success btn-sm" onclick="adicionarHorarioEdit('${dia.code}')">
                           <i class="fas fa-plus"></i> Adicionar horário
                       </button>`
                    : `<button type="button" class="btn btn-danger btn-sm" onclick="removerHorario(this)">
                           <i class="fas fa-trash"></i> Remover
                       </button>`;
                
                html += `
                    <div class="horario-bloco mb-2">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="time" class="form-control hora-inicio" value="${bloco.inicio}">
                            </div>
                            <div class="col-md-4">
                                <input type="time" class="form-control hora-fim" value="${bloco.fim}">
                            </div>
                            <div class="col-md-4">
                                ${btnHtml}
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            html += `
                <div class="horario-bloco mb-2">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="time" class="form-control hora-inicio" value="14:00">
                        </div>
                        <div class="col-md-4">
                            <input type="time" class="form-control hora-fim" value="18:00">
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-success btn-sm" onclick="adicionarHorarioEdit('${dia.code}')">
                                <i class="fas fa-plus"></i> Adicionar horário
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
        
        html += `
                </div>
            </div>
        `;
    });
    
    document.getElementById('editHorariosContainer').innerHTML = html;
    
    // Adicionar event listeners
    document.querySelectorAll('.dia-checkbox-edit').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const dia = this.value.toLowerCase().substring(0, 3);
            const horariosDiv = document.getElementById('edit-horarios-' + dia);
            if (this.checked) {
                horariosDiv.style.display = 'block';
            } else {
                horariosDiv.style.display = 'none';
            }
        });
    });
    
    $('#modalEditarDisciplina').modal('show');
}

function adicionarHorarioEdit(diaCode) {
    const container = document.getElementById('edit-horarios-' + diaCode);
    const novoBloco = document.createElement('div');
    novoBloco.className = 'horario-bloco mb-2';
    novoBloco.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <input type="time" class="form-control hora-inicio" value="10:00">
            </div>
            <div class="col-md-4">
                <input type="time" class="form-control hora-fim" value="12:00">
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-danger btn-sm" onclick="removerHorario(this)">
                    <i class="fas fa-trash"></i> Remover
                </button>
            </div>
        </div>
    `;
    container.appendChild(novoBloco);
}

function prepararHorariosEdit() {
    const horarios = {};
    const diasMap = {
        'seg': 'Segunda',
        'ter': 'Terça',
        'qua': 'Quarta',
        'qui': 'Quinta',
        'sex': 'Sexta',
        'sab': 'Sábado'
    };
    
    document.querySelectorAll('.dia-checkbox-edit:checked').forEach(checkbox => {
        const diaCompleto = checkbox.value;
        const diaCode = diaCompleto.toLowerCase().substring(0, 3);
        const container = document.getElementById('edit-horarios-' + diaCode);
        const blocos = container.querySelectorAll('.horario-bloco');
        
        const horariosArray = [];
        blocos.forEach(bloco => {
            const inicio = bloco.querySelector('.hora-inicio').value;
            const fim = bloco.querySelector('.hora-fim').value;
            horariosArray.push({ inicio, fim });
        });
        
        horarios[diaCompleto] = horariosArray;
    });
    
    document.getElementById('horariosJsonEdit').value = JSON.stringify(horarios);
    
    if (Object.keys(horarios).length > 0) {
        const primeiroHorario = Object.values(horarios)[0][0];
        document.getElementById('editHoraInicioHidden').value = primeiroHorario.inicio;
        document.getElementById('editHoraFimHidden').value = primeiroHorario.fim;
    }
    
    return true;
}
</script>
@endsection