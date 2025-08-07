<?php if(! defined('BASEPATH')) exit('No direct script acess allowed'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-warning" style="color:#fbc02d"></i> <?= $title_web; ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?= base_url('data/bukuhilang'); ?>">Data Buku Hilang</a></li>
            <li class="active">Detail Buku Hilang</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-warning">
                    <div class="box-header with-border" style="background:#ffe082;">
                        <h3 class="box-title" style="color:#b26a00;"><i class="fa fa-warning"></i> Detail Buku Hilang</h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Judul Buku</th>
                                <td><?= $detail['judul_buku']; ?></td>
                            </tr>
                            <tr>
                                <th>ISBN</th>
                                <td><?= $detail['isbn']; ?></td>
                            </tr>
                            <tr>
                                <th>Sampul</th>
                                <td><img src="<?= base_url('assets_style/image/buku/'.$detail['sampul']); ?>" style="width:120px;"></td>
                            </tr>
                            <tr>
                                <th>Anggota</th>
                                <td><?= $detail['nama_anggota'] ? $detail['nama_anggota'] : '-'; ?></td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td><?= $detail['keterangan'] ? $detail['keterangan'] : '-'; ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Hilang</th>
                                <td><?= date('d/m/Y H:i', strtotime($detail['tanggal'])); ?></td>
                            </tr>
                            <tr>
                                <th>Petugas</th>
                                <td><?= $detail['nama_petugas'] ? $detail['nama_petugas'] : '-'; ?></td>
                            </tr>
                        </table>
                        <a href="<?= base_url('data/bukuhilang'); ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>