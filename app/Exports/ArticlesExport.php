<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ArticlesExport implements FromCollection, WithHeadings,WithMapping
{
    /**
<<<<<<< HEAD
    * @return \Illuminate\Support\Collection
    */
//    public function collection()
//    {
//        return Article::all([
//            'id',
//            'en_title',
//            'created_at',
//            'updated_at'
//        ]);
//    }
    use Exportable;

    protected $articleDataCollection;

    public function __construct($query = null)
    {
        $this->articleDataCollection = $query;
    }

    public function map($article) : array
    {
        return [
            $article['id'],
            $article['en_title'],
            $article['en_body'],
            $article['category_name'],
            $article['author_name'],
            $article['status'],
            $article['tag'],
            $article['publish_date'],
        ];
    }

    public function headings(): array
    {
        return [
            "ID",
            "Title",
            "Body",
            "Category Name",
            "Author Name",
            "Status",
            "Tag",
            "Publish At",
        ];
    }

    public function collection()
    {
        return $this->articleDataCollection;
    }
}
