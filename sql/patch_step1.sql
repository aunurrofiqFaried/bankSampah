-- =========================================================
-- PATCH STEP 1 — Perbaikan skema banksampah
-- Jalankan file ini SETELAH import banksampah.sql yang lama
-- =========================================================

-- 1. Bersihkan ENUM yang punya opsi string kosong ganda
ALTER TABLE `tarik_saldo`
  MODIFY `status` ENUM('pending','selesai','ditolak') NOT NULL DEFAULT 'pending';

ALTER TABLE `transaksi`
  MODIFY `tipe` ENUM('setor','jual_pengepul') NOT NULL;

-- 2. Tambah kolom verifikasi pada transaksi setor sampah
--    (siswa/guru setor -> status pending -> admin verifikasi)
ALTER TABLE `transaksi`
  ADD COLUMN `status` ENUM('pending','diverifikasi','ditolak') NOT NULL DEFAULT 'pending' AFTER `total_rp`,
  ADD COLUMN `diverifikasi_oleh` INT(11) NULL AFTER `status`,
  ADD COLUMN `tanggal_verifikasi` DATETIME NULL AFTER `diverifikasi_oleh`;

ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_verifikator_fk`
  FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `users`(`id_user`) ON DELETE SET NULL;

-- 3. Tambah kolom siapa yang verifikasi pencairan saldo
ALTER TABLE `tarik_saldo`
  ADD COLUMN `diverifikasi_oleh` INT(11) NULL AFTER `status`;

ALTER TABLE `tarik_saldo`
  ADD CONSTRAINT `tarik_saldo_verifikator_fk`
  FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `users`(`id_user`) ON DELETE SET NULL;

-- =========================================================
-- CATATAN PENTING soal alur bisnis:
--
-- - Saat siswa/guru SETOR sampah -> insert ke `transaksi` dengan
--   tipe='setor', status='pending'. SALDO BELUM BERTAMBAH.
-- - Saat admin VERIFIKASI -> update status='diverifikasi',
--   isi diverifikasi_oleh & tanggal_verifikasi, BARU saldo user
--   ditambahkan (via query terpisah di controller, bukan trigger,
--   supaya gampang di-debug).
-- - Saat admin jual ke PENGEPUL -> insert transaksi baru dengan
--   tipe='jual_pengepul', id_user = id milik akun pengepul,
--   status langsung 'diverifikasi' (karena ini transaksi keluar
--   dari sisi admin, bukan input siswa).
-- - Stok di tabel `sampah` dikurangi saat jual_pengepul,
--   ditambah saat setor diverifikasi.
-- =========================================================
