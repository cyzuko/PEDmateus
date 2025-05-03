@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <h2>Bem-vindo, {{ Auth::user()->name }}!</h2>
                    
                    <div class="my-4">
                        <h4>Suas últimas faturas</h4>
                        
                        @if($faturas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Fornecedor</th>
                                            <th>Data</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($faturas as $fatura)
                                            <tr>
                                                <td>{{ $fatura->fornecedor }}</td>
                                                <td>{{ $fatura->data->format('d/m/Y') }}</td>
                                                <td>€{{ number_format($fatura->valor, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <a href="{{ route('faturas.index') }}" class="btn btn-primary">Ver todas as faturas</a>
                        @else
                            <p>Você ainda não tem faturas registradas.</p>
                            <a href="{{ route('faturas.create') }}" class="btn btn-success">Adicionar sua primeira fatura</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection