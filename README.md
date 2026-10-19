# 📚 API Catatan Belajar — laravel_sanctum

Backend REST API untuk aplikasi **Catatan Belajar Pribadi** menggunakan **Laravel + Sanctum**.  
Token-based authentication, isolasi data per user, validasi lengkap.

---

## 🛠️ Instalasi

### Prasyarat
- PHP ≥ 8.2
- Composer ≥ 2.x
- SQLite (sudah tersedia di PHP)

### Langkah

```bash
# 1. Clone / masuk ke direktori
cd "c:\Users\Kayla Artalita\laravel_sanctum"

# 2. Install dependensi
composer install

# 3. Salin konfigurasi environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Jalankan migrasi database
php artisan migrate

# 6. Jalankan server API
php artisan serve --port=8000
```

> **Catatan**: File `.env` tidak di-push ke GitHub. Gunakan `.env.example` sebagai referensi.  
> Database SQLite sudah terconfigurasi dan siap dipakai.

---

## 🌐 Alamat API

| Environment | URL |
|---|---|
| Development | `http://127.0.0.1:8000` |

---

## 📡 Endpoint API

> **Semua request wajib menyertakan header:**  
> `Accept: application/json`  
> **Untuk POST dengan body JSON juga wajib:**  
> `Content-Type: application/json`

### Autentikasi

| Method | Endpoint | Auth | Deskripsi |
|---|---|---|---|
| `POST` | `/api/register` | ❌ Tidak perlu | Daftar akun baru |
| `POST` | `/api/login` | ❌ Tidak perlu | Login, kembalikan token Sanctum |
| `GET` | `/api/profil` | ✅ Bearer Token | Data akun yang sedang login |
| `POST` | `/api/logout` | ✅ Bearer Token | Cabut token aktif |

### Catatan

| Method | Endpoint | Auth | Deskripsi |
|---|---|---|---|
| `GET` | `/api/catatan` | ✅ Bearer Token | Daftar catatan milik akun aktif |
| `POST` | `/api/catatan` | ✅ Bearer Token | Simpan catatan baru |

---

## 📋 Detail Request & Response

### POST `/api/register`

**Request Body:**
```json
{
  "name": "Rafli Maulana",
  "email": "rafli@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response `201 Created`:**
```json
{
  "message": "Akun berhasil dibuat. Silakan login.",
  "user": {
    "id": 1,
    "name": "Rafli Maulana",
    "email": "rafli@example.com"
  }
}
```

---

### POST `/api/login`

**Request Body:**
```json
{
  "email": "rafli@example.com",
  "password": "password123"
}
```

**Response `200 OK`:**
```json
{
  "message": "Login berhasil.",
  "token": "7|Ln3LR7gg1W52FxIUqN...",
  "user": {
    "id": 1,
    "name": "Rafli Maulana",
    "email": "rafli@example.com"
  }
}
```

> ⚠️ **Samarkan token**: Simpan token hanya di session server atau storage aman. Jangan tampilkan ke user.

---

### GET `/api/catatan`

**Header:**
```
Authorization: Bearer <token>
Accept: application/json
```

**Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "judul": "Catatan Laravel Sanctum",
      "isi": "Implementasi token-based auth.",
      "dibuat": "2026-10-19T13:00:00.000000Z"
    }
  ]
}
```

---

### POST `/api/catatan`

**Header:**
```
Authorization: Bearer <token>
Accept: application/json
Content-Type: application/json
```

**Request Body:**
```json
{
  "judul": "Judul catatan (max 100 karakter)",
  "isi": "Isi catatan pelajaran..."
}
```

**Response `201 Created`:**
```json
{
  "message": "Catatan berhasil disimpan.",
  "catatan": {
    "id": 2,
    "judul": "Judul catatan",
    "isi": "Isi catatan pelajaran...",
    "dibuat": "2026-10-19T13:10:00.000000Z"
  }
}
```

---

### POST `/api/logout`

**Header:**
```
Authorization: Bearer <token>
Accept: application/json
```

**Response `200 OK`:**
```json
{
  "message": "Logout berhasil. Token telah dicabut."
}
```

---

## 🔐 Keamanan

- Password di-hash otomatis menggunakan **bcrypt** (tidak disimpan plaintext)
- `user_id` catatan ditentukan dari **akun terautentikasi**, bukan dari input client
- Token Sanctum disimpan di database (`personal_access_tokens`) — dapat dicabut sewaktu-waktu
- Isolasi data: setiap user hanya bisa mengakses catatan miliknya sendiri
- Validasi input lengkap di semua endpoint

---

## 🗂️ Struktur Proyek

```
app/
├── Http/Controllers/Api/
│   ├── AuthController.php      ← register, login, profil, logout
│   └── CatatanController.php   ← index, store
├── Http/Resources/
│   ├── UserResource.php        ← format respons user
│   └── CatatanResource.php     ← format respons catatan
├── Models/
│   ├── User.php
│   └── Catatan.php
database/
├── migrations/
│   ├── create_users_table.php
│   ├── create_catatans_table.php
│   └── create_personal_access_tokens_table.php
routes/
└── api.php                     ← definisi semua endpoint API
```

---

## 🚀 Cara Menjalankan (Bersama Frontend)

```powershell
# Terminal 1 — API Backend (port 8000)
cd "c:\Users\Kayla Artalita\laravel_sanctum"
php artisan serve --port=8000

# Terminal 2 — Frontend (port 8001)
cd "c:\Users\Kayla Artalita\laravel_frontend"
php artisan serve --port=8001
```

Lalu buka browser: **`http://localhost:8001`**

---

## 👤 Akun Demo

| Akun | Email | Password |
|---|---|---|
| Pengguna A | `a@test.com` | `password123` |
| Pengguna B | `b@test.com` | `password123` |

---

*Dibuat oleh **Kayla Artalita** — Kelas XI-3 RPL*
