<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Karyawan;

class Departemen extends Model
{
    use HasFactory;

    protected $primaryKey = 'kode_departemen';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_departemen',
        'nama_departemen'
    ];

    public function karyawans(): HasMany
    {
        return $this->hasMany(Karyawan::class, 'kode_departemen', 'kode_departemen');
    }
}