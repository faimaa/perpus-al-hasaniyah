<!DOCTYPE html>
<html>
<head>
    <title><?= $title_web ?></title>
    <link rel="stylesheet" href="<?php echo base_url();?>assets_style/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }
        .table th, .table td {
            border: 1px solid #333 !important;
            font-size: 12px;
        }
        .table th {
            background: #eee;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="header">
            <?php if (file_exists(FCPATH.'assets_style/image/logo.png')): ?>
                <img src="<?php echo base_url('assets_style/image/logo.png'); ?>" class="logo">
            <?php endif; ?>
            <h3>PERPUSTAKAAN AL-HASANIYAH</h3>
            <div>Jl. Contoh Alamat No. 123, Kota, Provinsi</div>
            <h4 style="margin-top:15px;">Laporan History Transaksi</h4>
            <?php if (!empty($periode)): ?>
                <div><b><?= $periode ?></b></div>
            <?php endif; ?>
        </div>
        <table class="table table-bordered">
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
        <div class="text-right" style="margin-top:20px; font-size:12px;">
            Dicetak pada: <?= date('d-m-Y H:i') ?>
        </div>
        <div class="no-print" style="margin-top:20px;">
            <a href="javascript:window.close();" class="btn btn-default">Tutup</a>
        </div>
    </div>
</body>
</html> 