-- Pastikan kolom id_pinjam ada di tbl_pinjam
ALTER TABLE tbl_pinjam CHANGE id_pinjam id_pinjam INT AUTO_INCREMENT PRIMARY KEY;

-- Pastikan kolom pinjam_id ada di tbl_denda
ALTER TABLE tbl_denda MODIFY pinjam_id INT;
