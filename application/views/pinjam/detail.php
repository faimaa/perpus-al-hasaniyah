<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i>  <?= $title_web;?>
    </h1>
    <ol class="breadcrumb">
			<li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
			<li class="active"><i class="fa fa-edit"></i>&nbsp;  <?= $title_web;?></li>
    </ol>
  </section>
  <section class="content">
	<div class="row">
	    <div class="col-md-12">
	        <div class="box box-primary">
                <div class="box-header with-border">
                </div>
			    <!-- /.box-header -->
			    <div class="box-body">
						<div class="row">
							<div class="col-sm-5">
								<table class="table table-striped">
									<tr style="background:yellowgreen">
										<td colspan="3">Data Transaksi</td>
									</tr>
									<tr>
										<td>No Peminjaman</td>
										<td>:</td>
										<td>
											<?= $pinjam->pinjam_id;?>
										</td>
									</tr>
									<tr>
										<td>Tgl Peminjaman</td>
										<td>:</td>
										<td>
											<?= $pinjam->tgl_pinjam;?>
										</td>
									</tr>
									<tr>
										<td>Tgl pengembalian</td>
										<td>:</td>
										<td>
											<?= $pinjam->tgl_balik;?>
										</td>
									</tr>
									<tr>
										<td>ID Anggota</td>
										<td>:</td>
										<td>
											<?= $pinjam->anggota_id;?>
										</td>
									</tr>
									<tr>
										<td>Biodata</td>
										<td>:</td>
										<td>
											<?php
											$user = $this->M_Admin->get_tableid_edit('tbl_login','anggota_id',$pinjam->anggota_id);
											error_reporting(0);
											if($user->nama != null)
											{
												echo '<table class="table table-striped">
															<tr>
																<td>Nama Anggota</td>
																<td>:</td>
																<td>'.$user->nama.'</td>
															</tr>
															<tr>
																<td>Telepon</td>
																<td>:</td>
																<td>'.$user->telepon.'</td>
															</tr>
															<tr>
																<td>E-mail</td>
																<td>:</td>
																<td>'.$user->email.'</td>
															</tr>
															<tr>
																<td>Alamat</td>
																<td>:</td>
																<td>'.$user->alamat.'</td>
															</tr>
															<tr>
																<td>Level</td>
																<td>:</td>
																<td>'.$user->level.'</td>
															</tr>
														</table>';
											}else{
												echo 'Anggota Tidak Ditemukan !';
											}
											?>
										</td>
									</tr>
									<tr>
										<td>Lama Peminjaman</td>
										<td>:</td>
										<td>
											<?= $pinjam->lama_pinjam;?> Hari
										</td>
									</tr>
								</table>
							</div>
							<div class="col-sm-7">
								<table class="table table-striped">
									<tr style="background:yellowgreen">
										<td colspan="3">Pinjam Buku</td>
									</tr>
									<tr>
										<td>Status</td>
										<td>:</td>
										<td>
											<?= $pinjam->status;?>
										</td>
									</tr>
									<tr>
										<td>Tgl Kembali</td>
										<td>:</td>
										<td>
											<?php 
												if($pinjam->tgl_kembali == '0')
												{
													echo '<p style="color:red;">belum dikembalikan</p>';
												}else{
													echo $pinjam->tgl_kembali;
												}
											
											?>
										</td>
									</tr>
									<tr>
										<td>Total Denda</td>
										<td>:</td>
										<td>
											<?php 
												$pinjam_id = $pinjam->pinjam_id;
												if($pinjam->status == 'Di Kembalikan') {
													// Ambil total denda untuk semua buku dalam peminjaman ini
													$total = $this->db->query("SELECT SUM(denda) as total_denda 
														FROM tbl_denda WHERE pinjam_id = '$pinjam_id'")->row();
														echo '<strong>'.$this->M_Admin->rp($total->total_denda).'</strong>';
												} else {
													$date1 = new DateTime(date('Y-m-d'));
													$date2 = new DateTime($pinjam->tgl_balik);
													
													if($date1 > $date2) {
														$diff = $date1->diff($date2)->days;
														$dd = $this->M_Admin->get_tableid_edit('tbl_biaya_denda','stat','Aktif');
														
														// Hitung jumlah buku yang dipinjam
														$buku_count = count($items);
														$potensi_denda = $dd->harga_denda * $diff * $buku_count;
														echo '<strong style="color:red;font-size:18px;">'.$this->M_Admin->rp($potensi_denda).'</strong>';
													} else {
														echo '<p style="color:green;">Tidak Ada Denda</p>';
													}
												}
											?>
										</td>
									</tr>
									<tr>
										<td>Data Buku</td>
										<td>:</td>
										<td>
											<table class="table table-striped">
												<thead>
													<tr>
														<th>No</th>
														<th>Kode Buku</th>
														<th>Judul Buku</th>
														<th>Pengarang</th>
														<th>Tahun</th>
														<th>Denda</th>
													</tr>
												</thead>
												<tbody>
												<?php 
										$no=1;
										$date1 = new DateTime(date('Y-m-d'));
										$date2 = new DateTime($pinjam->tgl_balik);
										$diff = $date1->diff($date2)->days;
										$dd = $this->M_Admin->get_tableid_edit('tbl_biaya_denda','stat','Aktif');
										$total_denda = 0;
										
										// Menggunakan pin yang berisi data berdasarkan id_pinjam
										$pin = $this->M_Admin->get_tableid('tbl_pinjam','id_pinjam',$pinjam->id_pinjam);
										foreach($pin as $isi)
										{
											$buku = $this->M_Admin->get_tableid_edit('tbl_buku','buku_id',$isi['buku_id']);
												?>
													<tr>
														<td><?= $no;?></td>
														<td><?= $buku->buku_id;?></td>
														<td><?= $buku->judul_buku;?></td>
														<td><?= $buku->pengarang;?></td>
														<td><?= $buku->thn_buku;?></td>
														<td>
															<?php
																if($date1 > $date2) {
																	$denda_per_buku = $dd->harga_denda * $diff;
																	$total_denda += $denda_per_buku;
																	echo $this->M_Admin->rp($denda_per_buku).'';
																} else {
																	echo '<span style="color:green">Tidak Ada Denda</span>';
																}
															?>
														</td>
													</tr>
												<?php $no++;}?>
												<?php if($date1 > $date2): ?>
												<tr>
													<td colspan="5" align="right"><strong>Total Denda:</strong></td>
													<td><strong><?= $this->M_Admin->rp($total_denda) ?></strong></td>
												</tr>
												<?php endif; ?>
												</tbody>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</div>
                        <div class="pull-right">
							<a href="<?= base_url('transaksi');?>" class="btn btn-danger btn-md">Kembali</a>
						</div>
		        </div>
	        </div>
	    </div>
    </div>
</section>
</div>
