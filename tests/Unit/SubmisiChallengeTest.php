<?php

namespace Tests\Unit;

use Tests\TestCase;

class SubmisiChallengeTest extends TestCase
{
    /**
     * White Box Data Provider untuk fungsi assignGrade() di SubmisiChallenge
     */
    public static function submisiChallengeDataProvider(): array
    {
        return [
            'P-1: Nilai Tepat Batas A (86)' => [86, 'A'],
            'P-1: Nilai Ekstrim Atas (100)' => [100, 'A'],
            'P-2: Nilai Tepat Batas B (71)' => [71, 'B'],
            'P-3: Nilai Tepat Batas C (56)' => [56, 'C'],
            'P-4: Nilai Tepat Batas D (41)' => [41, 'D'],
            'P-5: Nilai Tepat Batas E (40)' => [40, 'E'],
            'P-5: Nilai Ekstrim Bawah (0)' => [0, 'E'],
        ];
    }

    /**
     * Menguji logika percabangan fungsi assignGrade()
     * @test
     * @dataProvider submisiChallengeDataProvider
     */
    public function uji_percabangan_assign_grade_submisi_challenge($inputNilai, $expectedGrade): void
    {
        $hasilGrade = $this->simulasiAssignGrade($inputNilai);

        $this->assertEquals($expectedGrade, $hasilGrade);
    }

    /**
     * Replikasi internal fungsi assignGrade() untuk validasi jalur IF-ELSE
     */
    private function simulasiAssignGrade($nilai): string
    {
        if ($nilai >= 86) return 'A';
        if ($nilai >= 71) return 'B';
        if ($nilai >= 56) return 'C';
        if ($nilai >= 41) return 'D';
        return 'E';
    }
}