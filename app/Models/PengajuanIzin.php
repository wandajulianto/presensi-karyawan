<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Model;
use App\Models\Karyawan;

class PengajuanIzin extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'tanggal_izin',
        'status',
        'keterangan',
        'status_approved',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
}
