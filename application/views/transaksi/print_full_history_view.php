<!DOCTYPE html>
<html>
<head>
    <title><?= isset($title_web) ? $title_web : 'Cetak History Transaksi' ?></title>
    <link rel="stylesheet" href="<?php echo base_url();?>assets_style/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8f9fa;
        }
        @media print {
            .no-print { display: none; }
            body { background: white; }
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }
        .logo {
            width: 70px;
            height: 70px;
            margin-bottom: 8px;
        }
        .library-title {
            font-size: 1.7em;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 1px;
        }
        .library-address {
            font-size: 1em;
            color: #555;
        }
        .report-title {
            font-size: 1.2em;
            font-weight: 600;
            margin-top: 10px;
            color: #333;
        }
        .periode {
            font-size: 1em;
            margin-bottom: 10px;
            color: #444;
        }
        .table {
            background: white;
        }
        .table th {
            background: #e3f0fa !important;
            color: #007bff;
            font-weight: 600;
            border-bottom: 2px solid #007bff !important;
        }
        .table td {
            background: #fafdff;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 1em;
            color: #444;
        }
        .ttd {
            margin-top: 60px;
            text-align: right;
            font-size: 1em;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <?php if (file_exists(FCPATH.'assets_style/image/logo.png')): ?>
                <img src="<?php echo base_url('assets_style/image/logo.png'); ?>" class="logo">
            <?php endif; ?>
            <div class="library-title">PERPUSTAKAAN AL-HASANIYAH</div>
            <div class="library-address">Jl. Contoh Alamat No. 123, Kota, Provinsi</div>
            <div class="report-title">Laporan History Transaksi</div>
            <?php if (!empty($periode)): ?>
                <div class="periode"><b><?= $periode ?></b></div>
            <?php endif; ?>
        </div>
        <table class="table table-bordered" style="margin-top:20px;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Tipe Transaksi</th>
                    <th>Kode</th>
                    <th>Judul Buku</th>
                    <th>ISBN</th>
                    <th>Jumlah</th>
                    <th>Anggota</th>
                    <th>Admin</th>
                    <th>Keterangan</th>
                    <th>Harga Denda</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach ($history as $isi): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($isi['tanggal'])) ?></td>
                    <td><?= $isi['tipe_transaksi'] ?></td>
                    <td><?= $isi['kode_transaksi'] ?></td>
                    <td><?= $isi['judul_buku'] ?></td>
                    <td><?= $isi['isbn'] ?></td>
                    <td><?= isset($isi['jumlah']) ? $isi['jumlah'] : '-' ?></td>
                    <td><?= $isi['nama_anggota'] ?: '-' ?></td>
                    <td><?= $isi['nama_petugas'] ?></td>
                    <td><?= $isi['keterangan'] ?></td>
                    <td>
                        <?php
                        if ($isi['tipe_transaksi'] !== 'Buku Rusak' && !empty($isi['harga_denda'])) {
                            echo 'Rp ' . number_format($isi['harga_denda'], 0, ',', '.');
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="footer">
            Dicetak pada: <?= date('d-m-Y H:i') ?>
        </div>
        <div class="ttd">
            <div>Admin,</div>
            <br><br><br>
            <div style="font-weight:bold; text-decoration:underline;">_________________________</div>
        </div>
        <div class="no-print" style="margin-top:20px;">
            <a href="javascript:window.close();" class="btn btn-default">Tutup</a>
        </div>
    </div>
</body>
</html> 