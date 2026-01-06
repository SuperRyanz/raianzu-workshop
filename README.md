# 🔧 Arman Jaya - Website Bengkel & Penjualan Onderdil

![Version](https://img.shields.io/badge/version-1.0-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

Website profesional untuk **Arman Jaya** - bengkel servis kendaraan dan penjualan onderdil berkualitas. Website ini menyediakan informasi layanan, katalog onderdil, artikel perawatan kendaraan, dan sistem admin untuk manajemen konten.

## 📋 Daftar Isi
- [Fitur Utama](#-fitur-utama)
- [Teknologi](#-teknologi)
- [Struktur Project](#-struktur-project)
- [Instalasi](#-instalasi)
- [Konfigurasi Database](#-konfigurasi-database)
- [Panduan Penggunaan](#-panduan-penggunaan)
- [Admin Panel](#-admin-panel)
- [Screenshot](#-screenshot)
- [Deployment](#-deployment)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

---

## ✨ Fitur Utama

### Website Publik
- **Homepage dengan Typing Animation** - Hero section dengan efek typing yang menarik
- **Katalog Layanan** - Tampilan layanan servis dengan gambar dan deskripsi lengkap
- **Katalog Onderdil** - 
  - List produk dengan pagination
  - Filter dan pencarian
  - Detail produk dengan gambar
  - Tombol minta penawaran langsung
- **Blog/Artikel** - 6 artikel lengkap tentang perawatan kendaraan dengan modal popup
- **Formulir Kontak** - Form untuk inquiry dan permintaan penawaran
- **Responsive Design** - Optimal di desktop, tablet, dan mobile
- **Dark Theme Modern** - Desain dengan glass morphism dan gradient

### Admin Panel
- **Dashboard** - Overview data layanan, onderdil, dan pesan
- **Manajemen Layanan** - CRUD (Create, Read, Update, Delete) layanan
- **Manajemen Onderdil** - CRUD onderdil dengan upload gambar
- **Inbox Pesan** - Melihat pesan dari pelanggan
- **Upload Gambar** - Support multiple image upload
- **Authentication** - Login system dengan password hashing
- **CSRF Protection** - Keamanan dari CSRF attacks
- **Session Management** - Secure session handling

---

## 🛠 Teknologi

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Custom styling + animations
- **Bootstrap 5.3** - Responsive framework
- **JavaScript (Vanilla)** - Interactive elements
- **Font Awesome 6.4** - Icons library

### Backend
- **PHP 8.x** - Server-side scripting
- **MySQL/MariaDB** - Relational database
- **PDO/MySQLi** - Database connection

### Security
- **Password Hashing** - bcrypt algorithm
- **CSRF Token** - Cross-site request forgery protection
- **Session Security** - Secure session management
- **SQL Prepared Statements** - SQL injection prevention

---

## 📁 Struktur Project

```
raianzu-workshop/
├── admin/                      # Admin panel
│   ├── assets/
│   │   └── css/
│   │       └── style.css      # Admin styling
│   ├── includes/
│   │   ├── auth.php           # Authentication check
│   │   └── csrf.php           # CSRF token handling
│   ├── dashboard.php          # Admin dashboard
│   ├── services.php           # Manage services
│   ├── parts.php              # Manage parts/onderdil
│   ├── messages.php           # View customer messages
│   ├── edit_service.php       # Edit service form
│   ├── edit_part.php          # Edit part form
│   ├── index.php              # Admin login
│   └── logout.php             # Logout handler
│
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet
│   ├── img/                   # Images folder
│   │   ├── parts/             # Parts images
│   │   └── services/          # Services images
│   └── js/
│       └── script.js          # JavaScript utilities
│
├── database/
│   ├── config.php             # Database configuration
│   └── raianzu_workshop.sql   # Database schema
│
├── index.html                 # Homepage
├── services.php               # Services listing
├── parts.php                  # Parts catalog
├── part.php                   # Part detail page
├── about.html                 # About us page
├── contact.php                # Contact form
├── contact_submit.php         # Contact form handler
├── robots.txt                 # SEO robots file
├── sitemap.xml                # SEO sitemap
└── README.md                  # Documentation

```

---

## 🚀 Instalasi

### Prerequisites
- **XAMPP/WAMP/LAMP** atau web server dengan PHP 8.x
- **MySQL/MariaDB** 5.7+
- **Browser modern** (Chrome, Firefox, Edge)

### Langkah Instalasi

1. **Clone atau download project**
   ```bash
   git clone https://github.com/yourusername/raianzu-workshop.git
   cd raianzu-workshop
   ```

2. **Pindahkan ke folder web server**
   ```bash
   # Untuk XAMPP
   cp -r raianzu-workshop C:/xampp/htdocs/
   
   # Untuk Linux/Mac
   sudo mv raianzu-workshop /var/www/html/
   ```

3. **Import database**
   - Buka **phpMyAdmin** (http://localhost/phpmyadmin)
   - Buat database baru: `raianzu_workshop`
   - Import file `database/raianzu_workshop.sql`

4. **Konfigurasi database**
   Edit file `database/config.php`:
   ```php
   <?php
   $servername = "localhost";
   $username = "root";          // Sesuaikan dengan username MySQL Anda
   $password = "";              // Sesuaikan dengan password MySQL Anda
   $dbname = "raianzu_workshop";
   ```

5. **Akses website**
   - **Website publik**: http://localhost/raianzu-workshop/
   - **Admin panel**: http://localhost/raianzu-workshop/admin/

---

## 🗄 Konfigurasi Database

### Struktur Tabel

**1. users** - Data admin
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- username (VARCHAR)
- password (VARCHAR, hashed)
- created_at (TIMESTAMP)
```

**2. services** - Data layanan
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- nama (VARCHAR)
- deskripsi (TEXT)
- harga (DECIMAL)
- image (VARCHAR)
- created_at (TIMESTAMP)
```

**3. parts** - Data onderdil
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- nama (VARCHAR)
- deskripsi (TEXT)
- harga (DECIMAL)
- stok (INT)
- image (VARCHAR)
- created_at (TIMESTAMP)
```

**4. messages** - Pesan dari pelanggan
```sql
- id (INT, AUTO_INCREMENT, PRIMARY KEY)
- name (VARCHAR)
- email (VARCHAR)
- phone (VARCHAR)
- message (TEXT)
- product (VARCHAR, optional)
- created_at (TIMESTAMP)
```

### Membuat Admin User

Jalankan query berikut untuk membuat user admin:
```sql
INSERT INTO users (username, password) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- Password default: password
-- Ganti dengan password yang aman!
```

Atau gunakan script PHP:
```php
<?php
// Buat file hash.php di folder admin/
$password = "your_secure_password";
echo password_hash($password, PASSWORD_DEFAULT);
```

---

## 📖 Panduan Penggunaan

### Website Publik

1. **Beranda** - Tampilan hero dengan typing animation, layanan unggulan, dan artikel
2. **Layanan** - Lihat semua layanan yang tersedia dengan detail
3. **Onderdil** - Browse katalog onderdil, klik detail untuk info lengkap
4. **Tentang** - Informasi tentang bengkel
5. **Kontak** - Form untuk menghubungi atau minta penawaran

### Menambah Konten

#### Upload Gambar Layanan/Onderdil
1. Siapkan gambar (format: JPG, PNG, max 2MB)
2. Rename sesuai kebutuhan (contoh: `service-motor.jpg`)
3. Upload via admin panel atau copy ke:
   - Layanan: `assets/img/services/`
   - Onderdil: `assets/img/parts/`

---

## 🔐 Admin Panel

### Login Admin
- **URL**: http://localhost/raianzu-workshop/admin/
- **Default Username**: admin
- **Default Password**: password

⚠️ **PENTING**: Segera ganti password default setelah instalasi!

### Menu Admin

#### Dashboard
- Overview jumlah layanan, onderdil, dan pesan
- Quick stats dan navigasi

#### Manajemen Layanan
- Tambah layanan baru
- Edit layanan existing
- Hapus layanan
- Upload gambar layanan

#### Manajemen Onderdil
- Tambah onderdil baru dengan stok dan harga
- Edit detail onderdil
- Update stok
- Hapus onderdil
- Upload gambar produk

#### Inbox Pesan
- Lihat pesan dari form kontak
- Detail inquiry pelanggan
- Hapus pesan

### Keamanan Admin
- ✅ Password di-hash dengan bcrypt
- ✅ CSRF token protection
- ✅ Session timeout (4 jam)
- ✅ Secure session handling
- ✅ SQL injection prevention

---

## 📸 Screenshot

### Homepage
- Hero section dengan typing animation
- Layanan unggulan
- Artikel blog dengan modal

### Admin Dashboard
- Dark theme modern dengan glass morphism
- Table dengan clickable thumbnails
- Responsive sidebar navigation

---

## 🌐 Deployment

### Untuk Hosting Shared

1. **Export database**
   ```bash
   mysqldump -u root -p raianzu_workshop > backup.sql
   ```

2. **Upload files via FTP/cPanel File Manager**
   - Upload semua file ke `public_html/`

3. **Import database di hosting**
   - Buat database baru via cPanel
   - Import file `backup.sql`

4. **Update config.php**
   ```php
   $servername = "localhost";
   $username = "your_db_user";
   $password = "your_db_password";
   $dbname = "your_db_name";
   ```

5. **Set permissions**
   ```bash
   chmod 755 admin/
   chmod 644 database/config.php
   chmod 777 assets/img/parts/
   chmod 777 assets/img/services/
   ```

### SEO & Performance
- ✅ Sitemap.xml included
- ✅ Robots.txt configured
- ✅ Meta tags optimized
- ✅ Image lazy loading
- ✅ Minified CSS/JS (optional)

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan:
1. Fork repository
2. Buat branch fitur (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push ke branch (`git push origin feature/amazing-feature`)
5. Buat Pull Request

---

## 📄 Lisensi

Project ini dilisensikan di bawah **MIT License**. Bebas digunakan untuk project komersial maupun personal.

---

## 📞 Kontak & Support

**Arman Jaya Workshop**
- 📍 Jl. Kapten Dulasim No.169, Jegong, Pulopancikan, Kec. Gresik, Kabupaten Gresik, Jawa Timur 61124
- 📱 0897-0180-971
- 📧 masryansyaha@gmail.com
- 📷 Instagram: [@arman-jaya](https://instagram.com/arman-jaya)
- 🎵 TikTok: [@arman-jaya](https://tiktok.com/@arman-jaya)

---

## 🔄 Version History

### v1.0 (Current)
- ✅ Homepage dengan typing animation
- ✅ Katalog layanan dan onderdil
- ✅ Blog articles dengan modal popup
- ✅ Admin panel dengan CRUD lengkap
- ✅ Contact form dengan database
- ✅ Responsive design
- ✅ Dark theme modern
- ✅ Security features (CSRF, password hashing)

---

**Made with ❤️ for Arman Jaya Workshop**

*Last updated: January 2026*
