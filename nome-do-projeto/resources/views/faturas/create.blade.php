<!-- resources/views/faturas/create.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Fatura</title>
</head>
<body>
    <h1>Adicionar Nova Fatura</h1>
    
    @if($errors->any())
        <div style="background-color: #f8d7da; padding: 10px; margin-bottom: 15px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('faturas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="margin-bottom: 15px;">
            <label for="fornecedor">Fornecedor:</label>
            <input type="text" name="fornecedor" value="{{ old('fornecedor') }}" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="data">Data:</label>
            <input type="date" name="data" value="{{ old('data') }}" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="valor">Valor:</label>
            <input type="number" name="valor" step="0.01" value="{{ old('valor') }}" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="imagem">Imagem da Fatura:</label>
            <input type="file" name="imagem">
        </div>
        
        <button type="submit">Salvar Fatura</button>
    </form>
    
    <a href="{{ route('faturas.index') }}" style="display: block; margin-top: 20px;">Voltar para Lista</a>
</body>
