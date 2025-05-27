<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kantor extends Model
{
    use HasFactory;

    protected $table = 'kantor';

    protected $fillable = [
        'nama_kantor',
        'alamat', 
        'latitude',
        'longitude',
        'radius_meter',
        'kode_kantor',
        'deskripsi',
        'is_active',
        'timezone',
        'jam_masuk',
        'jam_keluar'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
        'jam_masuk' => 'datetime:H:i:s',
        'jam_keluar' => 'datetime:H:i:s'
    ];

    /**
     * Scope untuk kantor yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Mendapatkan kantor utama (default)
     */
    public static function getKantorUtama()
    {
        return static::active()->first();
    }

    /**
     * Menghitung jarak dari koordinat tertentu ke kantor (dalam meter)
     */
    public function hitungJarak($lat, $lng)
    {
        $earthRadius = 6371000; // radius bumi dalam meter

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($lat);
        $lonTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Mengecek apakah koordinat berada dalam radius kantor
     */
    public function dalamRadius($lat, $lng)
    {
        $jarak = $this->hitungJarak($lat, $lng);
        return $jarak <= $this->radius_meter;
    }

    /**
     * Format alamat lengkap
     */
    public function getAlamatLengkapAttribute()
    {
        return $this->nama_kantor . ', ' . $this->alamat;
    }
}
