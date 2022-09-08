<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;


class UsersExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    protected $userDataCollection;

    public function __construct($query = null)
    {
        $this->userDataCollection = $query;
    }

    public function headings(): array
    {
        return [
            "id",
            "Username",
            "First Name",
            "Last Name",
            "Email",
            "Role Name",
            "Created At",
            "Updated At"
        ];
    }

    public function map($user) : array
    {
        return [
            $user['id'],
            $user['username'],
            $user['first_name'],
            $user['last_name'],
            $user['email'],
            $user['role_name'],
            $user['created_at'],
            $user['updated_at'],
        ];
    }

    public function collection()
    {
        return $this->userDataCollection;
    }
}
