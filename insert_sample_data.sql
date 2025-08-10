-- Script untuk mengisi data sample di database Railway
-- Jalankan script ini setelah struktur tabel diperbaiki

-- 1. Insert data kategori buku
INSERT IGNORE INTO `tbl_kategori` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Pendidikan', 'Buku-buku pendidikan dan pembelajaran'),
(2, 'Novel', 'Buku fiksi dan novel'),
(3, 'Teknologi', 'Buku teknologi dan komputer'),
(4, 'Agama', 'Buku keagamaan dan spiritual'),
(5, 'Sains', 'Buku sains dan pengetahuan umum');

-- 2. Insert data rak buku
INSERT IGNORE INTO `tbl_rak` (`id_rak`, `nama_rak`, `lokasi`, `kapasitas`, `deskripsi`) VALUES
(1, 'Rak A', 'Lantai 1 - Depan', 100, 'Rak untuk buku pendidikan'),
(2, 'Rak B', 'Lantai 1 - Tengah', 80, 'Rak untuk novel dan fiksi'),
(3, 'Rak C', 'Lantai 1 - Belakang', 60, 'Rak untuk teknologi'),
(4, 'Rak D', 'Lantai 2 - Depan', 70, 'Rak untuk agama'),
(5, 'Rak E', 'Lantai 2 - Tengah', 90, 'Rak untuk sains');

-- 3. Insert data buku
INSERT IGNORE INTO `tbl_buku` (`id_buku`, `buku_id`, `id_kategori`, `id_rak`, `isbn`, `judul_buku`, `penerbit`, `pengarang`, `thn_buku`, `jml`, `tgl_masuk`, `status`) VALUES
(1, 'BK001', 1, 1, '9786021234567', 'Matematika Dasar', 'Penerbit A', 'Dr. Ahmad', '2020-01-01', 10, '2020-01-15', 'Tersedia'),
(2, 'BK002', 1, 1, '9786021234568', 'Bahasa Indonesia', 'Penerbit B', 'Dr. Siti', '2020-02-01', 8, '2020-02-15', 'Tersedia'),
(3, 'BK003', 2, 2, '9786021234569', 'Novel Cinta', 'Penerbit C', 'Penulis A', '2020-03-01', 5, '2020-03-15', 'Tersedia'),
(4, 'BK004', 3, 3, '9786021234570', 'Pemrograman Web', 'Penerbit D', 'Programmer A', '2020-04-01', 12, '2020-04-15', 'Tersedia'),
(5, 'BK005', 4, 4, '9786021234571', 'Kitab Suci', 'Penerbit E', 'Ulama A', '2020-05-01', 15, '2020-05-15', 'Tersedia');

-- 4. Insert data login (admin dan petugas)
INSERT IGNORE INTO `tbl_login` (`id_login`, `username`, `password`, `nama`, `level`, `email`, `status`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'Admin', 'admin@perpus.com', 'Aktif'),
(2, 'petugas1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Petugas Satu', 'Petugas', 'petugas1@perpus.com', 'Aktif'),
(3, 'petugas2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Petugas Dua', 'Petugas', 'petugas2@perpus.com', 'Aktif');

-- 5. Insert data anggota
INSERT IGNORE INTO `tbl_login` (`id_login`, `username`, `password`, `nama`, `level`, `anggota_id`, `email`, `no_telp`, `alamat`, `tgl_lahir`, `jenis_kelamin`, `status`) VALUES
(4, 'anggota001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Anggota Satu', 'Anggota', 'AG001', 'anggota1@email.com', '08123456789', 'Jl. Contoh No. 1', '1990-01-01', 'Laki-laki', 'Aktif'),
(5, 'anggota002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Anggota Dua', 'Anggota', 'AG002', 'anggota2@email.com', '08123456790', 'Jl. Contoh No. 2', '1992-02-02', 'Perempuan', 'Aktif'),
(6, 'anggota003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Anggota Tiga', 'Anggota', 'AG003', 'anggota3@email.com', '08123456791', 'Jl. Contoh No. 3', '1995-03-03', 'Laki-laki', 'Aktif');

-- 6. Insert data pinjam
INSERT IGNORE INTO `tbl_pinjam` (`id_pinjam`, `pinjam_id`, `buku_id`, `anggota_id`, `petugas_id`, `tgl_pinjam`, `tgl_kembali`, `lama_pinjam`, `status`, `keterangan`) VALUES
(1, 'PJ001', 'BK001', 'AG001', 1, '2025-01-01', '2025-01-08', 7, 'Di Kembalikan', 'Peminjaman buku matematika'),
(2, 'PJ002', 'BK002', 'AG002', 2, '2025-01-02', '2025-01-09', 7, 'Di Kembalikan', 'Peminjaman buku bahasa'),
(3, 'PJ003', 'BK003', 'AG003', 1, '2025-01-03', '2025-01-10', 7, 'Di Pinjam', 'Peminjaman novel'),
(4, 'PJ004', 'BK004', 'AG001', 2, '2025-01-04', '2025-01-11', 7, 'Di Pinjam', 'Peminjaman buku programming'),
(5, 'PJ005', 'BK005', 'AG002', 1, '2025-01-05', '2025-01-12', 7, 'Di Pinjam', 'Peminjaman kitab');

-- 7. Insert data history
INSERT IGNORE INTO `tbl_history` (`id`, `tipe_transaksi`, `kode_transaksi`, `buku_id`, `anggota_id`, `petugas_id`, `jumlah`, `keterangan`, `tanggal`) VALUES
(1, 'Peminjaman', 'PJ001', 1, 4, 1, 1, 'Peminjaman buku matematika selama 7 hari', '2025-01-01 09:00:00'),
(2, 'Peminjaman', 'PJ002', 2, 5, 2, 1, 'Peminjaman buku bahasa selama 7 hari', '2025-01-02 09:00:00'),
(3, 'Peminjaman', 'PJ003', 3, 6, 1, 1, 'Peminjaman novel selama 7 hari', '2025-01-03 09:00:00'),
(4, 'Peminjaman', 'PJ004', 4, 4, 2, 1, 'Peminjaman buku programming selama 7 hari', '2025-01-04 09:00:00'),
(5, 'Peminjaman', 'PJ005', 5, 5, 1, 1, 'Peminjaman kitab selama 7 hari', '2025-01-05 09:00:00'),
(6, 'Pengembalian', 'PJ001', 1, 4, 1, 1, 'Pengembalian buku matematika', '2025-01-08 09:00:00'),
(7, 'Pengembalian', 'PJ002', 2, 5, 2, 1, 'Pengembalian buku bahasa', '2025-01-09 09:00:00');

-- 8. Insert data denda
INSERT IGNORE INTO `tbl_denda` (`id_denda`, `pinjam_id`, `denda`, `lama_waktu`, `tgl_denda`) VALUES
(1, 'PJ001', 0, 0, '2025-01-08'),
(2, 'PJ002', 0, 0, '2025-01-09');

-- 9. Insert data biaya denda
INSERT IGNORE INTO `tbl_biaya_denda` (`id_biaya_denda`, `harga_denda`, `stat`, `tgl_tetap`) VALUES
(1, 4000, 'Aktif', '2025-01-01');

-- 10. Insert data pengembalian
INSERT IGNORE INTO `tbl_pengembalian` (`id_pengembalian`, `id_pinjam`, `tgl_kembali`, `denda`, `keterangan`, `petugas_id`) VALUES
(1, 1, '2025-01-08 09:00:00', 0.00, 'Pengembalian tepat waktu', 1),
(2, 2, '2025-01-09 09:00:00', 0.00, 'Pengembalian tepat waktu', 2);

-- 11. Update status pinjam yang sudah dikembalikan
UPDATE `tbl_pinjam` SET `status` = 'Di Kembalikan' WHERE `pinjam_id` IN ('PJ001', 'PJ002');

-- 12. Verifikasi data yang sudah diinsert
SELECT 'Kategori' as tabel, COUNT(*) as jumlah FROM tbl_kategori
UNION ALL
SELECT 'Rak', COUNT(*) FROM tbl_rak
UNION ALL
SELECT 'Buku', COUNT(*) FROM tbl_buku
UNION ALL
SELECT 'Login', COUNT(*) FROM tbl_login
UNION ALL
SELECT 'Pinjam', COUNT(*) FROM tbl_pinjam
UNION ALL
SELECT 'History', COUNT(*) FROM tbl_history
UNION ALL
SELECT 'Denda', COUNT(*) FROM tbl_denda
UNION ALL
SELECT 'Biaya Denda', COUNT(*) FROM tbl_biaya_denda
UNION ALL
SELECT 'Pengembalian', COUNT(*) FROM tbl_pengembalian; 