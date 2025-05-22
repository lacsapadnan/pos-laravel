# 📦 Laravel POS App – Setup & Instalasi Awal

POS web application dengan Laravel + Autentikasi + Role-based Access (Admin & Kasir). Dibuat untuk pengelolaan penjualan yang modular dan scalable.

---


## 🚀 Fitur Awal

- Autentikasi (Login, Register)
- Role-based access:
  - `admin`
  - `kasir`
- Auto-generate user per role
- Role disimpan dan dikelola menggunakan [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)

---

## ⚙️ Cara Install & Setup

### 1. Clone Project

```bash
git clone https://github.com/username/nama-project.git
cd nama-project
```

### 2. Install Dependency

```bash
composer install
npm install && npm run dev
```

### 3. Copy `.env` dan Konfigurasi

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` dan sesuaikan DB kamu:

```
DB_DATABASE=laravel_pos
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi Database + Seeder

```bash
php artisan migrate
php artisan db:seed --class=RoleSeeder
```

Seeder akan otomatis:

- Membuat role `admin` dan `kasir`
- Membuat user:
  - **admin@mail.com / password**
  - **kasir@mail.com / password**
- Assign role ke user yang sesuai

---

## 🔐 Login Test Akun

| Role  | Email             | Password  |
|-------|-------------------|-----------|
| Admin | `admin@mail.com`  | `password` |
| Kasir | `kasir@mail.com`  | `password` |

---

## 📁 Struktur Dasar

```
├── app/
│   ├── Models/
│   ├── Http/
│       ├── Controllers/
│       ├── Middleware/
|   |── Repositories/
|       |── Interfaces/
|   |── Services/
|       |── Interfaces/
│
├── database/
│   ├── migrations/
│   ├── seeders/
│    
│
├── routes/
│   └── web.php
├── resources/
│   └── views/
```

---

## 📌 Packages Digunakan

- [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze) – Auth starter kit
- [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) – Role & permission management
- [DataTables](https://datatables.net/) - DataTables

---

## 🛠️ TODO Selanjutnya

- ✅ Manajemen Hak Akses
- [ ] Manajemen Kategori
- [ ] Manajemen Produk
- [ ] Manajemen Supplier
- [ ] Manajemen Konsumen
- [ ] Manajemen Stok
- [ ] Transaksi Pembelian (Stok)
- [ ] Laporan Pembelian (Stok)
- [ ] Transaksi Penjualan
- [ ] Laporan Penjualan

---

## 🧑‍💻 Developer

Dibuat oleh Pascal Adnan – [@lacsapadnan](https://github.com/lacsapadnan) ✨