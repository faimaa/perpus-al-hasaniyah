-- Database: perpus_alhasaniyah

CREATE DATABASE IF NOT EXISTS perpus_alhasaniyah;
USE perpus_alhasaniyah;

-- Tabel User/Anggota/Petugas/Admin
CREATE TABLE IF NOT EXISTS tbl_login (
    id_login INT AUTO_INCREMENT PRIMARY KEY,
    anggota_id VARCHAR(50) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    level ENUM('Admin','Petugas','Anggota') NOT NULL,
    email VARCHAR(100),
    telepon VARCHAR(20),
    alamat TEXT,
    foto VARCHAR(100),
    status VARCHAR(20) DEFAULT 'Aktif'
);

-- Tabel Kategori
CREATE TABLE IF NOT EXISTS tbl_kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

-- Tabel Rak
CREATE TABLE IF NOT EXISTS tbl_rak (
    id_rak INT AUTO_INCREMENT PRIMARY KEY,
    nama_rak VARCHAR(100) NOT NULL
);

-- Tabel Buku
CREATE TABLE IF NOT EXISTS tbl_buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    buku_id VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    isbn VARCHAR(50),
    pengarang VARCHAR(100),
    penerbit VARCHAR(100),
    tahun VARCHAR(10),
    kategori_id INT,
    rak_id INT,
    jml INT DEFAULT 0,
    status ENUM('Tersedia','Habis') DEFAULT 'Tersedia',
    image VARCHAR(100),
    FOREIGN KEY (kategori_id) REFERENCES tbl_kategori(id_kategori),
    FOREIGN KEY (rak_id) REFERENCES tbl_rak(id_rak)
);

-- Tabel Peminjaman
CREATE TABLE IF NOT EXISTS tbl_pinjam (
    id_pinjam INT AUTO_INCREMENT PRIMARY KEY,
    pinjam_id VARCHAR(50) NOT NULL,
    anggota_id VARCHAR(50) NOT NULL,
    buku_id VARCHAR(50) NOT NULL,
    status ENUM('Dipinjam','Di Kembalikan') NOT NULL DEFAULT 'Dipinjam',
    tgl_pinjam DATE NOT NULL,
    lama_pinjam INT NOT NULL,
    tgl_balik DATE NOT NULL,
    tgl_kembali DATE DEFAULT NULL,
    FOREIGN KEY (anggota_id) REFERENCES tbl_login(anggota_id),
    FOREIGN KEY (buku_id) REFERENCES tbl_buku(buku_id)
);

-- Tabel Denda
CREATE TABLE IF NOT EXISTS tbl_denda (
    id_denda INT AUTO_INCREMENT PRIMARY KEY,
    pinjam_id VARCHAR(50) NOT NULL,
    denda INT DEFAULT 0,
    lama_waktu INT DEFAULT 0,
    tgl_denda DATE,
    FOREIGN KEY (pinjam_id) REFERENCES tbl_pinjam(pinjam_id)
);

-- Tabel Biaya Denda
CREATE TABLE IF NOT EXISTS tbl_biaya_denda (
    id_biaya_denda INT AUTO_INCREMENT PRIMARY KEY,
    harga_denda INT NOT NULL,
    stat ENUM('Aktif','Tidak Aktif') DEFAULT 'Tidak Aktif',
    tgl_tetap DATE
);

-- Tabel History Transaksi
CREATE TABLE IF NOT EXISTS tbl_history (
    id_history INT AUTO_INCREMENT PRIMARY KEY,
    tipe_transaksi ENUM('Peminjaman','Pengembalian') NOT NULL,
    kode_transaksi VARCHAR(50) NOT NULL,
    buku_id INT NOT NULL,
    anggota_id INT NOT NULL,
    petugas_id INT NOT NULL,
    keterangan TEXT,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buku_id) REFERENCES tbl_buku(id_buku),
    FOREIGN KEY (anggota_id) REFERENCES tbl_login(id_login),
    FOREIGN KEY (petugas_id) REFERENCES tbl_login(id_login)
); 