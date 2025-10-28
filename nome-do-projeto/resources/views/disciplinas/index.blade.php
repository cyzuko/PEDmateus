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

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="60">Emoji</th>
                        <th>Nome</th>
                        <th>Horário</th>
                        <th>Capacidade</th>
                        <th>Cor</th>
                        <th>Status</th>
                        <th width="200">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disciplinas as $disc)
                    <tr>
                        <td style="font-size: 1.8em;">{{ $disc->emoji }}</td>
                        <td><strong>{{ $disc->nome }}</strong></td>
                        <td><small>{{ $disc->hora_inicio }} - {{ $disc->hora_fim }}</small></td>
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
                                <button type="submit" class="btn btn-sm btn-{{ $disc->ativa ? 'warning' : 'success' }}">
                                    <i class="fas fa-{{ $disc->ativa ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('disciplinas.destroy', $disc) }}" method="POST" style="display: inline;" 
                                  onsubmit="return confirm('Remover disciplina?')">
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
                        <td colspan="7" class="text-center text-muted">Nenhuma disciplina cadastrada</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Nova -->
<div class="modal fade" id="modalNovaDisciplina">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('disciplinas.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5>Nova Disciplina</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome:</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Emoji:</label>
                        <input type="text" name="emoji" class="form-control" placeholder="📚" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora Início:</label>
                                <input type="time" name="hora_inicio" class="form-control" value="14:00" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora Fim:</label>
                                <input type="time" name="hora_fim" class="form-control" value="18:00" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Capacidade (vagas simultâneas):</label>
                        <input type="number" name="capacidade" class="form-control" value="4" min="1" max="20" required>
                    </div>
                    <div class="form-group">
                        <label>Cor do Badge:</label>
                        <input type="color" name="cor_badge" class="form-control" value="#007bff">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Criar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditarDisciplina">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditar" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5>Editar Disciplina</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome:</label>
                        <input type="text" name="nome" id="editNome" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Emoji:</label>
                        <input type="text" name="emoji" id="editEmoji" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora Início:</label>
                                <input type="time" name="hora_inicio" id="editHoraInicio" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hora Fim:</label>
                                <input type="time" name="hora_fim" id="editHoraFim" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Capacidade:</label>
                        <input type="number" name="capacidade" id="editCapacidade" class="form-control" min="1" max="20" required>
                    </div>
                    <div class="form-group">
                        <label>Cor do Badge:</label>
                        <input type="color" name="cor_badge" id="editCorBadge" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editarDisciplina(disc) {
    document.getElementById('formEditar').action = '/disciplinas/' + disc.id;
    document.getElementById('editNome').value = disc.nome;
    document.getElementById('editEmoji').value = disc.emoji;
    document.getElementById('editHoraInicio').value = disc.hora_inicio;
    document.getElementById('editHoraFim').value = disc.hora_fim;
    document.getElementById('editCapacidade').value = disc.capacidade;
    document.getElementById('editCorBadge').value = disc.cor_badge || '#007bff';
    $('#modalEditarDisciplina').modal('show');
}
</script>
@endsection