<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-edit" style="color:green"> </i>  Update User - <?= $user->nama;?>
    </h1>
    <ol class="breadcrumb">
			<li><a href="<?php echo base_url('dashboard');?>"><i class="fa fa-dashboard"></i>&nbsp; Dashboard</a></li>
			<li class="active"><i class="fa fa-edit"></i>&nbsp; Update User - <?= $user->nama;?></li>
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
                </div>
			    <!-- /.box-header -->
			    <div class="box-body">
                    <form action="<?php echo base_url('user/upd');?>" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nama Pengguna</label>
                                    <input type="text" class="form-control" value="<?= $user->nama;?>" name="nama" required="required" placeholder="Nama Pengguna">
                                </div>
                                <div class="form-group">
                                    <label>Tempat Lahir</label>
                                    <input type="text" class="form-control" name="lahir" value="<?= $user->tempat_lahir;?>" required="required" placeholder="Contoh : Bekasi">
                                </div>
                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" class="form-control" name="tgl_lahir" value="<?= $user->tgl_lahir;?>" required="required" placeholder="Contoh : 1999-05-18">
                                </div>
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" readonly value="<?= $user->user;?>"  name="user" required="required" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <label>Password (opsional)</label>
                                    <input type="password" class="form-control" name="pass" placeholder="Isi Password Jika di Perlukan Ganti">
                                </div>
                                <div class="form-group">
                                    <label>Level</label>
                                    <select name="level" class="form-control" required="required">
                                    <?php if($this->session->userdata('level') == 'Admin'){ ?>
                                        <option value="Admin" <?php if($user->level == 'Admin'){ echo 'selected';}?>>Admin</option>
                                        <option value="Petugas" <?php if($user->level == 'Petugas'){ echo 'selected';}?>>Petugas</option>
                                        <option value="Anggota" <?php if($user->level == 'Anggota'){ echo 'selected';}?>>Anggota</option>
                                    <?php } elseif($this->session->userdata('level') == 'Petugas'){ ?>
                                        <option value="Anggota" <?php if($user->level == 'Petugas'){ echo 'selected';}?>>Petugas</option>
                                    <?php } elseif($this->session->userdata('level') == 'Anggota'){ ?>
                                        <option value="Anggota" <?php if($user->level == 'Anggota'){ echo 'selected';}?>>Anggota</option>
                                    <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Kelamin</label>
                                    <br/>
                                    <input type="radio" name="jenkel" <?php if($user->jenkel == 'Laki-Laki'){ echo 'checked';}?> value="Laki-Laki" required="required"> Laki-Laki
                                    <br/>
                                    <input type="radio" name="jenkel" <?php if($user->jenkel == 'Perempuan'){ echo 'checked';}?> value="Perempuan" required="required"> Perempuan
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Telepon</label>
                                    <input id="uintTextBox" class="form-control" value="<?= $user->telepon;?>" name="telepon" required="required" placeholder="Contoh : 089618173609">
                                </div>
                                <div class="form-group">
                                    <label>E-mail</label>
                                    <input type="email"  value="<?= $user->email;?>" readonly class="form-control" name="email" required="required" placeholder="Contoh : fauzan1892@codekop.com">
                                </div>
                                <div class="form-group">
                                    <label>Pas Foto</label>
                                    <input type="file" accept="image/*" name="gambar">
                                    
                                    <br/>
                                    <?php echo get_user_photo($user->foto, $user->nama, 'xl'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea class="form-control" name="alamat"  required="required"><?= $user->alamat;?></textarea>
                                    <input type="hidden" class="form-control" value="<?= $user->id_login;?>" name="id_login">
                                    <input type="hidden" class="form-control" value="<?= $user->foto;?>" name="foto">
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary btn-md">Edit Data</button> 
						</form>
						<?php if($this->session->userdata('level') == 'Petugas'){?>
							<a href="<?= base_url('user');?>" class="btn btn-danger btn-md">Kembali</a>
						<?php }elseif($this->session->userdata('level') == 'Anggota'){?>
							<a href="<?= base_url('transaksi');?>" class="btn btn-danger btn-md">Kembali</a>
						<?php }?>
                        </div>
		        </div>
	        </div>
	    </div>
    </div>
</section>
</div>

<script>
// Photo preview functionality
document.querySelector('input[name="gambar"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Check if file is an image
        if (!file.type.startsWith('image/')) {
            alert('Silakan pilih file gambar (JPG, PNG, GIF)');
            this.value = '';
            return;
        }
        
        // Check file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2MB');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create preview image
            const previewContainer = document.querySelector('.user-avatar-placeholder');
            if (previewContainer) {
                previewContainer.innerHTML = `<img src="${e.target.result}" class="img-responsive img-thumbnail" alt="Preview Foto" style="max-width:200px; height:auto;border:2px solid #ddd;box-shadow:0 2px 4px rgba(0,0,0,0.1);">`;
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
