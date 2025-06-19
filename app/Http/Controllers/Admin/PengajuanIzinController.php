<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;
use App\Models\Karyawan;
use App\Models\KopSurat;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Traits\KopSuratTrait;

class PengajuanIzinController extends Controller
{
    use KopSuratTrait;
    /**
     * Display a listing of pengajuan izin.
     */
    public function index(Request $request)
    {
        $query = Izin::with('karyawan');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->byNama($request->search);
        }

        // Filter berdasarkan status (izin/sakit)
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter berdasarkan approval
        if ($request->filled('approval')) {
            $query->byApproval($request->approval);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->byMonth($request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->byYear($request->tahun);
        } else {
            // Default tahun ini
            $query->byYear(date('Y'));
        }

        $pengajuanIzins = $query->orderBy('tanggal_izin', 'desc')
                               ->orderBy('created_at', 'desc')
                               ->paginate(15);

        // Data untuk summary cards
        $summary = $this->getSummaryData($request);

        return view('admin.dashboard.pengajuan-izin.index', compact('pengajuanIzins', 'summary'));
    }

    /**
     * Show the form for creating a new pengajuan izin.
     */
    public function create()
    {
        $karyawans = Karyawan::orderBy('nama_lengkap')->get();
        return view('admin.dashboard.pengajuan-izin.create', compact('karyawans'));
    }

    /**
     * Store a newly created pengajuan izin in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|exists:karyawan,nik',
            'tanggal_izin' => 'required|date|after_or_equal:today',
            'status' => 'required|in:i,s',
            'keterangan' => 'required|string|max:500',
        ], [
            'nik.required' => 'Karyawan wajib dipilih',
            'nik.exists' => 'Karyawan tidak ditemukan',
            'tanggal_izin.required' => 'Tanggal izin wajib diisi',
            'tanggal_izin.date' => 'Format tanggal tidak valid',
            'tanggal_izin.after_or_equal' => 'Tanggal izin tidak boleh di masa lalu',
            'status.required' => 'Tipe izin wajib dipilih',
            'status.in' => 'Tipe izin tidak valid',
            'keterangan.required' => 'Keterangan wajib diisi',
            'keterangan.max' => 'Keterangan maksimal 500 karakter',
        ]);

        // Cek apakah sudah ada pengajuan di tanggal yang sama
        $existing = Izin::where('nik', $request->nik)
                        ->where('tanggal_izin', $request->tanggal_izin)
                        ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Karyawan sudah memiliki pengajuan izin/sakit pada tanggal tersebut');
        }

        Izin::create([
            'nik' => $request->nik,
            'tanggal_izin' => $request->tanggal_izin,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'status_approved' => 0, // Default pending
        ]);

        return redirect()->route('admin.pengajuan-izin.index')
            ->with('success', 'Pengajuan izin berhasil ditambahkan');
    }

    /**
     * Display the specified pengajuan izin.
     */
    public function show(Izin $pengajuanIzin)
    {
        $pengajuanIzin->load('karyawan');
        return view('admin.dashboard.pengajuan-izin.show', compact('pengajuanIzin'));
    }

    /**
     * Show the form for editing the specified pengajuan izin.
     */
    public function edit(Izin $pengajuanIzin)
    {
        $karyawans = Karyawan::orderBy('nama_lengkap')->get();
        return view('admin.dashboard.pengajuan-izin.edit', compact('pengajuanIzin', 'karyawans'));
    }

    /**
     * Update the specified pengajuan izin in storage.
     */
    public function update(Request $request, Izin $pengajuanIzin)
    {
        $request->validate([
            'nik' => 'required|exists:karyawan,nik',
            'tanggal_izin' => 'required|date',
            'status' => 'required|in:i,s',
            'keterangan' => 'required|string|max:500',
            'status_approved' => 'required|in:0,1,2',
        ], [
            'nik.required' => 'Karyawan wajib dipilih',
            'nik.exists' => 'Karyawan tidak ditemukan',
            'tanggal_izin.required' => 'Tanggal izin wajib diisi',
            'tanggal_izin.date' => 'Format tanggal tidak valid',
            'status.required' => 'Tipe izin wajib dipilih',
            'status.in' => 'Tipe izin tidak valid',
            'keterangan.required' => 'Keterangan wajib diisi',
            'keterangan.max' => 'Keterangan maksimal 500 karakter',
            'status_approved.required' => 'Status approval wajib dipilih',
            'status_approved.in' => 'Status approval tidak valid',
        ]);

        // Cek duplikasi (exclude pengajuan yang sedang diedit)
        $existing = Izin::where('nik', $request->nik)
                        ->where('tanggal_izin', $request->tanggal_izin)
                        ->where('id', '!=', $pengajuanIzin->id)
                        ->first();

        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Karyawan sudah memiliki pengajuan izin/sakit pada tanggal tersebut');
        }

        $pengajuanIzin->update([
            'nik' => $request->nik,
            'tanggal_izin' => $request->tanggal_izin,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
            'status_approved' => $request->status_approved,
        ]);

        return redirect()->route('admin.pengajuan-izin.index')
            ->with('success', 'Pengajuan izin berhasil diupdate');
    }

    /**
     * Remove the specified pengajuan izin from storage.
     */
    public function destroy(Izin $pengajuanIzin)
    {
        $pengajuanIzin->delete();

        return redirect()->route('admin.pengajuan-izin.index')
            ->with('success', 'Pengajuan izin berhasil dihapus');
    }

    /**
     * Approve pengajuan izin.
     */
    public function approve(Izin $pengajuanIzin)
    {
        $pengajuanIzin->update(['status_approved' => 1]);

        return redirect()->route('admin.pengajuan-izin.index')
            ->with('success', "Pengajuan izin {$pengajuanIzin->karyawan->nama_lengkap} berhasil disetujui");
    }

    /**
     * Reject pengajuan izin.
     */
    public function reject(Izin $pengajuanIzin)
    {
        $pengajuanIzin->update(['status_approved' => 2]);

        return redirect()->route('admin.pengajuan-izin.index')
            ->with('success', "Pengajuan izin {$pengajuanIzin->karyawan->nama_lengkap} berhasil ditolak");
    }

    /**
     * Export pengajuan izin to CSV.
     */
    public function export(Request $request)
    {
        $query = Izin::with('karyawan');

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->byNama($request->search);
        }
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('approval')) {
            $query->byApproval($request->approval);
        }
        if ($request->filled('bulan')) {
            $query->byMonth($request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->byYear($request->tahun);
        } else {
            $query->byYear(date('Y'));
        }

        $pengajuanIzins = $query->orderBy('tanggal_izin', 'desc')->get();

        $filename = 'pengajuan_izin_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($pengajuanIzins, $request) {
            $file = fopen('php://output', 'w');
            
            // BOM untuk support UTF-8 di Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header CSV dengan Kop Surat
            $kopSurat = KopSurat::getActive();
            
            fputcsv($file, ['LAPORAN PENGAJUAN IZIN / SAKIT']);
            fputcsv($file, [$kopSurat ? $kopSurat->nama_instansi : config('app.name', 'Nama Instansi')]);
            fputcsv($file, [$kopSurat ? $kopSurat->alamat_instansi : 'Alamat Instansi']);
            if ($kopSurat && $kopSurat->telepon_instansi) {
                fputcsv($file, ['Telp: ' . $kopSurat->telepon_instansi]);
            }
            fputcsv($file, ['']);
            fputcsv($file, ['Periode: ' . 
                ($request->bulan ? Carbon::create()->month((int) $request->bulan)->translatedFormat('F') : 'Semua Bulan') . ' ' .
                ($request->tahun ? $request->tahun : date('Y'))
            ]);
            fputcsv($file, ['Tanggal Cetak: ' . Carbon::now()->translatedFormat('d F Y H:i:s')]);
            fputcsv($file, ['']);
            
            // Header Data CSV
            fputcsv($file, [
                'NIK',
                'Nama Karyawan',
                'Departemen',
                'Tanggal Izin',
                'Tipe',
                'Keterangan',
                'Status',
                'Tanggal Pengajuan'
            ]);

            // Data
            foreach ($pengajuanIzins as $izin) {
                fputcsv($file, [
                    $izin->nik,
                    $izin->karyawan->nama_lengkap,
                    $izin->karyawan->departemen->nama_departemen ?? '-',
                    \Carbon\Carbon::parse($izin->tanggal_izin)->format('d/m/Y'),
                    $izin->status_text,
                    $izin->keterangan,
                    $izin->status_approved_text,
                    $izin->created_at->format('d/m/Y H:i')
                ]);
            }

            // Footer CSV - Tanda Tangan
            fputcsv($file, ['']);
            fputcsv($file, ['']);
            fputcsv($file, [$this->extractCityFromAddress($kopSurat ? $kopSurat->alamat_instansi : null) . ', ' . Carbon::now()->translatedFormat('d F Y')]);
            fputcsv($file, ['']);
            fputcsv($file, [$kopSurat ? $kopSurat->jabatan_pimpinan : 'Pimpinan']);
            fputcsv($file, ['']);
            fputcsv($file, ['']);
            fputcsv($file, ['']);
            fputcsv($file, [$kopSurat ? $kopSurat->nama_pimpinan : 'Nama Pimpinan']);
            if ($kopSurat && $kopSurat->nip_pimpinan) {
                fputcsv($file, ['NIP: ' . $kopSurat->nip_pimpinan]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get summary data for dashboard cards.
     */
    private function getSummaryData($request)
    {
        $query = Izin::query();

        // Apply same filters as main query
        if ($request->filled('bulan')) {
            $query->byMonth($request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->byYear($request->tahun);
        } else {
            $query->byYear(date('Y'));
        }

        return [
            'total' => $query->count(),
            'pending' => $query->clone()->where('status_approved', 0)->count(),
            'approved' => $query->clone()->where('status_approved', 1)->count(),
            'rejected' => $query->clone()->where('status_approved', 2)->count(),
            'izin' => $query->clone()->where('status', 'i')->count(),
            'sakit' => $query->clone()->where('status', 's')->count(),
        ];
    }

    /**
     * Cetak laporan pengajuan izin dalam bentuk PDF
     */
    public function cetakPDF(Request $request)
    {
        $query = Izin::with('karyawan');

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->byNama($request->search);
        }
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('approval')) {
            $query->byApproval($request->approval);
        }
        if ($request->filled('bulan')) {
            $query->byMonth($request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->byYear($request->tahun);
        } else {
            $query->byYear(date('Y'));
        }

        $pengajuanIzins = $query->orderBy('tanggal_izin', 'desc')->get();

        // Get summary data
        $summary = $this->getSummaryData($request);

        // Siapkan data filter untuk tampilan
        $filterStatus = null;
        if ($request->filled('status')) {
            $filterStatus = $request->status == 'i' ? 'Izin' : 'Sakit';
        }

        $filterApproval = null;
        if ($request->filled('approval')) {
            switch($request->approval) {
                case '0': $filterApproval = 'Pending'; break;
                case '1': $filterApproval = 'Disetujui'; break;
                case '2': $filterApproval = 'Ditolak'; break;
            }
        }

        $period = ($request->bulan ? Carbon::create()->month((int) $request->bulan)->translatedFormat('F') : 'Semua Bulan') . ' ' . ($request->tahun ? $request->tahun : date('Y'));

        // Ambil data untuk PDF menggunakan trait
        $title = 'Laporan Pengajuan Izin / Sakit';
        
        $data = $this->getPDFData($title, $period);
        $data['pengajuanIzins'] = $pengajuanIzins;
        $data['summary'] = $summary;
        $data['filterStatus'] = $filterStatus;
        $data['filterApproval'] = $filterApproval;

        // Generate PDF
        $pdf = Pdf::loadView('admin.dashboard.pengajuan-izin.pdf', $data)
            ->setPaper('a4', 'landscape');

        $fileName = 'laporan_pengajuan_izin_' . date('Y-m-d_H-i-s') . '.pdf';
        return $pdf->download($fileName);
    }
}
