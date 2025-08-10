CREATE TABLE tbl_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipe_transaksi ENUM('Peminjaman', 'Pengembalian', 'Buku Rusak', 'Perbaikan Buku') NOT NULL,
    kode_transaksi VARCHAR(50),
    buku_id INT,
    anggota_id INT NULL,
    petugas_id INT,
    jumlah INT NOT NULL DEFAULT 1,
    keterangan TEXT,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buku_id) REFERENCES tbl_buku(id_buku),
    FOREIGN KEY (petugas_id) REFERENCES tbl_login(id_login),
    FOREIGN KEY (anggota_id) REFERENCES tbl_login(id_login)
);
