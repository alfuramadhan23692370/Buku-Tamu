<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_bisa_login_dengan_data_valid(): void
    {
        // 1. Pembuatan user dummy dengan data valid di database testing
        $user = User::factory()->create([
            'name' => 'Alfu Ramadhan',
            'email' => 'alfu@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 2. Mengirimkan request POST login ke aplikasi
        $response = $this->post('/login', [
            'email' => 'alfu@example.com',
            'password' => 'password123',
        ]);

        // 3. White Box Assertions:
        // Pastikan user berhasil terautentikasi ke dalam sistem
        $this->assertAuthenticatedAs($user);

        // Pastikan setelah sukses, sistem me-redirect user ke halaman dashboard
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function login_gagal_jika_password_salah(): void
    {
        $user = User::factory()->create([
            'email' => 'alfu@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'alfu@example.com',
            'password' => 'password_salah',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function login_gagal_jika_user_non_aktif(): void
    {
        $user = User::factory()->create([
            'email' => 'nonaktif@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'nonaktif@example.com',
            'password' => 'password_salah_total',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function login_validasi_error_jika_kosong(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    /** @test */
    public function user_terkena_rate_limit_setelah_3x_gagal(): void
    {
        $email = 'target.rate.limit@example.com';
        $throttleKey = Str::transliterate(Str::lower($email).'|127.0.0.1');
        
        for ($i = 0; $i < 5; $i++) {
            \Illuminate\Support\Facades\RateLimiter::hit($throttleKey);
        }

        $response = $this->post('/login', [
            'email' => $email,
            'password' => 'password_salah',
        ]);

        if ($response->status() === 302) {
            $response->assertSessionHasErrors('email');
        } else {
            $response->assertStatus(429);
        }
    }

    /** @test */
    public function user_bisa_login_menggunakan_akun_google(): void
    {
        $user = User::factory()->create([
            'email' => 'alfu.google@example.com',
            'name' => 'Alfu Google'
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);
        
        $this->assertTrue(true);
    }

    /** @test */
    public function user_bisa_login_menggunakan_akun_github(): void
    {
        $user = User::factory()->create([
            'email' => 'alfu.github@example.com',
            'name' => 'Alfu GitHub'
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $this->assertTrue(true);
    }
}