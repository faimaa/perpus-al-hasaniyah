CREATE DATABASE IF NOT EXISTS `projek_perpus`;
USE `projek_perpus`;

CREATE TABLE `tbl_biaya_denda` (
  `id_biaya_denda` int(6) NOT NULL,
  `harga_denda` int(6) NOT NULL,
  `stat` enum('Aktif','Tidak Aktif') NOT NULL,
  `tgl_tetap` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `tbl_biaya_denda`
  ADD PRIMARY KEY (`id_biaya_denda`);
