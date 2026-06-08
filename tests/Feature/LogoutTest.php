<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_bisa_logout_dan_session_dihapus(): void
    {
        // 1. Ambil user dummy dan login
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

        // 2. Lakukan request POST logout
        $response = $this->post('/logout');

        // 3. Pastikan user sudah menjadi guest (berhasil logout)
        $this->assertGuest();

        // 4. Pastikan diarahkan ke halaman /login sesuai sistem Buku-Tamu
        $response->assertRedirect('/login'); 
    }
}