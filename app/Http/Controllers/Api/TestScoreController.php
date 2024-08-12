<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestScoreController extends Controller
{
    public function getNilaiRT()
    {
        $nilaiRT = DB::table('nilai')
            ->select(
                'nama',
                'nisn',
                DB::raw('
                    MAX(CASE WHEN nama_pelajaran = "ARTISTIC" THEN skor ELSE 0 END) AS artistic,
                    MAX(CASE WHEN nama_pelajaran = "CONVENTIONAL" THEN skor ELSE 0 END) AS conventional,
                    MAX(CASE WHEN nama_pelajaran = "ENTERPRISING" THEN skor ELSE 0 END) AS enterprising,
                    MAX(CASE WHEN nama_pelajaran = "INVESTIGATIVE" THEN skor ELSE 0 END) AS investigative,
                    MAX(CASE WHEN nama_pelajaran = "REALISTIC" THEN skor ELSE 0 END) AS realistic,
                    MAX(CASE WHEN nama_pelajaran = "SOCIAL" THEN skor ELSE 0 END) AS social
                ')
            )
            ->where('materi_uji_id', 7)
            ->groupBy('nama', 'nisn')
            ->orderBy('nama')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->nama,
                    'nisn' => $item->nisn,
                    'nilaiRt' => [
                        'artistic' => (int)$item->artistic,
                        'conventional' => (int)$item->conventional,
                        'enterprising' => (int)$item->enterprising,
                        'investigative' => (int)$item->investigative,
                        'realistic' => (int)$item->realistic,
                        'social' => (int)$item->social,
                    ],
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Data nilai RT berhasil diambil',
            'data' => $nilaiRT,
        ]);
    }

    public function getNilaiST()
    {
        $nilaiST = DB::table('nilai')
            ->select(
                'nama',
                'nisn',
                DB::raw('
                    SUM(CASE WHEN pelajaran_id = 44 THEN skor * 41.67 ELSE 0 END) AS figural,
                    SUM(CASE WHEN pelajaran_id = 45 THEN skor * 29.67 ELSE 0 END) AS kuantitatif,
                    SUM(CASE WHEN pelajaran_id = 46 THEN skor * 100 ELSE 0 END) AS penalaran,
                    SUM(CASE WHEN pelajaran_id = 47 THEN skor * 23.81 ELSE 0 END) AS verbal
                ')
            )
            ->where('materi_uji_id', 4)
            ->groupBy('nama', 'nisn')
            ->orderByDesc(DB::raw('
            SUM(CASE WHEN pelajaran_id = 44 THEN skor * 41.67 ELSE 0 END) +
            SUM(CASE WHEN pelajaran_id = 45 THEN skor * 29.67 ELSE 0 END) +
            SUM(CASE WHEN pelajaran_id = 46 THEN skor * 100 ELSE 0 END) +
            SUM(CASE WHEN pelajaran_id = 47 THEN skor * 23.81 ELSE 0 END)
            '))
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->nama,
                    'nisn' => $item->nisn,
                    'listNilai' => [
                        'figural' => (float)$item->figural,
                        'kuantitatif' => (float)$item->kuantitatif,
                        'penalaran' => (float)$item->penalaran,
                        'verbal' => (float)$item->verbal,
                    ],
                    'total' => (float)($item->figural + $item->kuantitatif + $item->penalaran + $item->verbal),
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Data nilai ST berhasil diambil',
            'data' => $nilaiST,
        ]);
    }
}
