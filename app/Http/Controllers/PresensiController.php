<?php

namespace App\Http\Controllers;

use App\Models\PengajuanIzin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PresensiController extends Controller
{
    // Koordinat kantor tetap sebagai konstanta class
    private const OFFICE_LAT = -7.33351589751558;
    private const OFFICE_LONG = 108.22279680492574;
    private const MAX_DISTANCE_METERS = 20;

    /**
     * Menampilkan halaman presensi.
     * Mengecek apakah user sudah melakukan presensi hari ini.
     * 
     * @return \Illuminate\View\View
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
     * Menyimpan data presensi (masuk/keluar) ke database.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::guard('karyawan')->user();

            // Validasi user dan NIK
            if (!$user || !$user->nik) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum login atau NIK tidak tersedia.'
                ], 401);
            }

            $nik = $user->nik;
            $tanggal = Carbon::today()->toDateString();
            $jam = Carbon::now()->format('H:i:s');

            // Validasi lokasi
            if (!$this->isValidLocation($request->location)) {
                return response("error|Lokasi tidak valid|");
            }

            [$userLat, $userLong] = explode(',', $request->location);
            $distance = $this->calculateDistanceFromOffice($userLat, $userLong);

            if ($distance > self::MAX_DISTANCE_METERS) {
                return response("error|Maaf, Anda berada di luar radius kantor. Jarak Anda {$distance} meter dari kantor|");
            }

            // Cek apakah sudah absen hari ini dan tentukan status
            $status = $this->getAttendanceStatus($nik, $tanggal);

            // Validasi dan simpan gambar base64
            $image = $request->input('image');
            if (!$this->isValidBase64Image($image)) {
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
                return $this->processCheckOut($nik, $tanggal, $jam, $request->location, $fileName, $storagePath, $imageBinary);
            }

            return $this->processCheckIn($nik, $tanggal, $jam, $request->location, $fileName, $storagePath, $imageBinary);

        } catch (\Exception $e) {
            Log::error("Presensi error: " . $e->getMessage());
            return response("error|Terjadi kesalahan di server: {$e->getMessage()}|");
        }
    }

    /**
     * Memproses presensi masuk
     */
    private function processCheckIn($nik, $tanggal, $jam, $location, $fileName, $storagePath, $imageBinary)
    {
        $inserted = DB::table('presensi')->insert([
            'nik' => $nik,
            'tanggal_presensi' => $tanggal,
            'jam_masuk' => $jam,
            'foto_masuk' => $fileName,
            'lokasi_masuk' => $location,
        ]);

        if ($inserted) {
            $this->saveImage($storagePath, $imageBinary);
            return response("success|Terima kasih, selamat bekerja|in");
        }

        return response("error|Absen masuk gagal, silakan hubungi admin|in");
    }

    /**
     * Memproses presensi keluar
     */
    private function processCheckOut($nik, $tanggal, $jam, $location, $fileName, $storagePath, $imageBinary)
    {
        $updated = DB::table('presensi')
            ->where('tanggal_presensi', $tanggal)
            ->where('nik', $nik)
            ->update([
                'jam_keluar' => $jam,
                'foto_keluar' => $fileName,
                'lokasi_keluar' => $location,
            ]);

        if ($updated) {
            $this->saveImage($storagePath, $imageBinary);
            return response("success|Terima kasih, hati-hati di jalan|out");
        }

        return response("error|Absen keluar gagal, silakan hubungi admin|out");
    }

    /**
     * Mengecek status presensi (masuk/keluar)
     */
    private function getAttendanceStatus($nik, $tanggal)
    {
        return DB::table('presensi')
            ->where('tanggal_presensi', $tanggal)
            ->where('nik', $nik)
            ->exists() ? 'out' : 'in';
    }

    /**
     * Validasi format lokasi
     */
    private function isValidLocation($location)
    {
        return $location && str_contains($location, ',');
    }

    /**
     * Validasi format gambar base64
     */
    private function isValidBase64Image($image)
    {
        return $image && str_contains($image, ';base64,');
    }

    /**
     * Menghitung jarak dari kantor dalam meter
     */
    private function calculateDistanceFromOffice($userLat, $userLong)
    {
        $result = $this->calculateDistance(
            self::OFFICE_LAT, 
            self::OFFICE_LONG, 
            $userLat, 
            $userLong
        );
        
        return round($result['meters']);
    }

    /**
     * Menyimpan gambar ke storage disk publik.
     * 
     * @param string $path
     * @param string $content
     * @throws \Exception
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
     * Menghitung jarak antara dua titik koordinat dalam meter.
     * 
     * @param float $lat1 Latitude titik pertama
     * @param float $lon1 Longitude titik pertama
     * @param float $lat2 Latitude titik kedua
     * @param float $lon2 Longitude titik kedua
     * @return array ['meters' => jarak dalam meter]
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

    /**
     * Menampilkan halaman riwayat presensi.
     * 
     * @return \Illuminate\View\View
     */
    public function history(Request $request)
    {
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];

        return view('presensi.history', compact('months'));
    }

    /**
     * Mencari riwayat presensi berdasarkan bulan dan tahun.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function searchHistory(Request $request)
    {
        $user = Auth::guard('karyawan')->user();
        $nik = $user->nik;
        $month = $request->month;
        $year = $request->year;

        $history = DB::table('presensi')
            ->whereMonth('tanggal_presensi', $month)
            ->whereYear('tanggal_presensi', $year)
            ->where('nik', $nik)
            ->orderBy('tanggal_presensi')
            ->get();

        return view('presensi.historyResult', compact('history'));
    }

    /**
     * Menampilkan data izin/sakit.
     * 
     * @return \Illuminate\View\View
     */
    public function izin(Request $request)
    {
        $nik = auth('karyawan')->user()->nik;

        $query = PengajuanIzin::where('nik', $nik);

        // Menyaring berdasarkan tanggal jika ada
        if ($request->filled('startDate') && $request->filled('endDate')) {
            // Konversi format dari d-m-Y ke Y-m-d
            $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->startDate)->format('Y-m-d');
            $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', $request->endDate)->format('Y-m-d');

            $query->whereBetween('tanggal_izin', [$startDate, $endDate]);
        }

        // Menyaring berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Menyaring berdasarkan approval
        if ($request->filled('approval')) {
            $query->where('status_approved', $request->approval);
        }

        // Ambil data berdasarkan query
        $dataIzin = $query->orderByDesc('created_at')->get();

        return view('presensi.izin', compact('dataIzin'));
    }

    /**
     * Menampilkan formulir permohonan izin/sakit.
     * 
     * @return \Illuminate\View\View
     */
    public function createIzin()
    {
        return view('presensi.createIzin');
    }

    /**
     * Menyimpan data izin/sakit ke database.
     * 
     * @param  \Illuminate\Http\Request  $request
     */
    public function storeIzin(Request $request)
    {
        // Validasi input dari form
        $credentials = $request->validate([
            'tanggalIzin' => 'required|date',
            'status' => 'required|in:i,s',
            'keterangan' => 'required|string|max:1000',
        ]);

        // Simpan data jika lolos validasi
        $save = PengajuanIzin::create([
            'nik' => auth('karyawan')->user()->nik, // asumsi user login
            'tanggal_izin' => $request->tanggalIzin,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'status_approved' => '0', // default pending
        ]);

        // Jika data tidak berhasil disimpan, maka tampilkan pesan error
        if (!$save) {
            return redirect()->route('presensi.izin')->with(['error' => 'Data Gagal Disimpan']);
        }

        return redirect()->route('presensi.izin')->with(['success' => 'Data Berhasil Disimpan']);
    }
}