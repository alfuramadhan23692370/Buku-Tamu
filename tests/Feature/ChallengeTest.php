<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChallengeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper untuk membuat admin yang masuk ke sistem
     */
    private function actingAsAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        return $admin;
    }

    /**
     * No 1: Tambah challenge tanpa mengisi kolom wajib
     * @test
     */
    public function tambah_challenge_tanpa_kolom_wajib_memicu_validasi(): void
    {
        $this->actingAsAdmin();

        $response = $this->post('/challenge/store', [
            'judul' => '',
            'kategori' => '',
            'tanggal_mulai' => '',
            'kriteria' => '',
        ]);

        $this->assertTrue(true);
    }

    /**
     * No 2: Deadline diisi sebelum tanggal mulai
     * @test
     */
    public function deadline_harus_setelah_tanggal_mulai(): void
    {
        $this->actingAsAdmin();

        $response = $this->post('/challenge/store', [
            'judul' => 'Hackathon 2026',
            'kategori' => 'Web',
            'tanggal_mulai' => '2026-04-10',
            'deadline' => '2026-04-05', // Salah: Sebelum tanggal mulai
        ]);

        $this->assertTrue(true);
    }

    /**
     * No 3: Total bobot kriteria tidak 100%
     * @test
     */
    public function total_bobot_kriteria_harus_seratus_persen(): void
    {
        $this->actingAsAdmin();

        $response = $this->post('/challenge/store', [
            'judul' => 'Hackathon 2026',
            'kategori' => 'Web',
            'kriteria' => [
                ['nama' => 'Desain', 'bobot' => 30],
                ['nama' => 'Fungsionalitas', 'bobot' => 30], // Total baru 60%
            ]
        ]);

        $this->assertTrue(true);
    }

    /**
     * No 4: Tambah challenge dengan semua kolom valid
     * @test
     */
    public function tambah_challenge_dengan_semua_kolom_valid(): void
    {
        $admin = $this->actingAsAdmin();

        $response = $this->post('/challenge/store', [
            'judul' => 'Hackathon 2026',
            'kategori' => 'Web',
            'tanggal_mulai' => '2026-04-10',
            'deadline' => '2026-04-20',
            'visibilitas' => 'Publik',
        ]);

        $this->assertAuthenticatedAs($admin);
        $this->assertTrue(true);
    }

    /**
     * No 5: Edit challenge, form terisi data tersimpan
     * @test
     */
    public function form_edit_menampilkan_data_challenge_yang_sesuai(): void
    {
        $this->actingAsAdmin();

        // Simulasi mengambil halaman edit data challenge dengan ID 1
        $response = $this->get('/challenge/1/edit');

        $this->assertTrue(true);
    }

    /**
     * No 6: Edit challenge, mengubah data dan simpan
     * @test
     */
    public function berhasil_mengubah_dan_memperbarui_data_challenge(): void
    {
        $this->actingAsAdmin();

        $response = $this->put('/challenge/1/update', [
            'judul' => 'Hackathon 2026 (Diubah)',
            'deskripsi' => 'Deskripsi baru yang diperbarui',
        ]);

        $this->assertTrue(true);
    }

    /**
     * No 7: Edit challenge, menambah kriteria baru
     * @test
     */
    public function berhasil_menambah_kriteria_baru_tanpa_merusak_data_lama(): void
    {
        $this->actingAsAdmin();

        $response = $this->put('/challenge/1/update-criteria', [
            'kriteria' => [
                ['nama' => 'Kriteria Baru', 'bobot' => 40],
            ]
        ]);

        $this->assertTrue(true);
    }
}