# uts-pbf-1178
Project membuat REST API sederhana menggunakan Laravel dengan Migration dan Seeder untuk konfigurasi database, otentikasi JWT dan OAuth Google.

<br>

## Owner identity
||||
|----|----|----|
|Nama|:|Muhammad Rifky Alfian|
|NIM|:|22416255201178|
|Kelas|:|IF22E|

<br><br>
## Endpoint API
```
├── [GET] /api/categories/               # Request data kategori dengan method GET
├── [POST] /api/categories/              # Insert data baru kategori dengan method POST
├── [PUT] /api/categories/{id}           # Update data kategori dengan method PUT
├── [DELETE] /api/categories/{id}        # Delete data kategori dengan method DELETE
.
├── [GET] /api/products/                 # Request data produk dengan method GET
├── [POST] /api/products/                # Insert data baru produk dengan method POST
├── [PUT] /api/products/{id}             # Update data produk dengan method PUT
├── [DELETE] /api/products/{id}          # Delete data produk dengan method DELETE
.
├── [POST] /api/register/                # Register akun baru
├── [POST] /api/login/                   # Otentikasi akun menggunakan username dan password untuk mendapatkan JWT
├── [GET] /api/oauth/register/           # URI untuk memulai google oauth
```

<br><br>
## Kebutuhan

1. PHP __8.0+__
2. Laravel __9.1+__
3. Composer __2.4+__
4. MariaDB __10.4+__, MySQL __8.0+__, PostgreSQL __16.0+__

<br><br>

## Composer

1. Jalankan perintah berikut untuk men-download dependency laravel:

```bash
composer update
```

2. Secara otomatis composer akan membuat direktori __*vendor/*__

<br><br>

## Konfigurasi

### Konfigurasi database

1. Buka file environment di __*.env.example*__
2. Ganti nama file menjadi __*.env*__
3. Pergi ke baris berikut:

```env
DB_CONNECTION=mysql     # Driver database yang digunakan (mysql, mariadb, pgsql)
DB_HOST=localhost       # Host database (default: localhost)
DB_PORT=3306            # Port database (default mysql & mariadb: 3306, default postgresql: 5432)
DB_DATABASE=laravel     # Nama database
DB_USERNAME=root        # Username database
DB_PASSWORD=            # Password database
```

4. Ganti sesuai dengan nama database dan driver database yang digunakan
5. Selesai

### Konfigurasi Migration dan Seeding

1. Jalankan perintah berikut untuk membuat tabel di database yang digunakan:

```bash
php artisan migrate
```

2. Jalankan perintah berikut untuk seeding ke tabel *users* di database:

```bash
php artisan db:seed --class=AdminSeeder
```

### Konfigurasi JSON Web Token (JWT) *tymon/jwt-auth*

1. Publish konfigurasi JWT menggunakan perintah artisan:

```bash
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

2. Generate secret key untuk JWT:

```bash
php artisan jwt:secret
```

<br><br>

## Google OAUTH

Konfigurasi Google OAUTH untuk mendukung login menggunakan google oauth

### Membuat project Google Cloud

1. Buka <a href="https://console.cloud.google.com">Google Cloud Console</a>
2. Buat project Google Cloud baru <a href="https://console.cloud.google.com/projectcreate">disini</a>

### Mendaftarkan aplikasi ke Google Cloud

1. Buka menu __*APIs & Services > OAuth consent screen*__, anda bisa membuka menu <a href="https://console.cloud.google.com/apis/credentials/consent">disini</a>
2. Pilih *User Type* "External"
3. Isi kolom *App name*, *User support email*, dan *Email addresses*
4. Anda bisa men-skip tahap __*Scopes*__, __*Test Users*__, dan __*Summary*__ dengan meng-klik tombol "SAVE AND CONTINUE" dibagian bawah form untuk mempercepat proses

### Mendapatkan Google Client ID & Client Secret

1. Buka menu __*APIs & Services > Credentials*__, anda bisa membuka menu <a href="https://console.cloud.google.com/apis/credentials">disini</a>
2. Klik tombol "CREATE CREDENTIALS"
3. Pilih menu "OAuth Client ID"
4. Pilih "Web Application" untuk kolom *Application type*
5. Isi kolom *Name*
6. Pada bagian __*Authorized redirect URIs*__ klik tombol "ADD URI"
7. Isi kolom *URIs* dengan URL lengkap untuk menerima callback dari Google OAuth
8. Klik tombol "CREATE" untuk menyimpan konfigurasi
9. Klik tombol "DOWNLOAD JSON" untuk mendapatkan Google Client ID dan Client Secret
10. File .json akan berisi data seperti berikut:

```json
{
  "web": {
    "client_id": <client-id>,
    "project_id": "",
    "auth_uri": "",
    "token_uri": "",
    "auth_provider_x509_cert_url": "",
    "client_secret": <client-secret>,
    "redirect_uris": [
      <redirect_uri>
    ]
  }
}
```

11. Buka file konfigurasi environment di __*.env*__
12. Pergi ke baris berikut, lalu isi dengan value dari file .json yang anda download tadi:

```env
GOOGLE_CLIENT_ID=xxx        // Isi dengan <client-id> anda dari file .json
GOOGLE_CLIENT_SECRET=xxx    // Isi dengan <client-secret> anda dari file .json
GOOGLE_CALLBACK_URL=xxx     // Isi dengan <redirect_uri> anda dari file .json
```
> [!TIP]
> Google biasanya tidak mendukung penggunaan Otentikasi OAuth dilokal server (localhost), jadi
> Anda harus meng-upload project ke hosting terlebih dahulu


<br><br>

## Development server

1. Jalankan Laravel development server dengan perintah berikut:

```bash
php artisan serve
```

2. Artisan akan memberikan respon default seperti berikut:

```bash
INFO  Server running on [http://127.0.0.1:8000]
Press Ctrl+C to stop the server
```
