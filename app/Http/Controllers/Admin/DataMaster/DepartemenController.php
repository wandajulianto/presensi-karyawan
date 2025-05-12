<?php

namespace App\Http\Controllers\Admin\DataMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Departemen;

class DepartemenController extends Controller
{
    public function index(Request $request)
    {
        $query = Departemen::query();

        // Pencarian nama departemen
        if ($request->filled('nama_departemen')) {
            $query->where('nama_departemen', 'like', '%' . $request->nama_departemen . '%');
        }

        $departemens = $query->orderBy('nama_departemen', 'asc')->paginate(10);
        return view('admin.dashboard.dataMaster.departemen.index', compact('departemens'));
    }

    public function create()
    {
        return view('admin.dashboard.dataMaster.departemen.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'kode_departemen' => 'required|string|max:10|unique:departemens,kode_departemen',
                'nama_departemen' => 'required|string|max:100'
            ]);

            Departemen::create($request->all());

            return redirect()->route('data-master.departemen')
                ->with('success', 'Data departemen berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('data-master.departemen')
                ->with('error', 'Gagal menyimpan data departemen: ' . $e->getMessage());
        }
    }

    public function edit($kode_departemen)
    {
        $departemen = Departemen::findOrFail($kode_departemen);
        return view('admin.dashboard.dataMaster.departemen.edit', compact('departemen'));
    }

    public function update(Request $request, $kode_departemen)
    {
        try {
            $request->validate([
                'nama_departemen' => 'required|string|max:100'
            ]);

            $departemen = Departemen::findOrFail($kode_departemen);
            $departemen->update($request->all());

            return redirect()->route('data-master.departemen')
                ->with('success', 'Data departemen berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('data-master.departemen')
                ->with('error', 'Gagal memperbarui data departemen: ' . $e->getMessage());
        }
    }

    public function delete($kode_departemen)
    {
        try {
            $departemen = Departemen::findOrFail($kode_departemen);
            
            // Cek apakah departemen masih digunakan oleh karyawan
            if ($departemen->karyawan()->exists()) {
                return redirect()->route('data-master.departemen')
                    ->with('error', 'Departemen tidak dapat dihapus karena masih digunakan oleh karyawan');
            }
            
            $departemen->delete();

            return redirect()->route('data-master.departemen')
                ->with('success', 'Data departemen berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('data-master.departemen')
                ->with('error', 'Gagal menghapus data departemen: ' . $e->getMessage());
        }
    }
}
