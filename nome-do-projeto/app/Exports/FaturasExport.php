<?php

namespace App\Exports;

use App\Models\Fatura;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection; 
use Maatwebsite\Excel\Concerns\WithHeadings; 

class FaturasExport implements FromCollection, WithHeadings
{
    /**
     * Retorna a coleção de dados para exportação
     */
    public function collection()
    {
        // Busca todas as faturas do usuário autenticado3
        return Fatura::where('user_id', Auth::id())
            ->select('fornecedor', 'nif', 'data', 'valor')
            ->get();
    }

    /**
     * Cabeçalhos das colunas no Excel
     */
    public function headings(): array
    {
        return [
            'Fornecedor',
            'NIF',
            'Data',
            'Valor',
        ];
    }
}
