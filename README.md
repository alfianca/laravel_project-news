# Project News API

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

## 📌 Deskripsi

**Project News API** adalah backend REST API berbasis **Laravel** yang digunakan untuk mengelola berita (news) dengan sistem autentikasi berbasis token menggunakan **Laravel Sanctum**.

Project ini dibuat sebagai **backend-ready API** sebelum masuk ke tahap frontend.

---

## 🚀 Fitur Utama

* ✅ Register & Login User
* 🔐 Authentication menggunakan Laravel Sanctum (Bearer Token)
* 👤 Role-based access (Admin & User)
* 📰 CRUD News (Admin Only)
* 📄 List & Detail News (Authenticated User)
* 🙍‍♂️ Manajemen Profile

  * Update data profile
  * Update password
  * Upload photo
* 🧼 Sanitasi konten menggunakan **HTML Purifier**

---

## 🛠️ Teknologi yang Digunakan

* PHP >= 8.1
* Laravel
* Laravel Sanctum
* SQLite / MySQL
* Composer
* Postman (Testing API)

---

## ⚙️ Instalasi Project

### 1️⃣ Clone Repository

```bash
git clone https://github.com/alfianca/laravel_project-news.git
cd laravel_project-news
```

### 2️⃣ Install Dependency

```bash
composer install
```

### 3️⃣ Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

Atur database di file `.env`

### 4️⃣ Migrasi Database

```bash
php artisan migrate
```

### 5️⃣ Jalankan Server

```bash
php artisan serve
```

---

## 🔐 Authentication

Semua endpoint protected membutuhkan **Bearer Token**.

Header:

```
Authorization: Bearer YOUR_TOKEN
```

---

## 📡 Endpoint API

### 🔓 Public

| Method | Endpoint      | Deskripsi     |
| ------ | ------------- | ------------- |
| POST   | /api/register | Register user |
| POST   | /api/login    | Login user    |

---

### 🔒 Protected (Auth Required)

#### 📰 News

| Method | Endpoint       | Role  |
| ------ | -------------- | ----- |
| GET    | /api/news      | User  |
| GET    | /api/news/{id} | User  |
| POST   | /api/news      | Admin |
| PUT    | /api/news/{id} | Admin |
| DELETE | /api/news/{id} | Admin |

#### 👤 Profile

| Method | Endpoint              | Deskripsi       |
| ------ | --------------------- | --------------- |
| GET    | /api/profile          | Lihat profile   |
| PUT    | /api/profile          | Update profile  |
| PUT    | /api/profile/password | Update password |
| POST   | /api/profile/photo    | Upload photo    |

#### 🚪 Logout

| POST | /api/logout | Logout user |

---

## 🧪 Contoh Request (Create News)

```json
{
  "title": "Judul Berita",
  "content": "Isi berita"
}
```

---

## 📂 Struktur Penting

```
app/
 ├── Http/Controllers
 │   ├── AuthController.php
 │   ├── NewsController.php
 │   └── ProfileController.php
 ├── Http/Middleware
 │   └── RoleMiddleware.php
```

---

## 📄 Lisensi

Project ini menggunakan lisensi **MIT**.

---

## ✨ Catatan

Project ini difokuskan pada **backend API** dan siap diintegrasikan dengan frontend (React, Vue, Flutter, dsb).

---

👨‍💻 Dibuat oleh **Alfianca**
