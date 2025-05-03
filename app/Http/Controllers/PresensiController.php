<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    /**
     * Tampilkan halaman presensi dan cek apakah user sudah absen hari ini.
     */
    public function create()
    {
        $today = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;

        $hasAbsentToday = DB::table('presensi')
            ->where('tanggal_presensi', $today)
            ->where('nik', $nik)
            ->exists();

        return view('presensi.create', ['isAbsent' => $hasAbsentToday]);
    }

    /**
     * Simpan data presensi (masuk atau keluar).
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::guard('karyawan')->user();

            if (!$user || !$user->nik) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum login atau NIK tidak tersedia'
                ], 401);
            }

            $nik = $user->nik;
            $tanggal = date("Y-m-d");
            $jam = date("H:i:s");

            // Lokasi kantor (statis)
            $officeLatitude = -7.33351589751558;
            $officeLongitude = 108.22279680492574;

            // Ambil lokasi pengguna dari request
            $userLocation = explode(",", $request->location);
            [$userLatitude, $userLongitude] = $userLocation;

            // Hitung jarak pengguna ke kantor
            $radius = round($this->calculateDistance($officeLatitude, $officeLongitude, $userLatitude, $userLongitude)['meters']);

            // Validasi gambar
            $image = $request->image;
            if (!$image || !str_contains($image, ';base64,')) {
                Log::error("Format gambar tidak valid atau kosong.");
                return response()->json(['error' => 'Format gambar tidak valid'], 400);
            }

            // Decode gambar base64
            [$meta, $base64Data] = explode(";base64,", $image);
            $imageBinary = base64_decode($base64Data);
            if (!$imageBinary) {
                return response()->json(['error' => 'Gagal mendekode gambar'], 400);
            }

            // Nama file dan path penyimpanan
            $fileName = "{$nik}-{$tanggal}.png";
            $storagePath = "uploads/absention/{$fileName}";

            // Cek apakah sudah absen hari ini
            $hasAbsentToday = DB::table('presensi')
                ->where('tanggal_presensi', $tanggal)
                ->where('nik', $nik)
                ->exists();

            if ($radius > 20) {
                return response("error|Maaf, Anda berada di luar radius kantor. Jarak Anda {$radius} meter dari kantor|");
            }

            if ($hasAbsentToday) {
                // Proses absen keluar
                $dataOut = [
                    'jam_keluar' => $jam,
                    'foto_keluar' => $fileName,
                    'lokasi_keluar' => $request->location,
                ];

                $update = DB::table('presensi')
                    ->where('tanggal_presensi', $tanggal)
                    ->where('nik', $nik)
                    ->update($dataOut);

                if ($update) {
                    $this->saveImage($storagePath, $imageBinary);
                    return response("success|Terima kasih, hati-hati di jalan|out");
                }

                return response("error|Absen keluar gagal, silakan hubungi admin|out");
            }

            // Proses absen masuk
            $dataIn = [
                'nik' => $nik,
                'tanggal_presensi' => $tanggal,
                'jam_masuk' => $jam,
                'foto_masuk' => $fileName,
                'lokasi_masuk' => $request->location,
            ];

            $insert = DB::table('presensi')->insert($dataIn);

            if ($insert) {
                $this->saveImage($storagePath, $imageBinary);
                return response("success|Terima kasih, selamat bekerja|in");
            }

            return response("error|Absen masuk gagal, silakan hubungi admin|in");

        } catch (\Exception $e) {
            Log::error("Error saat presensi: " . $e->getMessage());
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Simpan gambar presensi ke storage publik.
     */
    private function saveImage(string $path, string $content): void
    {
        $saved = Storage::disk('public')->put($path, $content);

        if (!$saved) {
            Log::error("Gagal menyimpan gambar ke storage: {$path}");
            throw new \Exception('Gagal menyimpan gambar');
        }

        $absolutePath = storage_path('app/public/' . $path);
        Log::info("Gambar presensi disimpan di: {$absolutePath}");
    }

    /**
     * Hitung jarak antara dua koordinat dalam meter.
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): array
    {
        $theta = $lon1 - $lon2;
        $angle = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                 cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $angle = acos(min(max($angle, -1), 1)); // Hindari nilai di luar range acos
        $miles = rad2deg($angle) * 60 * 1.1515;
        $meters = $miles * 1.609344 * 1000;

        return ['meters' => $meters];
    }
}
