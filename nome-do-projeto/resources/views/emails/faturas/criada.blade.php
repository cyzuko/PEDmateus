@component('mail::message')
# Nova Fatura Criada

Uma nova fatura foi registrada com os seguintes dados:

- **Fornecedor:** {{ $fatura->fornecedor }}
- **NIF:** {{ $fatura->nif ?? 'N/A' }}
- **Data:** {{ $fatura->data->format('d/m/Y') }}
- **Valor:** €{{ number_format($fatura->valor, 2) }}

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
