# Mutaku - Mutasi Qris Orderkuota

![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-8892BF.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Laravel](https://img.shields.io/badge/Laravel-Compatible-red.svg)
![PHP Native](https://img.shields.io/badge/PHP%20Native-Compatible-green.svg)
![Downloads](https://img.shields.io/packagist/dt/nodev/mutaku)

Package PHP untuk mengambil data mutasi, saldo, dan gambar QRIS dari OrderKuota secara mudah (Non-Resmi). Mendukung **Laravel** dan **PHP Native**.

## ✨ Fitur

- 🏦 **Ambil Data Mutasi** - Riwayat transaksi QRIS dengan filter tanggal
- 💰 **Cek Saldo** - Saldo QRIS real-time
- 📱 **Gambar QRIS** - URL gambar QRIS untuk ditampilkan
- 🚀 **Multi Environment** - Support Laravel dan PHP Native
- 📅 **Filter Tanggal** - Filter transaksi berdasarkan range tanggal
- 💳 **Filter Status Transaksi** - Filter status transaksi incoming atau outgoing
- 📄 **Pagination** - Support pagination untuk data yang banyak
- 🔧 **Easy Setup** - Instalasi dan konfigurasi yang mudah

## 📄 Requirements

- PHP >= 7.4
- ext-curl
- ext-json
- ext-openssl
- vlucas/phpdotenv

## 🔑 Cara Mendapatkan Auth Token

Untuk mendapatkan Auth Token, silakan mengikuti [tutorial berikut](TUTORIAL.md).

## 📦 Instalasi

### Via Composer

```bash
composer require nodev/mutaku
```

## ⚙️ Konfigurasi

### 1. Laravel

#### Publish Config

```bash
php artisan vendor:publish --tag=mutaku-config
```

#### Set Environment Variables

Tambahkan ke file `.env`:

```env
ORDERKUOTA_AUTH_TOKEN=your_auth_token_here
ORDERKUOTA_ACCOUNT_USERNAME=your_username_here
```

### 2. PHP Native

#### Buat file `.env` atau set environment variables

```env
ORDERKUOTA_AUTH_TOKEN=your_auth_token_here
ORDERKUOTA_ACCOUNT_USERNAME=your_username_herr
```

#### Atau konfigurasi manual

```php
<?php
require_once 'vendor/autoload.php';

use Nodev\Mutaku\Config;

Config::initialize(); // Load dari .env
// atau
Config::load([
    'authToken' => 'your_auth_token_here',
    'accountUsername' => 'your_username_here',
]);
```

## 🚀 Penggunaan

### 1. Ambil Data Mutasi

```php
use Nodev\Mutaku\Core;

// Ambil mutasi 30 hari terakhir (default)
$result = Core::getMutations();

// Ambil mutasi dengan tanggal tertentu
$result = Core::getMutations('01-01-2025', '31-01-2025');

// Ambil mutasi dengan pagination
$result = Core::getMutations('01-01-2025', '31-01-2025', 2); // page 2

// Ambil hanya mutasi status IN
$result = Core::getMutations('01-01-2025', '31-01-2025', 2, true);

// Response
if ($result['error']) {
    echo "Error: " . $result['message'];
} else {
    echo "Data: " . json_encode($result['data']);
}
```

### 2. Cek Saldo QRIS

```php
use Nodev\Mutaku\Core;

$result = Core::getBalance();

if ($result['error']) {
    echo "Error: " . $result['message'];
} else {
    echo "Saldo: " . $result['balance'];
}
```

### 3. Ambil Gambar QRIS

```php
use Nodev\Mutaku\Core;

$result = Core::getImage();

if ($result['error']) {
    echo "Error: " . $result['message'];
} else {
    echo "URL Gambar: " . $result['image_url'];
}
```

## 📅 Format Tanggal

Package ini menggunakan format tanggal **d-m-Y** (contoh: `31-12-2024`).

```php
// ✅ Benar
$result = Core::getMutations('01-01-2025', '31-01-2025');

// ❌ Salah
$result = Core::getMutations('2025-01-01', '2025-01-31');
$result = Core::getMutations('01/01/2025', '31/01/2025');
```

## 📊 Format Response

Semua method mengembalikan array dengan format yang konsisten:

### Success Response

```php
[
    'error' => false,
    'data' => [...] // atau 'balance' => '...', 'image_url' => '...'
]
```

### Example Success Response

#### 1.  Get Mutations

```json
{
  "error": false,
  "date": "10-06-2025 to 10-07-2025",
  "filter_out": false,
  "data": {
    "success": true,
    "qris_history": {
      "success": true,
      "total": 2,
      "page": 1,
      "pages": 1,
      "results": [
        {
          "id": 157XXXXXX,
          "debet": "1.000",
          "kredit": "1.000",
          "saldo_akhir": "1.010",
          "keterangan": "Pencairan Saldo R#20XXX",
          "tanggal": "10/07/2025 16:43",
          "status": "OUT",
          "fee": "",
          "brand": {
            "name": "Orderkuota",
            "logo": "https://app.orderkuota.com/assets/qris/orderkuota.png"
          }
        },
        {
          "id": 156XXXXXX,
          "debet": "0",
          "kredit": "1.000",
          "saldo_akhir": "2.710",
          "keterangan": "NOBU / JOHN DOE      ",
          "tanggal": "08/07/2025 22:33",
          "status": "IN",
          "fee": "",
          "brand": {
            "name": "BCA",
            "logo": "https://app.orderkuota.com/assets/qris/bca.png"
          }
        }
      ]
    }
  }
}
```

#### 2. Get Balance

```json
{
  "error": false,
  "balance": "Rp 1.010"
}
```

#### 3. Get Image

```json
{
  "error": false,
  "image_url": "https://qris.orderkuota.com/qrnobu/1554446-29b8594e1aa4c23ac999XXXXXXXXXXXXXXXXXXXX-QR.png"
}
```

### Error Response

```php
[
    'error' => true,
    'message' => 'Error description'
]
```

## 🔧 Troubleshooting

### 1. "Invalid date format"

Pastikan format tanggal benar (d-m-Y)

```php
$result = Core::getMutations('31-12-2024', '01-01-2025');
```

### 2. "Config not loaded"

Pastikan telah set environment variables, tambahan khusus PHP Native yaitu initialize config

```php
Config::initialize();
```

### 3. "User tidak ditemukan dan/ Token tidak benar"

Pastikan auth token dan username di environment variables berikut sudah tepat

```php
ORDERKUOTA_AUTH_TOKEN=
ORDERKUOTA_ACCOUNT_USERNAME=
```

### 4. "Parameter tidak benar"

Pastikan tidak ada kesalahan dalam penulisan environment variables dan tidak kosong

```php
ORDERKUOTA_AUTH_TOKEN=
ORDERKUOTA_ACCOUNT_USERNAME=
```

## 🐞 Lapor Issues

Jika Anda menemukan bug, masalah, atau memiliki saran fitur, silakan buat [issue baru di GitHub](https://github.com/nodev-id/mutaku-php/issues). Sertakan detail troubleshooting yang telah dilakukan, pesan error, dan environment (native/laravel & versi) yang digunakan agar kami dapat membantu lebih cepat.

## 📝 License

Package ini menggunakan [MIT License](LICENSE).

## ⚠️ Disclaimer

Package ini **tidak resmi** dan tidak berafiliasi dengan OrderKuota. Gunakan dengan risiko sendiri dan patuhi terms of service OrderKuota.

---

**Di coding oleh [Fieza Ghifari](https://github.com/Glaezz) & [Dio Rizqi](https://github.com/diorizqi404)**
