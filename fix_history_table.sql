-- Script untuk memperbaiki struktur tabel tbl_history di Railway
-- Jalankan script ini di database Railway

-- 1. Periksa apakah tabel tbl_history ada
SHOW TABLES LIKE 'tbl_history';

-- 2. Jika tabel tidak ada, buat tabel baru
CREATE TABLE IF NOT EXISTS `tbl_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe_transaksi` enum('Peminjaman','Pengembalian','Buku Rusak','Perbaikan Buku','Buku Hilang','Mengganti Buku Baru') NOT NULL,
  `kode_transaksi` varchar(50) DEFAULT NULL,
  `buku_id` int(11) DEFAULT NULL,
  `anggota_id` int(11) DEFAULT NULL,
  `petugas_id` int(11) DEFAULT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `keterangan` text DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Jika tabel sudah ada, periksa struktur kolom anggota_id
DESCRIBE tbl_history;

-- 4. Jika kolom anggota_id tidak mengizinkan NULL, ubah menjadi bisa NULL
ALTER TABLE `tbl_history` MODIFY COLUMN `anggota_id` int(11) DEFAULT NULL;

-- 5. Periksa struktur akhir
DESCRIBE tbl_history;

-- 6. Test insert sederhana untuk memastikan tidak ada error
INSERT INTO `tbl_history` (`tipe_transaksi`, `kode_transaksi`, `buku_id`, `petugas_id`, `jumlah`, `keterangan`) 
VALUES ('Buku Rusak', 'TEST001', 1, 1, 1, 'Test insert tanpa anggota_id');

-- 7. Hapus data test
DELETE FROM `tbl_history` WHERE `kode_transaksi` = 'TEST001';

-- 8. Tampilkan struktur final
SHOW CREATE TABLE tbl_history; 