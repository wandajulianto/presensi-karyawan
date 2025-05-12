<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Departemen;

class DataMasterController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'nullable|string|max:100',
            'departemen' => 'nullable|string|max:10',
        ]);

        $query = Karyawan::query()->with('departemen');

        // Pencarian nama (selalu aktif jika diisi)
        if ($request->filled('nama_lengkap')) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }

        // Filter departemen (hanya aktif jika bukan value default)
        if ($request->filled('departemen') && $request->departemen !== '') {
            if ($request->departemen == 'null') {
                $query->whereNull('kode_departemen');
            } else {
                $query->where('kode_departemen', $request->departemen);
            }
        }

        $karyawans = $query->orderBy('nama_lengkap', 'asc')->paginate(10);
        $departemens = Departemen::all();

        return view('admin.dashboard.dataMaster', compact('karyawans', 'departemens'));
    }

    public function create()
    {
        $departemens = Departemen::all();
        return view('admin.dashboard.tambahKaryawan', compact('departemens'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nik' => 'required|numeric|unique:karyawan,nik',
                'nama_lengkap' => 'required|string|max:100',
                'jabatan' => 'required|string|max:50',
                'no_hp' => 'required|string|max:15',
                'kode_departemen' => 'nullable|string|exists:departemens,kode_departemen',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $data = $request->except('foto');
            
            // Set password default (NIK karyawan)
            $data['password'] = $request->nik;
            
            // Handle upload foto
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoName = time() . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('uploads/foto_karyawan', $fotoName, 'public');
                $data['foto'] = $fotoName;
            }

            // Jika kode_departemen kosong, set ke null
            if (empty($data['kode_departemen'])) {
                $data['kode_departemen'] = null;
            }

            Karyawan::create($data);

            return redirect()->route('data-master.karyawan')
                ->with('success', 'Data karyawan berhasil ditambahkan. Password default adalah NIK karyawan.');
        } catch (\Exception $e) {
            return redirect()->route('data-master.karyawan')
                ->with('error', 'Gagal menyimpan data karyawan: ' . $e->getMessage());
        }
    }
}
