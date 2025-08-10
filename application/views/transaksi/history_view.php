<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>
<style>
    .bg-warning {
        background-color: #fbc02d !important;
        color: #fff !important;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-history"></i> Laporan Transaksi
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-history"></i>&nbsp; Laporan Transaksi</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Laporan Transaksi</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="<?php echo base_url('transaksi/print_full_history_view?tanggal_awal=' . (isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '') . '&tanggal_akhir=' . (isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '')); ?>" class="btn btn-info" target="_blank">
                                    <i class="fa fa-print"></i> Cetak History Per Periode
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <form class="form-inline" method="get" action="<?php echo base_url('transaksi/history'); ?>">
                            <div class="form-group">
                                <label for="tanggal_awal">Periode: </label>
                                <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="<?php echo isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : ''; ?>">
                                <span> s/d </span>
                                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : ''; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary" name="filter" value="1"><i class="fa fa-filter"></i> Filter</button>
                            <a href="<?php echo base_url('transaksi/history'); ?>" class="btn btn-default">Reset</a>
                        </form>
<div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped table-hover">
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
                                        <th>Petugas</th>
                                        <th>Keterangan</th>
                                        <th>Harga Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no=1; foreach ($history as $isi): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= date('d M Y H:i', strtotime($isi['tanggal'])) ?></td>
                                        <td>
                                            <?php
                                            $badge_class = '';
                                            switch ($isi['tipe_transaksi']) {
                                                case 'Peminjaman':
                                                    $badge_class = 'bg-blue';
                                                    break;
                                                case 'Pengembalian':
                                                    $badge_class = 'bg-green';
                                                    break;
                                                case 'Buku Rusak':
                                                    $badge_class = 'bg-red';
                                                    break;
                                                case 'Perbaikan Buku':
                                                    $badge_class = 'bg-yellow';
                                                    break;
                                                case 'Buku Hilang':
                                                    $badge_class = 'bg-fuchsia';
                                                    break;
                                                case 'Mengganti Buku Baru':
                                                    $badge_class = 'bg-purple';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?= $badge_class ?>"><?= $isi['tipe_transaksi'] ?></span>
                                        </td>
                                        <td><?= $isi['kode_transaksi'] ?></td>
                                        <td><?= $isi['judul_buku'] ?></td>
                                        <td><?= $isi['isbn'] ?></td>
                                        <td><?= $isi['jumlah'] ?></td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
