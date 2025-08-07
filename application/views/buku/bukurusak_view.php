<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-warning" style="color:red"> </i>  <?= $title_web;?>
    </h1>
    <ol class="breadcrumb">
			<li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
			<li class="active"><i class="fa fa-warning"></i>&nbsp; <?= $title_web;?></li>
    </ol>
  </section>
  <section class="content">
<?php 
// Cek apakah ini request pertama kali atau redirect dari aksi tertentu
$is_redirect = $this->input->get('notif') === '1' || $this->input->get('updated') === '1';
$pesan = $this->session->flashdata('pesan');

// Hanya tampilkan notifikasi jika ada pesan DAN ini adalah redirect dari aksi
if (!empty($pesan) && $is_redirect) {
    echo $pesan;
?>
<script>
// Auto hide alert after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    // Hapus parameter notif dari URL tanpa reload halaman
    if (window.history.replaceState && (new URLSearchParams(window.location.search).has('notif') || new URLSearchParams(window.location.search).has('updated'))) {
        const url = new URL(window.location);
        url.searchParams.delete('notif');
        url.searchParams.delete('updated');
        window.history.replaceState({}, '', url);
    }

    // Set timeout untuk auto-hide notifikasi
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var fadeEffect = setInterval(function() {
                if (!alert.style.opacity) {
                    alert.style.opacity = 1;
                }
                if (alert.style.opacity > 0) {
                    alert.style.opacity -= 0.1;
                } else {
                    clearInterval(fadeEffect);
                    alert.style.display = 'none';
                }
            }, 50);
        });
    }, 5000);
});
</script>
<?php } ?>
	<div class="row">
	    <div class="col-md-12">
	        <div class="box box-danger box-solid">
                <div class="box-header with-border">
                    <?php if(in_array($this->session->userdata('level'), ['Admin','Petugas'])){?>
                    <a href="<?php echo base_url('data/inputbukurusak');?>"><button class="btn btn-danger">
                        <i class="fa fa-plus"> </i> Input Buku Rusak</button></a>
                    <?php }?>
                </div>
				<!-- /.box-header -->
				<div class="box-body">
					<div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped table" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Sampul</th>
                                <th>Judul Buku</th>
                                <th>Jumlah Rusak</th>
                                <th>Keterangan</th>
                                <th>Tanggal Rusak</th>
                                <th>Petugas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(empty($buku)):?>
                        <tr>
                            <td colspan="8">
                                <div class="alert alert-warning text-center">
                                    <i class="fa fa-info-circle"></i> Belum ada data buku rusak
                                </div>
                            </td>
                        </tr>
                    <?php else:?>
                        <?php $no=1;foreach($buku as $isi){?>
                            <tr>
                                <td><?= $no;?></td>
                                <td>
                                    <center>
                                        <?php if(!empty($isi['sampul'] !== "0")){?>
                                        <img src="<?php echo base_url();?>assets_style/image/buku/<?php echo $isi['sampul'];?>" alt="#" 
                                        class="img-responsive" style="height:auto;width:100px;"/>
                                        <?php }else{?>
                                            <img src="<?php echo base_url();?>assets_style/image/buku/0.jpg" alt="#" 
                                            class="img-responsive" style="height:auto;width:100px;"/>
                                        <?php }?>
                                    </center>
                                </td>
                                <td>
                                    <strong><?= $isi['judul_buku'];?></strong><br/>
                                    <small class="text-muted">ISBN: <?= $isi['isbn'];?></small>
                                </td>
                                <td>
                                    <span class="label label-danger"><?= $isi['jumlah_rusak'];?> buku</span>
                                </td>
                                <td><?= $isi['keterangan'] ? $isi['keterangan'] : '-';?></td>
                                <td><?= $isi['tgl_rusak'] ? date('d/m/Y H:i', strtotime($isi['tgl_rusak'])) : '-';?></td>
                                <td><?= $isi['nama_petugas'] ? $isi['nama_petugas'] : '-';?></td>
                                <td style="width:25%;">
								<?php if(in_array($this->session->userdata('level'), ['Admin','Petugas'])){?>
								<a href="<?= base_url('data/detailbukurusak/'.$isi['id']);?>" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Detail Rusak</a>
                                <?php if($this->session->userdata('level') == 'Admin'){ ?>
                                <a href="javascript:void(0)" onclick="konfirmasiPerbaikan(<?= $isi['id'] ?>, '<?= $isi['judul_buku'] ?>')" class="btn btn-success btn-sm">
                                    <i class="fa fa-wrench"></i> Perbaiki
                                </a>
                                <?php } ?>
								<?php }?>
                                </td>
                            </tr>
                        <?php $no++;}?>
                    <?php endif;?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

<script>
function konfirmasiPerbaikan(id, judul_buku) {
    Swal.fire({
        judul_buku: 'Konfirmasi Perbaikan',
        text: 'Apakah buku "' + judul_buku + '" sudah selesai diperbaiki?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Ya, sudah diperbaiki!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url() ?>data/perbaikan_buku/' + id;
        }
    });
}
</script>
