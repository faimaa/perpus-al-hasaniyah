<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-warning" style="color:red"></i> <?= $title_web; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li><a href="<?php echo base_url('data/bukurusak');?>"><i class="fa fa-warning"></i>&nbsp; Data Buku Rusak</a></li>
      <li class="active"><i class="fa fa-info-circle"></i>&nbsp; Detail Buku Rusak</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="box box-danger box-solid">
          <div class="box-header with-border">
            <h4 class="box-title">Detail Buku Rusak</h4>
          </div>
          <div class="box-body">
            <?php if(empty($rusak)): ?>
              <div class="alert alert-danger text-center">Data buku rusak tidak ditemukan.</div>
              <a href="<?= base_url('data/bukurusak'); ?>" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
            <?php else: ?>
            <table class="table table-bordered table-striped">
              <tr>
                <th style="width:30%">Judul Buku</th>
                <td><?= $rusak->judul_buku ? $rusak->judul_buku : '-'; ?></td>
              </tr>
              <tr>
                <th>ISBN</th>
                <td><?= $rusak->isbn ? $rusak->isbn : '-'; ?></td>
              </tr>
              <tr>
                <th>Sampul</th>
                <td>
                  <?php if(!empty($rusak->sampul) && $rusak->sampul !== '0'){ ?>
                    <img src="<?= base_url('assets_style/image/buku/'.$rusak->sampul); ?>" style="width:120px;height:120px;" class="img-responsive">
                  <?php }else{ echo '<span class="text-danger">Tidak ada sampul</span>'; } ?>
                </td>
              </tr>
              <tr>
                <th>Penerbit</th>
                <td><?= $rusak->penerbit ? $rusak->penerbit : '-'; ?></td>
              </tr>
              <tr>
                <th>Pengarang</th>
                <td><?= $rusak->pengarang ? $rusak->pengarang : '-'; ?></td>
              </tr>
              <tr>
                <th>Tahun Buku</th>
                <td><?= $rusak->thn_buku ? $rusak->thn_buku : '-'; ?></td>
              </tr>
              <tr>
                <th>Jumlah Rusak</th>
                <td><span class="label label-danger"><?= $rusak->jumlah; ?> buku</span></td>
              </tr>
              <tr>
                <th>Keterangan</th>
                <td><?= $rusak->keterangan ? $rusak->keterangan : '-'; ?></td>
              </tr>
              <tr>
                <th>Tanggal Rusak</th>
                <td><?= $rusak->tanggal ? date('d/m/Y H:i', strtotime($rusak->tanggal)) : '-'; ?></td>
              </tr>
              <tr>
                <th>Petugas</th>
                <td><?= $rusak->nama_petugas ? $rusak->nama_petugas : '-'; ?></td>
              </tr>
            </table>
            <a href="<?= base_url('data/bukurusak'); ?>" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Kembali</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div> 