<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicine extends Model
{
    protected $fillable = [
        'nama',
        'kategori',
        'satuan',
        'harga',
        'stok',
        'min_stock',
    ];

    public function medicalRecords(): BelongsToMany
    {
        return $this->belongsToMany(MedicalRecord::class, 'medical_record_medicine')
                    ->withPivot('dosis', 'jumlah')
                    ->withTimestamps();
    }
}
