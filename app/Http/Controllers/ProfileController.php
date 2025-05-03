<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile untuk user.
     */
    public function edit()
    {
        $user = Auth::guard('karyawan')->user();
        $nik = $user->nik;

        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        return view('profile.edit', [
            'karyawan' => $karyawan
        ]);
    }

    /**
     * Update data profile.
     */
    public function update(Request $request, $nik)
    {
        // Validasi input
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // Data yang akan diupdate
        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp' => $request->no_hp
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = $nik . '.' . $file->getClientOriginalExtension();

            // Simpan file ke storage/app/public/uploads/foto_karyawan
            $file->storeAs('uploads/foto_karyawan', $filename, 'public');

            $data['foto'] = $filename;
        }

        // Update ke database
        DB::table('karyawan')->where('nik', $nik)->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
