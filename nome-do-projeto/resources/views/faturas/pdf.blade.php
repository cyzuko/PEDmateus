<!DOCTYPE html>
<html>
<head>
    <title>Faturas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h3>Faturas de {{ auth()->user()->name }}</h3>
    <table>
        <thead>
            <tr>
                <th>Fornecedor</th>
                <th>NIF</th>
                <th>Data</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($faturas as $fatura)
                <tr>
                    <td>{{ $fatura->fornecedor }}</td>
                    <td>{{ $fatura->nif ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($fatura->data)->format('d/m/Y') }}</td>
                    <td>€{{ number_format($fatura->valor, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
