<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MedicalRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'keluhan',
        'kode_icd_10',
        'tindakan_medis',
        'tekanan_darah',
        'suhu',
        'nadi',
        'berat_badan',
        'tinggi_badan',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class, 'medical_record_medicine')
                    ->withPivot('dosis', 'jumlah')
                    ->withTimestamps();
    }
}
