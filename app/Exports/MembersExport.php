<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class MembersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $members;

    public function __construct(Collection $members)
    {
        $this->members = $members;
    }

    public function collection()
    {
        return $this->members;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone',
            'Company',
            'Chapter',
            'Designation',
            'Status',
            'Created At',
        ];
    }

    public function map($member): array
    {
        return [
            $member->id,
            $member->name,
            $member->email,
            $member->phone,
            $member->company,
            $member->chapter,
            $member->designation,
            ucfirst($member->status),
            $member->created_at ? $member->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}