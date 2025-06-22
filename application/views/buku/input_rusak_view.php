<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-warning" style="color:red"> </i> Input Buku Rusak
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li class="active"><i class="fa fa-warning"></i>&nbsp; Input Buku Rusak</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger box-solid">
          <div class="box-header with-border">
            <h3 class="box-title">Input Buku Rusak</h3>
          </div>
          <div class="box-body">
            <form action="<?php echo base_url('data/prosesbukurusak');?>" method="POST">
              <div class="form-group">
                <label>Pilih Buku</label>
                <select name="buku_id" class="form-control select2" required>
                  <option value="" disabled selected>-- Pilih Buku --</option>
                  <?php foreach($buku as $buk):?>
                    <?php if($buk['status'] != 'Rusak' && $buk['jml'] > 0):?>
                      <option value="<?= $buk['id_buku'];?>">
                        <?= $buk['title'];?> (ISBN: <?= $buk['isbn'];?>) - Stok: <?= $buk['jml'];?>
                      </option>
                    <?php endif;?>
                  <?php endforeach;?>
                </select>
              </div>
              <div class="form-group">
                <label>Jumlah Buku Rusak</label>
                <input type="number" class="form-control" name="jumlah_rusak" min="1" required>
              </div>
              <div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="3" placeholder="Jelaskan kerusakan buku"></textarea>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-danger">Submit</button>
                <a href="<?= base_url('data/bukurusak');?>" class="btn btn-default">Kembali</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
