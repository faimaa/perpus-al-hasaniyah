<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i>  Daftar Data User
    </h1>
    <ol class="breadcrumb">
			<li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
			<li class="active"><i class="fa fa-file-text"></i>&nbsp; Daftar Data User</li>
    </ol>
  </section>
  <section class="content">
<?php if($this->input->get('updated') && $this->session->flashdata('pesan')): ?>
    <?= $this->session->flashdata('pesan'); ?>
<?php endif; ?>
	<div class="row">
	    <div class="col-md-12">
	        <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="user/tambah"><button class="btn btn-primary"><i class="fa fa-plus"> </i> Tambah User</button></a>

                </div>
				<!-- /.box-header -->
				<div class="box-body">
				<div class="table-responsive">
                    <br/>
                    <table id="example1" class="table table-bordered table-striped table" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>User</th>
                                <th>Jenkel</th>
                                <th>Telepon</th>
                                <th>Level</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no=1;foreach($user as $isi){?>
                            <tr>
                                <td><?= $no;?></td>
                                <td><?= $isi['anggota_id'];?></td>
                                <td>
                                    <center>
                                        <?php echo get_user_photo($isi['foto'], $isi['nama'], 'md'); ?>
                                    </center>
                                </td>
                                <td><?= $isi['nama'];?></td>
                                <td><?= $isi['user'];?></td>
                                <td><?= $isi['jenkel'];?></td>
                                <td><?= $isi['telepon'];?></td>
                                <td>
                                    <?php echo get_user_level_badge($isi['level']); ?>
                                </td>
                                <td><?= $isi['alamat'];?></td>
                                <td style="width:20%;">
                                    <?php if($this->session->userdata('level') == 'Admin'){ ?>
                                        <a href="<?= base_url('user/edit/'.$isi['id_login']);?>"><button class="btn btn-success btn-sm"><i class="fa fa-edit"></i></button></a>
                                        <a href="<?= base_url('user/del/'.$isi['id_login']);?>" onclick="return confirm('Anda yakin user akan dihapus ?');">
                                        <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></a>
                                    <?php } ?>
                                    <a href="<?= base_url('user/detail/'.$isi['id_login']);?>" target="_blank"><button class="btn btn-primary btn-sm">
                                        <i class="fa fa-print"></i> Cetak Kartu</button></a>
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

<style>
.user-avatar-placeholder {
    transition: all 0.3s ease;
}

.user-avatar-placeholder:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.img-thumbnail {
    transition: all 0.3s ease;
}

.img-thumbnail:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.table td {
    vertical-align: middle;
}

.label {
    font-size: 11px;
    padding: 4px 8px;
}

/* Improved table styling */
.table {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

/* Button improvements */
.btn-sm {
    margin: 2px;
    border-radius: 6px;
    font-size: 11px;
    padding: 4px 8px;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
    border: none;
}

/* Photo column styling */
.table td:nth-child(3) {
    width: 120px;
    text-align: center;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
    }
    
    .btn-sm {
        margin: 1px;
        font-size: 10px;
        padding: 3px 6px;
    }
}
</style>
