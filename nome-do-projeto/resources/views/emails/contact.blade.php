<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            background: linear-gradient(135deg, #1e88e5 0%, #FFD700 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .field {
            margin-bottom: 20px;
        }
        .field strong {
            color: #1e88e5;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📧 Nova Mensagem de Contacto</h2>
        </div>
        <div class="content">
            <div class="field">
                <strong>Nome:</strong><br>
                {{ $nome }}
            </div>
            
            <div class="field">
                <strong>Email:</strong><br>
                {{ $email }}
            </div>
            
            <div class="field">
                <strong>Mensagem:</strong><br>
                {{ $mensagem }}
            </div>
            
            <hr>
            
            <p style="font-size: 12px; color: #666;">
                <strong>Data:</strong> {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
        <div class="footer">
            <p>Sistema de Gestão EUREKA</p>
        </div>
    </div>
</body>
</html>