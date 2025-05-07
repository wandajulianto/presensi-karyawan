<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;

class DataMasterController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::orderBy('nama_lengkap', 'asc')
            ->leftJoin('departemens', 'karyawan.kode_departemen', '=', 'departemens.kode_departemen')
            ->get();

        return view('admin.dashboard.dataMaster', compact('karyawans'));
    }
}
