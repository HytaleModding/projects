<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationRequirementTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_user_cannot_access_dashboard()
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_verified_user_can_access_dashboard()
    {
        $user = User::factory()->create(); // Factory creates verified users by default
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertOk();
    }

    public function test_unverified_user_cannot_view_mods_index()
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user);

        $response = $this->get(route('mods.index'));
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_unverified_user_cannot_create_pages()
    {
        $user = User::factory()->unverified()->create();
        $mod = \App\Models\Mod::factory()->create(['owner_id' => $user->id]);
        $this->actingAs($user);

        $pageData = [
            'title' => 'Test Page',
            'content' => 'Test content',
            'published' => true,
        ];

        $response = $this->post(route('pages.store', $mod), $pageData);
        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseMissing('pages', ['title' => 'Test Page']);
    }
}
