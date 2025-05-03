<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PresensiController extends Controller
{
    /**
     * Tampilkan halaman presensi dan cek apakah user sudah absen hari ini.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $today = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;

        $isAbsent = DB::table('presensi')
            ->where('tanggal_presensi', $today)
            ->where('nik', $nik)
            ->count();

        return view('presensi.create', compact('isAbsent'));
    }

    /**
     * Simpan data presensi (masuk/keluar).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::guard('karyawan')->user();

            // Validasi user login
            if (!$user || !$user->nik) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum login atau NIK tidak tersedia'
                ], 401);
            }

            $nik = $user->nik;
            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");

            $location = $request->location;
            $image = $request->image;

            // Validasi format gambar base64
            if (!$image || !str_contains($image, ';base64,')) {
                Log::error("Format gambar tidak valid atau kosong.");
                return response()->json(['error' => 'Format gambar tidak valid'], 400);
            }

            // Persiapkan nama dan path file gambar
            $fileName = "{$nik}-{$tanggal}.png";
            $folderPath = "uploads/absention/";
            $fullPath = $folderPath . $fileName;

            // Decode gambar base64
            [$meta, $base64Data] = explode(";base64,", $image);
            $imageBinary = base64_decode($base64Data);

            if (!$imageBinary) {
                return response()->json(['error' => 'Gagal mendekode gambar'], 400);
            }

            // Cek apakah user sudah absen hari ini
            $alreadyAbsent = DB::table('presensi')
                ->where('tanggal_presensi', $tanggal)
                ->where('nik', $nik)
                ->exists();

            if ($alreadyAbsent) {
                // Update jam keluar
                $dataOut = [
                    'jam_keluar' => $jam,
                    'foto_keluar' => $fileName,
                    'lokasi_keluar' => $location,
                ];

                $update = DB::table('presensi')
                    ->where('tanggal_presensi', $tanggal)
                    ->where('nik', $nik)
                    ->update($dataOut);

                if ($update) {
                    $this->saveImage($fullPath, $imageBinary);
                    echo "success|Terimakasih, Hati-Hati Dijalan|out";
                } else {
                    echo "error|Absen Anda Gagal, Silahkan Hubungi Admin|out";
                }
            } else {
                // Simpan jam masuk
                $dataIn = [
                    'nik' => $nik,
                    'tanggal_presensi' => $tanggal,
                    'jam_masuk' => $jam,
                    'foto_masuk' => $fileName,
                    'lokasi_masuk' => $location,
                ];

                $insert = DB::table('presensi')->insert($dataIn);

                if ($insert) {
                    $this->saveImage($fullPath, $imageBinary);
                    echo "success|Terimakasih, Selamat Bekerja|in";
                } else {
                    echo "error|Absen Anda Gagal, Silahkan Hubungi Admin|in";
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simpan gambar ke storage publik.
     *
     * @param string $path
     * @param string $content
     * @return void
     */
    private function saveImage(string $path, string $content): void
    {
        $saved = Storage::disk('public')->put($path, $content);

        if (!$saved) {
            Log::error("Gagal menyimpan gambar ke storage: {$path}");
            throw new \Exception('Gagal menyimpan gambar');
        }

        // Log lokasi penyimpanan
        $absolutePath = storage_path('app/public/' . $path);
        Log::info("Gambar presensi disimpan di: {$absolutePath}");
    }
}
