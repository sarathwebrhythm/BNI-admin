<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\ExcelService;
use App\Http\Requests\ImportExcelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    protected $excelService;

    public function __construct(ExcelService $excelService)
    {
        $this->excelService = $excelService;
    }

    public function showImport()
    {
        return view('imports.index');
    }

    public function import(ImportExcelRequest $request)
    {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $adminId = Auth::guard('admin')->id();

        $result = $this->excelService->importMembers($file, $filename, $adminId);

        if ($result['success']) {
            return redirect()->route('admin.import.show')->with([
                'success' => 'Excel file processed.',
                'summary' => [
                    'total' => $result['total'],
                    'imported' => $result['imported'],
                    'skipped' => $result['skipped'],
                ]
            ]);
        }

        return redirect()->route('admin.import.show')->withErrors([
            'file' => 'Import failed: ' . $result['message'],
        ]);
    }

    public function export(Request $request)
    {
        $search = $request->input('search');
        $filters = [
            'status' => $request->input('status'),
            'chapter' => $request->input('chapter'),
        ];

        $export = $this->excelService->exportMembers($search, $filters);
        
        $filename = 'bni_members_' . date('Y_m_d_His') . '.xlsx';
        return Excel::download($export, $filename);
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bni_member_import_template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'email', 'phone', 'company', 'chapter', 'designation', 'status']);
            fputcsv($file, ['John Doe', 'john.doe@example.com', '+1234567890', 'Alpha Co', 'Pinnacle', 'President', 'active']);
            fputcsv($file, ['Jane Smith', 'jane.smith@example.com', '+0987654321', 'Beta Corp', 'Synergy', 'Vice President', 'active']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}