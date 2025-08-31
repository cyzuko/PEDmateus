@extends('layouts.app')

@section('title', 'Calendário de Explicações')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>
                        <i class="fas fa-calendar mr-2"></i>
                        Calendário de Explicações
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('explicacoes.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Nova Explicação
                        </a>
                        <a href="{{ route('explicacoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Lista
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros rápidos -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label>Mês:</label>
                            <select class="form-control" id="filtroMes">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Ano:</label>
                            <select class="form-control" id="filtroAno">
                                @for($ano = date('Y') - 1; $ano <= date('Y') + 1; $ano++)
                                    <option value="{{ $ano }}" {{ date('Y') == $ano ? 'selected' : '' }}>
                                        {{ $ano }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Vista mensal simplificada -->
                    <div class="table-responsive">
                        <table class="table table-bordered calendario-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Dom</th>
                                    <th>Seg</th>
                                    <th>Ter</th>
                                    <th>Qua</th>
                                    <th>Qui</th>
                                    <th>Sex</th>
                                    <th>Sáb</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $mes = request('mes', date('n'));
                                    $ano = request('ano', date('Y'));
                                    $primeiroDia = date('w', mktime(0, 0, 0, $mes, 1, $ano));
                                    $diasNoMes = date('t', mktime(0, 0, 0, $mes, 1, $ano));
                                    $diaAtual = 1;
                                    $semanas = ceil(($diasNoMes + $primeiroDia) / 7);
                                @endphp

                                @for($semana = 0; $semana < $semanas; $semana++)
                                    <tr>
                                        @for($diaSemana = 0; $diaSemana < 7; $diaSemana++)
                                            <td class="calendario-dia" style="height: 120px; vertical-align: top;">
                                                @if(($semana == 0 && $diaSemana >= $primeiroDia) || ($semana > 0 && $diaAtual <= $diasNoMes))
                                                    @if($diaAtual <= $diasNoMes)
                                                        <div class="dia-numero">
                                                            <strong>{{ $diaAtual }}</strong>
                                                            @if($diaAtual == date('j') && $mes == date('n') && $ano == date('Y'))
                                                                <span class="badge badge-primary badge-sm">Hoje</span>
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Explicações do dia -->
                                                        @php
                                                            $dataCompleta = sprintf('%04d-%02d-%02d', $ano, $mes, $diaAtual);
                                                            $explicacoesDia = $explicacoes->where('data_explicacao', $dataCompleta);
                                                        @endphp

                                                        @foreach($explicacoesDia as $explicacao)
                                                            @php
                                                                $statusClasses = [
                                                                    'agendada' => 'warning',
                                                                    'confirmada' => 'info',
                                                                    'concluida' => 'success',
                                                                    'cancelada' => 'danger',
                                                                ];
                                                            @endphp
                                                            <div class="explicacao-item mb-1">
                                                                <a href="{{ route('explicacoes.show', $explicacao->id) }}" 
                                                                   class="btn btn-{{ $statusClasses[$explicacao->status] ?? 'secondary' }} btn-sm btn-block text-truncate" 
                                                                   title="{{ $explicacao->disciplina }} - {{ $explicacao->nome_aluno }} ({{ $explicacao->hora_inicio }})">
                                                                    {{ substr($explicacao->disciplina, 0, 10) }}{{ strlen($explicacao->disciplina) > 10 ? '...' : '' }}
                                                                    <br><small>{{ $explicacao->hora_inicio }}</small>
                                                                </a>
                                                            </div>
                                                        @endforeach

                                                        @php $diaAtual++; @endphp
                                                    @endif
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <!-- Legenda -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Legenda:</h6>
                            <span class="badge badge-warning mr-2">Agendada</span>
                            <span class="badge badge-info mr-2">Confirmada</span>
                            <span class="badge badge-success mr-2">Concluída</span>
                            <span class="badge badge-danger mr-2">Cancelada</span>
                        </div>
                    </div>

                    <!-- Resumo do mês -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $explicacoes->where('status', 'agendada')->count() }}</h4>
                                    <p class="mb-0">Agendadas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $explicacoes->where('status', 'confirmada')->count() }}</h4>
                                    <p class="mb-0">Confirmadas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $explicacoes->where('status', 'concluida')->count() }}</h4>
                                    <p class="mb-0">Concluídas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $explicacoes->where('status', 'cancelada')->count() }}</h4>
                                    <p class="mb-0">Canceladas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Atualizar calendário quando alterar filtros
    $('#filtroMes, #filtroAno').on('change', function() {
        var mes = $('#filtroMes').val();
        var ano = $('#filtroAno').val();
        
        window.location.href = '{{ route("explicacoes.calendario") }}?mes=' + mes + '&ano=' + ano;
    });

    // Tooltip para explicações truncadas
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

<style>
.calendario-table {
    font-size: 0.9em;
}

.calendario-dia {
    position: relative;
    padding: 5px;
}

.dia-numero {
    margin-bottom: 5px;
}

.explicacao-item .btn {
    font-size: 0.75em;
    padding: 2px 5px;
    line-height: 1.2;
}

.explicacao-item .btn small {
    font-size: 0.8em;
}

.badge-sm {
    font-size: 0.65em;
}

.calendario-dia:hover {
    background-color: #f8f9fa;
}

@media (max-width: 768px) {
    .calendario-table {
        font-size: 0.8em;
    }
    
    .calendario-dia {
        height: 100px !important;
    }
    
    .explicacao-item .btn {
        font-size: 0.7em;
        padding: 1px 3px;
    }
}
</style>
@endsection