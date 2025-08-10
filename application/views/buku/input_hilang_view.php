<?php 
if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-warning" style="color:orange"> </i> Input Buku Hilang
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
      <li class="active"><i class="fa fa-warning"></i>&nbsp; Input Buku Hilang</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-warning box-solid">
          <div class="box-header with-border">
            <h3 class="box-title">Input Buku Hilang</h3>
          </div>
          <div class="box-body">
            <form action="<?php echo base_url('data/prosesbukuhilang'); ?>" method="POST">
              <div class="form-group">
                <label>Pilih Buku & Anggota (Pinjam Aktif)</label><br>
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalPilihBukuAktif">
                    Pilih Buku & Anggota
                </button>
                <div class="row" style="margin-top:10px;">
                  <div class="col-md-6">
                    <label>Judul Buku</label>
                    <input type="text" id="input_judul_buku" class="form-control" readonly>
                    <input type="hidden" id="input_id_buku" name="id_buku">
                    <input type="hidden" id="input_kode_buku">
                  </div>
                  <div class="col-md-6">
                    <label>Nama Anggota</label>
                    <input type="text" id="input_nama_anggota" class="form-control" readonly>
                    <input type="hidden" id="input_anggota_id" name="anggota_id">
                  </div>
                </div>
              </div>
              <!-- Modal Pilih Buku & Anggota (Transaksi Peminjaman Aktif) -->
<div class="modal fade" id="modalPilihBukuAktif" tabindex="-1" role="dialog" aria-labelledby="modalPilihBukuAktifLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalPilihBukuAktifLabel">Pilih Transaksi Peminjaman (Masih Aktif)</h4>
      </div>
      <div class="modal-body">

        <table class="table table-bordered table-striped" id="tabel-pilih-buku-aktif">
          <thead>
            <tr>
              <th>No</th>
              <th>ID Pinjam</th>
              <th>Judul Buku</th>
              <th>Nama Anggota</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($pinjam)): $no=1; foreach($pinjam as $row): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $row['pinjam_id']; ?></td>
              <td><?= $row['judul_buku']; ?></td>
              <td><?= $row['nama_anggota']; ?></td>
              <td>
                <button type="button" class="btn btn-primary btn-xs pilih-buku-aktif"
                  data-id_buku="<?= $row['id_buku']; ?>"
                  data-kode_buku="<?= $row['kode_buku']; ?>"
                  data-judul_buku="<?= htmlspecialchars($row['judul_buku']); ?>"
                  data-anggota_id="<?= $row['anggota_id']; ?>"
                  data-nama_anggota="<?= htmlspecialchars($row['nama_anggota']); ?>">
                  <i class="fa fa-check"></i> Pilih
                </button>
              </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="5" class="text-center">Tidak ada transaksi peminjaman aktif</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  $(document).on('click', '.pilih-buku-aktif', function(){
    var id_buku = $(this).data('id_buku');
    var kode_buku = $(this).data('kode_buku');
    var judul_buku = $(this).data('judul_buku');
    var anggota_id = $(this).data('anggota_id');
    var nama_anggota = $(this).data('nama_anggota');
    $('#input_id_buku').val(id_buku);
    $('#input_kode_buku').val(kode_buku);
    $('#input_judul_buku').val(judul_buku);
    $('#input_anggota_id').val(anggota_id);
    $('#input_nama_anggota').val(nama_anggota);
    $('#modalPilihBukuAktif').modal('hide');
  });
});
</script>
<div class="form-group">
                <label>Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="3" placeholder="Jelaskan kehilangan buku"></textarea>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-warning">Submit</button>
                <a href="<?= base_url('data/bukuhilang'); ?>" class="btn btn-default">Kembali</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>