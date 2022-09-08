<?php


namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;


class FaqsExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    protected $faqDataCollection;

    public function __construct($query = null)
    {
        $this->faqDataCollection = $query;
    }

    public function headings(): array
    {
        return [
            "Id",
            "En Title",
            "En Body",
            "Category Name",
            "Author Name",
            "Status",
            "Tag",
            "Published Date"
        ];
    }


    public function map($faq) : array
    {
        return [
            $faq['id'],
            $faq['en_title'],
            $faq['en_body'],
            $faq['category_name'],
            $faq['author_name'],
            $faq['status'],
            $faq['tag'],
            $faq['publish_date'],
        ];
    }

    public function collection()
    {
        return $this->faqDataCollection;
    }
}