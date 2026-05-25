# ShelterTrack

ShelterTrack adalah aplikasi Laravel untuk manajemen dog shelter. MVP ini memakai Laravel 11, Breeze Blade, Tailwind CSS, Alpine.js, Spatie Permission, MySQL Laragon, dan upload local storage.

## Status Implementasi

- Phase 0: Laravel 11, Breeze Blade, Tailwind/Vite, Spatie Permission, auth baseline, storage link.
- Phase 1: data foundation, migration, model relationship, roles, dan seed demo.
- Phase 2: register Admin Shelter dan Adopter, caretaker hanya dibuat Admin, route group role-aware.
- Phase 3: public adoption page `/`, `/adopt`, `/adopt/{dog}` dengan filter dan detail public-safe.
- Phase 4: dashboard role-aware untuk Admin, Caretaker, dan Adopter.
- Phase 5: Admin dog CRUD, photo upload, archive via `is_active`, primary caretaker assignment history.
- Phase 6: schedule CRUD Admin, caretaker schedule, mark complete, activity log.
- Phase 7: caretaker health record, contact log, urgent alert, admin contact trace.
- Phase 8: caretaker user management dan profile dasar.
- Phase 9: visual baseline mengikuti referensi `UI_UX Sesi 10 - 11.zip` untuk sidebar, dashboard, my pets, schedule, login, dan register.

## Sisa 20% Untuk Teman

Bagian ini sengaja paling cocok dikerjakan manual supaya teman tetap punya kontribusi jelas:

- Polish UI pixel-level dari ZIP: jarak, icon asli, ilustrasi, state hover, dan notification pop-up.
- Upload preview gambar sebelum submit di dog, health record, dan activity log.
- Calendar UX lebih lengkap: prev/next month, today shortcut, dan highlight task per tanggal.
- Empty state visual yang lebih halus untuk dogs, schedules, contact trace, dan caretaker workload.
- Tambahan validasi kecil: blok deactivate admin sendiri, konfirmasi delete lebih rapi, dan audit copy final.
- Demo script/presentasi: urutan klik public browse, admin assign, caretaker log, admin trace.

## Setup Lokal

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Sesuaikan database di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sheltertrack
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
php artisan test
```

Untuk membuka aplikasi:

```bash
php artisan serve
```

## Akun Demo

Semua akun seed memakai password `password`.

- `admin@shelter.com` sebagai Admin
- `budi@shelter.com` sebagai Caretaker
- `siti@shelter.com` sebagai Caretaker
- `andi@shelter.com` sebagai Caretaker
- `maya@shelter.com` sebagai Caretaker
- `adopter@example.com` sebagai Adopter

## Route Utama

- Public: `/`, `/adopt`, `/adopt/{dog}`
- Authenticated: `/dashboard`, `/profile`
- Admin: `/admin/dogs`, `/admin/users`, `/admin/schedules`, `/admin/contact-trace`
- Caretaker: `/caretaker/dogs`, `/caretaker/schedules`, `/caretaker/health-records/create`, `/caretaker/contact-log`

## Test Coverage

Test yang sudah tersedia:

- Breeze auth/profile baseline.
- Phase 1 data foundation dan relationship.
- Admin registration membuat shelter.
- Adopter registration tidak membuat shelter.
- Public adoption hanya menampilkan dog active dan available.
- Caretaker tidak bisa melihat dog di luar assignment.
- Reassign caretaker menutup assignment lama dan membuat assignment aktif baru.

```bash
php artisan test
```

Hasil terakhir: `32 passed`.
