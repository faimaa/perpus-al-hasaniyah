# 🔧 Panduan Memperbaiki Database Railway

## 📋 Masalah yang Ditemukan

Website mengalami error dengan pesan:
```
Error Number: 1054
Unknown column 'h.kode_transaksi' in 'on clause'
```

**Penyebab**: Database MySQL di Railway tidak memiliki kolom `kode_transaksi` di tabel `tbl_history`, padahal kode aplikasi membutuhkannya.

## 🎯 Solusi

Kita perlu memperbaiki struktur database Railway agar sama dengan file `perpus_new.sql` yang lengkap.

## 📁 File yang Dibutuhkan

1. **`fix_database_structure.php`** - Script PHP untuk pemeriksaan dan perbaikan otomatis
2. **`fix_database_railway.sql`** - Script SQL untuk memperbaiki struktur tabel
3. **`insert_sample_data.sql`** - Script SQL untuk mengisi data sample
4. **`perpus_new.sql`** - File SQL lengkap (sudah ada)

## 🚀 Langkah-langkah Perbaikan

### Langkah 1: Periksa Koneksi Database Railway

1. Buka file `fix_database_structure.php`
2. Sesuaikan kredensial database:
   ```php
   $host = 'your-railway-host'; // Host database Railway
   $username = 'your-username';  // Username database
   $password = 'your-password';  // Password database
   $database = 'perpus_new';     // Nama database
   ```

### Langkah 2: Jalankan Script Pemeriksaan

1. Upload `fix_database_structure.php` ke server Railway
2. Jalankan script melalui browser atau command line
3. Script akan menampilkan:
   - Status koneksi database
   - Struktur tabel yang ada
   - Kolom yang hilang
   - Jumlah data di setiap tabel

### Langkah 3: Perbaiki Struktur Database

1. Buka phpMyAdmin atau MySQL client Railway
2. Jalankan script `fix_database_railway.sql`
3. Script akan:
   - Menambahkan kolom `kode_transaksi` ke `tbl_history`
   - Memperbaiki struktur semua tabel
   - Menambahkan index untuk performa

### Langkah 4: Isi Data Sample

1. Jalankan script `insert_sample_data.sql`
2. Script akan mengisi:
   - Kategori buku
   - Rak buku
   - Data buku sample
   - User admin dan petugas
   - Data peminjaman sample
   - Data history sample

### Langkah 5: Verifikasi Perbaikan

1. Jalankan kembali `fix_database_structure.php`
2. Pastikan semua error sudah teratasi
3. Test query yang bermasalah

## 🔍 Detail Perbaikan

### Tabel yang Diperbaiki

1. **`tbl_history`**
   - ✅ Tambah kolom `kode_transaksi VARCHAR(6)`
   - ✅ Tambah index untuk performa

2. **`tbl_denda`**
   - ✅ Perbaiki struktur kolom
   - ✅ Tambah index `pinjam_id`

3. **`tbl_buku`**
   - ✅ Perbaiki tipe data kolom
   - ✅ Tambah index untuk pencarian

4. **`tbl_login`**
   - ✅ Perbaiki struktur user
   - ✅ Tambah timestamp

5. **`tbl_pinjam`**
   - ✅ Perbaiki struktur peminjaman
   - ✅ Tambah index status

### Query yang Diperbaiki

Query yang bermasalah:
```sql
SELECT h.*, b.judul_buku, b.isbn, l1.nama as nama_petugas, 
       COALESCE(l2.nama, CONCAT('[ID:', h.anggota_id, ']')) as nama_anggota, 
       d.denda as harga_denda 
FROM tbl_history h
LEFT JOIN tbl_buku b ON h.buku_id = b.id_buku
LEFT JOIN tbl_login l1 ON h.petugas_id = l1.id_login
LEFT JOIN tbl_login l2 ON h.anggota_id = l2.id_login
LEFT JOIN tbl_denda d ON d.pinjam_id = h.kode_transaksi  -- ✅ Kolom ini sudah ditambahkan
ORDER BY h.tanggal DESC
```

## 🧪 Testing

Setelah perbaikan, test fitur berikut:

1. **History Transaksi** - Halaman yang bermasalah
2. **Data Peminjaman** - Pastikan join berfungsi
3. **Laporan Denda** - Pastikan data denda terhubung
4. **Dashboard** - Pastikan semua query berjalan

## ⚠️ Catatan Penting

1. **Backup Database**: Selalu backup database sebelum melakukan perubahan struktur
2. **Kredensial**: Pastikan kredensial database Railway sudah benar
3. **Permission**: Pastikan user database memiliki permission ALTER dan INSERT
4. **Testing**: Test di environment development terlebih dahulu

## 🆘 Troubleshooting

### Error "Access denied"
- Periksa username dan password database
- Pastikan user memiliki permission yang cukup

### Error "Table doesn't exist"
- Pastikan nama database sudah benar
- Jalankan CREATE TABLE dari `perpus_new.sql`

### Error "Column already exists"
- Script menggunakan `IF NOT EXISTS`, jadi aman dijalankan berulang

### Query masih error
- Periksa apakah semua kolom sudah ditambahkan
- Jalankan `DESCRIBE tbl_history` untuk verifikasi

## 📞 Bantuan

Jika masih mengalami masalah:

1. Periksa log error database
2. Pastikan semua script dijalankan dengan urutan yang benar
3. Verifikasi struktur tabel dengan `DESCRIBE`
4. Test query satu per satu untuk isolasi masalah

## ✅ Checklist Selesai

- [ ] Kredensial database sudah disesuaikan
- [ ] Script pemeriksaan sudah dijalankan
- [ ] Struktur database sudah diperbaiki
- [ ] Data sample sudah diisi
- [ ] Query bermasalah sudah ditest
- [ ] Website sudah berfungsi normal

---

**🎉 Setelah semua langkah selesai, website seharusnya berfungsi normal tanpa error database!** 