<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Izin extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_izins';
    
    protected $fillable = [
        'nik',
        'tanggal_izin',
        'status',
        'keterangan',
        'status_approved'
    ];

    protected $casts = [
        'tanggal_izin' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }

    // Accessor untuk status
    public function getStatusTextAttribute()
    {
        return $this->status === 'i' ? 'Izin' : 'Sakit';
    }

    // Accessor untuk status approval
    public function getStatusApprovedTextAttribute()
    {
        switch ($this->status_approved) {
            case 0:
                return 'Pending';
            case 1:
                return 'Disetujui';
            case 2:
                return 'Ditolak';
            default:
                return 'Unknown';
        }
    }

    // Accessor untuk badge class
    public function getStatusApprovedBadgeAttribute()
    {
        switch ($this->status_approved) {
            case 0:
                return 'warning';
            case 1:
                return 'success';
            case 2:
                return 'danger';
            default:
                return 'secondary';
        }
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Scope untuk filter berdasarkan approval
    public function scopeByApproval($query, $approval)
    {
        if ($approval !== null && $approval !== '') {
            return $query->where('status_approved', $approval);
        }
        return $query;
    }

    // Scope untuk filter berdasarkan bulan
    public function scopeByMonth($query, $month)
    {
        if ($month) {
            return $query->whereMonth('tanggal_izin', $month);
        }
        return $query;
    }

    // Scope untuk filter berdasarkan tahun
    public function scopeByYear($query, $year)
    {
        if ($year) {
            return $query->whereYear('tanggal_izin', $year);
        }
        return $query;
    }

    // Scope untuk pencarian nama karyawan
    public function scopeByNama($query, $nama)
    {
        if ($nama) {
            return $query->whereHas('karyawan', function($q) use ($nama) {
                $q->where('nama_lengkap', 'like', '%' . $nama . '%');
            });
        }
        return $query;
    }
}
