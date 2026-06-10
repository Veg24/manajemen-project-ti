# SI-KLINIK (Clinic Management Information System)

SI-KLINIK is a comprehensive, premium web-based **Clinic Management Information System** built using **Laravel**, **TailwindCSS**, and **SQLite/MySQL**. It supports role-based access control (RBAC) across six specific roles: Admin, Receptionist (Resepsionis), Patient (Pasien), Doctor (Dokter), Nurse (Perawat), and Pharmacist (Apoteker).

---

## 🚀 Key Features by User Role

### 🛡️ Admin Panel
* **Medicine Inventory (US-16):** Full CRUD for medicines. Input validation prevents negative prices or stock quantities.
* **Low-Stock Alerts (US-15):** Automatic highlighted red warning badges for items where `stock <= min_stock`.
* **Doctor Schedules (US-11):** CRUD interface to manage doctor shift days and times.
* **Clinic Reports (US-17, US-18, US-19):** Date-filtered metrics showing daily visits, financial breakdowns, and a Chart.js Line/Bar graph.
* **PDF Export (US-20):** One-click download of clinic financial statements compiled cleanly using `dompdf`.

### 💁 Receptionist (Resepsionis)
* **Patient CRUD (US-01):** Smooth registration and profile editing. Includes NIK validation (exactly 16 digits, must be unique).
* **Deactivation / SoftDeletes (US-04):** Soft deletes to deactivate patients, filterable under "Data Nonaktif" (restorable by Admin).
* **Patient Search (US-02):** Case-insensitive search bar filtering by Patient Name or NIK.
* **Queue Monitor (US-10):** Real-time monitoring board showing today's queue cards with active status updating controls.

### 🩺 Nurse (Perawat)
* **Vitals Signs Entry (US-07):** Pre-consultation form to log patient temperature (°C), blood pressure (mmHg), pulse rate (bpm), weight (kg), and height (cm).

### 🥼 Doctor (Dokter)
* **Patient Consultations (US-05):** Log patient complaints (keluhan), diagnosis ICD-10 codes, and medical actions.
* **Digital Prescription (US-06):** Dynamic JavaScript widget to select, adjust, and append multiple medicines and instructions in one session.
* **Medical Timeline (US-08):** Side-by-side chronological history timeline showing past diagnoses and prescriptions.
* **Personal Statistics (US-19):** Dashboard Chart.js Pie chart visualizing top 5 diagnosed ICD-10 codes.

### 💊 Pharmacist (Apoteker)
* **Prescription Dispatch (US-13):** Lists pending prescription tickets containing direct dosing details and quantity requested.
* **Atomic Stock Deduction (US-14):** Safe decrement transactions using `DB::transaction` with row-level pessimistic locks (`lockForUpdate`). Automatically rolls back and shows warnings if a medicine's stock is insufficient.

### 👤 Patient (Pasien)
* **Online Booking (US-09):** Automated ticket numbers (e.g. `U-001`, `A-001`) generated based on selected Doctor/Poli. Booking is blocked if capacity limit is reached (10 bookings/session) or if the doctor has no shift on that day.
* **Queue Alerts (US-12):** Active warning badge appearing on the patient's dashboard when their turn is less than or equal to 2 people away.
* **Medical Record History (US-03):** Chronological log of their personal medical consultations. Restrictive authorization prevents users from accessing other patients' histories (throws a 403 error).

---

## 🛠️ Technology Stack
* **Backend Framework:** Laravel (Latest)
* **Frontend styling:** TailwindCSS v4.0.0 & Blade Templates
* **Database Engine:** SQLite (Local development default) / MySQL compatible
* **Graphics/Visualization:** Chart.js (Loaded dynamically)
* **Document compiler:** Barryvdh/Laravel-DomPDF

---

## 💾 Seeded Credentials for Testing

The database comes pre-seeded with accounts for all roles. The default password for all seed accounts is `password`.

| Role | Email | Description |
| :--- | :--- | :--- |
| **Admin** | `admin@klinik.com` | Manages schedules, master inventory, and financial statements. |
| **Receptionist** | `resepsionis@klinik.com` | Registers patients and controls queues. |
| **Doctor (General)** | `dokter1@klinik.com` | Conducts general practitioner consultations. |
| **Doctor (Pediatric)** | `dokter2@klinik.com` | Conducts pediatric consultations. |
| **Nurse** | `perawat@klinik.com` | Records vital signs. |
| **Pharmacist** | `apoteker@klinik.com` | Dispenses medications and decreases stock. |
| **Patient** | `pasien@klinik.com` | Books queues and views their medical cards. |

---

## ⚙️ Installation & Setup

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Veg24/manajemen-project-ti.git
   cd manajemen-project-ti
   ```
2. **Install Composer Packages:**
   ```bash
   composer install
   ```
3. **Configure Environment File:**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
   *Note: By default, the database is configured to use `sqlite` pointing to `database/database.sqlite`. Make sure the file exists or run `touch database/database.sqlite`.*
4. **Run Database Migrations and Seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```
5. **Install NPM Packages and Compile Assets:**
   ```bash
   npm install
   npm run build
   ```
6. **Launch Development Server:**
   ```bash
   php artisan serve
   ```
   Open `http://127.0.5.1:8000` in your web browser. Run `npm run dev` in a separate terminal for hot-reloading asset compilation.
