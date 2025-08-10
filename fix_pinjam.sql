-- Backup data lama
CREATE TABLE tbl_pinjam_backup AS SELECT * FROM tbl_pinjam;

-- Ubah struktur tabel pinjam
ALTER TABLE tbl_pinjam MODIFY pinjam_id BIGINT NOT NULL AUTO_INCREMENT;

-- Reset auto increment
ALTER TABLE tbl_pinjam AUTO_INCREMENT = 1;

-- Update format pinjam_id yang ada
UPDATE tbl_pinjam 
SET pinjam_id = (
    SELECT @row_number := @row_number + 1 
    FROM (SELECT @row_number := 0) AS t
);
