<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nik',
        'tanggal_presensi',
        'jam_masuk',
        'foto_masuk',
        'jam_pulang',
        'foto_pulang',
        'status',
        'keterangan'
    ];

    // Relasi ke model Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
} 