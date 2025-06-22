-- Drop tabel lama jika ada
DROP TABLE IF EXISTS `tbl_buku_rusak`;

-- Buat tabel baru dengan struktur yang benar
CREATE TABLE `tbl_buku_rusak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `buku_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `petugas_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `buku_id` (`buku_id`),
  KEY `petugas_id` (`petugas_id`),
  CONSTRAINT `tbl_buku_rusak_ibfk_1` FOREIGN KEY (`buku_id`) REFERENCES `tbl_buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_buku_rusak_ibfk_2` FOREIGN KEY (`petugas_id`) REFERENCES `tbl_login` (`id_login`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
