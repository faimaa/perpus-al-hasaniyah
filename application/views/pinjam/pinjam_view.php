<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i>  <?= $title_web;?>
    </h1>
    <ol class="breadcrumb">
			<li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
			<li class="active"><i class="fa fa-file-text"></i>&nbsp; <?= $title_web;?></li>
    </ol>
  </section>
  <section class="content">
	<?php if(!empty($this->session->flashdata())){ echo $this->session->flashdata('pesan');}?>
	<div class="row">
	    <div class="col-md-12">
	        <div class="box box-primary">
                <div class="box-header with-border"><?php if(in_array($this->session->userdata('level'), ['Admin','Petugas'])){ ?>
                    <a href="transaksi/pinjam"><button class="btn btn-primary">
				<i class="fa fa-plus"> </i> Tambah Pinjam</button></a><?php }?>

                </div>
				<!-- /.box-header -->
				<div class="box-body">
                    <br/>
					<div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped table" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Pinjam</th>
                                <th>ID Anggota</th>
                                <th>Nama</th>
                                <th>Pinjam</th>
                                <th>Kembali</th>
                                <th style="width:10%">Status</th>
                                <th>Denda</th>
                                <th>Aksi</th>
                            </tr>
						</thead>
						<tbody>
						<?php 
							$no=1;
							foreach($pinjam->result_array() as $isi){
									$anggota_id = $isi['anggota_id'];
									$ang = $this->db->query("SELECT * FROM tbl_login WHERE anggota_id = '$anggota_id'")->row();

									$pinjam_id = $isi['pinjam_id'];
									$denda = $this->db->query("SELECT * FROM tbl_denda WHERE pinjam_id = '$pinjam_id'");
									$total_denda = $denda->row();
						?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= format_pinjam_id($isi['pinjam_id'], $isi['tgl_pinjam']);?></td>
                                <td><?= $isi['anggota_id'];?></td>
                                <td><?= $ang->nama;?></td>
                                <td><?= $isi['tgl_pinjam'];?></td>
                                <td><?= $isi['tgl_balik'];?></td>
                                <td><?= $isi['status'];?></td>
                                <td>
									<?php 
										if($isi['status'] == 'Di Kembalikan')
										{
											echo $this->M_Admin->rp($total_denda->denda);
										}else{
											$date1 = new DateTime(date('Y-m-d'));
											$date2 = new DateTime($isi['tgl_balik']);
											$diff = $date1->diff($date2)->days;
											if($date1 > $date2)
											{
												echo $diff.' hari';
												$dd = $this->M_Admin->get_tableid_edit('tbl_biaya_denda','stat','Aktif');
												
												// Hitung denda harian
												$denda_per_hari = $dd->harga_denda;
												$total_denda = $denda_per_hari * $diff;
												
												// Ambil jumlah buku untuk informasi saja
												$jml_buku = $this->db->query("SELECT COUNT(DISTINCT buku_id) as total FROM tbl_pinjam WHERE pinjam_id = '$pinjam_id'")->row()->total;
												
												echo '<p style="color:red;font-size:18px;">'.$this->M_Admin->rp($total_denda).' 
												</p><small style="color:#333;">* Untuk '.$jml_buku.' Buku</small>';
											}else{
												echo '<p style="color:green;">Tidak Ada Denda</p>';
											}
										}
									?>
								</td>
								<td style="text-align:center;">
									<?php if(in_array($this->session->userdata('level'), ['Admin','Petugas'])){ ?>
										<?php if($isi['tgl_kembali'] == '0') {?>
											<a href="<?= base_url('transaksi/kembalis/'.$isi['id_pinjam']);?>" class="btn btn-warning btn-sm" title="pengembalian buku">
												<i class="fa fa-sign-out"></i> Kembalikan</a>
										<?php }else{ ?>
											<a href="<?= base_url('transaksi/kembalipinjam/'.$isi['id_pinjam']);?>" class="btn btn-warning btn-sm" title="pengembalian buku">
											<i class="fa fa-sign-out"></i> Dikembalikan</a>
										<?php }?>
										<a href="<?= base_url('transaksi/detailpinjam/'.$isi['id_pinjam'].'?pinjam=yes');?>" class="btn btn-primary btn-sm" title="detail pinjam"><i class="fa fa-eye"></i></button></a>
										<a href="<?= base_url('transaksi/hapus_pinjam?id_pinjam='.$isi['id_pinjam']);?>" 
											onclick="return confirm('Anda yakin Peminjaman Ini akan dihapus ?');" 
											class="btn btn-danger btn-sm" title="hapus pinjam">
											<i class="fa fa-trash"></i></a>
									<?php }else{?>
										<a href="<?= base_url('transaksi/detailpinjam/'.$isi['id_pinjam']);?>" 
											class="btn btn-primary btn-sm" title="detail pinjam">
											<i class="fa fa-eye"></i> Detail Pinjam</a>
									<?php }?>
                                </td>
                            </tr>
                        <?php $no++;}?>
						</tbody>
					</table>
			    </div>
			    </div>
	        </div>
    	</div>
    </div>
</section>
</div>
