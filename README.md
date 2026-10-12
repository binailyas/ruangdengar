<p align="center">
  <img src="public/images/logo-ruangdengar-ungu.svg" alt="ruangdengar" width="220">
</p>

<h1 align="center">ruangdengar</h1>
<p align="center"><i>Suarakan Ceritamu, Temukan Jalanmu</i></p>

<p align="center">
  Platform layanan BK (Bimbingan Konseling) sekolah berbasis web — pelaporan anonim, triase prioritas berbantuan AI,
  dan analitik pola insiden untuk mewujudkan Budaya Sekolah yang Aman dan Nyaman (BSAN).
</p>

<p align="center">
  <b>Lomba Web Development ITX (Informatic Technology Experience) 2026</b> · FESTIDA, Universitas Darussalam Gontor<br>
  Tim <b>vibe coders sejati</b> — Institut Teknologi Sepuluh Nopember (ITS)
</p>

<p align="center">
  <a href="https://ruangdengar-web.onrender.com/"><b>🔗 Demo</b></a> ·
  <a href="docs/SUBMISSION.md"><b>📄 Dokumentasi Submission</b></a> ·
  <a href="docs/ERD.md"><b>🗂️ ERD</b></a>
</p>

---

## Tentang Proyek

Banyak kasus kekerasan dan perundungan di sekolah tidak terlaporkan karena korban/saksi takut intimidasi balasan, stigma sosial, atau risiko terbukanya identitas. Di sisi lain, Guru BK dan Wali Kelas kesulitan memantau, memprioritaskan, dan memetakan pola kejadian ketika penanganan masih dilakukan secara manual.

**ruangdengar** hadir sebagai platform BK digital dengan lima pilar utama:

1. **Pelaporan Anonim Temporal** — akun sementara tanpa menyimpan IP/User-Agent/identitas perangkat.
2. **Triase Otomatis Berbasis AI/NLP** — rekomendasi prioritas (Rendah/Sedang/Tinggi) beserta *confidence score*; keputusan akhir tetap di tangan Guru BK (*human-in-the-loop*).
3. **Tata Kelola Peran Terstruktur (RBAC)** — Wali Kelas sebagai peninjau awal, Guru BK sebagai penanganan utama & konselor, Admin tanpa akses ke isi laporan.
4. **Analitik Pola Insiden** — peringkat kata kunci, *watchlist*, pemetaan pihak terlibat, dan pencatatan skor kredit kedisiplinan yang transparan.
5. **Ekosistem Layanan Pendukung Inklusif** — chatbot panduan, konsultasi karier dengan Guru BK, dan tombol akses darurat.


## Fitur per Peran

| Peran | Fitur Utama |
|---|---|
| **Siswa / Anonim** | Buat laporan, cek status via tiket, chat dengan BK, chatbot, konsultasi karier, lihat skor kredit |
| **Guru BK** | Tinjau laporan + saran prioritas AI, proses kasus, analitik kata kunci/pola, kelola informasi & skor kredit |
| **Wali Kelas** | Tinjau laporan kelas asuhan (baca-saja + tandai "Ditinjau"), koordinasi dengan Guru BK |
| **Admin** | Kelola akun & kelas, hak akses per peran, kategori insiden, audit log (tanpa membaca isi laporan) |

## Tech Stack

`Laravel 12` · `Blade` · `Tailwind CSS v4` · `MySQL/MariaDB` · `FastAPI (Python)` sidecar AI · model NLP `TF-IDF + Logistic Regression`

> Catatan versi: rancangan awal menyebut Laravel 11, tetapi semua rilis 11.x diblokir Composer karena advisory keamanan (EOL), jadi proyek memakai **Laravel 12** (kode kompatibel).

Lima peran pengguna: **Siswa**, **Anonim**, **Admin**, **Guru BK**, **Wali Kelas**. Desain mengikuti brief UI *"Tactile Counseling Companion"* (palet ungu lembut).

---

## Menjalankan Secara Lokal (Laragon, port bawaan)

Port bawaan Laragon: web **80**, MySQL **3306** (user `root`, tanpa sandi). `.env` sudah disetel demikian.
Nama database: **`ruang_dengar`** (sebelumnya `bk_sahabat`).

1. Letakkan folder proyek di `C:\laragon\www\ruangdengar` → Laragon otomatis membuat **http://ruangdengar.test** (ubah `APP_URL` bila perlu).
   Tanpa Laragon: `.\serve-local.ps1` menjalankan `php artisan serve` di **http://127.0.0.1:80**.
2. Start All di Laragon (Apache/Nginx + MySQL), lalu:

```powershell
composer install
copy .env.example .env          # lewati bila .env sudah ada
php artisan key:generate
# buat database kosong "ruang_dengar" (HeidiSQL/phpMyAdmin Laragon), lalu:
php artisan migrate --seed      # skema 28 tabel + data demo (hanya env local)
```

3. CSS Tailwind v4 sudah dibangun di `public/css/app.css`. Tanpa Node, pakai binary standalone
   (unduh `tailwindcss-windows-x64.exe` v4.1 ke `tools/tailwindcss.exe`):
   `composer css` (sekali) atau `composer css:watch`.

### Pindah dari database lama `bk_sahabat`

Cara paling aman (foreign key ikut terbawa): dump lalu impor, kemudian `php artisan migrate`.

```powershell
mysql -uroot -e "CREATE DATABASE ruang_dengar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
mysqldump -uroot bk_sahabat | mysql -uroot ruang_dengar
php artisan migrate
```

Jangan memakai `CREATE TABLE ... LIKE`: itu tidak menyalin foreign key. Setelah yakin berjalan, `bk_sahabat` boleh dihapus.

### Penjadwal (arsip kasus otomatis)

Kasus Selesai diarsipkan 30 hari kemudian (pemberitahuan hari ke-25) oleh `php artisan kasus:arsipkan`, dijadwalkan harian.
Jalankan scheduler tiap menit: cron `* * * * * php artisan schedule:run`, atau Task Scheduler Windows memanggil `php artisan schedule:run`.

### Akun demo (kata sandi: `password`)

| Peran | Login |
|---|---|
| Admin | `admin@sekolah.sch.id` (form login yang sama di `/masuk`) |
| Guru BK | `bk1@sekolah.sch.id` |
| Wali Kelas | `wk1@sekolah.sch.id` (X-3, XI-1) · `wk2@…` · `wk3@…` |
| Siswa | NIS `2024001` (Rani Putri, X-3) |
| Anonim | tombol "Lapor tanpa nama" |

Staf memakai **2FA**; di mode lokal kode OTP ditampilkan di halaman verifikasi (produksi: kirim via email/SMS).

## Deploy Model AI (`.pkl`)

Lihat [ai-service/README.md](ai-service/README.md). Ringkas: salin model ke `ai-service/models/model.pkl`,
samakan versi scikit-learn, jalankan `ai-service\run.ps1` (port 8001). Laravel memanggil `POST /classify`;
bila mati, laporan tetap berjalan dan kolom saran AI kosong. Status/versi terlihat di Admin → Pengaturan → AI Model.

## ERD

`docs/ERD.md` (Mermaid per domain + kamus tabel) dan `docs/erd.html` (diagram penuh). Dihasilkan dari skema nyata:
`php artisan docs:erd`.

## Uji

`php artisan test` — smoke test semua halaman per peran + aturan akses (admin tak bisa buka laporan, Wali Kelas baca-saja & terbatas kelas,
saran AI tak bocor ke siswa, 2FA, akun anonim).

## Batasan yang Perlu Diketahui

* Hak akses per peran diedit admin di Role dan akses (tabel `role_permissions`); ambang skor kredit di Pengaturan.
* Skor kredit tidak direset saat naik kelas (saldo bawaan).
* Gambar informasi BK disajikan lewat `/media/informasi/...` (tanpa `storage:link`).
* Login satu form (`/masuk`): nama lengkap (hanya bila unik), NIS, atau email; peran dikenali otomatis.
* Prioritas laporan: siswa memilih 3 level (bawaan Rendah, kolom `prioritas_siswa`); AI hanya memberi saran (`ai_priority_suggestion`); prioritas akhir diubah petugas. Wali Kelas hanya boleh mengubahnya saat status Baru.
* Wali Kelas hanya melihat laporan yang melibatkan kelas asuhannya atau yang belum punya pihak bersiswa; tidak ada mode "semua kelas".
* Role dan akses: semua peran dapat diatur dan dikunci per peran (`role_locks`); izin siswa/anonim/admin ikut ditegakkan di rute.
* Ubah kata sandi untuk semua peran berakun: `/akun/kata-sandi` (staf/admin) dan Profil (siswa).
* Alur laporan: Baru → Wali Kelas menandai Ditinjau (satu-satunya perubahan status untuk WK) → BK memproses sampai diarsipkan. Laporan berisiko (kata berisiko / AI ≥ 90% Tinggi) langsung terlihat BK.
* Asisten chat di beranda siswa berbasis aturan (bukan LLM).
* Impor massal: **CSV** saja (XLSX butuh PhpSpreadsheet + ekstensi gd).
* Unduhan ringkasan/analitik: **CSV** (bukan PDF/XLSX).
* Matriks Role & Akses: sel dikunci sistem; hanya 3 kebijakan sekolah (Wali Kelas: ubah status, baca chat, catat skor) yang dapat diubah dan benar-benar dipakai.
* Chat memakai polling 8 detik (belum WebSocket/Reverb). Email/SMS (OTP, undangan) belum terhubung.
* Pengecekan visual dilakukan lewat render headless; uji di perangkat/browser nyata sebelum rilis.

---

## Tim

**vibe coders sejati** — Institut Teknologi Sepuluh Nopember (ITS)

- Devin Alif Fansyah Yahya
- Bina Ilyas Santosa
- Rafsyah Fachri Isfansyah

Seluruh anggota berkontribusi secara kolektif dan setara di semua tahap pengembangan — riset, desain, backend, frontend, integrasi AI, dan pengujian.
