<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Central Dashboard (US-10, US-13, etc.)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // EPIC 1: Patient Management
    Route::resource('patients', PatientController::class)->except(['destroy']);
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy')->middleware('role:Admin');
    Route::patch('/patients/{id}/restore', [PatientController::class, 'restore'])->name('patients.restore')->middleware('role:Admin');

    // EPIC 2: Medical Records (Dokter & Perawat)
    Route::get('/queues/{queue}/vitals', [MedicalRecordController::class, 'vitalsForm'])->name('medical-records.vitals')->middleware('role:Admin,Perawat');
    Route::post('/queues/{queue}/vitals', [MedicalRecordController::class, 'storeVitals'])->name('medical-records.store-vitals')->middleware('role:Admin,Perawat');
    Route::get('/queues/{queue}/consult', [MedicalRecordController::class, 'consultForm'])->name('medical-records.consult')->middleware('role:Admin,Dokter');
    Route::post('/queues/{queue}/consult', [MedicalRecordController::class, 'storeConsult'])->name('medical-records.store-consult')->middleware('role:Admin,Dokter');

    // EPIC 3: Queues & Doctor Schedules
    // Booking Online (Pasien)
    Route::get('/queues/book', [QueueController::class, 'bookForm'])->name('queues.book')->middleware('role:Admin,Pasien');
    Route::post('/queues/book', [QueueController::class, 'storeBook'])->name('queues.store-book')->middleware('role:Admin,Pasien');
    // Monitor Antrean (Resepsionis)
    Route::get('/queues/monitor', [QueueController::class, 'monitor'])->name('queues.monitor')->middleware('role:Admin,Resepsionis');
    Route::post('/queues/{queue}/status', [QueueController::class, 'updateStatus'])->name('queues.update-status')->middleware('role:Admin,Resepsionis,Dokter,Perawat');
    // Doctor Schedules CRUD (Admin)
    Route::resource('doctor-schedules', ScheduleController::class)->middleware('role:Admin');

    // EPIC 4: Medicines Inventory
    Route::resource('medicines', MedicineController::class)->middleware('role:Admin,Apoteker');
    // Pharmacist Dashboard
    Route::get('/pharmacy/prescriptions', [MedicineController::class, 'prescriptionsIndex'])->name('pharmacy.prescriptions')->middleware('role:Admin,Apoteker');
    Route::post('/pharmacy/prescriptions/{record}/dispense', [MedicineController::class, 'dispensePrescription'])->name('pharmacy.dispense')->middleware('role:Admin,Apoteker');

    // EPIC 5: Reports & Financials
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:Admin,Dokter');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export')->middleware('role:Admin,Dokter');
});

require __DIR__.'/auth.php';

