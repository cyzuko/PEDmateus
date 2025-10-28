DB::table('disciplinas')->insert([
    [
        'nome' => 'Matemática',
        'disciplina' => 'Matemática',
        'emoji' => '📐',
        'capacidade' => 4,
        'hora_inicio' => '14:00',
        'hora_fim' => '18:00',
        'cor_badge' => '#007bff',
        'ativa' => true,
        'ordem' => 1
    ],
    [
        'nome' => 'Física',
        'disciplina' => 'Física',
        'emoji' => '🔬',
        'capacidade' => 4,
        'hora_inicio' => '14:00',
        'hora_fim' => '18:00',
        'cor_badge' => '#28a745',
        'ativa' => true,
        'ordem' => 2
    ]
]);