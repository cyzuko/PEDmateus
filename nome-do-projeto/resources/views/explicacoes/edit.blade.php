@extends('layouts.app')

@section('title', 'Editar Explicação')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>
                        <i class="fas fa-edit mr-2"></i>
                        Editar Explicação
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('explicacoes.show', $explicacao->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Ver Detalhes
                        </a>
                        <a href="{{ route('explicacoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Erro:</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('explicacoes.update', $explicacao->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Disciplina *</label>
                                    <input type="text" name="disciplina" class="form-control" 
                                           value="{{ old('disciplina', $explicacao->disciplina) }}" required 
                                           placeholder="Ex: Matemática, Português">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Data da Explicação *</label>
                                    <input type="date" name="data_explicacao" class="form-control" 
                                           value="{{ old('data_explicacao', $explicacao->data_explicacao) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hora Início *</label>
                                    <input type="time" name="hora_inicio" class="form-control" 
                                           value="{{ old('hora_inicio', $explicacao->hora_inicio) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Hora Fim *</label>
                                    <input type="time" name="hora_fim" class="form-control" 
                                           value="{{ old('hora_fim', $explicacao->hora_fim) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nome do Aluno *</label>
                                    <input type="text" name="nome_aluno" class="form-control" 
                                           value="{{ old('nome_aluno', $explicacao->nome_aluno) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contacto do Aluno *</label>
                                    <input type="text" name="contacto_aluno" class="form-control" 
                                           value="{{ old('contacto_aluno', $explicacao->contacto_aluno) }}" required 
                                           placeholder="Telefone ou email">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Local *</label>
                                    <input type="text" name="local" class="form-control" 
                                           value="{{ old('local', $explicacao->local) }}" required 
                                           placeholder="Ex: Online, Biblioteca, Casa do aluno">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Preço (€) *</label>
                                    <input type="number" step="0.01" min="0" name="preco" 
                                           class="form-control" value="{{ old('preco', $explicacao->preco) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Observações</label>
                            <textarea name="observacoes" class="form-control" rows="4" 
                                      placeholder="Informações adicionais sobre a explicação">{{ old('observacoes', $explicacao->observacoes) }}</textarea>
                        </div>

                        <!-- Status atual (só para mostrar) -->
                        <div class="form-group">
                            <label>Status Atual:</label>
                            @php
                                $statusLabels = [
                                    'agendada' => 'Agendada',
                                    'confirmada' => 'Confirmada',
                                    'concluida' => 'Concluída',
                                    'cancelada' => 'Cancelada',
                                ];
                                $statusClasses = [
                                    'agendada' => 'warning',
                                    'confirmada' => 'info',
                                    'concluida' => 'success',
                                    'cancelada' => 'danger',
                                ];
                            @endphp
                            <br>
                            <span class="badge badge-{{ $statusClasses[$explicacao->status] ?? 'secondary' }} p-2">
                                {{ $statusLabels[$explicacao->status] ?? $explicacao->status }}
                            </span>
                        </div>

                        <hr>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Atualizar Explicação
                            </button>
                            <a href="{{ route('explicacoes.show', $explicacao->id) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Ver Detalhes
                            </a>
                            <a href="{{ route('explicacoes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ações Adicionais -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Ações Rápidas</h5>
                </div>
                <div class="card-body text-center">
                    @php
                        $dataHora = strtotime($explicacao->data_explicacao . ' ' . $explicacao->hora_fim);
                        $jaPassou = $dataHora < time();
                        $podeSerCancelada = !$jaPassou && in_array($explicacao->status, ['agendada', 'confirmada']);
                    @endphp

                    <!-- Confirmar -->
                    @if($explicacao->status === 'agendada')
                        <form method="POST" action="{{ route('explicacoes.confirmar', $explicacao->id) }}" 
                              style="display: inline;" class="mr-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('Confirmar esta explicação?')">
                                <i class="fas fa-check"></i> Confirmar
                            </button>
                        </form>
                    @endif

                    <!-- Concluir -->
                    @if($explicacao->status === 'confirmada' && !$jaPassou)
                        <form method="POST" action="{{ route('explicacoes.concluir', $explicacao->id) }}" 
                              style="display: inline;" class="mr-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('Marcar como concluída?')">
                                <i class="fas fa-check-double"></i> Concluir
                            </button>
                        </form>
                    @endif

                    <!-- Cancelar -->
                    @if($podeSerCancelada)
                        <form method="POST" action="{{ route('explicacoes.cancelar', $explicacao->id) }}" 
                              style="display: inline;" class="mr-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning" 
                                    onclick="return confirm('Tem certeza que deseja cancelar esta explicação?')">
                                <i class="fas fa-times"></i> Cancelar
                            </button>
                        </form>
                    @endif

                    <!-- Eliminar -->
                    <form method="POST" action="{{ route('explicacoes.destroy', $explicacao->id) }}" 
                          style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Tem certeza que deseja eliminar esta explicação? Esta ação não pode ser desfeita.')">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Calcular duração em tempo real
document.getElementById('hora_inicio').addEventListener('change', calcularDuracao);
document.getElementById('hora_fim').addEventListener('change', calcularDuracao);

function calcularDuracao() {
    const inicio = document.getElementById('hora_inicio').value;
    const fim = document.getElementById('hora_fim').value;
    
    if (inicio && fim) {
        const inicioTime = new Date('2000-01-01 ' + inicio);
        const fimTime = new Date('2000-01-01 ' + fim);
        
        if (fimTime > inicioTime) {
            const diffMs = fimTime - inicioTime;
            const diffMins = Math.floor(diffMs / 60000);
            
            // Mostrar duração (pode adicionar um elemento para isso)
            console.log('Duração: ' + diffMins + ' minutos');
        }
    }
}

// Validação antes de submeter
document.querySelector('form').addEventListener('submit', function(e) {
    const inicio = document.getElementById('hora_inicio').value;
    const fim = document.getElementById('hora_fim').value;
    
    if (inicio && fim && fim <= inicio) {
        e.preventDefault();
        alert('A hora de fim deve ser posterior à hora de início!');
        return false;
    }
});
</script>
@endsection