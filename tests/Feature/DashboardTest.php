<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pengguna_anonim_tidak_bisa_mengakses_dashboard(): void
    {
        // Mencoba mengakses halaman dashboard tanpa login
        $response = $this->get('/dashboard');

        // White Box check: Memastikan ditendang (redirect) ke halaman login (status 302)
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

   /** @test */
    public function pengguna_yang_sudah_login_bisa_mengakses_dashboard(): void
    {
        // Sesuaikan nama kolom (misal: 'role') dan nilainya (misal: 'admin') dengan sistem di aplikasimu
        $user = User::factory()->create([
            'role' => 'admin', 
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }
}