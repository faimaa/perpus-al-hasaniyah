<?php 
if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-warning" style="color:orange"> </i>  <?= $title_web;?>
    </h1>
    <ol class="breadcrumb">
			<li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
			<li class="active"><i class="fa fa-warning"></i>&nbsp; <?= $title_web;?></li>
    </ol>
  </section>
  <section class="content">
	<?php
	// Cek apakah ada parameter notif di URL
	$show_notif = $this->input->get('notif') === '1';
	$pesan = $this->session->flashdata('pesan');
	
	// Hanya tampilkan notifikasi jika ada pesan DAN parameter notif=1
	if (!empty($pesan) && $show_notif) {
		echo $pesan;
	?>
	<script>
	// Auto hide alert after 5 seconds
	document.addEventListener('DOMContentLoaded', function() {
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
	<?php
	}
	?>
	<div class="row">
	    <div class="col-md-12">
	        <div class="box box-warning box-solid">
                <div class="box-header with-border">
                    <?php if(in_array($this->session->userdata('level'), ['Admin','Petugas'])){?>
                    <a href="<?php echo base_url('data/inputbukuhilang');?>"><button class="btn btn-warning">
                        <i class="fa fa-plus"> </i> Input Buku Hilang</button></a>
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
                                <th>No Pinjam</th>
                                <th>Anggota</th>
                                <th>Keterangan</th>
                                <th>Tanggal Hilang</th>
                                <th>Petugas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(empty($buku)):?>
                        <tr>
                            <td colspan="8">
                                <div class="alert alert-warning text-center">
                                    <i class="fa fa-info-circle"></i> Belum ada data buku hilang
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
                                <td><?= $isi['pinjam_id'] ? $isi['pinjam_id'] : '-';?></td>
                                <td><?= $isi['nama_anggota'] ? $isi['nama_anggota'] : '-';?></td>
                                <td><?= $isi['keterangan'] ? $isi['keterangan'] : '-';?></td>
                                <td><?= $isi['tgl_hilang'] ? date('d/m/Y H:i', strtotime($isi['tgl_hilang'])) : '-';?></td>
                                <td><?= $isi['nama_petugas'] ? $isi['nama_petugas'] : '-';?></td>
                                <td>
                                    <a href="<?= base_url('data/detailbukuhilang/'.$isi['id']); ?>" class="btn btn-info btn-xs" title="Detail"><i class="fa fa-eye"></i></a>
                                    <a href="<?= base_url('data/ubahstatusbukuhilang/'.$isi['id']); ?>" class="btn btn-success btn-xs" title="Diganti" onclick="return confirm('Yakin ingin menandai buku ini sudah diganti?');">
                                    <i class="fa fa-sign-out"></i> Mengganti</a>
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