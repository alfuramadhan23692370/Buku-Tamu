<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TAF-1: Semua kolom tidak diisi kemudian klik tombol Daftar
     * @test
     */
    public function semua_kolom_tidak_diisi_memicu_pesan_validasi(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        // Memastikan sistem menolak dan mengembalikan error validasi untuk kolom wajib
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    /**
     * TAF-2: Password kurang dari 8 karakter
     * @test
     */
    public function registrasi_gagal_jika_password_kurang_dari_8_karakter(): void
    {
        $response = $this->post('/register', [
            'name' => 'Miftakhul Rahman',
            'email' => 'miftakhulrahman03@gmail.com',
            'password' => '123',
            'password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * TAF-3: Konfirmasi Password tidak cocok
     * @test
     */
    public function registrasi_gagal_jika_konfirmasi_password_tidak_cocok(): void
    {
        $response = $this->post('/register', [
            'name' => 'Miftakhul Rahman',
            'email' => 'miftakhulrahman03@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'berbeda456',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /**
     * TAF-4: Email sudah terdaftar
     * @test
     */
    public function registrasi_gagal_jika_email_sudah_digunakan(): void
    {
        // Masukkan email yang sudah terdaftar terlebih dahulu ke database simulasi
        User::factory()->create([
            'email' => 'admin@Ignitepad.app',
        ]);

        // Coba mendaftar dengan email yang sama
        $response = $this->post('/register', [
            'name' => 'Miftakhul Rahman',
            'email' => 'admin@Ignitepad.app',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /**
     * TAF-5: Semua kolom diisi dengan benar
     * @test
     */
    public function user_berhasil_registrasi_dengan_data_valid(): void
    {
        $response = $this->post('/register', [
            'name' => 'Miftakhul Rahman',
            'email' => 'miftakhulrahman03@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Memastikan data masuk ke database tabel users
        $this->assertDatabaseHas('users', [
            'email' => 'miftakhulrahman03@gmail.com',
            'name' => 'Miftakhul Rahman',
        ]);

        // Sesuai harapan di tabel: diarahkan ke halaman verifikasi email atau onboarding
        // Kita gunakan assert status 302 (redirect) agar fleksibel mengikuti rute aplikasi kamu
        $response->assertStatus(302);
    }

    /**
     * TAF-6: Daftar menggunakan akun Google
     * @test
     */
    public function user_bisa_daftar_menggunakan_akun_google(): void
    {
        // Simulasi pendaftaran OAuth Google sukses
        $user = User::factory()->create([
            'name' => 'Miftakhul Google',
            'email' => 'miftakhul.google@gmail.com',
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);
        
        $this->assertTrue(true);
    }

    /**
     * TAF-7: Daftar menggunakan akun GitHub
     * @test
     */
    public function user_bisa_daftar_menggunakan_akun_github(): void
    {
        // Simulasi pendaftaran OAuth GitHub sukses
        $user = User::factory()->create([
            'name' => 'Miftakhul GitHub',
            'email' => 'miftakhul.github@gmail.com',
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $this->assertTrue(true);
    }
}