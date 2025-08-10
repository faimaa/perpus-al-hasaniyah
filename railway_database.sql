-- Railway Database Setup for Perpustakaan AL-HASANIYAH
-- Database: railway
-- Host: ballast.proxy.rlwy.net:15609

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS `railway`;
USE `railway`;

-- Table structure for table `tbl_biaya_denda`
CREATE TABLE `tbl_biaya_denda` (
  `id_biaya_denda` int(11) NOT NULL,
  `harga_denda` varchar(255) NOT NULL,
  `stat` varchar(255) NOT NULL,
  `tgl_tetap` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `tbl_buku`
CREATE TABLE `tbl_buku` (
  `id_buku` int(11) NOT NULL,
  `buku_id` varchar(255) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_rak` int(11) NOT NULL,
  `sampul` varchar(255) DEFAULT NULL,
  `isbn` varchar(255) DEFAULT NULL,
  `lampiran` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `penerbit` varchar(255) DEFAULT NULL,
  `pengarang` varchar(255) DEFAULT NULL,
  `thn_buku` varchar(255) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `jml` int(11) DEFAULT NULL,
  `tgl_masuk` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `tbl_denda`
CREATE TABLE `tbl_denda` (
  `id_denda` int(11) NOT NULL,
  `pinjam_id` varchar(255) NOT NULL,
  `denda` varchar(255) NOT NULL,
  `lama_waktu` int(11) NOT NULL,
  `tgl_denda` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `tbl_kategori`
CREATE TABLE `tbl_kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `tbl_login`
CREATE TABLE `tbl_login` (
  `id_login` int(11) NOT NULL,
  `anggota_id` varchar(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tgl_lahir` varchar(255) NOT NULL,
  `jenkel` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(25) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tgl_bergabung` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `tbl_pinjam`
CREATE TABLE `tbl_pinjam` (
  `id_pinjam` int(11) NOT NULL,
  `pinjam_id` varchar(255) NOT NULL,
  `anggota_id` varchar(255) NOT NULL,
  `buku_id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `tgl_pinjam` varchar(255) NOT NULL,
  `lama_pinjam` int(11) NOT NULL,
  `tgl_balik` varchar(255) NOT NULL,
  `tgl_kembali` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `tbl_rak`
CREATE TABLE `tbl_rak` (
  `id_rak` int(11) NOT NULL,
  `nama_rak` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data for login (password: 123)
INSERT INTO `tbl_login` (`id_login`, `anggota_id`, `user`, `pass`, `level`, `nama`, `tempat_lahir`, `tgl_lahir`, `jenkel`, `alamat`, `telepon`, `email`, `tgl_bergabung`, `foto`) VALUES
(1, 'AG001', 'anang', '202cb962ac59075b964b07152d234b70', 'Petugas', 'Anang', 'Bekasi', '1999-04-05', 'Laki-Laki', 'Ujung Harapan', '089618173609', 'fauzan1892@codekop.com', '2019-11-20', 'user_1567327491.png'),
(2, 'AG002', 'fauzan', '202cb962ac59075b964b07152d234b70', 'Anggota', 'Fauzan', 'Bekasi', '1998-11-18', 'Laki-Laki', 'Bekasi Barat', '08123123185', 'fauzanfalah21@gmail.com', '2019-11-21', 'user_1589911243.png');

INSERT INTO `tbl_kategori` (`id_kategori`, `nama_kategori`) VALUES
(2, 'Pemrograman');

-- Insert sample data for rak
INSERT INTO `tbl_rak` (`id_rak`, `nama_rak`) VALUES
(1, 'Rak Buku 1');

-- Insert sample data for buku
INSERT INTO `tbl_buku` (`id_buku`, `buku_id`, `id_kategori`, `id_rak`, `sampul`, `isbn`, `lampiran`, `title`, `penerbit`, `pengarang`, `thn_buku`, `isi`, `jml`, `tgl_masuk`) VALUES
(8, 'BK008', 2, 1, '0', '132-123-234-231', '0', 'CARA MUDAH BELAJAR PEMROGRAMAN C++', 'INFORMATIKA BANDUNG', 'BUDI RAHARJO ', '2012', 'Tipe Buku: Kertas, Bahasa: Indonesia', 23, '2019-11-23 11:49:57');

-- Insert sample data for biaya denda
INSERT INTO `tbl_biaya_denda` (`id_biaya_denda`, `harga_denda`, `stat`, `tgl_tetap`) VALUES
(1, '4000', 'Aktif', '2019-11-23');

-- Add primary keys
ALTER TABLE `tbl_biaya_denda` ADD PRIMARY KEY (`id_biaya_denda`);
ALTER TABLE `tbl_buku` ADD PRIMARY KEY (`id_buku`);
ALTER TABLE `tbl_denda` ADD PRIMARY KEY (`id_denda`);
ALTER TABLE `tbl_kategori` ADD PRIMARY KEY (`id_kategori`);
ALTER TABLE `tbl_login` ADD PRIMARY KEY (`id_login`);
ALTER TABLE `tbl_pinjam` ADD PRIMARY KEY (`id_pinjam`);
ALTER TABLE `tbl_rak` ADD PRIMARY KEY (`id_rak`);

-- Add auto increment
ALTER TABLE `tbl_biaya_denda` MODIFY `id_biaya_denda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `tbl_buku` MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `tbl_denda` MODIFY `id_denda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `tbl_kategori` MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `tbl_login` MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `tbl_pinjam` MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
ALTER TABLE `tbl_rak` MODIFY `id_rak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2; 