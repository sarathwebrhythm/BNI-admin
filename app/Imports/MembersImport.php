<?php

namespace App\Imports;

use App\Models\Member;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class MembersImport implements ToCollection, WithHeadingRow
{
    public $totalCount = 0;
    public $importedCount = 0;
    public $skippedCount = 0;

    protected $processedEmails = [];

    public function collection(Collection $rows)
    {
        $this->totalCount = $rows->count();

        foreach ($rows as $row) {
            // Trim heading row keys and values
            $data = [
                'name' => isset($row['name']) ? trim($row['name']) : null,
                'email' => isset($row['email']) ? trim($row['email']) : null,
                'phone' => isset($row['phone']) ? trim($row['phone']) : null,
                'joining_date' => isset($row['joining_date']) ? trim($row['joining_date']) : null,
                'company' => isset($row['company']) ? trim($row['company']) : null,
                'chapter' => isset($row['chapter']) ? trim($row['chapter']) : null,
                'designation' => isset($row['designation']) ? trim($row['designation']) : null,
                'status' => isset($row['status']) ? strtolower(trim($row['status'])) : 'active',
            ];

            // Normalize status to 'active' or 'inactive'
            if (!in_array($data['status'], ['active', 'inactive'])) {
                $data['status'] = 'active';
            }

            // Validate required fields
            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            if ($validator->fails()) {
                $this->skippedCount++;
                continue;
            }

            $email = strtolower($data['email']);

            // Skip if email has been processed in this import session or already exists in DB
            if (in_array($email, $this->processedEmails) || Member::where('email', $email)->exists()) {
                $this->skippedCount++;
                continue;
            }

            // Create Member
            Member::create([
                'name' => $data['name'],
                'email' => $email,
                'phone' => $data['phone'],
                 'password' => Hash::make('BNI@' . $data['phone']),
                'company' => $data['company'],
                'joining_date' => $data['joining_date'],
                'chapter' => $data['chapter'],
                'designation' => $data['designation'],
                'status' => $data['status'],
            ]);

            $this->processedEmails[] = $email;
            $this->importedCount++;
        }
    }
}