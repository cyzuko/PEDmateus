@component('mail::message')
# @if($acao === 'criada') Nova Explicação Registrada @elseif($acao === 'aprovada') Explicação Aprovada @elseif($acao === 'rejeitada') Explicação Rejeitada @else Explicação Atualizada @endif

Olá!

@if($acao === 'criada')
Uma nova explicação foi registrada no sistema e está pendente de aprovação.
@elseif($acao === 'aprovada')
A explicação foi aprovada pelo administrador e pode ser confirmada.
@elseif($acao === 'rejeitada')
A explicação foi rejeitada pelo administrador.
@else
Uma explicação foi atualizada no sistema.
@endif

## Detalhes da Explicação

**Disciplina:** {{ $explicacao->disciplina }}  
**Professor:** {{ $explicacao->user->name ?? 'N/A' }}  
**Aluno:** {{ $explicacao->nome_aluno }}  
**Data:** {{ \Carbon\Carbon::parse($explicacao->data_explicacao)->format('d/m/Y') }}  
**Horário:** {{ substr($explicacao->hora_inicio, 0, 5) }} às {{ substr($explicacao->hora_fim, 0, 5) }}  
**Local:** {{ $explicacao->local }}  
**Preço:** €{{ number_format($explicacao->preco, 2, ',', '.') }}  
**Status:** {{ ucfirst($explicacao->status) }}  
**Aprovação:** {{ ucfirst($explicacao->aprovacao_admin) }}

@if($acao === 'rejeitada' && $explicacao->motivo_rejeicao)
**Motivo da Rejeição:** {{ $explicacao->motivo_rejeicao }}
@endif

@if($explicacao->observacoes)
**Observações:** {{ $explicacao->observacoes }}
@endif

@component('mail::button', ['url' => route('explicacoes.show', $explicacao->id)])
Ver Explicação
@endcomponent

Obrigado por usar o sistema de Explicações!

Cumprimentos,<br>
{{ config('app.name') }}
@endcomponent