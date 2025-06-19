<?php

namespace App\Http\Controllers;

use App\Models\PengajuanIzin;
use App\Models\Kantor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PresensiController extends Controller
{
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

        // Ambil data kantor utama
        $kantor = Kantor::getKantorUtama();

        return view('presensi.create', [
            'isAbsent' => $hasAbsentToday,
            'kantor' => $kantor
        ]);
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
            
            // Ambil data kantor dan validasi jarak
            $kantor = Kantor::getKantorUtama();
            if (!$kantor) {
                return response("error|Data kantor tidak ditemukan, hubungi admin|");
            }

            $distance = $kantor->hitungJarak($userLat, $userLong);

            if (!$kantor->dalamRadius($userLat, $userLong)) {
                return response("error|Maaf, Anda berada di luar radius kantor. Jarak Anda " . round($distance) . " meter dari kantor|");
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

        // Jika ada parameter dari dashboard, langsung trigger pencarian
        $autoSearch = $request->has('month') || $request->has('year') || 
                     $request->has('status') || $request->has('kehadiran');

        return view('presensi.history', compact('months', 'autoSearch'));
    }

    /**
     * Mencari riwayat presensi berdasarkan bulan, tahun, status kedisiplinan, dan kehadiran.
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
        $status = $request->status;
        $kehadiran = $request->kehadiran;

        // Ambil jam masuk dari konfigurasi kantor
        $kantor = Kantor::getKantorUtama();
        
        // Pastikan format jam masuk kantor dalam string H:i:s
        if ($kantor && $kantor->jam_masuk) {
            $jamMasukKantor = is_string($kantor->jam_masuk) ? 
                $kantor->jam_masuk : 
                $kantor->jam_masuk->format('H:i:s');
        } else {
            $jamMasukKantor = '07:00:00';
        }

        $query = DB::table('presensi')
            ->whereMonth('tanggal_presensi', $month)
            ->whereYear('tanggal_presensi', $year)
            ->where('nik', $nik);

        // Filter berdasarkan status kedisiplinan dengan perbandingan string waktu
        if ($status === 'tepat_waktu') {
            $query->whereRaw("TIME(jam_masuk) < TIME(?)", [$jamMasukKantor]);
        } elseif ($status === 'terlambat') {
            $query->whereRaw("TIME(jam_masuk) >= TIME(?)", [$jamMasukKantor]);
        }

        $history = $query->orderBy('tanggal_presensi')->get();

        // Generate kalender untuk bulan dan tahun yang dipilih
        $calendarData = $this->generateCalendarData($month, $year, $nik, $status, $jamMasukKantor, $kehadiran);

        // Data bulan untuk header kalender
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];

        return view('presensi.historyResult', compact('history', 'kantor', 'calendarData', 'month', 'year', 'months'));
    }

    /**
     * Generate data kalender untuk menampilkan semua tanggal dalam bulan
     * dengan status kehadiran
     */
    private function generateCalendarData($month, $year, $nik, $statusFilter = null, $jamMasukKantor = '07:00:00', $kehadiranFilter = null)
    {
        $calendar = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        
        // Ambil semua data presensi untuk bulan dan tahun yang dipilih
        $presensiQuery = DB::table('presensi')
            ->whereMonth('tanggal_presensi', $month)
            ->whereYear('tanggal_presensi', $year)
            ->where('nik', $nik);

        // Apply filter status jika ada
        if ($statusFilter === 'tepat_waktu') {
            $presensiQuery->whereRaw("TIME(jam_masuk) < TIME(?)", [$jamMasukKantor]);
        } elseif ($statusFilter === 'terlambat') {
            $presensiQuery->whereRaw("TIME(jam_masuk) >= TIME(?)", [$jamMasukKantor]);
        }

        $presensiData = $presensiQuery->get()->keyBy('tanggal_presensi');

        // Generate data untuk setiap tanggal dalam bulan
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $dayOfWeek = date('w', strtotime($date)); // 0=Minggu, 1=Senin, ..., 6=Sabtu
            $dayName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$dayOfWeek];

            $dayData = [
                'date' => $date,
                'day' => $day,
                'day_name' => $dayName,
                'is_weekend' => ($dayOfWeek == 0 || $dayOfWeek == 6), // Minggu atau Sabtu
                'is_sunday' => ($dayOfWeek == 0),
                'is_saturday' => ($dayOfWeek == 6),
                'presensi' => null,
                'status' => 'tidak_hadir'
            ];

            // Cek apakah ada data presensi untuk tanggal ini
            if (isset($presensiData[$date])) {
                $dayData['presensi'] = $presensiData[$date];
                $dayData['status'] = 'hadir';
            } elseif ($dayData['is_sunday']) {
                $dayData['status'] = 'libur_minggu';
            } elseif ($dayData['is_saturday']) {
                $dayData['status'] = 'libur_sabtu';
            } else {
                $dayData['status'] = 'tidak_hadir';
            }

            // Apply filter kehadiran
            $includeDay = true;
            if ($kehadiranFilter === 'hadir' && $dayData['status'] !== 'hadir') {
                $includeDay = false;
            } elseif ($kehadiranFilter === 'tidak_hadir') {
                // Tidak hadir = tidak ada presensi di hari kerja (bukan weekend)
                if ($dayData['status'] === 'hadir' || $dayData['status'] === 'libur_minggu' || $dayData['status'] === 'libur_sabtu') {
                    $includeDay = false;
                }
            }

            if ($includeDay) {
                $calendar[] = $dayData;
            }
        }

        return $calendar;
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