<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class AdminProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil admin.
     */
    public function edit()
    {
        $user = Auth::guard('user')->user();
        
        return view('admin.dashboard.profile.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update profil admin.
     */
    public function update(Request $request)
    {
        $user = Auth::guard('user')->user();

        // Validasi input dasar
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
        ];

        $messages = [
            'name.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg.',
            'foto.max' => 'Ukuran foto maksimal 2MB.'
        ];

        // Validasi password hanya jika diisi
        if ($request->filled('password')) {
            $passwordRules = [
                'password' => ['nullable', 'confirmed', 'min:8'],
                'password_confirmation' => ['nullable']
            ];
            $passwordMessages = [
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.min' => 'Password minimal 8 karakter.'
            ];
            
            // Validasi password terpisah
            $request->validate($passwordRules, $passwordMessages);
        }

        // Validasi field lainnya
        $request->validate($rules, $messages);

        try {
            // Data yang akan diupdate
            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Update password jika diisi
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            // Upload foto jika ada
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = 'admin_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Hapus foto lama jika ada
                if ($user->foto && Storage::disk('public')->exists('uploads/foto_user/' . $user->foto)) {
                    Storage::disk('public')->delete('uploads/foto_user/' . $user->foto);
                }

                // Simpan foto baru
                $file->storeAs('uploads/foto_user', $filename, 'public');
                $data['foto'] = $filename;
            }

            // Update data user
            $user->update($data);

            return redirect()->route('admin.profile.edit')
                ->with('success', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->route('admin.profile.edit')
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
}
