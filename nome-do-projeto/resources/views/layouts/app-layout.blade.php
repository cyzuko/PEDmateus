<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login e Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <header class="bg-blue-600 text-white p-4">
            <h1 class="text-center text-xl">Sistema de Faturas</h1>
        </header>

        <main class="flex-1">
            <div class="container mx-auto p-6">
                @yield('content')
            </div>
        </main>

        <footer class="bg-blue-600 text-white p-4 text-center">
            © {{ date('Y') }} Sistema de Faturas
        </footer>
    </div>
    
</body>
</html>
