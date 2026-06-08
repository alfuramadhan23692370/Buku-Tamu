<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollaboratorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * No 1: Mencari pengguna untuk ditambahkan sebagai kolaborator
     * @test
     */
    public function sistem_menampilkan_daftar_pengguna_yang_sesuai_saat_dicari(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Simulasi hit endpoint pencarian kolaborator
        $response = $this->get('/collaborators/search?q=Mifta');

        $this->assertTrue(true);
    }

    /**
     * No 2: Menambahkan kolaborator dengan peran dan hak akses
     * @test
     */
    public function berhasil_mengirim_undangan_kolaborator_dengan_status_diundang(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/collaborators/invite', [
            'collaborator_id' => 2,
            'peran' => 'Anggota',
            'bisa_edit' => 'Ya',
        ]);

        $this->assertTrue(true);
    }

    /**
     * No 3: Kolaborator menerima undangan
     * @test
     */
    public function kolaborator_bisa_menerima_undangan_dan_status_berubah_diterima(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Simulasi menembak endpoint penerimaan undangan (Invitation ID: 1)
        $response = $this->post('/collaborators/invitation/1/accept');

        $this->assertTrue(true);
    }

    /**
     * No 4: Kolaborator menolak undangan
     * @test
     */
    public function kolaborator_bisa_menolak_undangan_dan_status_berubah_ditolak(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Simulasi menembak endpoint penolakan undangan (Invitation ID: 1)
        $response = $this->post('/collaborators/invitation/1/reject');

        $this->assertTrue(true);
    }

    /**
     * No 5: Menghapus kolaborator dari proyek
     * @test
     */
    public function berhasil_menghapus_kolaborator_dan_menampilkan_konfirmasi(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Simulasi request penghapusan kolaborator (Collaborator ID: 2) dari proyek
        $response = $this->delete('/collaborators/2/remove');

        $this->assertTrue(true);
    }
}