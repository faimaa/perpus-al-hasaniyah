-- 1. Tambah kolom baru di tabel pinjam
ALTER TABLE tbl_pinjam 
ADD COLUMN jumlah_pinjam INT NOT NULL DEFAULT 1,
ADD COLUMN denda DECIMAL(10,2) DEFAULT 0.00,
ADD COLUMN status_denda ENUM('Belum Bayar', 'Lunas') DEFAULT NULL;

-- 2. Tambah constraint untuk validasi data
ALTER TABLE tbl_pinjam
ADD CONSTRAINT chk_jumlah_pinjam 
CHECK (jumlah_pinjam > 0),
ADD CONSTRAINT chk_tgl_kembali 
CHECK (tgl_kembali IS NULL OR tgl_kembali >= tgl_pinjam);

-- 3. Tambah index untuk optimasi query
CREATE INDEX idx_status_buku ON tbl_pinjam(buku_id, status);
CREATE INDEX idx_anggota_status ON tbl_pinjam(anggota_id, status);

-- 4. Buat tabel untuk logging transaksi
CREATE TABLE tbl_log_peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pinjam_id VARCHAR(255),
    buku_id INT,
    anggota_id INT,
    jumlah INT,
    tipe_transaksi ENUM('Pinjam', 'Kembali', 'Perpanjang'),
    status_sebelum VARCHAR(50),
    status_sesudah VARCHAR(50),
    stok_sebelum INT,
    stok_sesudah INT,
    petugas_id INT,
    waktu_transaksi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    keterangan TEXT
);

-- 5. Buat tabel untuk reservasi buku
CREATE TABLE tbl_reservasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buku_id INT,
    anggota_id INT,
    tanggal_reservasi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Menunggu', 'Tersedia', 'Dibatalkan', 'Selesai') DEFAULT 'Menunggu',
    notifikasi_sent BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (buku_id) REFERENCES tbl_buku(id_buku),
    FOREIGN KEY (anggota_id) REFERENCES tbl_login(id_login)
);

-- 6. Tambah trigger untuk update stok otomatis
DELIMITER //

CREATE TRIGGER after_pinjam_insert
AFTER INSERT ON tbl_pinjam
FOR EACH ROW
BEGIN
    -- Update stok buku
    UPDATE tbl_buku 
    SET jml = jml - NEW.jumlah_pinjam
    WHERE id_buku = NEW.buku_id;
    
    -- Log transaksi
    INSERT INTO tbl_log_peminjaman 
    (pinjam_id, buku_id, anggota_id, jumlah, tipe_transaksi, 
     status_sebelum, status_sesudah, petugas_id)
    VALUES 
    (NEW.pinjam_id, NEW.buku_id, NEW.anggota_id, NEW.jumlah_pinjam, 'Pinjam',
     NULL, NEW.status, NEW.petugas_id);
END;//

CREATE TRIGGER after_pinjam_update
AFTER UPDATE ON tbl_pinjam
FOR EACH ROW
BEGIN
    -- Jika status berubah menjadi "Di Kembalikan"
    IF OLD.status = 'Dipinjam' AND NEW.status = 'Di Kembalikan' THEN
        -- Update stok buku
        UPDATE tbl_buku 
        SET jml = jml + NEW.jumlah_pinjam
        WHERE id_buku = NEW.buku_id;
        
        -- Log transaksi
        INSERT INTO tbl_log_peminjaman 
        (pinjam_id, buku_id, anggota_id, jumlah, tipe_transaksi,
         status_sebelum, status_sesudah, petugas_id)
        VALUES 
        (NEW.pinjam_id, NEW.buku_id, NEW.anggota_id, NEW.jumlah_pinjam, 'Kembali',
         OLD.status, NEW.status, NEW.petugas_id);
    END IF;
END;//

DELIMITER ;

-- 7. Tambah view untuk melihat peminjaman aktif
CREATE VIEW v_peminjaman_aktif AS
SELECT 
    p.pinjam_id,
    p.anggota_id,
    l.nama as nama_anggota,
    p.buku_id,
    b.title as judul_buku,
    p.jumlah_pinjam,
    p.tgl_pinjam,
    p.tgl_balik,
    DATEDIFF(CURRENT_DATE, p.tgl_balik) as hari_terlambat,
    CASE 
        WHEN CURRENT_DATE > p.tgl_balik THEN 
            DATEDIFF(CURRENT_DATE, p.tgl_balik) * 1000 -- denda per hari
        ELSE 0 
    END as denda_terhitung
FROM tbl_pinjam p
JOIN tbl_login l ON p.anggota_id = l.id_login
JOIN tbl_buku b ON p.buku_id = b.id_buku
WHERE p.status = 'Dipinjam';

-- 8. Tambah view untuk statistik peminjaman
CREATE VIEW v_statistik_peminjaman AS
SELECT 
    b.id_buku,
    b.title,
    COUNT(p.pinjam_id) as total_dipinjam,
    SUM(CASE WHEN p.status = 'Dipinjam' THEN 1 ELSE 0 END) as sedang_dipinjam,
    b.jml as stok_tersedia
FROM tbl_buku b
LEFT JOIN tbl_pinjam p ON b.id_buku = p.buku_id
GROUP BY b.id_buku, b.title, b.jml;
