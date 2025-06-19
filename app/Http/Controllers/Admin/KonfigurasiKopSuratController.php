<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KopSurat;
use Illuminate\Support\Facades\Storage;

class KonfigurasiKopSuratController extends Controller
{
    /**
     * Display the form for configuring kop surat.
     */
    public function index()
    {
        $kopSurat = KopSurat::getActive();
        
        // Jika belum ada konfigurasi, buat default
        if (!$kopSurat) {
            $kopSurat = new KopSurat();
            $kopSurat->fill([
                'nama_instansi' => 'PT. ARISYA TEKNOLOGI INDONESIA',
                'alamat_instansi' => 'Jl. Raya Ciamis No. 123, Kecamatan Ciamis, Kabupaten Ciamis, Jawa Barat 46211',
                'telepon_instansi' => '(0265) 123456',
                'email_instansi' => 'info@arisya.co.id',
                'website_instansi' => 'www.arisya.co.id',
                'nama_pimpinan' => 'John Doe, S.T., M.T.',
                'jabatan_pimpinan' => 'Direktur Utama',
                'nip_pimpinan' => '198501012010011001',
                'is_active' => true
            ]);
        }
        
        return view('admin.dashboard.konfigurasi.kop-surat.index', compact('kopSurat'));
    }

    /**
     * Update or create kop surat configuration.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'alamat_instansi' => 'required|string|max:500',
            'telepon_instansi' => 'nullable|string|max:20',
            'email_instansi' => 'nullable|email|max:100',
            'website_instansi' => 'nullable|string|max:100',
            'nama_pimpinan' => 'required|string|max:255',
            'jabatan_pimpinan' => 'required|string|max:100',
            'nip_pimpinan' => 'nullable|string|max:50',
            'logo_instansi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'nama_instansi.required' => 'Nama instansi wajib diisi',
            'alamat_instansi.required' => 'Alamat instansi wajib diisi',
            'email_instansi.email' => 'Format email tidak valid',
            'nama_pimpinan.required' => 'Nama pimpinan wajib diisi',
            'jabatan_pimpinan.required' => 'Jabatan pimpinan wajib diisi',
            'logo_instansi.image' => 'File harus berupa gambar',
            'logo_instansi.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'logo_instansi.max' => 'Ukuran gambar maksimal 2MB'
        ]);

        try {
            $kopSurat = KopSurat::getActive();
            
            // Jika belum ada, buat baru
            if (!$kopSurat) {
                $kopSurat = new KopSurat();
            }

            // Handle upload logo
            $logoFileName = $kopSurat->logo_instansi;
            if ($request->hasFile('logo_instansi')) {
                // Hapus logo lama jika ada
                if ($logoFileName && Storage::disk('public')->exists('uploads/logo/' . $logoFileName)) {
                    Storage::disk('public')->delete('uploads/logo/' . $logoFileName);
                }

                // Upload logo baru
                $file = $request->file('logo_instansi');
                $logoFileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads/logo', $logoFileName, 'public');
            }

            // Update data
            $kopSurat->fill([
                'nama_instansi' => $request->nama_instansi,
                'alamat_instansi' => $request->alamat_instansi,
                'telepon_instansi' => $request->telepon_instansi,
                'email_instansi' => $request->email_instansi,
                'website_instansi' => $request->website_instansi,
                'logo_instansi' => $logoFileName,
                'nama_pimpinan' => $request->nama_pimpinan,
                'jabatan_pimpinan' => $request->jabatan_pimpinan,
                'nip_pimpinan' => $request->nip_pimpinan,
                'is_active' => true
            ]);

            $kopSurat->save();

            return redirect()->route('admin.konfigurasi.kop-surat.index')
                ->with('success', 'Konfigurasi kop surat berhasil disimpan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan konfigurasi: ' . $e->getMessage());
        }
    }

    /**
     * Preview kop surat configuration.
     */
    public function preview()
    {
        $kopSurat = KopSurat::getActive();
        
        if (!$kopSurat) {
            return response()->json([
                'error' => 'Konfigurasi kop surat belum tersedia'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama_instansi' => $kopSurat->nama_instansi,
                'alamat_instansi' => $kopSurat->alamat_instansi,
                'telepon_instansi' => $kopSurat->telepon_instansi,
                'email_instansi' => $kopSurat->email_instansi,
                'website_instansi' => $kopSurat->website_instansi,
                'logo_url' => $kopSurat->logo_url,
                'nama_pimpinan' => $kopSurat->nama_pimpinan,
                'jabatan_pimpinan' => $kopSurat->jabatan_pimpinan,
                'nip_pimpinan' => $kopSurat->nip_pimpinan
            ]
        ]);
    }
}
