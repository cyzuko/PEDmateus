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

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="60">Ano</th>
                        <th>Nome</th>
                        <th>Sala</th>
                        <th>Horários</th>
                        <th>Capacidade</th>
                        <th>Cor</th>
                        <th>Status</th>
                        <th width="200">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disciplinas as $disc)
                    <tr>
                        <td><span class="badge badge-dark" style="font-size: 1.1em;">{{ $disc->emoji }}</span></td>
                        <td><strong>{{ $disc->nome }}</strong></td>
                        <td><span class="badge badge-secondary">{{ $disc->sala ?? 'N/A' }}</span></td>
                        <td>
                            @php
                                $horarios = json_decode($disc->horarios_json ?? '{}', true);
                            @endphp
                            @if(!empty($horarios))
                                <small>
                                    @foreach($horarios as $dia => $horario)
                                        <div><strong>{{ $dia }}:</strong> {{ $horario['inicio'] }} - {{ $horario['fim'] }}</div>
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
                    <tr>
                        <td colspan="8" class="text-center text-muted">Nenhuma disciplina cadastrada</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sala: <span class="text-danger">*</span></label>
                        <input type="text" name="sala" class="form-control" placeholder="Ex: Sala 1, Lab A, etc." required>
                    </div>

                    <hr>
                    <h6><i class="fas fa-calendar-week"></i> Horários por Dia da Semana</h6>
                    <small class="text-muted">Defina os horários para cada dia. Deixe desmarcado os dias sem aula.</small>
                    
                    <div class="mt-3">
                        <!-- Segunda-feira -->
                        <div class="form-check mb-2">
                            <input class="form-check-input dia-checkbox" type="checkbox" id="seg" value="Segunda">
                            <label class="form-check-label font-weight-bold" for="seg">
                                Segunda-feira
                            </label>
                        </div>
                        <div class="row mb-3 horario-row" id="horario-seg" style="display: none;">
                            <div class="col-md-5 offset-md-1">
                                <input type="time" class="form-control hora-inicio" data-dia="Segunda" value="14:00">
                            </div>
                            <div class="col-md-5">
                                <input type="time" class="form-control hora-fim" data-dia="Segunda" value="18:00">
                            </div>
                        </div>

                        <!-- Terça-feira -->
                        <div class="form-check mb-2">
                            <input class="form-check-input dia-checkbox" type="checkbox" id="ter" value="Terça">
                            <label class="form-check-label font-weight-bold" for="ter">
                                Terça-feira
                            </label>
                        </div>
                        <div class="row mb-3 horario-row" id="horario-ter" style="display: none;">
                            <div class="col-md-5 offset-md-1">
                                <input type="time" class="form-control hora-inicio" data-dia="Terça" value="14:00">
                            </div>
                            <div class="col-md-5">
                                <input type="time" class="form-control hora-fim" data-dia="Terça" value="18:00">
                            </div>
                        </div>

                        <!-- Quarta-feira -->
                        <div class="form-check mb-2">
                            <input class="form-check-input dia-checkbox" type="checkbox" id="qua" value="Quarta">
                            <label class="form-check-label font-weight-bold" for="qua">
                                Quarta-feira
                            </label>
                        </div>
                        <div class="row mb-3 horario-row" id="horario-qua" style="display: none;">
                            <div class="col-md-5 offset-md-1">
                                <input type="time" class="form-control hora-inicio" data-dia="Quarta" value="14:00">
                            </div>
                            <div class="col-md-5">
                                <input type="time" class="form-control hora-fim" data-dia="Quarta" value="18:00">
                            </div>
                        </div>

                        <!-- Quinta-feira -->
                        <div class="form-check mb-2">
                            <input class="form-check-input dia-checkbox" type="checkbox" id="qui" value="Quinta">
                            <label class="form-check-label font-weight-bold" for="qui">
                                Quinta-feira
                            </label>
                        </div>
                        <div class="row mb-3 horario-row" id="horario-qui" style="display: none;">
                            <div class="col-md-5 offset-md-1">
                                <input type="time" class="form-control hora-inicio" data-dia="Quinta" value="14:00">
                            </div>
                            <div class="col-md-5">
                                <input type="time" class="form-control hora-fim" data-dia="Quinta" value="18:00">
                            </div>
                        </div>

                        <!-- Sexta-feira -->
                        <div class="form-check mb-2">
                            <input class="form-check-input dia-checkbox" type="checkbox" id="sex" value="Sexta">
                            <label class="form-check-label font-weight-bold" for="sex">
                                Sexta-feira
                            </label>
                        </div>
                        <div class="row mb-3 horario-row" id="horario-sex" style="display: none;">
                            <div class="col-md-5 offset-md-1">
                                <input type="time" class="form-control hora-inicio" data-dia="Sexta" value="14:00">
                            </div>
                            <div class="col-md-5">
                                <input type="time" class="form-control hora-fim" data-dia="Sexta" value="18:00">
                            </div>
                        </div>

                        <!-- Sábado -->
                        <div class="form-check mb-2">
                            <input class="form-check-input dia-checkbox" type="checkbox" id="sab" value="Sábado">
                            <label class="form-check-label font-weight-bold" for="sab">
                                Sábado
                            </label>
                        </div>
                        <div class="row mb-3 horario-row" id="horario-sab" style="display: none;">
                            <div class="col-md-5 offset-md-1">
                                <input type="time" class="form-control hora-inicio" data-dia="Sábado" value="09:00">
                            </div>
                            <div class="col-md-5">
                                <input type="time" class="form-control hora-fim" data-dia="Sábado" value="13:00">
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
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sala: <span class="text-danger">*</span></label>
                        <input type="text" name="sala" id="editSala" class="form-control" required>
                    </div>

                    <hr>
                    <h6><i class="fas fa-calendar-week"></i> Horários por Dia da Semana</h6>
                    
                    <div class="mt-3" id="editHorarios">
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
// Modal de criação
document.querySelectorAll('.dia-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const dia = this.value.toLowerCase().substring(0, 3);
        const horarioRow = document.getElementById('horario-' + dia);
        if (this.checked) {
            horarioRow.style.display = 'flex';
        } else {
            horarioRow.style.display = 'none';
        }
    });
});

function prepararHorarios() {
    const horarios = {};
    document.querySelectorAll('.dia-checkbox:checked').forEach(checkbox => {
        const dia = checkbox.value;
        const diaCode = dia.toLowerCase().substring(0, 3);
        const inicio = document.querySelector(`#horario-${diaCode} .hora-inicio`).value;
        const fim = document.querySelector(`#horario-${diaCode} .hora-fim`).value;
        horarios[dia] = { inicio, fim };
    });
    
    document.getElementById('horariosJson').value = JSON.stringify(horarios);
    
    // Preencher campos ocultos com primeiro horário disponível
    if (Object.keys(horarios).length > 0) {
        const primeiroHorario = Object.values(horarios)[0];
        document.getElementById('horaInicioHidden').value = primeiroHorario.inicio;
        document.getElementById('horaFimHidden').value = primeiroHorario.fim;
    }
    
    return true;
}

// Modal de edição
function editarDisciplina(disc) {
    document.getElementById('formEditar').action = '/disciplinas/' + disc.id;
    document.getElementById('editNome').value = disc.nome;
    document.getElementById('editEmoji').value = disc.emoji;
    document.getElementById('editSala').value = disc.sala || '';
    document.getElementById('editCapacidade').value = disc.capacidade;
    document.getElementById('editCorBadge').value = disc.cor_badge || '#007bff';
    
    // Criar estrutura de horários
    const dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    const horarios = disc.horarios_json ? JSON.parse(disc.horarios_json) : {};
    
    let html = '';
    dias.forEach(dia => {
        const diaCode = dia.toLowerCase().substring(0, 3);
        const checked = horarios[dia] ? 'checked' : '';
        const display = horarios[dia] ? 'flex' : 'none';
        const inicio = horarios[dia]?.inicio || '14:00';
        const fim = horarios[dia]?.fim || '18:00';
        
        html += `
            <div class="form-check mb-2">
                <input class="form-check-input dia-checkbox-edit" type="checkbox" id="edit-${diaCode}" value="${dia}" ${checked}>
                <label class="form-check-label font-weight-bold" for="edit-${diaCode}">
                    ${dia}-feira
                </label>
            </div>
            <div class="row mb-3 horario-row-edit" id="edit-horario-${diaCode}" style="display: ${display};">
                <div class="col-md-5 offset-md-1">
                    <input type="time" class="form-control hora-inicio-edit" data-dia="${dia}" value="${inicio}">
                </div>
                <div class="col-md-5">
                    <input type="time" class="form-control hora-fim-edit" data-dia="${dia}" value="${fim}">
                </div>
            </div>
        `;
    });
    
    document.getElementById('editHorarios').innerHTML = html;
    
    // Adicionar event listeners
    document.querySelectorAll('.dia-checkbox-edit').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const dia = this.value.toLowerCase().substring(0, 3);
            const horarioRow = document.getElementById('edit-horario-' + dia);
            if (this.checked) {
                horarioRow.style.display = 'flex';
            } else {
                horarioRow.style.display = 'none';
            }
        });
    });
    
    $('#modalEditarDisciplina').modal('show');
}

function prepararHorariosEdit() {
    const horarios = {};
    document.querySelectorAll('.dia-checkbox-edit:checked').forEach(checkbox => {
        const dia = checkbox.value;
        const diaCode = dia.toLowerCase().substring(0, 3);
        const inicio = document.querySelector(`#edit-horario-${diaCode} .hora-inicio-edit`).value;
        const fim = document.querySelector(`#edit-horario-${diaCode} .hora-fim-edit`).value;
        horarios[dia] = { inicio, fim };
    });
    
    document.getElementById('horariosJsonEdit').value = JSON.stringify(horarios);
    
    if (Object.keys(horarios).length > 0) {
        const primeiroHorario = Object.values(horarios)[0];
        document.getElementById('editHoraInicioHidden').value = primeiroHorario.inicio;
        document.getElementById('editHoraFimHidden').value = primeiroHorario.fim;
    }
    
    return true;
}
</script>
@endsection