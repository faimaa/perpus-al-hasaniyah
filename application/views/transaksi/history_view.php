<?php if (!defined('BASEPATH')) exit('No direct script acess allowed'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-history"></i> History Transaksi
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
            <li class="active"><i class="fa fa-history"></i>&nbsp; History Transaksi</li>
        </ol>
    </section>
    <section class="content">
        <?php if (!empty($this->session->flashdata())) { echo $this->session->flashdata('pesan'); } ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>History Transaksi</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="<?php echo base_url('transaksi/download_history');?>" class="btn btn-success">
                                    <i class="fa fa-download"></i> Download CSV
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
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
