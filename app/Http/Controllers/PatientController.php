<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     */
    public function index(Request $request)
    {
        $query = Patient::query();

        // Admin can filter by status 'nonaktif' (soft-deleted data)
        if ($request->get('status') === 'nonaktif' && auth()->user()->isAdmin()) {
            $query->onlyTrashed();
        }

        // Search feature (US-02) - search by name or NIK (case-insensitive)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('NIK', 'LIKE', "%{$search}%");
            });
        }

        $patients = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        // Only Resepsionis and Admin can register
        if (!auth()->user()->isResepsionis() && !auth()->user()->isAdmin()) {
            abort(403, 'Hanya Resepsionis atau Admin yang dapat mendaftarkan pasien.');
        }

        return view('patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isResepsionis() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'NIK' => 'required|string|size:16|unique:patients,NIK',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            // Option to create user account
            'create_account' => 'nullable|boolean',
            'email' => 'required_if:create_account,1|nullable|email|unique:users,email',
            'password' => 'required_if:create_account,1|nullable|string|min:8|confirmed',
        ], [
            'NIK.size' => 'NIK harus tepat 16 digit.',
            'NIK.unique' => 'NIK ini sudah terdaftar.',
            'email.required_if' => 'Email wajib diisi jika ingin membuat akun.',
            'password.required_if' => 'Password wajib diisi jika ingin membuat akun.',
        ]);

        DB::transaction(function () use ($request) {
            $userId = null;

            // If create account is checked, create a user with Pasien role
            if ($request->boolean('create_account')) {
                $user = User::create([
                    'name' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'Pasien',
                ]);
                $userId = $user->id;
            }

            Patient::create([
                'user_id' => $userId,
                'nama' => $request->nama,
                'NIK' => $request->NIK,
                'tgl_lahir' => $request->tgl_lahir,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
            ]);
        });

        return redirect()->route('patients.index')->with('success', 'Pasien berhasil didaftarkan.');
    }

    /**
     * Display the specified patient profile (US-03 & US-08).
     */
    public function show(Patient $patient)
    {
        $user = auth()->user();

        // Authorization:
        // Logged-in Patient can ONLY see their own profile history.
        if ($user->isPasien()) {
            if ($patient->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki hak akses untuk melihat data pasien lain.');
            }
        }

        // Get medical records and queues ordered descending
        $medicalRecords = $patient->medicalRecords()->with('doctor', 'medicines')->get();
        $queues = $patient->queues()->with('doctor')->get();

        return view('patients.show', compact('patient', 'medicalRecords', 'queues'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
    {
        if (!auth()->user()->isResepsionis() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        if (!auth()->user()->isResepsionis() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'NIK' => [
                'required',
                'string',
                'size:16',
                Rule::unique('patients')->ignore($patient->id),
            ],
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
        ], [
            'NIK.size' => 'NIK harus tepat 16 digit.',
            'NIK.unique' => 'NIK ini sudah terdaftar.',
        ]);

        $patient->update([
            'nama' => $request->nama,
            'NIK' => $request->NIK,
            'tgl_lahir' => $request->tgl_lahir,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
        ]);

        // If patient is linked to a user, sync user's name
        if ($patient->user) {
            $patient->user->update([
                'name' => $request->nama,
            ]);
        }

        return redirect()->route('patients.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Deactivate (SoftDelete) the patient. Accessible only by Admin.
     */
    public function destroy(Patient $patient)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin yang dapat menonaktifkan data pasien.');
        }

        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Data pasien berhasil dinonaktifkan.');
    }

    /**
     * Restore a soft-deleted patient. Accessible only by Admin.
     */
    public function restore($id)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $patient = Patient::onlyTrashed()->findOrFail($id);
        $patient->restore();

        return redirect()->route('patients.index')->with('success', 'Data pasien berhasil diaktifkan kembali.');
    }
}
