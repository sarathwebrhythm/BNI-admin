<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExcelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_template(): void
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'test@admin.com',
            'password' => Hash::make('secret-pass'),
        ]);

        $this->actingAs($admin, 'admin');

        $response = $this->get('/admin/export-template');
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="bni_member_import_template.csv"');
        
        $content = $response->streamedContent();
        $this->assertStringContainsString('name,email,phone,company,chapter,designation,status', $content);
    }

    public function test_admin_can_export_members(): void
    {
        Excel::fake();

        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'test@admin.com',
            'password' => Hash::make('secret-pass'),
        ]);

        $this->actingAs($admin, 'admin');

        Member::create([
            'name' => 'Export Member',
            'email' => 'export@bni.com',
            'phone' => '111222333',
            'status' => 'active',
        ]);

        $response = $this->get('/admin/export');
        $response->assertStatus(200);

        $filename = 'bni_members_' . date('Y_m_d_His') . '.xlsx';
        Excel::assertDownloaded($filename);
    }
}