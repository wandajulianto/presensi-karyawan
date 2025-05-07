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
}
