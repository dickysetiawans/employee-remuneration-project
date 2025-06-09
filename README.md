# 📘 Employee Remuneration App

Aplikasi pencatatan pekerjaan pegawai dan perhitungan remunerasi, lengkap dengan pembagian prorata jika lebih dari satu pegawai mengerjakan tugas yang sama.

---

## 📐 Arsitektur Solusi

```mermaid
graph LR
    Frontend(Next.js) -->|HTTP Request (axios/fetch)| Backend(Laravel API)
    Backend -->|JSON Response| Frontend
    Backend --> Database[(MySQL / MariaDB)]
```

- **Frontend (Next.js)**: Menyediakan antarmuka pengguna untuk input dan melihat data.
- **Backend (Laravel)**: Menyediakan REST API untuk CRUD dan logika perhitungan remunerasi.
- **Database**: Menyimpan record pekerjaan, pegawai, dan hasil perhitungan remunerasi.

---

## 🎨 Penjelasan Desain

### 🧠 Alasan Pendekatan
- **Perhitungan remunerasi** dilakukan di Laravel backend untuk menjaga konsistensi logika dan validasi.
- Jika lebih dari satu pegawai mengerjakan tugas yang sama, maka:
  ```
  total_remuneration = (total jam * rate) + biaya tambahan
  masing-masing pegawai mendapat: 
    (jam kerja pegawai / total jam) * total_remunerasi
  ```

- **Modular API**: Setiap operasi CRUD memiliki endpoint RESTful sendiri, memudahkan integrasi frontend.

---

## 🛠️ Setup & Deploy

### 🔧 Backend (Laravel)
```bash
cd employee-remuneration-api

# Install dependencies
composer install

# Salin .env dan sesuaikan
cp .env.example .env

# Generate key
php artisan key:generate

# Buat dan migrasi database
php artisan migrate

# Jalankan server lokal
php artisan serve
```

### 💻 Frontend (Next.js)
```bash
cd employee-remuneration-frontend

# Install dependencies
npm install

# Jalankan server
npm run dev
```

### 🌐 Environment
Sesuaikan file `.env` di Laravel dan Next.js agar URL API sesuai:
- Laravel: `http://127.0.0.1:8000`
- Next.js: atur base URL ke `.env.local`:
  ```env
  NEXT_PUBLIC_API_BASE_URL=http://127.0.0.1:8000/api
  ```

---

## 🚧 Tantangan & Solusi

| Tantangan | Solusi |
|----------|--------|
| `MassAssignmentException` karena field belum masuk ke `$fillable` | Menambahkan field seperti `employee_name`, `hours_spent`, dll ke model Laravel |
| `SQLSTATE[22003]` (Out of range for column) | Mengubah kolom numeric (`decimal(12,2)`, `decimal(15,2)`) menggunakan Doctrine DBAL |
| Error saat migrasi kolom dengan `change()` | Menginstall `doctrine/dbal` agar Laravel bisa modifikasi kolom |
| Perhitungan remunerasi kompleks | Menangani pembagian prorata di controller Laravel berdasarkan total jam kerja |

---

## 🧪 Contoh Data

```json
{
  "employee_name": "Budi",
  "task_description": "Membuat laporan keuangan",
  "date": "2025-06-09",
  "hours_spent": 5,
  "hourly_rate": 150000,
  "additional_charges": 100000,
  "total_remuneration": 850000
}
```

---

## ✅ Fitur

- [x] Tambah data tugas pegawai
- [x] Edit dan hapus data
- [x] Hitung otomatis remunerasi berdasarkan jam kerja
- [x] Bagi remunerasi secara prorata jika lebih dari satu pegawai
- [x] Tampilkan detail perhitungan di UI Next.js

---

## 📎 Lisensi

Proyek ini dibuat untuk keperluan coding challenge dan pendidikan.