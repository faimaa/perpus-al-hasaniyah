-- Script untuk memperbaiki struktur database Railway
-- Jalankan script ini di database Railway untuk menambahkan kolom yang hilang

-- 1. Perbaiki tabel tbl_history - tambahkan kolom kode_transaksi jika belum ada
ALTER TABLE `tbl_history` 
ADD COLUMN IF NOT EXISTS `kode_transaksi` VARCHAR(6) DEFAULT NULL AFTER `tipe_transaksi`;

-- 2. Periksa dan perbaiki struktur tabel tbl_denda
-- Pastikan tabel memiliki struktur yang benar
ALTER TABLE `tbl_denda` 
MODIFY COLUMN `id_denda` int(6) NOT NULL AUTO_INCREMENT,
MODIFY COLUMN `pinjam_id` varchar(6) NOT NULL,
MODIFY COLUMN `denda` int(11) NOT NULL,
MODIFY COLUMN `lama_waktu` int(2) NOT NULL,
MODIFY COLUMN `tgl_denda` date NOT NULL;

-- 3. Periksa dan perbaiki struktur tabel tbl_buku
ALTER TABLE `tbl_buku` 
MODIFY COLUMN `id_buku` int(6) NOT NULL AUTO_INCREMENT,
MODIFY COLUMN `buku_id` varchar(5) NOT NULL,
MODIFY COLUMN `id_kategori` int(6) NOT NULL,
MODIFY COLUMN `id_rak` int(6) NOT NULL,
MODIFY COLUMN `sampul` blob DEFAULT NULL,
MODIFY COLUMN `isbn` varchar(13) DEFAULT NULL,
MODIFY COLUMN `judul_buku` varchar(20) DEFAULT NULL,
MODIFY COLUMN `penerbit` varchar(20) DEFAULT NULL,
MODIFY COLUMN `pengarang` varchar(20) DEFAULT NULL,
MODIFY COLUMN `thn_buku` date DEFAULT NULL,
MODIFY COLUMN `isi` text DEFAULT NULL,
MODIFY COLUMN `jml` int(2) DEFAULT NULL,
MODIFY COLUMN `tgl_masuk` date DEFAULT NULL,
MODIFY COLUMN `status` enum('Tersedia','Rusak') DEFAULT 'Tersedia';

-- 4. Periksa dan perbaiki struktur tabel tbl_login
ALTER TABLE `tbl_login` 
MODIFY COLUMN `id_login` int(6) NOT NULL AUTO_INCREMENT,
MODIFY COLUMN `username` varchar(20) NOT NULL,
MODIFY COLUMN `password` varchar(255) NOT NULL,
MODIFY COLUMN `nama` varchar(50) NOT NULL,
MODIFY COLUMN `level` enum('Admin','Petugas','Anggota') NOT NULL,
MODIFY COLUMN `anggota_id` varchar(6) DEFAULT NULL,
MODIFY COLUMN `email` varchar(50) DEFAULT NULL,
MODIFY COLUMN `no_telp` varchar(15) DEFAULT NULL,
MODIFY COLUMN `alamat` text DEFAULT NULL,
MODIFY COLUMN `tgl_lahir` date DEFAULT NULL,
MODIFY COLUMN `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
MODIFY COLUMN `foto` varchar(255) DEFAULT NULL,
MODIFY COLUMN `status` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif',
MODIFY COLUMN `created_at` timestamp DEFAULT current_timestamp(),
MODIFY COLUMN `updated_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp();

-- 5. Periksa dan perbaiki struktur tabel tbl_pinjam
ALTER TABLE `tbl_pinjam` 
MODIFY COLUMN `id_pinjam` int(6) NOT NULL AUTO_INCREMENT,
MODIFY COLUMN `pinjam_id` varchar(6) NOT NULL,
MODIFY COLUMN `buku_id` varchar(5) NOT NULL,
MODIFY COLUMN `anggota_id` varchar(6) NOT NULL,
MODIFY COLUMN `petugas_id` int(6) NOT NULL,
MODIFY COLUMN `tgl_pinjam` date NOT NULL,
MODIFY COLUMN `tgl_kembali` date NOT NULL,
MODIFY COLUMN `lama_pinjam` int(2) NOT NULL,
MODIFY COLUMN `status` enum('Di Pinjam','Di Kembalikan','Terlambat','Denda') DEFAULT 'Di Pinjam',
MODIFY COLUMN `keterangan` text DEFAULT NULL,
MODIFY COLUMN `created_at` timestamp DEFAULT current_timestamp(),
MODIFY COLUMN `updated_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp();

-- 6. Periksa dan perbaiki struktur tabel tbl_kategori
ALTER TABLE `tbl_kategori` 
MODIFY COLUMN `id_kategori` int(6) NOT NULL AUTO_INCREMENT,
MODIFY COLUMN `nama_kategori` varchar(50) NOT NULL,
MODIFY COLUMN `deskripsi` text DEFAULT NULL,
MODIFY COLUMN `created_at` timestamp DEFAULT current_timestamp(),
MODIFY COLUMN `updated_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp();

-- 7. Periksa dan perbaiki struktur tabel tbl_rak
ALTER TABLE `tbl_rak` 
MODIFY COLUMN `id_rak` int(6) NOT NULL AUTO_INCREMENT,
MODIFY COLUMN `nama_rak` varchar(50) NOT NULL,
MODIFY COLUMN `lokasi` varchar(100) DEFAULT NULL,
MODIFY COLUMN `kapasitas` int(3) DEFAULT NULL,
MODIFY COLUMN `deskripsi` text DEFAULT NULL,
MODIFY COLUMN `created_at` timestamp DEFAULT current_timestamp(),
MODIFY COLUMN `updated_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp();

-- 8. Periksa dan perbaiki struktur tabel tbl_biaya_denda
ALTER TABLE `tbl_biaya_denda` 
MODIFY COLUMN `id_biaya_denda` int(6) NOT NULL AUTO_INCREMENT,
MODIFY COLUMN `harga_denda` int(6) NOT NULL,
MODIFY COLUMN `stat` enum('Aktif','Tidak Aktif') NOT NULL,
MODIFY COLUMN `tgl_tetap` date NOT NULL;

-- 9. Periksa dan perbaiki struktur tabel tbl_buku_hilang
ALTER TABLE `tbl_buku_hilang` 
MODIFY COLUMN `id` int(6) NOT NULL AUTO_INCREMENT,
MODIFY COLUMN `buku_id` varchar(5) NOT NULL,
MODIFY COLUMN `anggota_id` varchar(6) NOT NULL,
MODIFY COLUMN `keterangan` text DEFAULT NULL,
MODIFY COLUMN `tgl_hilang` datetime NOT NULL,
MODIFY COLUMN `petugas_id` int(6) NOT NULL,
MODIFY COLUMN `pinjam_id` varchar(6) NOT NULL;

-- 10. Periksa dan perbaiki struktur tabel tbl_buku_rusak
ALTER TABLE `tbl_buku_rusak` 
MODIFY COLUMN `id` int(6) NOT NULL AUTO_INCREMENT,
MODIFY COLUMN `buku_id` varchar(5) NOT NULL,
MODIFY COLUMN `keterangan` text DEFAULT NULL,
MODIFY COLUMN `tgl_rusak` datetime NOT NULL,
MODIFY COLUMN `petugas_id` int(6) NOT NULL,
MODIFY COLUMN `status` enum('Rusak','Diperbaiki','Tidak Bisa Diperbaiki') DEFAULT 'Rusak',
MODIFY COLUMN `tgl_perbaikan` datetime DEFAULT NULL;

-- 11. Periksa dan perbaiki struktur tabel tbl_pengembalian
ALTER TABLE `tbl_pengembalian` 
MODIFY COLUMN `id_pengembalian` int(6) NOT NULL AUTO_INCREMENT,
MODIFY COLUMN `id_pinjam` int(6) NOT NULL,
MODIFY COLUMN `tgl_kembali` datetime NOT NULL,
MODIFY COLUMN `denda` decimal(10,2) DEFAULT 0.00,
MODIFY COLUMN `keterangan` text DEFAULT NULL,
MODIFY COLUMN `petugas_id` int(6) NOT NULL,
MODIFY COLUMN `created_at` timestamp DEFAULT current_timestamp(),
MODIFY COLUMN `updated_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp();

-- 12. Tambahkan index untuk performa query
ALTER TABLE `tbl_history` ADD INDEX IF NOT EXISTS `idx_kode_transaksi` (`kode_transaksi`);
ALTER TABLE `tbl_history` ADD INDEX IF NOT EXISTS `idx_tanggal` (`tanggal`);
ALTER TABLE `tbl_history` ADD INDEX IF NOT EXISTS `idx_buku_id` (`buku_id`);
ALTER TABLE `tbl_history` ADD INDEX IF NOT EXISTS `idx_anggota_id` (`anggota_id`);
ALTER TABLE `tbl_history` ADD INDEX IF NOT EXISTS `idx_petugas_id` (`petugas_id`);

ALTER TABLE `tbl_denda` ADD INDEX IF NOT EXISTS `idx_pinjam_id` (`pinjam_id`);
ALTER TABLE `tbl_denda` ADD INDEX IF NOT EXISTS `idx_tgl_denda` (`tgl_denda`);

ALTER TABLE `tbl_buku` ADD INDEX IF NOT EXISTS `idx_buku_id` (`buku_id`);
ALTER TABLE `tbl_buku` ADD INDEX IF NOT EXISTS `idx_isbn` (`isbn`);
ALTER TABLE `tbl_buku` ADD INDEX IF NOT EXISTS `idx_judul` (`judul_buku`);

ALTER TABLE `tbl_pinjam` ADD INDEX IF NOT EXISTS `idx_pinjam_id` (`pinjam_id`);
ALTER TABLE `tbl_pinjam` ADD INDEX IF NOT EXISTS `idx_anggota_id` (`anggota_id`);
ALTER TABLE `tbl_pinjam` ADD INDEX IF NOT EXISTS `idx_status` (`status`);

-- 13. Periksa apakah ada data yang perlu diisi
-- Jika tabel kosong, jalankan INSERT statements dari perpus_new.sql

-- 14. Test query yang bermasalah
-- Query ini seharusnya berjalan setelah struktur diperbaiki
/*
SELECT 
    h.*,
    b.judul_buku,
    b.isbn,
    l1.nama as nama_petugas,
    COALESCE(l2.nama, CONCAT('[ID:', h.anggota_id, ']')) as nama_anggota,
    d.denda as harga_denda
FROM tbl_history h
LEFT JOIN tbl_buku b ON h.buku_id = b.id_buku
LEFT JOIN tbl_login l1 ON h.petugas_id = l1.id_login
LEFT JOIN tbl_login l2 ON h.anggota_id = l2.id_login
LEFT JOIN tbl_denda d ON d.pinjam_id = h.kode_transaksi
ORDER BY h.tanggal DESC
LIMIT 10;
*/

-- 15. Verifikasi struktur tabel
-- Jalankan perintah ini untuk memastikan semua kolom sudah benar
-- DESCRIBE tbl_history;
-- DESCRIBE tbl_denda;
-- DESCRIBE tbl_buku;
-- DESCRIBE tbl_login;
-- DESCRIBE tbl_pinjam; 