<?php

namespace App\Exports;

use App\Models\Fatura;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FaturasExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Fatura::select('fornecedor', 'nif', 'data', 'valor')->get();
    }

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
