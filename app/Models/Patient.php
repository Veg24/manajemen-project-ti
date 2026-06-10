<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama',
        'NIK',
        'tgl_lahir',
        'alamat',
        'no_telp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class)->orderBy('created_at', 'desc');
    }

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class)->orderBy('tanggal', 'desc');
    }
}
