# Deploy ke Railway (Backend PHP + MySQL)

## Prasyarat
- Akun Railway
- Repo GitHub sudah terhubung (sudah ada Dockerfile)

## Langkah Cepat
1) Buat project baru di Railway → Deploy from GitHub → pilih repo ini.
2) Tambah plugin MySQL: `Add Plugin` → `MySQL`.
3) Set environment variables di Project → Variables:
   - `DB_HOST` = host dari plugin MySQL (lihat halaman plugin)
   - `DB_USER` = user dari plugin
   - `DB_PASS` = password dari plugin
   - `DB_NAME` = `raianzu_workshop`
4) Import schema: buka plugin MySQL → gunakan phpMyAdmin/Workbench dengan kredensial plugin → import `database/raianzu_workshop.sql`.
5) Deploy: Railway otomatis build Dockerfile dan expose port 80. Catat domain publik, mis. `https://<project>.up.railway.app`.

## Setelah Deploy
- Admin: `https://<domain-backend>/admin/`
- Kontak/form: `https://<domain-backend>/contact.php`
- Layanan dinamis: `https://<domain-backend>/services.php`
- Onderdil dinamis: `https://<domain-backend>/parts.php`

## Opsi (sesuaikan repo)
Jika ingin frontend Pages tetap, arahkan tautan dinamis di navbar ke domain backend setelah domain diketahui.
