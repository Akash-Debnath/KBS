<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class CategoriesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Category::all([
            'id',
            'name',
            'created_at',
            'updated_at'
        ]);
    }

    public function headings(): array
    {
        return [
            "ID",
            "Category Name",
            "Created At",
            "Updated At",
        ];
    }
}
