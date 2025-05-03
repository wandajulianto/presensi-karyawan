<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PresensiController extends Controller
{
    /**
     * Tampilkan halaman presensi dan cek apakah user sudah absen hari ini.
     */
    public function create()
    {
        $today = Carbon::today()->toDateString();
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
                    'message' => 'Anda belum login atau NIK tidak tersedia.'
                ], 401);
            }

            $nik = $user->nik;
            $tanggal = Carbon::today()->toDateString();
            $jam = Carbon::now()->format('H:i:s');

            // Koordinat kantor
            $officeLat = -7.33351589751558;
            $officeLong = 108.22279680492574;

            // Ambil lokasi user dari request
            if (!$request->filled('location') || !str_contains($request->location, ',')) {
                return response("error|Lokasi tidak valid|");
            }

            [$userLat, $userLong] = explode(',', $request->location);

            $radius = round($this->calculateDistance($officeLat, $officeLong, $userLat, $userLong)['meters']);

            if ($radius > 20) {
                return response("error|Maaf, Anda berada di luar radius kantor. Jarak Anda {$radius} meter dari kantor|");
            }

            // Cek status absen hari ini
            $isAlreadyAbsent = DB::table('presensi')
                ->where('tanggal_presensi', $tanggal)
                ->where('nik', $nik)
                ->exists();

            $status = $isAlreadyAbsent ? 'out' : 'in';

            // Validasi dan simpan gambar
            $image = $request->input('image');
            if (!$image || !str_contains($image, ';base64,')) {
                return response("error|Format gambar tidak valid|{$status}");
            }

            [$meta, $base64Data] = explode(";base64,", $image);
            $imageBinary = base64_decode($base64Data);
            if (!$imageBinary) {
                return response("error|Gagal mendekode gambar|{$status}");
            }

            $fileName = "{$nik}-{$tanggal}-{$status}.png";
            $storagePath = "uploads/absention/{$fileName}";

            if ($status === 'out') {
                // Update absen keluar
                $updated = DB::table('presensi')
                    ->where('tanggal_presensi', $tanggal)
                    ->where('nik', $nik)
                    ->update([
                        'jam_keluar' => $jam,
                        'foto_keluar' => $fileName,
                        'lokasi_keluar' => $request->location,
                    ]);

                if ($updated) {
                    $this->saveImage($storagePath, $imageBinary);
                    return response("success|Terima kasih, hati-hati di jalan|out");
                }

                return response("error|Absen keluar gagal, silakan hubungi admin|out");
            }

            // Insert absen masuk
            $inserted = DB::table('presensi')->insert([
                'nik' => $nik,
                'tanggal_presensi' => $tanggal,
                'jam_masuk' => $jam,
                'foto_masuk' => $fileName,
                'lokasi_masuk' => $request->location,
            ]);

            if ($inserted) {
                $this->saveImage($storagePath, $imageBinary);
                return response("success|Terima kasih, selamat bekerja|in");
            }

            return response("error|Absen masuk gagal, silakan hubungi admin|in");

        } catch (\Exception $e) {
            Log::error("Presensi error: " . $e->getMessage());
            return response("error|Terjadi kesalahan di server: {$e->getMessage()}|");
        }
    }

    /**
     * Simpan gambar ke storage publik.
     */
    private function saveImage(string $path, string $content): void
    {
        if (!Storage::disk('public')->put($path, $content)) {
            Log::error("Gagal menyimpan gambar: {$path}");
            throw new \Exception('Gagal menyimpan gambar');
        }

        Log::info("Gambar presensi disimpan di: " . storage_path("app/public/{$path}"));
    }

    /**
     * Hitung jarak dua titik koordinat dalam meter.
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): array
    {
        $theta = $lon1 - $lon2;
        $angle = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                 cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $angle = acos(min(max($angle, -1), 1));
        $miles = rad2deg($angle) * 60 * 1.1515;
        $meters = $miles * 1.609344 * 1000;

        return ['meters' => $meters];
    }
}
