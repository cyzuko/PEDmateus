@extends('layouts.app')

@section('title', 'Estatísticas das Faturas')

@section('content_header')
    <h1>Estatísticas das Faturas</h1>
@stop

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <h3>Resumo Geral</h3>
            <p><strong>Total de Faturas:</strong> {{ $totalFaturas }}</p>
            <p><strong>Valor Total das Faturas:</strong> €{{ number_format($valorTotal, 2, ',', '.') }}</p>
            <p><strong>Valor Médio por Fatura:</strong> €{{ number_format($mediaValor, 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h3>Estatísticas Mensais</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Mês</th>
                        <th>Total de Faturas</th>
                        <th>Valor Total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estatisticasMensais as $estat)
                    <tr>
                        <td>{{ $estat->mes }}</td>
                        <td>{{ $estat->total }}</td>
                        <td>€{{ number_format($estat->total_valor, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h3>Estatísticas por Fornecedor</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fornecedor</th>
                        <th>Total de Faturas</th>
                        <th>Valor Total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($estatisticasFornecedor as $estat)
                    <tr>
                        <td>{{ $estat->fornecedor }}</td>
                        <td>{{ $estat->total }}</td>
                        <td>€{{ number_format($estat->total_valor, 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
