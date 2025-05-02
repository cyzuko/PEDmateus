<!-- resources/views/faturas/index.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Faturas</title>
</head>
<body>
    <h1>Minhas Faturas</h1>
    
    @if(session('success'))
        <div style="background-color: #dff0d8; padding: 10px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif
    
    <a href="{{ route('faturas.create') }}">Adicionar Nova Fatura</a>
    
    <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr>
                <th>Fornecedor</th>
                <th>Data</th>
                <th>Valor</th>
                <th>Imagem</th>
            </tr>
        </thead>
        <tbody>
            @forelse($faturas as $fatura)
                <tr>
                    <td>{{ $fatura->fornecedor }}</td>
                    <td>{{ date('d/m/Y', strtotime($fatura->data)) }}</td>
                    <td>R$ {{ number_format($fatura->valor, 2, ',', '.') }}</td>
                    <td>
                        @if($fatura->imagem)
                            <img src="{{ asset('storage/' . $fatura->imagem) }}" width="50">
                        @else
                            Sem imagem
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhuma fatura encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <a href="{{ route('dashboard') }}" style="display: block; margin-top: 20px;">Voltar para Dashboard</a>
</body>
</html>