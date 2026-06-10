<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Database\Factories\UserFactory;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // A user with role 'Pasien' may have a Patient profile record
    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id');
    }

    // A user with role 'Dokter' has schedules, medical records, and queues
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }

    public function doctorMedicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }

    public function doctorQueues()
    {
        return $this->hasMany(Queue::class, 'doctor_id');
    }

    // Helper Role Checkers
    public function isAdmin(): bool { return $this->role === 'Admin'; }
    public function isResepsionis(): bool { return $this->role === 'Resepsionis'; }
    public function isPasien(): bool { return $this->role === 'Pasien'; }
    public function isDokter(): bool { return $this->role === 'Dokter'; }
    public function isPerawat(): bool { return $this->role === 'Perawat'; }
    public function isApoteker(): bool { return $this->role === 'Apoteker'; }
}
