-- Fix untuk Admin tidak bisa mengembalikan buku
-- Pastikan ada data pinjam dengan status 'Dipinjam' dan tgl_kembali = '0'

-- 1. Hapus data pinjam lama untuk testing
DELETE FROM tbl_pinjam WHERE pinjam_id = 'PJ001';

-- 2. Pastikan ada data anggota
INSERT IGNORE INTO tbl_login (id_login, anggota_id, nama, username, password, level) VALUES
(1, 'AG001', 'Test Anggota', 'anggota', '123456', 'Anggota');

-- 3. Pastikan ada data buku
INSERT IGNORE INTO tbl_buku (id_buku, buku_id, judul_buku, pengarang, penerbit, thn_buku, jml, status) VALUES
(1, 'BK001', 'Buku Test Pengembalian', 'Pengarang Test', 'Penerbit Test', '2024', 5, 'Tersedia');

-- 4. Tambah data pinjam yang bisa dikembalikan
INSERT INTO tbl_pinjam (pinjam_id, anggota_id, buku_id, status, tgl_pinjam, lama_pinjam, tgl_balik, tgl_kembali) VALUES
('PJ001', 'AG001', 'BK001', 'Dipinjam', '2024-01-15', 7, '2024-01-22', '0');

-- 5. Update stok buku (kurangi 1 karena dipinjam)
UPDATE tbl_buku SET jml = jml - 1 WHERE buku_id = 'BK001';

-- 6. Cek data yang sudah dibuat
SELECT 'Data Pinjam:' as info;
SELECT * FROM tbl_pinjam WHERE pinjam_id = 'PJ001';

SELECT 'Data Buku:' as info;
SELECT buku_id, judul_buku, jml, status FROM tbl_buku WHERE buku_id = 'BK001';

SELECT 'Data Login:' as info;
SELECT id_login, anggota_id, nama, level FROM tbl_login WHERE anggota_id = 'AG001'; 