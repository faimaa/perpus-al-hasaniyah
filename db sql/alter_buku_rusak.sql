-- Hapus foreign key constraints jika ada
ALTER TABLE `tbl_buku_rusak` 
  DROP FOREIGN KEY IF EXISTS `tbl_buku_rusak_ibfk_1`,
  DROP FOREIGN KEY IF EXISTS `tbl_buku_rusak_ibfk_2`;

-- Hapus indexes
ALTER TABLE `tbl_buku_rusak`
  DROP INDEX IF EXISTS `buku_id`,
  DROP INDEX IF EXISTS `petugas_id`;

-- Modifikasi kolom
ALTER TABLE `tbl_buku_rusak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
  MODIFY `buku_id` int(11) NOT NULL,
  MODIFY `jumlah` int(11) NOT NULL,
  MODIFY `keterangan` text DEFAULT NULL,
  MODIFY `tanggal` datetime NOT NULL,
  MODIFY `petugas_id` int(11) NOT NULL;

-- Tambah kembali indexes
ALTER TABLE `tbl_buku_rusak`
  ADD KEY `buku_id` (`buku_id`),
  ADD KEY `petugas_id` (`petugas_id`);

-- Tambah kembali foreign keys
ALTER TABLE `tbl_buku_rusak`
  ADD CONSTRAINT `tbl_buku_rusak_ibfk_1` 
    FOREIGN KEY (`buku_id`) REFERENCES `tbl_buku` (`id_buku`) 
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_buku_rusak_ibfk_2` 
    FOREIGN KEY (`petugas_id`) REFERENCES `tbl_login` (`id_login`) 
    ON DELETE CASCADE ON UPDATE CASCADE;

-- Set charset dan collation
ALTER TABLE `tbl_buku_rusak` 
  CONVERT TO CHARACTER SET utf8mb4 
  COLLATE utf8mb4_general_ci;
