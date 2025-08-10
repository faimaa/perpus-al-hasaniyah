-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 10 Agu 2025 pada 18.53
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpus_new`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_biaya_denda`
--

CREATE TABLE `tbl_biaya_denda` (
  `id_biaya_denda` int(6) NOT NULL,
  `harga_denda` int(6) NOT NULL,
  `stat` enum('Aktif','Tidak Aktif') NOT NULL,
  `tgl_tetap` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_biaya_denda`
--

INSERT INTO `tbl_biaya_denda` (`id_biaya_denda`, `harga_denda`, `stat`, `tgl_tetap`) VALUES
(12, 4000, 'Aktif', '2025-07-23'),
(13, 6000, 'Tidak Aktif', '2025-07-23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_buku`
--

CREATE TABLE `tbl_buku` (
  `id_buku` int(6) NOT NULL,
  `buku_id` varchar(5) NOT NULL,
  `id_kategori` int(6) NOT NULL,
  `id_rak` int(6) NOT NULL,
  `sampul` blob DEFAULT NULL,
  `isbn` varchar(13) DEFAULT NULL,
  `judul_buku` varchar(20) DEFAULT NULL,
  `penerbit` varchar(20) DEFAULT NULL,
  `pengarang` varchar(20) DEFAULT NULL,
  `thn_buku` date DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `jml` int(2) DEFAULT NULL,
  `tgl_masuk` date DEFAULT NULL,
  `status` enum('Tersedia','Rusak') DEFAULT 'Tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_buku`
--

INSERT INTO `tbl_buku` (`id_buku`, `buku_id`, `id_kategori`, `id_rak`, `sampul`, `isbn`, `judul_buku`, `penerbit`, `pengarang`, `thn_buku`, `isi`, `jml`, `tgl_masuk`, `status`) VALUES
(9, 'BK009', 3, 1, 0x30636464303863363733613861393261633262366339396661663864316530662e6a7067, '734830923', 'Matematika', 'Pusat Kurikulum dan ', 'Muhammad Nuh', '2011-06-29', '', 21, '2025-07-29', 'Tersedia'),
(10, 'BK001', 4, 1, 0x34666438633730313332393237623630376236373733653762313362626634372e6a7067, '231231231314', 'bahasa indonesia', 'Pusat Kurikulum dan ', 'Lukman Surya Saputra', '2023-07-27', '', 21, '2025-07-29', 'Tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_buku_hilang`
--

CREATE TABLE `tbl_buku_hilang` (
  `id` int(6) NOT NULL,
  `buku_id` varchar(5) NOT NULL,
  `anggota_id` varchar(6) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tgl_hilang` datetime NOT NULL,
  `petugas_id` int(6) NOT NULL,
  `pinjam_id` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_buku_hilang`
--

INSERT INTO `tbl_buku_hilang` (`id`, `buku_id`, `anggota_id`, `keterangan`, `tgl_hilang`, `petugas_id`, `pinjam_id`) VALUES
(137, '9', 'AG005', '', '2025-08-03 22:15:23', 1, '368');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_buku_rusak`
--

CREATE TABLE `tbl_buku_rusak` (
  `id` int(6) NOT NULL,
  `buku_id` int(5) NOT NULL,
  `jumlah` int(2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `petugas_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_denda`
--

CREATE TABLE `tbl_denda` (
  `id_denda` int(6) NOT NULL,
  `pinjam_id` varchar(6) NOT NULL,
  `denda` int(11) NOT NULL,
  `lama_waktu` int(2) NOT NULL,
  `tgl_denda` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_denda`
--

INSERT INTO `tbl_denda` (`id_denda`, `pinjam_id`, `denda`, `lama_waktu`, `tgl_denda`) VALUES
(49, 'PJ001', 8000, 2, '2025-07-03'),
(50, 'PJ0022', 36000, 9, '2025-07-03'),
(51, 'PJ0023', 60000, 15, '2025-07-04'),
(52, 'PJ0024', 212000, 53, '2025-07-04'),
(53, 'PJ0025', 212000, 53, '2025-07-04'),
(54, 'PJ0026', 28000, 7, '2025-07-04'),
(55, 'PJ0027', 16000, 4, '2025-07-04'),
(56, 'PJ0028', 28000, 7, '2025-07-04'),
(57, 'PJ0030', 36000, 9, '2025-07-11'),
(58, 'PJ0033', 24000, 6, '2025-07-17'),
(59, 'PJ0034', 24000, 6, '2025-07-17'),
(60, 'PJ0035', 24000, 6, '2025-07-17'),
(61, 'PJ0036', 24000, 6, '2025-07-17'),
(62, 'PJ0037', 16000, 4, '2025-07-17'),
(63, 'PJ0038', 60000, 15, '2025-07-17'),
(64, 'PJ0039', 24000, 6, '2025-07-18'),
(65, 'PJ0031', 4000, 1, '2025-07-18'),
(66, 'PJ0029', 84000, 21, '2025-07-18'),
(67, 'PJ0040', 112000, 28, '2025-07-18'),
(68, 'PJ0041', 128000, 32, '2025-07-23'),
(69, 'PJ0044', 4000, 1, '2025-07-23'),
(70, 'PJ0044', 4000, 1, '2025-07-23'),
(71, 'PJ0042', 56000, 14, '2025-07-25'),
(72, 'PJ0049', 16000, 4, '2025-07-25'),
(73, 'PJ0051', 48000, 12, '2025-07-25'),
(74, 'PJ0053', 60000, 15, '2025-07-26'),
(75, 'PJ0054', 68000, 17, '2025-07-27'),
(76, 'PJ0055', 64000, 16, '2025-07-27'),
(77, 'PJ0052', 80000, 20, '2025-07-27'),
(78, 'PJ0056', 40000, 10, '2025-07-27'),
(79, 'PJ0057', 40000, 10, '2025-07-28'),
(80, 'PJ0060', 44000, 11, '2025-07-28'),
(81, 'PJ001', 108000, 27, '2025-07-28'),
(82, 'PJ0061', 8000, 2, '2025-07-28'),
(83, 'PJ0060', 44000, 11, '2025-07-28'),
(84, 'PJ0059', 44000, 11, '2025-07-28'),
(85, 'PJ0062', 44000, 11, '2025-07-28'),
(86, 'PJ0063', 36000, 9, '2025-07-28'),
(87, 'PJ0064', 8000, 2, '2025-07-28'),
(88, 'PJ0065', 4000, 1, '2025-07-28'),
(89, 'PJ0069', 72000, 18, '2025-07-29'),
(90, 'PJ0066', 48000, 12, '2025-07-29'),
(91, 'PJ0070', 44000, 11, '2025-07-29'),
(92, 'PJ0068', 76000, 19, '2025-07-29'),
(93, 'PJ0161', 4000, 1, '2025-08-04'),
(94, 'PJ0163', 12000, 3, '2025-08-05'),
(95, 'PJ0166', 8000, 2, '2025-08-08'),
(96, 'PJ0166', 8000, 2, '2025-08-08'),
(97, 'PJ0165', 20000, 5, '2025-08-08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_history`
--

CREATE TABLE `tbl_history` (
  `id` int(6) NOT NULL,
  `tipe_transaksi` enum('Peminjaman','Pengembalian','Buku Rusak','Perbaikan Buku','Buku Hilang','Mengganti Buku Baru') NOT NULL,
  `kode_transaksi` varchar(6) DEFAULT NULL,
  `buku_id` int(5) DEFAULT NULL,
  `anggota_id` int(6) DEFAULT NULL,
  `petugas_id` int(6) DEFAULT NULL,
  `jumlah` int(2) NOT NULL DEFAULT 1,
  `keterangan` text DEFAULT NULL,
  `tanggal` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_history`
--

INSERT INTO `tbl_history` (`id`, `tipe_transaksi`, `kode_transaksi`, `buku_id`, `anggota_id`, `petugas_id`, `jumlah`, `keterangan`, `tanggal`) VALUES
(456, 'Peminjaman', 'PJ0072', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-29 03:26:31'),
(458, 'Peminjaman', 'PJ0074', 10, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-29 03:27:03'),
(460, 'Buku Rusak', 'BR2025', 9, NULL, 1, 1, '', '2025-07-29 03:28:47'),
(462, 'Buku Hilang', '283', 10, 6, 1, 1, '', '2025-07-29 03:41:03'),
(465, 'Perbaikan Buku', 'PB2025', 10, NULL, 1, 1, 'Buku selesai diperbaiki', '2025-07-29 03:53:47'),
(466, 'Perbaikan Buku', 'PB2025', 9, NULL, 1, 1, 'Buku selesai diperbaiki', '2025-07-29 03:54:12'),
(467, 'Buku Hilang', '281', 9, 5, 1, 1, '', '2025-07-29 04:27:21'),
(470, 'Peminjaman', 'PJ0077', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-29 04:34:58'),
(472, 'Peminjaman', 'PJ0079', 10, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-29 20:39:10'),
(473, 'Peminjaman', 'PJ0080', 10, 6, 1, 1, 'Peminjaman buku selama 4 hari', '2025-07-29 20:53:12'),
(477, 'Peminjaman', 'PJ0084', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-29 21:35:31'),
(482, 'Peminjaman', 'PJ0089', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-29 22:21:48'),
(483, 'Peminjaman', 'PJ0090', 10, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-29 22:30:11'),
(495, 'Peminjaman', 'PJ0098', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-29 23:12:11'),
(496, 'Mengganti Buku Baru', '307', 9, 5, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-07-29 23:12:40'),
(497, 'Peminjaman', 'PJ0099', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-29 23:20:06'),
(498, 'Buku Hilang', 'BH308', 9, 5, 1, 1, '', '2025-07-29 23:21:05'),
(501, 'Peminjaman', 'PJ0101', 9, 6, 1, 1, 'Peminjaman buku selama 2 hari', '2025-07-29 23:45:26'),
(502, 'Buku Hilang', 'BH310', 9, 6, 1, 1, 'kacau', '2025-07-29 23:45:59'),
(503, 'Peminjaman', 'PJ0102', 10, 5, 1, 1, 'Peminjaman buku selama 4 hari', '2025-07-29 23:52:29'),
(504, 'Buku Hilang', 'BH311', 10, 5, 1, 1, '2222axsxadx', '2025-07-29 23:53:21'),
(507, 'Peminjaman', 'PJ0104', 10, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-30 00:06:06'),
(508, 'Buku Hilang', 'BH313', 10, 5, 1, 1, 'dijual', '2025-07-30 00:07:07'),
(513, 'Peminjaman', 'PJ0106', 9, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-30 00:35:52'),
(514, 'Mengganti Buku Baru', 'BH315', 9, 6, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-07-30 00:42:59'),
(521, 'Peminjaman', 'PJ0110', 9, 6, 1, 1, 'Peminjaman buku selama 5 hari', '2025-07-30 14:14:49'),
(522, 'Mengganti Buku Baru', 'BH319', 9, 6, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-07-30 14:24:25'),
(525, 'Peminjaman', 'PJ0112', 9, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-30 14:36:51'),
(526, 'Mengganti Buku Baru', 'BH321', 9, 6, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-07-30 14:37:27'),
(527, 'Peminjaman', 'PJ0113', 9, 5, 1, 1, 'Peminjaman buku selama 4 hari', '2025-07-30 14:40:53'),
(528, 'Mengganti Buku Baru', 'BH322', 9, 5, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-07-30 14:50:38'),
(529, 'Peminjaman', 'PJ0114', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-30 14:51:06'),
(530, 'Mengganti Buku Baru', 'BH323', 9, 5, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-07-30 14:52:00'),
(535, 'Peminjaman', 'PJ0117', 9, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-30 15:08:57'),
(536, 'Mengganti Buku Baru', 'BH326', 9, 6, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-07-30 15:17:14'),
(537, 'Peminjaman', 'PJ0118', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-30 15:17:45'),
(538, 'Mengganti Buku Baru', 'BH327', 9, 5, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-07-30 15:19:31'),
(539, 'Peminjaman', 'PJ0119', 10, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-30 15:23:03'),
(540, 'Mengganti Buku Baru', 'BH328', 10, 6, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-07-30 16:27:10'),
(541, 'Buku Rusak', 'BR2025', 9, NULL, 1, 1, 'banjir', '2025-07-30 15:44:50'),
(542, 'Buku Rusak', 'BR2025', 10, NULL, 1, 1, 'hilang', '2025-07-30 15:45:55'),
(543, 'Buku Rusak', 'BR2025', 9, NULL, 1, 1, 'kocak', '2025-07-30 15:55:03'),
(544, 'Buku Rusak', 'BR2025', 9, NULL, 1, 1, 'dasda', '2025-07-30 16:17:30'),
(545, 'Perbaikan Buku', 'PB2025', 9, NULL, 1, 1, 'Buku selesai diperbaiki', '2025-07-30 16:18:25'),
(546, 'Perbaikan Buku', 'PB2025', 9, NULL, 1, 1, 'Buku selesai diperbaiki', '2025-07-30 16:20:51'),
(547, 'Perbaikan Buku', 'PB2025', 9, NULL, 1, 1, 'Buku selesai diperbaiki', '2025-07-30 16:21:03'),
(548, 'Perbaikan Buku', 'PB2025', 10, NULL, 1, 1, 'Buku selesai diperbaiki', '2025-07-30 16:25:23'),
(549, 'Buku Rusak', 'BR2025', 9, NULL, 1, 1, 'jujur', '2025-07-30 16:25:42'),
(550, 'Peminjaman', 'PJ0120', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-30 16:26:37'),
(551, 'Mengganti Buku Baru', 'BH329', 9, 5, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-07-30 16:33:42'),
(554, 'Perbaikan Buku', 'PB2025', 9, NULL, 1, 1, 'Buku selesai diperbaiki', '2025-07-30 16:35:09'),
(557, 'Buku Rusak', 'BR2025', 9, NULL, 1, 1, 'kowon', '2025-07-30 22:07:25'),
(558, 'Buku Rusak', 'BR2025', 9, NULL, 1, 1, 'asa', '2025-07-30 22:12:48'),
(559, 'Peminjaman', 'PJ0123', 9, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-07-30 22:13:25'),
(560, 'Mengganti Buku Baru', 'BH332', 9, 6, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-08-01 15:57:33'),
(563, 'Peminjaman', 'PJ0125', 10, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 15:55:37'),
(564, 'Buku Hilang', 'BH', 10, 5, 1, 1, '', '2025-08-01 15:58:07'),
(573, 'Peminjaman', 'PJ0130', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 16:22:15'),
(574, 'Buku Hilang', 'BH2025', 9, 5, 1, 1, '', '2025-08-01 16:26:36'),
(575, 'Peminjaman', 'PJ0131', 10, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 16:30:34'),
(576, 'Buku Hilang', 'BH2025', 10, 5, 1, 1, '', '2025-08-01 16:31:01'),
(583, 'Peminjaman', 'PJ0135', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 17:23:16'),
(584, 'Buku Hilang', 'BH2025', 9, 5, 1, 1, '', '2025-08-01 17:32:51'),
(587, 'Peminjaman', 'PJ0137', 9, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 17:43:16'),
(588, 'Buku Hilang', 'BH2025', 9, 6, 1, 1, '', '2025-08-01 17:43:26'),
(591, 'Peminjaman', 'PJ0139', 10, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 18:08:25'),
(592, 'Buku Hilang', 'BH2025', 10, 5, 1, 1, '', '2025-08-01 18:08:35'),
(595, 'Peminjaman', 'PJ0141', 10, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 18:19:17'),
(596, 'Buku Hilang', 'BH2025', 10, 5, 1, 1, '', '2025-08-01 18:19:29'),
(602, 'Peminjaman', 'PJ0144', 10, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 22:34:46'),
(606, 'Buku Hilang', 'BH2025', 10, 5, 1, 1, '', '2025-08-01 22:43:06'),
(612, 'Peminjaman', 'PJ0149', 9, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 23:02:23'),
(613, 'Buku Hilang', 'BH2025', 9, 6, 1, 1, '', '2025-08-01 23:02:37'),
(616, 'Peminjaman', 'PJ0151', 9, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 23:18:33'),
(617, 'Buku Hilang', 'BH2025', 9, 6, 1, 1, '', '2025-08-01 23:18:50'),
(622, 'Peminjaman', 'PJ0154', 10, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-01 23:35:39'),
(623, 'Mengganti Buku Baru', 'BH2025', 10, 5, 1, 1, 'Buku telah diganti baru oleh anggota.', '2025-08-02 00:03:14'),
(626, 'Perbaikan Buku', 'PB2025', 9, NULL, 1, 1, 'Buku selesai diperbaiki', '2025-08-02 00:03:59'),
(629, 'Peminjaman', 'PJ0157', 10, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-02 00:30:40'),
(630, 'Mengganti Buku Baru', 'BH2025', 10, 5, 1, 1, 'Buku telah diganti', '2025-08-05 14:58:26'),
(633, 'Peminjaman', 'PJ0159', 9, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-03 22:14:33'),
(634, 'Buku Hilang', 'BH2025', 9, 5, 1, 1, '', '2025-08-03 22:15:23'),
(642, 'Peminjaman', 'PJ0164', 10, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-05 14:52:52'),
(643, 'Pengembalian', 'PJ0164', 10, 6, 1, 1, 'Pengembalian buku bahasa indonesia', '2025-08-05 14:53:05'),
(644, 'Peminjaman', 'PJ0165', 10, 5, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-05 14:53:31'),
(645, 'Perbaikan Buku', 'PB2025', 9, NULL, 1, 1, 'Buku selesai diperbaiki', '2025-08-05 14:58:10'),
(647, 'Peminjaman', 'PJ0167', 10, 5, 1, 1, 'Peminjaman buku selama 5 hari', '2025-08-08 10:23:29'),
(648, 'Peminjaman', 'PJ0167', 9, 5, 1, 1, 'Peminjaman buku selama 5 hari', '2025-08-08 10:23:29'),
(649, 'Peminjaman', 'PJ0166', 10, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-08 10:28:50'),
(650, 'Peminjaman', 'PJ0166', 9, 6, 1, 1, 'Peminjaman buku selama 1 hari', '2025-08-08 10:28:50'),
(651, 'Pengembalian', 'PJ0166', 10, 6, 1, 1, 'Pengembalian buku bahasa indonesia', '2025-08-08 10:29:16'),
(652, 'Pengembalian', 'PJ0166', 9, 6, 1, 1, 'Pengembalian buku Matematika', '2025-08-08 10:29:21'),
(653, 'Pengembalian', 'PJ0165', 10, 5, 1, 1, 'Pengembalian buku bahasa indonesia', '2025-08-08 10:29:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kategori`
--

CREATE TABLE `tbl_kategori` (
  `id_kategori` int(6) NOT NULL,
  `nama_kategori` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_kategori`
--

INSERT INTO `tbl_kategori` (`id_kategori`, `nama_kategori`) VALUES
(2, 'Pemrograma'),
(3, 'Matematika'),
(4, 'B.indonesi'),
(5, 'pendidikan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_login`
--

CREATE TABLE `tbl_login` (
  `id_login` int(6) NOT NULL,
  `anggota_id` varchar(6) NOT NULL,
  `user` varchar(20) NOT NULL,
  `pass` varchar(60) NOT NULL,
  `level` enum('Admin','Petugas','Anggota') NOT NULL,
  `nama` varchar(30) NOT NULL,
  `tempat_lahir` varchar(20) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jenkel` enum('Laki-Laki','Perempuan') NOT NULL,
  `alamat` text NOT NULL,
  `telepon` varchar(13) NOT NULL,
  `email` varchar(25) NOT NULL,
  `tgl_bergabung` date NOT NULL,
  `foto` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_login`
--

INSERT INTO `tbl_login` (`id_login`, `anggota_id`, `user`, `pass`, `level`, `nama`, `tempat_lahir`, `tgl_lahir`, `jenkel`, `alamat`, `telepon`, `email`, `tgl_bergabung`, `foto`) VALUES
(1, 'AG001', 'anang', '202cb962ac59075b964b07152d234b70', 'Admin', 'Anang', 'Bekasi', '1999-04-05', 'Laki-Laki', 'Ujung Harapa', '089618173609', 'fauzan1892@codekop.com', '2019-11-20', 0x757365725f313735313231343634302e706e67),
(2, 'AG002', 'fauzan', '202cb962ac59075b964b07152d234b70', 'Petugas', 'Fauzan', 'Bekasi', '1998-11-18', 'Laki-Laki', 'Bekasi Barat', '08123123185', 'fauzanfalah21@gmail.com', '2019-11-21', 0x757365725f313735313231343833302e6a7067),
(4, 'AG004', 'jaki ', '202cb962ac59075b964b07152d234b70', 'Petugas', 'jaki ', 'bogor ', '2025-06-04', 'Laki-Laki', 'gunung putriii', '0929929922029', 'jaki@gmail.com', '2025-06-23', 0x757365725f313735313231343839342e706e67),
(5, 'AG005', 'erwin', '785f0b13d4daf8eee0d11195f58302a4', 'Anggota', 'erwin', 'tangerang', '2014-03-13', 'Laki-Laki', 'jelupang', '08956713562', 'erwintakain32@gmail.com', '2025-06-27', 0x757365725f313735313031353737382e4a5047),
(6, 'AG006', 'faima', '202cb962ac59075b964b07152d234b70', 'Anggota', 'faima', 'tangerang', '2002-06-27', 'Laki-Laki', 'graha\r\n', '089853251234', 'faima@gmail.com', '2025-06-27', 0x757365725f313735313032343839332e4a5047),
(13, 'AG007', 'Bagus', '202cb962ac59075b964b07152d234b70', 'Petugas', 'Ujang', 'Batak', '2025-08-01', 'Laki-Laki', 'sfdsfafwefwefawfewfwef', '189372981379', 'bagus@gmail.com', '2025-08-10', 0x757365725f31373534383035363837);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pengembalian`
--

CREATE TABLE `tbl_pengembalian` (
  `id_kembali` int(6) NOT NULL,
  `id_pinjam` int(6) DEFAULT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `denda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_pengembalian`
--

INSERT INTO `tbl_pengembalian` (`id_kembali`, `id_pinjam`, `tgl_kembali`, `denda`) VALUES
(26, 227, '2025-07-28', 108000),
(27, 270, '2025-07-28', 8000),
(28, 269, '2025-07-28', 44000),
(29, 268, '2025-07-28', 44000),
(30, 271, '2025-07-28', 44000),
(31, 272, '2025-07-28', 36000),
(32, 273, '2025-07-28', 8000),
(33, 274, '2025-07-28', 4000),
(34, 278, '2025-07-29', 72000),
(35, 275, '2025-07-29', 48000),
(36, 279, '2025-07-29', 44000),
(37, 277, '2025-07-29', 76000),
(38, 370, '2025-08-04', 4000),
(39, 372, '2025-08-05', 12000),
(40, 373, '2025-08-05', 0),
(41, 378, '2025-08-08', 8000),
(42, 379, '2025-08-08', 8000),
(43, 374, '2025-08-08', 20000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pinjam`
--

CREATE TABLE `tbl_pinjam` (
  `id_pinjam` int(6) NOT NULL,
  `pinjam_id` varchar(6) NOT NULL,
  `anggota_id` varchar(6) NOT NULL,
  `buku_id` varchar(5) NOT NULL,
  `status` varchar(15) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `lama_pinjam` int(12) NOT NULL,
  `tgl_balik` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_pinjam`
--

INSERT INTO `tbl_pinjam` (`id_pinjam`, `pinjam_id`, `anggota_id`, `buku_id`, `status`, `tgl_pinjam`, `lama_pinjam`, `tgl_balik`) VALUES
(270, 'PJ0061', 'AG007', 'BK009', 'Di Kembalikan', '2025-07-25', 1, '2025-07-26'),
(271, 'PJ0062', 'AG005', 'BK001', 'Di Kembalikan', '2025-07-16', 1, '2025-07-17'),
(272, 'PJ0063', 'AG006', 'BK009', 'Di Kembalikan', '2025-07-18', 1, '2025-07-19'),
(273, 'PJ0064', 'AG008', 'BK009', 'Di Kembalikan', '2025-07-25', 1, '2025-07-26'),
(274, 'PJ0065', 'AG005', 'BK001', 'Di Kembalikan', '2025-07-26', 1, '2025-07-27'),
(275, 'PJ0066', 'AG007', 'BK009', 'Di Kembalikan', '2025-07-16', 1, '2025-07-17'),
(276, 'PJ0067', 'AG007', 'BK001', 'Hilang', '2025-07-25', 1, '2025-07-26'),
(277, 'PJ0068', 'AG007', 'BK009', 'Di Kembalikan', '2025-07-09', 1, '2025-07-10'),
(278, 'PJ0069', 'AG008', 'BK009', 'Di Kembalikan', '2025-07-10', 1, '2025-07-11'),
(279, 'PJ0070', 'AG008', 'BK001', 'Di Kembalikan', '2025-07-17', 1, '2025-07-18'),
(280, 'PJ0071', 'AG008', 'BK009', 'Hilang', '2025-07-18', 1, '2025-07-19'),
(281, 'PJ0072', 'AG005', 'BK009', 'Hilang', '2025-07-18', 1, '2025-07-19'),
(282, 'PJ0073', 'AG007', 'BK009', 'Hilang', '2025-07-26', 1, '2025-07-27'),
(283, 'PJ0074', 'AG006', 'BK001', 'Hilang', '2025-07-16', 1, '2025-07-17'),
(284, 'PJ0075', 'AG008', 'BK001', 'Hilang', '2025-07-19', 1, '2025-07-20'),
(285, 'PJ0076', 'AG008', 'BK009', 'Hilang', '2025-07-11', 1, '2025-07-12'),
(286, 'PJ0077', 'AG005', 'BK009', 'Hilang', '2025-07-11', 1, '2025-07-12'),
(287, 'PJ0078', 'AG007', 'BK001', 'Hilang', '2025-07-11', 1, '2025-07-12'),
(288, 'PJ0079', 'AG005', 'BK001', 'Hilang', '2025-07-16', 1, '2025-07-17'),
(289, 'PJ0080', 'AG006', 'BK001', 'Hilang', '2025-07-02', 4, '2025-07-06'),
(290, 'PJ0081', 'AG008', 'BK001', 'Hilang', '2025-07-09', 1, '2025-07-10'),
(291, 'PJ0082', 'AG008', 'BK001', 'Hilang', '2025-07-01', 2, '2025-07-03'),
(292, 'PJ0083', 'AG008', 'BK009', 'Hilang', '2025-07-17', 1, '2025-07-18'),
(293, 'PJ0084', 'AG005', 'BK009', 'Hilang', '2025-07-10', 1, '2025-07-11'),
(294, 'PJ0085', 'AG008', 'BK009', 'Hilang', '2025-07-16', 1, '2025-07-17'),
(295, 'PJ0086', 'AG008', 'BK001', 'Hilang', '2025-07-17', 1, '2025-07-18'),
(296, 'PJ0087', 'AG007', 'BK009', 'Hilang', '2025-07-17', 1, '2025-07-18'),
(297, 'PJ0088', 'AG007', 'BK001', 'Hilang', '2025-07-17', 1, '2025-07-18'),
(298, 'PJ0089', 'AG005', 'BK009', 'Hilang', '2025-07-26', 1, '2025-07-27'),
(299, 'PJ0090', 'AG006', 'BK001', 'Hilang', '2025-07-17', 1, '2025-07-18'),
(300, 'PJ0091', 'AG007', 'BK009', 'Hilang', '2025-07-27', 1, '2025-07-28'),
(301, 'PJ0092', 'AG007', 'BK009', 'Hilang', '2025-07-24', 1, '2025-07-25'),
(302, 'PJ0093', 'AG008', 'BK009', 'Hilang', '2025-07-29', 1, '2025-07-30'),
(303, 'PJ0094', 'AG008', 'BK009', 'Hilang', '2025-07-24', 1, '2025-07-25'),
(304, 'PJ0095', 'AG008', 'BK001', 'Hilang', '2025-07-17', 1, '2025-07-18'),
(305, 'PJ0096', 'AG007', 'BK009', 'Hilang', '2025-07-29', 1, '2025-07-30'),
(306, 'PJ0097', 'AG008', 'BK009', 'Hilang', '2025-07-27', 1, '2025-07-28'),
(307, 'PJ0098', 'AG005', 'BK009', 'Hilang', '2025-07-24', 1, '2025-07-25'),
(308, 'PJ0099', 'AG005', 'BK009', 'Hilang', '2025-07-25', 1, '2025-07-26'),
(309, 'PJ0100', 'AG008', 'BK009', 'Hilang', '2025-07-25', 1, '2025-07-26'),
(310, 'PJ0101', 'AG006', 'BK009', 'Hilang', '2025-07-04', 2, '2025-07-06'),
(311, 'PJ0102', 'AG005', 'BK001', 'Hilang', '2025-07-19', 4, '2025-07-23'),
(312, 'PJ0103', 'AG007', 'BK009', 'Hilang', '2025-07-22', 2, '2025-07-24'),
(313, 'PJ0104', 'AG005', 'BK001', 'Hilang', '2025-07-21', 1, '2025-07-22'),
(314, 'PJ0105', 'AG008', 'BK001', 'Hilang', '2025-07-11', 1, '2025-07-12'),
(315, 'PJ0106', 'AG006', 'BK009', 'Hilang', '2025-07-02', 1, '2025-07-03'),
(316, 'PJ0107', 'AG007', 'BK009', 'Hilang', '2025-07-03', 1, '2025-07-04'),
(317, 'PJ0108', 'AG008', 'BK009', 'Hilang', '2025-07-06', 2, '2025-07-08'),
(318, 'PJ0109', 'AG007', 'BK009', 'Hilang', '2025-07-05', 2, '2025-07-07'),
(319, 'PJ0110', 'AG006', 'BK009', 'Hilang', '2025-07-01', 5, '2025-07-06'),
(320, 'PJ0111', 'AG008', 'BK009', 'Hilang', '2025-07-09', 1, '2025-07-10'),
(321, 'PJ0112', 'AG006', 'BK009', 'Hilang', '2025-07-03', 1, '2025-07-04'),
(322, 'PJ0113', 'AG005', 'BK009', 'Hilang', '2025-07-14', 4, '2025-07-18'),
(323, 'PJ0114', 'AG005', 'BK009', 'Hilang', '2025-07-08', 1, '2025-07-09'),
(324, 'PJ0115', 'AG007', 'BK001', 'Hilang', '2025-07-17', 1, '2025-07-18'),
(325, 'PJ0116', 'AG007', 'BK009', 'Hilang', '2025-07-19', 1, '2025-07-20'),
(326, 'PJ0117', 'AG006', 'BK009', 'Hilang', '2025-07-27', 1, '2025-07-28'),
(327, 'PJ0118', 'AG005', 'BK009', 'Hilang', '2025-07-28', 1, '2025-07-29'),
(328, 'PJ0119', 'AG006', 'BK001', 'Hilang', '2025-07-30', 1, '2025-07-31'),
(329, 'PJ0120', 'AG005', 'BK009', 'Hilang', '2025-07-24', 1, '2025-07-25'),
(330, 'PJ0121', 'AG008', 'BK009', 'Hilang', '2025-07-22', 3, '2025-07-25'),
(331, 'PJ0122', 'AG007', 'BK001', 'Hilang', '2025-07-17', 1, '2025-07-18'),
(332, 'PJ0123', 'AG006', 'BK009', 'Hilang', '2025-07-03', 1, '2025-07-04'),
(333, 'PJ0124', 'AG008', 'BK009', 'Hilang', '2025-07-05', 1, '2025-07-06'),
(334, 'PJ0125', 'AG005', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(335, 'PJ0126', 'AG007', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(336, 'PJ0127', 'AG008', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(337, 'PJ0128', 'AG007', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(338, 'PJ0129', 'AG008', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(339, 'PJ0130', 'AG005', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(340, 'PJ0131', 'AG005', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(341, 'PJ0132', 'AG007', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(342, 'PJ0133', 'AG007', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(343, 'PJ0134', 'AG008', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(344, 'PJ0135', 'AG005', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(345, 'PJ0136', 'AG007', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(346, 'PJ0137', 'AG006', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(347, 'PJ0138', 'AG008', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(348, 'PJ0139', 'AG005', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(349, 'PJ0140', 'AG008', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(350, 'PJ0141', 'AG005', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(351, 'PJ0142', 'AG007', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(352, 'PJ0143', 'AG007', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(353, 'PJ0144', 'AG005', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(354, 'PJ0145', 'AG007', 'BK009', 'Hilang', '2025-07-23', 1, '2025-07-24'),
(355, 'PJ0146', 'AG007', 'BK009', 'Hilang', '2025-07-23', 1, '2025-07-24'),
(356, 'PJ0147', 'AG008', 'BK009', 'Hilang', '2025-07-29', 1, '2025-07-30'),
(357, 'PJ0148', 'AG007', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(358, 'PJ0149', 'AG006', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(359, 'PJ0150', 'AG008', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(360, 'PJ0151', 'AG006', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(361, 'PJ0152', 'AG007', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(362, 'PJ0153', 'AG008', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(363, 'PJ0154', 'AG005', 'BK001', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(364, 'PJ0155', 'AG008', 'BK009', 'Hilang', '2025-08-01', 1, '2025-08-02'),
(365, 'PJ0156', 'AG007', 'BK009', 'Hilang', '2025-08-02', 1, '2025-08-03'),
(366, 'PJ0157', 'AG005', 'BK001', 'Hilang', '2025-08-02', 1, '2025-08-03'),
(367, 'PJ0158', 'AG007', 'BK009', 'Hilang', '2025-08-03', 1, '2025-08-04'),
(368, 'PJ0159', 'AG005', 'BK009', 'Hilang', '2025-08-03', 1, '2025-08-04'),
(369, 'PJ0160', 'AG008', 'BK009', 'Hilang', '2025-08-03', 1, '2025-08-04'),
(370, 'PJ0161', 'AG007', 'BK001', 'Di Kembalikan', '2025-08-02', 1, '2025-08-03'),
(372, 'PJ0163', 'AG008', 'BK001', 'Di Kembalikan', '2025-08-01', 1, '2025-08-02'),
(373, 'PJ0164', 'AG006', 'BK001', 'Di Kembalikan', '2025-08-05', 1, '2025-08-06'),
(374, 'PJ0165', 'AG005', 'BK001', 'Di Kembalikan', '2025-08-02', 1, '2025-08-03'),
(378, 'PJ0166', 'AG006', 'BK001', 'Di Kembalikan', '2025-08-05', 1, '2025-08-06'),
(379, 'PJ0166', 'AG006', 'BK009', 'Di Kembalikan', '2025-08-05', 1, '2025-08-06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_rak`
--

CREATE TABLE `tbl_rak` (
  `id_rak` int(6) NOT NULL,
  `nama_rak` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `tbl_rak`
--

INSERT INTO `tbl_rak` (`id_rak`, `nama_rak`) VALUES
(1, 'Rak Buku 1'),
(2, 'Rak Buku 2'),
(3, 'Rak Buku 3');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_biaya_denda`
--
ALTER TABLE `tbl_biaya_denda`
  ADD PRIMARY KEY (`id_biaya_denda`);

--
-- Indeks untuk tabel `tbl_buku`
--
ALTER TABLE `tbl_buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_rak` (`id_rak`);

--
-- Indeks untuk tabel `tbl_buku_hilang`
--
ALTER TABLE `tbl_buku_hilang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `petugas_id` (`petugas_id`),
  ADD KEY `pinjam_id` (`pinjam_id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indeks untuk tabel `tbl_buku_rusak`
--
ALTER TABLE `tbl_buku_rusak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buku_id` (`buku_id`),
  ADD KEY `petugas_id` (`petugas_id`);

--
-- Indeks untuk tabel `tbl_denda`
--
ALTER TABLE `tbl_denda`
  ADD PRIMARY KEY (`id_denda`);

--
-- Indeks untuk tabel `tbl_history`
--
ALTER TABLE `tbl_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buku_id` (`buku_id`),
  ADD KEY `petugas_id` (`petugas_id`),
  ADD KEY `anggota_id` (`anggota_id`);

--
-- Indeks untuk tabel `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `tbl_login`
--
ALTER TABLE `tbl_login`
  ADD PRIMARY KEY (`id_login`);

--
-- Indeks untuk tabel `tbl_pengembalian`
--
ALTER TABLE `tbl_pengembalian`
  ADD PRIMARY KEY (`id_kembali`),
  ADD KEY `id_pinjam` (`id_pinjam`);

--
-- Indeks untuk tabel `tbl_pinjam`
--
ALTER TABLE `tbl_pinjam`
  ADD PRIMARY KEY (`id_pinjam`);

--
-- Indeks untuk tabel `tbl_rak`
--
ALTER TABLE `tbl_rak`
  ADD PRIMARY KEY (`id_rak`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_biaya_denda`
--
ALTER TABLE `tbl_biaya_denda`
  MODIFY `id_biaya_denda` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `tbl_buku`
--
ALTER TABLE `tbl_buku`
  MODIFY `id_buku` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `tbl_buku_hilang`
--
ALTER TABLE `tbl_buku_hilang`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT untuk tabel `tbl_buku_rusak`
--
ALTER TABLE `tbl_buku_rusak`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `tbl_denda`
--
ALTER TABLE `tbl_denda`
  MODIFY `id_denda` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT untuk tabel `tbl_history`
--
ALTER TABLE `tbl_history`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=654;

--
-- AUTO_INCREMENT untuk tabel `tbl_kategori`
--
ALTER TABLE `tbl_kategori`
  MODIFY `id_kategori` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tbl_login`
--
ALTER TABLE `tbl_login`
  MODIFY `id_login` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `tbl_pengembalian`
--
ALTER TABLE `tbl_pengembalian`
  MODIFY `id_kembali` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `tbl_pinjam`
--
ALTER TABLE `tbl_pinjam`
  MODIFY `id_pinjam` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=380;

--
-- AUTO_INCREMENT untuk tabel `tbl_rak`
--
ALTER TABLE `tbl_rak`
  MODIFY `id_rak` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_buku_hilang`
--
ALTER TABLE `tbl_buku_hilang`
  ADD CONSTRAINT `tbl_buku_hilang_ibfk_3` FOREIGN KEY (`petugas_id`) REFERENCES `tbl_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_buku_rusak`
--
ALTER TABLE `tbl_buku_rusak`
  ADD CONSTRAINT `tbl_buku_rusak_ibfk_1` FOREIGN KEY (`buku_id`) REFERENCES `tbl_buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_buku_rusak_ibfk_2` FOREIGN KEY (`petugas_id`) REFERENCES `tbl_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_history`
--
ALTER TABLE `tbl_history`
  ADD CONSTRAINT `tbl_history_ibfk_1` FOREIGN KEY (`buku_id`) REFERENCES `tbl_buku` (`id_buku`),
  ADD CONSTRAINT `tbl_history_ibfk_2` FOREIGN KEY (`petugas_id`) REFERENCES `tbl_login` (`id_login`),
  ADD CONSTRAINT `tbl_history_ibfk_3` FOREIGN KEY (`anggota_id`) REFERENCES `tbl_login` (`id_login`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
