# Expense Tracker — Laravel + MySQL + Docker Compose

Starter ini berisi file tambahan untuk project Laravel 12. Buat project Laravel terlebih dahulu, lalu salin file/folder dari ZIP ini ke root project Laravel (gabungkan folder yang namanya sama).

## 1. Buat project Laravel

Pastikan Composer dan PHP tersedia, lalu jalankan:

```bash
composer create-project laravel/laravel expense-tracker
cd expense-tracker
```

Salin seluruh isi starter ini ke folder `expense-tracker`. Saat diminta, pilih **replace/merge** untuk file yang sama. Pastikan `composer.lock` dari project Laravel tetap ada.

## 2. Siapkan environment

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Buka `.env`, pastikan `DB_HOST=db`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` sesuai contoh. Buat APP_KEY:

```powershell
docker compose run --rm --entrypoint php app artisan key:generate --show
```

Salin nilai `base64:...` yang ditampilkan ke `APP_KEY=` di file `.env`.

## 3. Jalankan aplikasi

```powershell
docker compose up -d --build
docker compose ps
```

Buka http://localhost:8000

Container aplikasi menjalankan migration secara otomatis saat start. Adminer opsional dapat dijalankan dengan:

```powershell
docker compose --profile tools up -d
```

Adminer: http://localhost:8080  
System: MySQL · Server: db · Username: expense_user · Password: expense_pass_123 · Database: expense_app

## 4. Uji endpoint

Health check:

```powershell
curl.exe -i http://localhost:8000/health
```

Baca data:

```powershell
curl.exe -i http://localhost:8000/api/expenses
```

Tambah data (PowerShell):

```powershell
curl.exe -i -X POST http://localhost:8000/api/expenses `
  -H "Content-Type: application/json" `
  -H "Accept: application/json" `
  -d '{ "description": "Makan siang", "amount": 25000, "expense_date": "2026-11-09" }'
```

GET mengembalikan HTTP 200, sedangkan POST yang berhasil mengembalikan HTTP 201.

## 5. Uji persistensi volume

1. Tambahkan pengeluaran melalui browser.
2. Jalankan `docker compose down`.
3. Jalankan `docker compose up -d`.
4. Buka kembali aplikasi. Data harus tetap ada karena disimpan di named volume `mysql-data`.

`docker compose down -v` menghapus container **dan named volume**, sehingga data database ikut terhapus.

## 6. Catatan Docker

- Multi-stage build: stage `vendor` memasang dependency Composer; stage `runtime` hanya membawa source dan folder `vendor` ke image PHP Apache.
- Dependency manifest (`composer.json` dan `composer.lock`) disalin sebelum source code agar layer dependency dapat memakai cache.
- `COPY` menyalin file/folder lokal secara langsung. `ADD` juga memiliki fitur tambahan seperti ekstraksi arsip lokal dan mengambil URL; karena tidak diperlukan, Dockerfile ini memakai `COPY`.
- Aplikasi mengakses MySQL melalui hostname `db`, yaitu nama service Compose, bukan `localhost` atau IP.
- Network `expense-network` menghubungkan container aplikasi dan database.
- Database menggunakan named volume `mysql-data`.
- `depends_on` dengan `service_healthy` menunggu healthcheck MySQL berhasil sebelum container aplikasi dimulai.
- Adminer menggunakan profile `tools`, sehingga tidak dijalankan pada perintah Compose biasa.

## 7. Sebelum push ke GitHub

Jangan commit `.env`. Commit `.env.example` saja. Periksa:

```powershell
git status
git add .
git commit -m "Add Laravel expense tracker application"
```

Buat beberapa commit bertahap yang benar-benar menggambarkan proses pengerjaan. Jangan commit password, token, atau data pribadi.

## 8. AI Usage Declaration

Sesuaikan deklarasi dengan bantuan yang benar-benar digunakan. Contoh:

> AI tool: ChatGPT. AI assistance was used for initial project structure, Docker configuration guidance, and debugging suggestions. The student reviewed, tested, modified, and is responsible for understanding the submitted source code and Docker configuration.
