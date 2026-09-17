# The Phantom Prototype // Noir Archive

![Veridia Police Department Logo](public/favicon.svg)

**Game Investigasi Detektif Retro-Noir 1950s Berbasis Flat-File Architecture**  
*Menyajikan sensasi deduksi analog klasik dengan UI diegetik, interogasi poligraf real-time, dan papan bukti benang merah dinamis.*

![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![Zero-Database](https://img.shields.io/badge/Database-Zero_DB_(Flat_File)-10B981?style=for-the-badge)
![PHPUnit Tests](https://img.shields.io/badge/Tests-26_Passed_(111_Assertions)-success?style=for-the-badge)
![MIT License](https://img.shields.io/badge/License-MIT-amber?style=for-the-badge)

---

## 📸 Showcase & Preview Galeri

### 1. Start Menu & Terminal Teletype

Portal utama investigasi dengan antarmuka teletype mesin tik retro, map manila berkas perkara rahasia, pendar lampu meja kerja, dan simulasi asap cerutu atmosferik.

![Start Menu & Terminal Teletype](public/screenshots/title-preview.png)

---

### 2. Meja Kerja Investigasi & Berkas Perkara

Pusat komando detektif dengan layout terkunci 100% viewport fit (zero vertical scrollbar). Menampilkan berkas perkara fisik berpenjepit kertas, kartu arsip tersangka vintage, dan baki barang bukti forensik dengan segel *Chain of Custody*.

![Meja Kerja Detektif](public/screenshots/desk-preview.png)

---

### 3. Ruang Interogasi & Osiloskop Poligraf ECG

Sesi interogasi tatap muka intensif dengan simulasi grafik poligraf ECG dinamis, deteksi kontradiksi kesaksian secara real-time, penyadapan telepon rahasia (*wiretap*), dan sistem sanggahan (*Objection!*).

![Ruang Interogasi](public/screenshots/interrogation-preview.png)

---

### 4. Papan Investigasi Benang Merah (SVG Dynamic Corkboard)

Papan gabus penyelidikan visual interaktif untuk menghubungkan pin tersangka, barang bukti, dan motif kejahatan menggunakan benang merah dinamis berbasis vektor SVG murni.

![Papan Benang Merah](public/screenshots/corkboard-preview.png)

---

## 🔍 Sinopsis Kasus: CASE_001 (Operation Ouroboros)

> *"14 November 1953, Pukul 23:42. Fasilitas Riset Bawah Tanah Sublevel 3 Aethelgard Quantum Dynamics diguncang insiden sabotase tingkat tinggi. Kepala Ilmuwan Kuantum, Dr. Wallace Vance, ditemukan tewas dengan terminal brankas vakum hangus terbakar. Inti prototipe revolusioner 'Ouroboros-X' raib tanpa jejak."*

### Profil Saksi & Tersangka Kunci

1. **Dr. Aris Thorne (Lead Research Physicist - Usia 44 Thn)**
   - Mengklaim berada di ruang observasi lantai 2 menyusun laporan kalibrasi spektrometer saat insiden terjadi.
2. **Elena Vance (Chief of Security - Usia 29 Thn)**
   - Putri kandung korban. Mengklaim sedang melakukan patroli rutin di pos gerbang luar gedung induk.
3. **Julian Croft (Aethelgard Financial Director - Usia 52 Thn)**
   - Mengklaim sedang menghadiri jamuan makan malam privat bersama investor di West End Club hingga larut malam.

---

## ⚡ Arsitektur & Keunggulan Sistem

- **Zero-Database Architecture (Flat-File JSON):**
  Seluruh konfigurasi kasus, lore berkas, transkrip dialog pohon interogasi, barang bukti, evaluasi dakwaan, hingga state penyimpanan (*save/load slot*) dikelola murni melalui berkas JSON terstruktur di `storage/app/cases/` tanpa memerlukan server database relasional eksternal.
- **Diegetic Vintage UI & 100% Viewport Locked:**
  Seluruh elemen antarmuka dirancang menyerupai perlengkapan fisik detektif era 1950-an (kertas manila berpenjepit klip logam, kartu indeks arsip kepolisian retro, kantong bukti plastik forensik berstempel lab). Seluruh halaman dirancang pas dalam satu layar (zero vertical scrollbar) pada resolusi 1080p maupun 1366x768.
- **Procedural Web Audio API Synthesizer:**
  Efek suara retro disintesis secara prosedural via Web Audio API (tuts mesin tik, frekuensi osiloskop, denyut poligraf, suara sobekan kertas, klik tombol analog, hingga dentuman palu sidang) dengan strict gesture guard yang kebal terhadap pembatasan autoplay browser modern.
- **Dynamic Real-Time SVG Corkboard:**
  Kanvas interaktif untuk menarik benang merah penghubung antar-bukti secara visual menggunakan kalkulasi matriks SVG bebas dependensi library eksternal.
- **GPU-Accelerated Retro CRT Scanlines:**
  Filter raster tabung kaca monitor retro dengan chromatic aberration mikro dan kelengkungan vignette yang diisolasi pada composite layer GPU agar performa rendering tetap 60 FPS tanpa mengaburkan teks mikro.

---

## 🛠️ Tech Stack

| Komponen | Teknologi | Deskripsi |
| :--- | :--- | :--- |
| **Backend Core** | [Laravel 12](https://laravel.com) | Framework PHP modern dengan arsitektur Service-Action |
| **Runtime** | PHP 8.2+ | Eksekusi server-side performa tinggi |
| **Styling & Theme** | [Tailwind CSS v4](https://tailwindcss.com) | Arsitektur modular `@import` terbagi atas theme, utilities, dan animasi |
| **Reactivity** | [Alpine.js 3.x](https://alpinejs.dev) | Manajemen state global store dan interaktivitas komponen UI |
| **Audio Engine** | Web Audio API | Synthesizer suara prosedural bebas file audio statis besar |
| **Vector Graphics** | Native Inline SVG | Rendering siluet tersangka resolusi tinggi dan benang merah papan bukti |
| **Testing Suite** | PHPUnit | Pengujian otomatis alur investigasi, evaluasi bukti, dan save state |

---

## 🚀 Panduan Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan pengembangan lokal:

### 1. Prasyarat Sistem

- PHP >= 8.2 dengan ekstensi `ext-json`, `ext-mbstring`, `ext-fileinfo`
- Composer >= 2.x
- Node.js >= 18.x & NPM

### 2. Clone Repositori

```bash
git clone https://github.com/NDVERS/noir-archive.git
cd noir-archive
```

### 3. Instalasi Dependensi

```bash
# Instal dependensi PHP
composer install

# Instal dependensi Frontend
npm install
```

### 4. Konfigurasi Environment

```bash
# Salin template environment
cp .env.example .env

# Generate application encryption key
php artisan key:generate
```

### 5. Kompilasi Aset Frontend

```bash
# Build untuk mode produksi
npm run build

# Atau jalankan dev server Vite untuk hot-reloading
npm run dev
```

### 6. Jalankan Server Lokal

```bash
php artisan serve
```

Buka peramban web dan akses game melalui: `http://127.0.0.1:8000`

---

## 🧪 Menjalankan Test Suite Otomatis

Proyek ini dilengkapi dengan 26 automated unit & feature tests yang mencakup verifikasi controller, engine evaluasi kontradiksi kesaksian, validasi berkas perkara, dan integritas save manager:

```bash
php artisan test
```

**Hasil Pengujian:**

```text
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\CaseRepositoryTest
  ✓ it loads case info correctly
  ✓ it returns all available cases
  ✓ it handles non existent case safely
  ✓ it bundles initial case data correctly

   PASS  Tests\Feature\CheckContradictionActionTest
  ✓ it validates successful contradiction
  ✓ it rejects incorrect evidence contradiction
  ✓ it rejects contradiction when evidence has no claim

   PASS  Tests\Feature\EvaluateAccusationActionTest
  ✓ it validates correct accusation solution
  ✓ it fails accusation with wrong culprit
  ✓ it fails accusation with missing required evidence
  ✓ it calculates credibility penalties correctly

   PASS  Tests\Feature\GameControllerTest
  ✓ it renders start menu welcome page
  ✓ it renders detective work desk page
  ✓ it renders investigation corkboard page
  ✓ it renders suspect interrogation page
  ✓ it checks testimony contradiction via api
  ✓ it evaluates case indictment accusation via api
  ✓ it handles save game state via api
  ✓ it loads game state via api
  ✓ it resets game progress via api

   PASS  Tests\Feature\SaveManagerServiceTest
  ✓ it lists save slots correctly
  ✓ it saves and retrieves game state
  ✓ it updates existing save slot
  ✓ it creates initial save when empty
  ✓ it resets game save state safely

  Tests:    26 passed (111 assertions)
  Duration: 2.15s
```

---

## 📂 Struktur Direktori Kasus (Flat-File Engine)

```text
storage/app/cases/case_001/
├── case_info.json    # Metadata kasus, ringkasan insiden, korban, lokasi, dan target investigasi
├── suspects.json     # Profil lengkap tersangka, peran, usia, alibi awal, dan ciri fisik
├── evidences.json    # Daftar barang bukti forensik, tag lab, kategori, dan hasil uji teknis
├── dialogues.json    # Pohon dialog interogasi, transkrip sadapan, dan pemetaan kontradiksi
└── solution.json     # Kunci kebenaran kasus, bobot penilaian dakwaan, dan skenario vonis
```

---

## 📜 Lisensi

Proyek ini dirilis di bawah lisensi [MIT License](LICENSE). Bebas digunakan, dipelajari, dan dikembangkan untuk keperluan edukasi dan non-komersial.
