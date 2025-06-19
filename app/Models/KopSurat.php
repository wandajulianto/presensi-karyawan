<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KopSurat extends Model
{
    use HasFactory;

    protected $table = 'kop_surat';

    protected $fillable = [
        'nama_instansi',
        'alamat_instansi',
        'telepon_instansi',
        'email_instansi',
        'website_instansi',
        'logo_instansi',
        'nama_pimpinan',
        'jabatan_pimpinan',
        'nip_pimpinan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the active kop surat configuration
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Get logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo_instansi && Storage::disk('public')->exists('uploads/logo/' . $this->logo_instansi)) {
            return Storage::url('uploads/logo/' . $this->logo_instansi);
        }
        
        return asset('assets/img/logo.png'); // default logo
    }

    /**
     * Get logo path for PDF
     */
    public function getLogoPathAttribute()
    {
        if ($this->logo_instansi && file_exists(public_path('storage/uploads/logo/' . $this->logo_instansi))) {
            return public_path('storage/uploads/logo/' . $this->logo_instansi);
        }
        
        return public_path('assets/img/logo.png'); // default logo path
    }
}
