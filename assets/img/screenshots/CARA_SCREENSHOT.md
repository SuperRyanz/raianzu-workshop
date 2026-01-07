# PANDUAN LENGKAP MENGAMBIL SCREENSHOT UNTUK DOKUMENTASI TESTING

## Persiapan
1. Pastikan XAMPP Apache + MySQL sudah running
2. Buka browser (Chrome/Firefox)
3. Siapkan tool screenshot (Win+Shift+S atau Snipping Tool)

---

## CHECKLIST SCREENSHOT YANG DIBUTUHKAN

### A. BLACK BOX TESTING - ADMIN LOGIN (2 screenshots)

**1. login-success.png**
- Buka: http://localhost/raianzu-workshop/admin/
- Login dengan: username = admin, password = password
- Screenshot: Halaman dashboard setelah berhasil login
- Pastikan terlihat: sidebar admin, welcome message, statistics cards

**2. login-error.png**
- Logout dulu dari admin
- Buka: http://localhost/raianzu-workshop/admin/
- Login dengan: username = admin, password = salahpassword
- Screenshot: Pesan error "Password salah!" di halaman login

---

### B. ADMIN DASHBOARD (1 screenshot)

**3. dashboard-overview.png**
- Sudah login sebagai admin
- URL: http://localhost/raianzu-workshop/admin/dashboard.php
- Screenshot: Dashboard lengkap dengan statistics cards (jumlah layanan, onderdil, pesan)
- Pastikan terlihat: sidebar menu, header, semua cards statistics

---

### C. CRUD LAYANAN (3 screenshots)

**4. services-crud-create.png**
- Klik menu "Layanan" di sidebar
- Klik tombol "Tambah Layanan" 
- Isi form: nama = "Test Servis Motor", harga = 150000, deskripsi = "Testing"
- Screenshot: Form tambah layanan yang sudah diisi SEBELUM klik Simpan

**5. services-crud-edit.png**
- Di halaman admin/services.php
- Klik tombol "Edit" pada salah satu layanan
- Screenshot: Form edit dengan data layanan yang sudah ada

**6. services-crud-delete.png**
- Di halaman admin/services.php (table list layanan)
- Screenshot: Table dengan tombol Edit dan Hapus terlihat jelas
- ATAU screenshot konfirmasi saat klik Hapus

---

### D. KATALOG ONDERDIL (2 screenshots)

**7. parts-catalog.png**
- Buka: http://localhost/raianzu-workshop/parts.php (halaman PUBLIK, bukan admin)
- Screenshot: Grid katalog onderdil dengan 12 item per page
- Pastikan terlihat: cards onderdil, harga, badge stok, tombol Detail & Minta Penawaran

**8. part-detail.png**
- Dari parts.php, klik tombol "Detail" pada salah satu onderdil
- Screenshot: Halaman detail onderdil (part.php?id=X)
- Pastikan terlihat: gambar besar, nama, harga, stok, deskripsi lengkap

---

### E. FORM KONTAK (1 screenshot)

**9. contact-form-submit.png**
- Buka: http://localhost/raianzu-workshop/contact.php
- Isi form dengan data test:
  - Nama: John Doe
  - Email: john@test.com
  - Phone: 08123456789
  - Message: Testing contact form
- Screenshot: Form yang sudah diisi SEBELUM submit
- ATAU screenshot success message SETELAH submit berhasil

---

### F. MODAL ARTIKEL (1 screenshot)

**10. modal-article-open.png**
- Buka: http://localhost/raianzu-workshop/index.html
- Scroll ke section "Tips & Artikel"
- Klik tombol "Baca Selengkapnya" pada salah satu artikel
- Screenshot: Modal popup yang terbuka dengan artikel lengkap
- Pastikan terlihat: judul artikel, icon, content, tombol Tutup

---

### G. TESTING REPORTS (2 screenshots)

**11. coverage-report.png**
Jika sudah setup PHPUnit:
- Run: php vendor/bin/phpunit --coverage-html coverage
- Buka: coverage/index.html di browser
- Screenshot: Laporan code coverage

Jika belum setup PHPUnit:
- Buat screenshot sederhana dari editor VS Code yang menunjukkan file testing
- ATAU screenshot dari dokumentasi testing yang sudah dibuat

**12. static-analysis-report.png**
- Screenshot dari terminal setelah run PHP CodeSniffer/PHPStan
- ATAU screenshot kode dengan komentar yang menunjukkan code quality
- ATAU screenshot dari file TESTING_DOCUMENTATION.md bagian White Box Testing

---

### H. JENKINS CI/CD (2 screenshots)

**13. jenkins-pipeline-success.png**
Jika sudah setup Jenkins:
- Buka: http://localhost:8080
- Screenshot: Jenkins pipeline dengan status SUCCESS (hijau)

Jika belum setup Jenkins:
- Screenshot dari file Jenkinsfile di VS Code
- ATAU screenshot dokumentasi Jenkins di TESTING_DOCUMENTATION.md

**14. jenkins-stages.png**
- Screenshot: Detail stages di Jenkins pipeline
- ATAU screenshot dari dokumentasi yang menjelaskan stages

---

## CARA MENGAMBIL SCREENSHOT DI WINDOWS

### Metode 1: Win+Shift+S (Paling Mudah)
1. Tekan tombol: Windows + Shift + S
2. Layar akan redup
3. Drag mouse untuk select area yang mau di-screenshot
4. Otomatis tersimpan di clipboard
5. Buka Paint (atau langsung paste ke folder)
6. Ctrl+V untuk paste
7. Save As → pilih PNG → simpan dengan nama sesuai checklist

### Metode 2: Snipping Tool
1. Cari "Snipping Tool" di Start Menu
2. Klik New
3. Select area
4. Save dengan nama sesuai checklist

### Metode 3: Print Screen
1. Tekan PrtSc (Print Screen) untuk full screen
2. Paste di Paint
3. Crop jika perlu
4. Save

---

## LOKASI PENYIMPANAN

Semua screenshot HARUS disimpan di folder:
C:\xampp\htdocs\raianzu-workshop\assets\img\screenshots\

Dengan nama file PERSIS seperti di checklist (huruf kecil, pakai dash).

---

## TIPS

1. **Resolusi**: Gunakan 1280x720 atau 1920x1080
2. **Format**: PNG (bukan JPG)
3. **Clarity**: Pastikan text readable, tidak blur
4. **Context**: Include navbar/header agar jelas ini dari mana
5. **Date/Time**: Jika bisa, include timestamp di screenshot

---

## PRIORITAS

Jika tidak bisa ambil semua, PRIORITASKAN yang ini:
1. ✅ login-success.png
2. ✅ dashboard-overview.png
3. ✅ parts-catalog.png
4. ✅ modal-article-open.png
5. ✅ contact-form-submit.png

Yang lain bisa screenshot dari dokumentasi atau kode sebagai backup.

---

## AFTER CAPTURE

Setelah semua screenshot diambil:
1. Verify semua file ada di folder screenshots
2. Verify nama file sesuai checklist
3. Git add & commit:
   git add assets/img/screenshots/*.png
   git commit -m "Add testing screenshots"

---

## TROUBLESHOOTING

**Aplikasi tidak jalan?**
- Cek XAMPP Apache & MySQL sudah start
- Cek http://localhost/raianzu-workshop/ bisa dibuka

**Admin tidak bisa login?**
- Cek database ada dan terisi
- Default: username = admin, password = password

**Screenshot blur/kecil?**
- Zoom browser ke 100%
- Gunakan full screen mode (F11)
- Capture dengan resolusi lebih tinggi

---

SELAMAT MENGAMBIL SCREENSHOT! 📸
