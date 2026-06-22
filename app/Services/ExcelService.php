<?php

namespace App\Services;

use App\Imports\MembersImport;
use App\Exports\MembersExport;
use App\Repositories\Contracts\MemberRepositoryInterface;
use App\Repositories\Contracts\UploadHistoryRepositoryInterface;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExcelService
{
    protected $memberRepo;
    protected $uploadHistoryRepo;

    public function __construct(
        MemberRepositoryInterface $memberRepo,
        UploadHistoryRepositoryInterface $uploadHistoryRepo
    ) {
        $this->memberRepo = $memberRepo;
        $this->uploadHistoryRepo = $uploadHistoryRepo;
    }

    public function importMembers($file, $filename, $adminId)
    {
        $import = new MembersImport();
        
        try {
            Excel::import($import, $file);
            
            // Record to upload history table
            $history = $this->uploadHistoryRepo->create([
                'filename' => $filename,
                'total_records' => $import->totalCount,
                'imported_records' => $import->importedCount,
                'skipped_records' => $import->skippedCount,
                'status' => 'completed',
                'uploaded_by' => $adminId,
            ]);

            // Log activity
            $this->logActivity("Uploaded Excel: {$filename}. Imported {$import->importedCount} members, skipped {$import->skippedCount}.");

            return [
                'success' => true,
                'total' => $import->totalCount,
                'imported' => $import->importedCount,
                'skipped' => $import->skippedCount,
                'history' => $history,
            ];
        } catch (\Exception $e) {
            $history = $this->uploadHistoryRepo->create([
                'filename' => $filename,
                'total_records' => 0,
                'imported_records' => 0,
                'skipped_records' => 0,
                'status' => 'failed',
                'uploaded_by' => $adminId,
            ]);

            $this->logActivity("Failed to upload Excel: {$filename}. Error: " . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'history' => $history,
            ];
        }
    }

    public function exportMembers($search = null, $filters = [])
    {
        $members = $this->memberRepo->getAllForExport($search, $filters);
        
        $filterDesc = '';
        if (!empty($filters['status'])) {
            $filterDesc .= "Status: {$filters['status']}; ";
        }
        if (!empty($filters['chapter'])) {
            $filterDesc .= "Chapter: {$filters['chapter']}; ";
        }
        if ($search) {
            $filterDesc .= "Search: '{$search}'; ";
        }

        $logMsg = "Exported members list" . ($filterDesc ? " with filters ($filterDesc)" : "");
        $this->logActivity($logMsg);

        return new MembersExport($members);
    }

    protected function logActivity($activity)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            ActivityLog::create([
                'admin_id' => $admin->id,
                'activity' => $activity,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}