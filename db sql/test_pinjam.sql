-- File untuk testing pengembalian buku
-- Pastikan ada data pinjam dengan status 'Dipinjam' dan tgl_kembali = '0'

-- Hapus data pinjam lama jika ada
DELETE FROM tbl_pinjam WHERE pinjam_id = 'PJ001';

-- Tambah data pinjam baru untuk testing
INSERT INTO tbl_pinjam (pinjam_id, anggota_id, buku_id, status, tgl_pinjam, lama_pinjam, tgl_balik, tgl_kembali) VALUES
('PJ001', 'AG001', 'BK001', 'Dipinjam', '2024-01-15', 7, '2024-01-22', '0');

-- Pastikan stok buku berkurang
UPDATE tbl_buku SET jml = jml - 1, status = 'Tersedia' WHERE buku_id = 'BK001';

-- Pastikan ada data anggota dan buku
INSERT IGNORE INTO tbl_login (id_login, anggota_id, nama, username, password, level) VALUES
(1, 'AG001', 'Test Anggota', 'anggota', '123456', 'Anggota');

INSERT IGNORE INTO tbl_buku (id_buku, buku_id, judul_buku, pengarang, penerbit, thn_buku, jml, status) VALUES
(1, 'BK001', 'Buku Test', 'Pengarang Test', 'Penerbit Test', '2024', 5, 'Tersedia'); 