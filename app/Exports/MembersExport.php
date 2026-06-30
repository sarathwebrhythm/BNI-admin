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
            'BNI ID',
            'Name',
            'Email',
            'Phone',
            'Company',
            'Address',
            'Joining Date',
            'Expiry Date',
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
            $member->bni_id,
            $member->name,
            $member->email,
            $member->phone,
            $member->company,
            $member->address,
            $member->joining_date
                ? \Carbon\Carbon::parse($member->joining_date)->format('Y-m-d')
                : '',
            $member->expire_date
                ? \Carbon\Carbon::parse($member->expire_date)->format('Y-m-d')
                : '',
            $member->chapter,
            $member->designation,
            ucfirst($member->status),
            $member->created_at
                ? $member->created_at->format('Y-m-d H:i:s')
                : '',
        ];
    }
}
