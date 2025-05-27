<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kantor;

class KantorController extends Controller
{
    /**
     * Display a listing of kantor.
     */
    public function index()
    {
        $kantors = Kantor::orderBy('is_active', 'desc')
            ->orderBy('nama_kantor')
            ->get();

        return view('admin.dashboard.konfigurasi.kantor.index', compact('kantors'));
    }

    /**
     * Show the form for creating a new kantor.
     */
    public function create()
    {
        return view('admin.dashboard.konfigurasi.kantor.create');
    }

    /**
     * Store a newly created kantor in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kantor' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:1|max:1000',
            'kode_kantor' => 'required|string|max:10|unique:kantor,kode_kantor',
            'deskripsi' => 'nullable|string',
            'timezone' => 'required|string',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required',
        ], [
            'nama_kantor.required' => 'Nama kantor wajib diisi',
            'alamat.required' => 'Alamat kantor wajib diisi',
            'latitude.required' => 'Latitude wajib diisi',
            'latitude.between' => 'Latitude harus antara -90 sampai 90',
            'longitude.required' => 'Longitude wajib diisi',
            'longitude.between' => 'Longitude harus antara -180 sampai 180',
            'radius_meter.required' => 'Radius meter wajib diisi',
            'radius_meter.min' => 'Radius minimal 1 meter',
            'radius_meter.max' => 'Radius maksimal 1000 meter',
            'kode_kantor.required' => 'Kode kantor wajib diisi',
            'kode_kantor.unique' => 'Kode kantor sudah digunakan',
            'timezone.required' => 'Timezone wajib dipilih',
            'jam_masuk.required' => 'Jam masuk wajib diisi',
            'jam_keluar.required' => 'Jam keluar wajib diisi',
        ]);

        Kantor::create([
            'nama_kantor' => $request->nama_kantor,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_meter' => $request->radius_meter,
            'kode_kantor' => strtoupper($request->kode_kantor),
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
            'timezone' => $request->timezone,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
        ]);

        return redirect()->route('admin.kantor.index')
            ->with('success', 'Data kantor berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified kantor.
     */
    public function edit(Kantor $kantor)
    {
        return view('admin.dashboard.konfigurasi.kantor.edit', compact('kantor'));
    }

    /**
     * Update the specified kantor in storage.
     */
    public function update(Request $request, Kantor $kantor)
    {
        $request->validate([
            'nama_kantor' => 'required|string|max:255',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:1|max:1000',
            'kode_kantor' => 'required|string|max:10|unique:kantor,kode_kantor,' . $kantor->id,
            'deskripsi' => 'nullable|string',
            'timezone' => 'required|string',
            'jam_masuk' => 'required',
            'jam_keluar' => 'required',
        ], [
            'nama_kantor.required' => 'Nama kantor wajib diisi',
            'alamat.required' => 'Alamat kantor wajib diisi',
            'latitude.required' => 'Latitude wajib diisi',
            'latitude.between' => 'Latitude harus antara -90 sampai 90',
            'longitude.required' => 'Longitude wajib diisi',
            'longitude.between' => 'Longitude harus antara -180 sampai 180',
            'radius_meter.required' => 'Radius meter wajib diisi',
            'radius_meter.min' => 'Radius minimal 1 meter',
            'radius_meter.max' => 'Radius maksimal 1000 meter',
            'kode_kantor.required' => 'Kode kantor wajib diisi',
            'kode_kantor.unique' => 'Kode kantor sudah digunakan',
            'timezone.required' => 'Timezone wajib dipilih',
            'jam_masuk.required' => 'Jam masuk wajib diisi',
            'jam_keluar.required' => 'Jam keluar wajib diisi',
        ]);

        $kantor->update([
            'nama_kantor' => $request->nama_kantor,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_meter' => $request->radius_meter,
            'kode_kantor' => strtoupper($request->kode_kantor),
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active'),
            'timezone' => $request->timezone,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
        ]);

        return redirect()->route('admin.kantor.index')
            ->with('success', 'Data kantor berhasil diupdate');
    }

    /**
     * Remove the specified kantor from storage.
     */
    public function destroy(Kantor $kantor)
    {
        // Cek apakah kantor masih aktif
        if ($kantor->is_active) {
            return redirect()->route('admin.kantor.index')
                ->with('error', 'Tidak dapat menghapus kantor yang masih aktif');
        }

        $kantor->delete();

        return redirect()->route('admin.kantor.index')
            ->with('success', 'Data kantor berhasil dihapus');
    }

    /**
     * Set kantor as active (hanya satu kantor yang bisa aktif)
     */
    public function setActive(Kantor $kantor)
    {
        // Nonaktifkan semua kantor
        Kantor::query()->update(['is_active' => false]);

        // Aktifkan kantor yang dipilih
        $kantor->update(['is_active' => true]);

        return redirect()->route('admin.kantor.index')
            ->with('success', "Kantor {$kantor->nama_kantor} berhasil diaktifkan");
    }

    /**
     * Get coordinates dari alamat (optional - untuk geocoding)
     */
    public function geocode(Request $request)
    {
        $alamat = $request->alamat;
        
        // Simulasi geocoding - dalam implementasi nyata bisa menggunakan Google Maps API
        return response()->json([
            'success' => true,
            'latitude' => -7.333174936756437,
            'longitude' => 108.2197967875599,
            'message' => 'Silakan adjust koordinat sesuai lokasi yang tepat'
        ]);
    }
}
