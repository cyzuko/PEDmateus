@extends('layouts.app')

@section('title', 'Disponibilidade de Horários')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-clock mr-2"></i>
                        Disponibilidade de Horários
                        @if(isset($modoVisualizacao) && $modoVisualizacao === 'admin')
                            <span class="badge badge-danger ml-2">Modo Admin</span>
                        @endif
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('explicacoes.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Nova Explicação
                        </a>
                        <a href="{{ route('explicacoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Minhas Explicações
                        </a>
                        <a href="{{ route('explicacoes.calendario') }}" class="btn btn-info">
                            <i class="fas fa-calendar"></i> Calendário
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Info sobre visualização -->
                  @if(auth()->user()->role === 'admin')
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Modo Administrador:</strong> Você está a ver TODAS as explicações de todos os alunos.
                        </div>
                    @else
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle"></i> 
                            Visualização <strong>as suas explicações confirmadas</strong> + <strong>explicações confirmadas de outros alunos</strong>.
                            <br>
                            <i class="fas fa-check-circle text-success"></i> <strong>Horário disponível para novas explicações: 14:00 - 18:00</strong>
                        </div>
                    @endif

                    <!-- NOVO: Seletor de Disciplina -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body py-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <label class="mb-0"><i class="fas fa-book"></i> <strong>Selecione a Disciplina:</strong></label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control form-control-lg" id="disciplinaSelecionada" onchange="filtrarPorDisciplina()">
                                                <option value="Matemática" selected>📐 Matemática</option>
                                                <option value="Física">🔬 Física</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                          
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros de semana -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="semanaInicio">Semana:</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="semanaInicio" 
                                       value="{{ request('semana', date('Y-m-d', strtotime('monday this week'))) }}">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" onclick="semanaAnterior()">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary" onclick="proximaSemana()">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="semanaAtual()">
                                        Hoje
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="horaInicio">Horário de início:</label>
                            <select class="form-control" id="horaInicio">
                                <option value="08:00">08:00</option>
                                <option value="09:00" selected>09:00</option>
                                <option value="10:00">10:00</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="horaFim">Horário de fim:</label>
                            <select class="form-control" id="horaFim">
                                <option value="18:00">18:00</option>
                                <option value="19:00">19:00</option>
                                <option value="20:00" selected>20:00</option>
                                <option value="21:00">21:00</option>
                            </select>
                        </div>
                    </div>

                    <!-- Grade de horários semanal -->
                    <div class="table-responsive">
                        <table class="table table-bordered horarios-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="10%">Horário</th>
                                    @php
                                        $diasSemana = [
                                            'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'
                                        ];
                                        $semanaInicio = request('semana', date('Y-m-d', strtotime('monday this week')));
                                        $userLogado = auth()->user();
                                        
                                        // Definir horário disponível
                                        $horaDisponivelInicio = 14;
                                        $horaDisponivelFim = 18;
                                    @endphp
                                    @for($i = 0; $i < 7; $i++)
                                        @php
                                            $dataAtual = date('Y-m-d', strtotime($semanaInicio . " +{$i} days"));
                                            $isHoje = $dataAtual == date('Y-m-d');
                                        @endphp
                                        <th width="12.8%" class="{{ $isHoje ? 'bg-primary text-white' : '' }}">
                                            {{ $diasSemana[$i] }}
                                            <br>
                                            <small>{{ date('d/m', strtotime($dataAtual)) }}</small>
                                            @if($isHoje)
                                                <br><span class="badge badge-light">Hoje</span>
                                            @endif
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $horaInicio = 9; // 09:00
                                    $horaFim = 20; // 20:00
                                @endphp
                                
                                @for($hora = $horaInicio; $hora <= $horaFim; $hora++)
                                    <tr>
                                        <td class="horario-coluna">
                                            <strong>{{ sprintf('%02d:00', $hora) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ sprintf('%02d:30', $hora) }}</small>
                                        </td>
                                        
                                        @for($dia = 0; $dia < 7; $dia++)
                                            @php
                                                $dataSlot = date('Y-m-d', strtotime($semanaInicio . " +{$dia} days"));
                                                $horaSlot = sprintf('%02d:00', $hora);
                                                $horaSlot30 = sprintf('%02d:30', $hora);
                                                
                                                // Verificar explicações neste slot (TODAS as disciplinas)
                                                $todasExplicacoesSlot = $explicacoes->filter(function($exp) use ($dataSlot, $horaSlot, $horaSlot30) {
                                                    if ($exp->data_explicacao != $dataSlot) return false;
                                                    $inicio = strtotime($exp->hora_inicio);
                                                    $fim = strtotime($exp->hora_fim);
                                                    $slotInicio = strtotime($horaSlot);
                                                    $slotFim = strtotime($horaSlot30);
                                                    return ($inicio < $slotFim) && ($fim > $slotInicio);
                                                });
                                                
                                                // Separar por disciplina
                                                $explicacoesMatematica = $todasExplicacoesSlot->where('disciplina', 'Matemática');
                                                $explicacoesFisica = $todasExplicacoesSlot->where('disciplina', 'Física');
                                                
                                                // Contar vagas por disciplina
                                                $vagasMatematica = 4 - $explicacoesMatematica->count();
                                                $vagasFisica = 4 - $explicacoesFisica->count();
                                                
                                                $isPassado = strtotime($dataSlot . ' ' . $horaSlot) < time();
                                                $isHoje = $dataSlot == date('Y-m-d');
                                                
                                                // Verificar se está no horário disponível (14:00 - 18:00)
                                                $isHorarioDisponivel = ($hora >= $horaDisponivelInicio && $hora <= $horaDisponivelFim);
                                            @endphp
                                            
                                            <td class="horario-slot {{ $isPassado ? 'slot-passado' : '' }} {{ $isHoje ? 'slot-hoje' : '' }} {{ !$isHorarioDisponivel ? 'slot-indisponivel' : '' }}" 
                                                data-data="{{ $dataSlot }}" 
                                                data-hora="{{ $horaSlot }}"
                                                data-vagas-matematica="{{ $vagasMatematica }}"
                                                data-vagas-fisica="{{ $vagasFisica }}"
                                                style="height: 60px; position: relative; cursor: pointer;">
                                                
                                                {{-- Container para Matemática --}}
                                                <div class="disciplina-container" data-disciplina="Matemática">
                                                    @if($explicacoesMatematica->count() > 0)
                                                        @foreach($explicacoesMatematica as $explicacao)
                                                            @php
                                                                $ehMinhaExplicacao = $explicacao->user_id == $userLogado->id;
                                                                $alunoNome = $explicacao->nome_aluno ?? 'Aluno';
                                                                
                                                                $statusClasses = [
                                                                    'confirmada' => $ehMinhaExplicacao ? 'info' : 'secondary',
                                                                    'concluida' => $ehMinhaExplicacao ? 'success' : 'dark',
                                                                ];
                                                                
                                                                $borderClass = $ehMinhaExplicacao ? 'border-own-slot' : 'border-other-slot';
                                                                
                                                                $tooltipText = $explicacao->disciplina . ' - ' . $alunoNome . 
                                                                              ' (' . $explicacao->hora_inicio . '-' . $explicacao->hora_fim . ')' .
                                                                              ($ehMinhaExplicacao ? ' [MINHA]' : '');
                                                            @endphp
                                                            <div class="explicacao-slot mb-1 {{ $borderClass }}">
                                                                <a href="{{ route('explicacoes.show', $explicacao->id) }}" 
                                                                   class="btn btn-{{ $statusClasses[$explicacao->status] ?? 'secondary' }} btn-sm btn-block text-truncate"
                                                                   title="{{ $tooltipText }}">
                                                                    <small>
                                                                        @if(!$ehMinhaExplicacao)
                                                                            <i class="fas fa-user-friends" style="font-size: 0.7em;"></i>
                                                                        @endif
                                                                        <strong>{{ substr($explicacao->hora_inicio, 0, 5) }}</strong><br>
                                                                        📐 S1 {{ substr($alunoNome, 0, 6) }}
                                                                    </small>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                        
                                                        @if($isHorarioDisponivel && !$isPassado && $vagasMatematica > 0)
                                                            <div class="slot-vagas" onclick="criarExplicacao('{{ $dataSlot }}', '{{ $horaSlot }}', 'Matemática')">
                                                                <span class="slot-vagas-texto">
                                                                    <i class="fas fa-plus-circle"></i>
                                                                    <small>📐 {{ $vagasMatematica }} vaga{{ $vagasMatematica > 1 ? 's' : '' }}</small>
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @else
                                                        @if($isHorarioDisponivel && !$isPassado)
                                                            <div class="slot-livre" onclick="criarExplicacao('{{ $dataSlot }}', '{{ $horaSlot }}', 'Matemática')">
                                                                <span class="slot-livre-texto">
                                                                    <i class="fas fa-plus"></i>
                                                                    <br><small>📐 4 vagas</small>
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                
                                                {{-- Container para Física --}}
                                                <div class="disciplina-container" data-disciplina="Física" style="display: none;">
                                                    @if($explicacoesFisica->count() > 0)
                                                        @foreach($explicacoesFisica as $explicacao)
                                                            @php
                                                                $ehMinhaExplicacao = $explicacao->user_id == $userLogado->id;
                                                                $alunoNome = $explicacao->nome_aluno ?? 'Aluno';
                                                                
                                                                $statusClasses = [
                                                                    'confirmada' => $ehMinhaExplicacao ? 'info' : 'secondary',
                                                                    'concluida' => $ehMinhaExplicacao ? 'success' : 'dark',
                                                                ];
                                                                
                                                                $borderClass = $ehMinhaExplicacao ? 'border-own-slot' : 'border-other-slot';
                                                                
                                                                $tooltipText = $explicacao->disciplina . ' - ' . $alunoNome . 
                                                                              ' (' . $explicacao->hora_inicio . '-' . $explicacao->hora_fim . ')' .
                                                                              ($ehMinhaExplicacao ? ' [MINHA]' : '');
                                                            @endphp
                                                            <div class="explicacao-slot mb-1 {{ $borderClass }}">
                                                                <a href="{{ route('explicacoes.show', $explicacao->id) }}" 
                                                                   class="btn btn-{{ $statusClasses[$explicacao->status] ?? 'secondary' }} btn-sm btn-block text-truncate"
                                                                   title="{{ $tooltipText }}">
                                                                    <small>
                                                                        @if(!$ehMinhaExplicacao)
                                                                            <i class="fas fa-user-friends" style="font-size: 0.7em;"></i>
                                                                        @endif
                                                                        <strong>{{ substr($explicacao->hora_inicio, 0, 5) }}</strong><br>
                                                                        🔬 S2 {{ substr($alunoNome, 0, 6) }}
                                                                    </small>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                        
                                                        @if($isHorarioDisponivel && !$isPassado && $vagasFisica > 0)
                                                            <div class="slot-vagas" onclick="criarExplicacao('{{ $dataSlot }}', '{{ $horaSlot }}', 'Física')">
                                                                <span class="slot-vagas-texto">
                                                                    <i class="fas fa-plus-circle"></i>
                                                                    <small>🔬 {{ $vagasFisica }} vaga{{ $vagasFisica > 1 ? 's' : '' }}</small>
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @else
                                                        @if($isHorarioDisponivel && !$isPassado)
                                                            <div class="slot-livre" onclick="criarExplicacao('{{ $dataSlot }}', '{{ $horaSlot }}', 'Física')">
                                                                <span class="slot-livre-texto">
                                                                    <i class="fas fa-plus"></i>
                                                                    <br><small>🔬 4 vagas</small>
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                
                                                {{-- Slot indisponível ou passado (mostra em ambas disciplinas) --}}
                                                @if(!$isHorarioDisponivel || $isPassado)
                                                    <div class="disciplina-container slot-global" data-disciplina="Matemática">
                                                        @if(!$isHorarioDisponivel)
                                                            <div class="slot-bloqueado">
                                                                <span class="slot-bloqueado-texto">
                                                                    <i class="fas fa-lock"></i>
                                                                    <br><small>Indisponível</small>
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div class="slot-passado-texto">
                                                                <small class="text-muted">-</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="disciplina-container slot-global" data-disciplina="Física" style="display: none;">
                                                        @if(!$isHorarioDisponivel)
                                                            <div class="slot-bloqueado">
                                                                <span class="slot-bloqueado-texto">
                                                                    <i class="fas fa-lock"></i>
                                                                    <br><small>Indisponível</small>
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div class="slot-passado-texto">
                                                                <small class="text-muted">-</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <!-- Legenda -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <h6>Legenda de Status:</h6>
                            <span class="badge badge-info mr-2">Confirmada</span>
                            <span class="badge badge-success mr-2">Concluída</span>
                        </div>
                        <div class="col-md-4">
                            <h6>Disponibilidade:</h6>
                            <span class="badge badge-light mr-2"><i class="fas fa-plus"></i> Horário Livre</span>
                            <span class="badge badge-warning mr-2"><i class="fas fa-lock"></i> Indisponível</span>
                            <span class="badge badge-secondary mr-2">Horário Passado</span>
                            <span class="badge badge-primary mr-2">Hoje</span>
                        </div>
                        <div class="col-md-4">
                            <h6>Propriedade:</h6>
                            <span class="badge badge-info mr-2 border-own-demo-slot">As Minhas Explicações</span>
                            <span class="badge badge-secondary mr-2 border-other-demo-slot"><i class="fas fa-user-friends"></i> Outros Alunos</span>
                        </div>
                    </div>

                    <!-- Resumo da semana -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Esta Semana</span>
                                    <span class="info-box-number">
                                        @php
                                            $inicioSemana = date('Y-m-d', strtotime($semanaInicio));
                                            $fimSemana = date('Y-m-d', strtotime($semanaInicio . ' +6 days'));
                                            $explicacoesSemana = $explicacoes->whereBetween('data_explicacao', [$inicioSemana, $fimSemana]);
                                            $minhasExplicacoes = $explicacoesSemana->where('user_id', $userLogado->id);
                                        @endphp
                                        {{ $explicacoesSemana->count() }} ({{ $minhasExplicacoes->count() }} minhas)
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">As Minhas Horas</span>
                                    <span class="info-box-number">
                                        @php
                                            $totalHoras = 0;
                                            foreach($minhasExplicacoes as $exp) {
                                                $inicio = strtotime($exp->hora_inicio);
                                                $fim = strtotime($exp->hora_fim);
                                                $totalHoras += ($fim - $inicio) / 3600;
                                            }
                                        @endphp
                                        {{ number_format($totalHoras, 1) }}h
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-euro-sign"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">A Minha Receita</span>
                                    <span class="info-box-number">
                                        €{{ number_format($minhasExplicacoes->sum('preco'), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-primary">
                                <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Taxa Ocupação</span>
                                    <span class="info-box-number">
                                        @php
                                            $totalSlots = 7 * (20 - 9 + 1); // 7 dias x horários disponíveis
                                            $slotsOcupados = $explicacoesSemana->count();
                                            $taxaOcupacao = $totalSlots > 0 ? ($slotsOcupados / $totalSlots) * 100 : 0;
                                        @endphp
                                        {{ number_format($taxaOcupacao, 1) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para criação rápida -->
<div class="modal fade" id="modalCriarExplicacao" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle"></i> Nova Explicação
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCriarExplicacao" method="POST" action="{{ route('explicacoes.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-calendar"></i> Data:</label>
                                <input type="date" class="form-control" id="modalData" name="data_explicacao" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="fas fa-clock"></i> Hora de início:</label>
                                <input type="time" class="form-control" id="modalHoraInicio" name="hora_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="fas fa-clock"></i> Hora de fim:</label>
                                <input type="time" class="form-control" id="modalHoraFim" name="hora_fim" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-book"></i> Disciplina: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modalDisciplina" name="disciplina" placeholder="Ex: Matemática, Física..." required readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Nome do Aluno: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nome_aluno" placeholder="Nome completo do aluno" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-map-marker-alt"></i> Local: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="modalLocal" name="local" placeholder="Sala será preenchida automaticamente" required readonly>
                                <small class="text-muted">📐 Matemática = Sala 1 | 🔬 Física = Sala 2</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-euro-sign"></i> Preço (€): <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control" name="preco" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Contacto do Aluno: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="contacto_aluno" placeholder="Telemóvel ou email" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-sticky-note"></i> Observações:</label>
                        <textarea class="form-control" name="observacoes" rows="3" placeholder="Observações adicionais (opcional)..."></textarea>
                    </div>

                    <!-- Opções de notificação -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-bell"></i> Notificações por Email</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="enviarEmailAdmin" name="enviar_email_admin" value="1">
                                        <label class="form-check-label" for="enviarEmailAdmin">
                                            <i class="fas fa-user-shield"></i> Notificar administrador
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="enviarEmailAluno" name="enviar_email_aluno" value="1">
                                        <label class="form-check-label" for="enviarEmailAluno">
                                            <i class="fas fa-user-graduate"></i> Notificar aluno
                                        </label>
                                        <small class="text-muted d-block">Apenas se o contacto for um email</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Criar Explicação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Atualizar quando mudar a semana
    $('#semanaInicio').on('change', function() {
        var semana = $(this).val();
        window.location.href = '{{ route("explicacoes.disponibilidade") }}?semana=' + semana;
    });

    // Validação do formulário
    $('#formCriarExplicacao').on('submit', function(e) {
        var horaInicio = $('#modalHoraInicio').val();
        var horaFim = $('#modalHoraFim').val();
        
        if (horaInicio && horaFim && horaInicio >= horaFim) {
            e.preventDefault();
            alert('A hora de fim deve ser posterior à hora de início!');
            return false;
        }
        
        // Mostrar loading
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Criando...');
    });

    // Reset do modal quando fechar
    $('#modalCriarExplicacao').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('button[type="submit"]').prop('disabled', false).html('<i class="fas fa-save"></i> Criar Explicação');
    });
});

function semanaAnterior() {
    var dataAtual = new Date(document.getElementById('semanaInicio').value);
    dataAtual.setDate(dataAtual.getDate() - 7);
    var novaData = dataAtual.toISOString().split('T')[0];
    window.location.href = '{{ route("explicacoes.disponibilidade") }}?semana=' + novaData;
}

function proximaSemana() {
    var dataAtual = new Date(document.getElementById('semanaInicio').value);
    dataAtual.setDate(dataAtual.getDate() + 7);
    var novaData = dataAtual.toISOString().split('T')[0];
    window.location.href = '{{ route("explicacoes.disponibilidade") }}?semana=' + novaData;
}

function semanaAtual() {
    var hoje = new Date();
    var segundaFeira = new Date(hoje.setDate(hoje.getDate() - hoje.getDay() + 1));
    var novaData = segundaFeira.toISOString().split('T')[0];
    window.location.href = '{{ route("explicacoes.disponibilidade") }}?semana=' + novaData;
}

function criarExplicacao(data, hora, disciplina) {
    // Se disciplina não foi passada, pegar do dropdown
    if (!disciplina) {
        disciplina = document.getElementById('disciplinaSelecionada').value;
    }
    
    // Definir o local baseado na disciplina
    var local = '';
    if (disciplina === 'Matemática') {
        local = 'Sala 1';
    } else if (disciplina === 'Física') {
        local = 'Sala 2';
    }
    
    // Preencher dados do modal
    document.getElementById('modalData').value = data;
    document.getElementById('modalHoraInicio').value = hora;
    document.getElementById('modalDisciplina').value = disciplina;
    document.getElementById('modalLocal').value = local;
    
    // Sugerir hora de fim (1 hora depois)
    var horaInicio = new Date('2000-01-01 ' + hora);
    horaInicio.setHours(horaInicio.getHours() + 1);
    var horaFimSugerida = horaInicio.toTimeString().slice(0, 5);
    document.getElementById('modalHoraFim').value = horaFimSugerida;
    
    // Limpar outros campos
    document.querySelector('input[name="nome_aluno"]').value = '';
    document.querySelector('input[name="preco"]').value = '';
    document.querySelector('input[name="contacto_aluno"]').value = '';
    document.querySelector('textarea[name="observacoes"]').value = '';
    
    // Desmarcar checkboxes
    document.getElementById('enviarEmailAdmin').checked = false;
    document.getElementById('enviarEmailAluno').checked = false;
    
    // Mostrar modal
    $('#modalCriarExplicacao').modal('show');
    
    // Focar no primeiro campo editável
    setTimeout(function() {
        document.querySelector('input[name="nome_aluno"]').focus();
    }, 500);
}

// NOVA FUNÇÃO: Filtrar horários por disciplina (VERSÃO CORRIGIDA)
function filtrarPorDisciplina() {
    var disciplinaSelecionada = document.getElementById('disciplinaSelecionada').value;
    
    // Esconder todos os containers de disciplina
    $('.disciplina-container').hide();
    
    // Mostrar apenas os containers da disciplina selecionada
    $('.disciplina-container[data-disciplina="' + disciplinaSelecionada + '"]').show();
    
    console.log('Filtrando por disciplina:', disciplinaSelecionada);
}

// Executar filtro ao carregar a página
$(document).ready(function() {
    filtrarPorDisciplina();
});

// Hover effects nos slots
$(document).on('mouseenter', '.slot-livre', function() {
    $(this).addClass('slot-hover');
});

$(document).on('mouseleave', '.slot-livre', function() {
    $(this).removeClass('slot-hover');
});

// Tooltips para explicações
$(document).ready(function() {
    $('[title]').tooltip();
});
</script>

<style>
.horarios-table {
    font-size: 0.9em;
}

.horario-coluna {
    background-color: #f8f9fa;
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
}

.horario-slot {
    padding: 2px;
    position: relative;
}

.disciplina-container {
    width: 100%;
}

.slot-vagas {
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed #28a745;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #28a745;
    background-color: rgba(40, 167, 69, 0.05);
    padding: 3px;
    margin-top: 2px;
}

.slot-vagas:hover {
    border-color: #218838;
    background-color: rgba(40, 167, 69, 0.15);
    color: #218838;
    transform: scale(1.02);
}

.slot-vagas-texto {
    text-align: center;
    font-size: 0.75em;
}

.slot-livre {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed #dee2e6;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #6c757d;
}

.slot-livre:hover,
.slot-hover {
    border-color: #28a745;
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
    transform: scale(1.02);
}

.slot-livre-texto {
    text-align: center;
}

.slot-bloqueado {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #ffc107;
    border-radius: 5px;
    background-color: rgba(255, 193, 7, 0.1);
    cursor: not-allowed;
    color: #856404;
}

.slot-bloqueado-texto {
    text-align: center;
    opacity: 0.7;
}

.slot-indisponivel {
    background-color: #fff9e6;
}

.slot-passado {
    background-color: #f8f9fa;
    opacity: 0.6;
}

.slot-passado-texto {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.slot-hoje {
    background-color: rgba(0, 123, 255, 0.05);
    box-shadow: inset 0 0 0 1px rgba(0, 123, 255, 0.2);
}

.explicacao-slot .btn {
    font-size: 0.7em;
    padding: 2px 4px;
    line-height: 1.1;
    margin: 1px 0;
}

/* Bordas para diferenciar explicações próprias e de outros */
.border-own-slot {
    border-left: 3px solid #007bff !important;
    padding-left: 2px;
}

.border-other-slot {
    border-left: 3px solid #6c757d !important;
    padding-left: 2px;
}

/* Para demonstração na legenda */
.border-own-demo-slot {
    border-left: 3px solid #007bff !important;
    padding-left: 8px;
}

.border-other-demo-slot {
    border-left: 3px solid #6c757d !important;
    padding-left: 8px;
}

.info-box {
    border-radius: 10px;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    margin-bottom: 1rem;
    color: white;
}

.info-box-icon {
    border-radius: 10px 0 0 10px;
    width: 70px;
    height: 70px;
    float: left;
    text-align: center;
    line-height: 70px;
    background-color: rgba(0,0,0,.1);
}

.info-box-content {
    margin-left: 70px;
    padding: 5px 10px;
}

.info-box-text {
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;
}

.info-box-number {
    display: block;
    font-size: 18px;
    font-weight: bold;
}

/* Modal melhorado */
.modal-lg {
    max-width: 800px;
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.form-group label {
    font-weight: 600;
    color: #495057;
}

.text-danger {
    color: #dc3545 !important;
}

/* Responsivo */
@media (max-width: 768px) {
    .horarios-table {
        font-size: 0.7em;
    }
    
    .horario-slot {
        height: 50px !important;
    }
    
    .explicacao-slot .btn {
        font-size: 0.6em;
        padding: 1px 2px;
    }
    
    .modal-dialog {
        margin: 10px;
    }
}
</style>
@endsection