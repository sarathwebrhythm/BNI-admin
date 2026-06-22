<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_login_via_dashboard(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/admin/dashboard');

        $followRedirect = $this->followingRedirects()->get('/');
        $followRedirect->assertSee('BNI Admin Portal');
        $followRedirect->assertSee('Sign in to manage members and reports.');
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'test@admin.com',
            'password' => Hash::make('secret-pass'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'test@admin.com',
            'password' => 'secret-pass',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_cannot_login_with_wrong_password(): void
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'test@admin.com',
            'password' => Hash::make('secret-pass'),
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'test@admin.com',
            'password' => 'wrong-pass',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('admin');
    }

    public function test_authenticated_admin_can_perform_member_crud(): void
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'test@admin.com',
            'password' => Hash::make('secret-pass'),
        ]);

        $this->actingAs($admin, 'admin');

        $memberData = [
            'name' => 'John Roster',
            'email' => 'john.roster@bni.com',
            'phone' => '1234567890',
            'company' => 'Apex Solutions',
            'chapter' => 'Pinnacle',
            'designation' => 'Tech Consultant',
            'status' => 'active',
        ];

        $response = $this->post('/admin/members', $memberData);
        $response->assertRedirect('/admin/members');

        $this->assertDatabaseHas('members', [
            'email' => 'john.roster@bni.com',
            'name' => 'John Roster',
        ]);

        $member = Member::where('email', 'john.roster@bni.com')->first();

        $response = $this->get("/admin/members/{$member->id}");
        $response->assertStatus(200);
        $response->assertSee('John Roster');
        $response->assertSee('Apex Solutions');

        $updatedData = $memberData;
        $updatedData['name'] = 'John Roster Updated';
        $updatedData['company'] = 'Apex Global';

        $response = $this->put("/admin/members/{$member->id}", $updatedData);
        $response->assertRedirect('/admin/members');

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'name' => 'John Roster Updated',
            'company' => 'Apex Global',
        ]);

        $response = $this->delete("/admin/members/{$member->id}");
        $response->assertRedirect('/admin/members');
        $this->assertDatabaseMissing('members', [
            'id' => $member->id,
        ]);
    }
}