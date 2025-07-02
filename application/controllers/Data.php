<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {
	function __construct(){
	 parent::__construct();
	 	//validasi jika user belum login
     $this->data['CI'] =& get_instance();
     $this->load->helper(array('form', 'url'));
     $this->load->model('M_Admin');
		if($this->session->userdata('masuk_perpus') != TRUE){
				$url=base_url('login');
				redirect($url);
		}
	}

	public function index()
	{
		$this->buku();
	}

	public function buku()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

		$sql = "SELECT b.*, 
			(SELECT COALESCE(SUM(br.jumlah), 0) 
			 FROM tbl_buku_rusak br 
			 WHERE br.buku_id = b.id_buku) as jumlah_rusak,
			(SELECT COUNT(*) 
			 FROM tbl_pinjam p 
			 WHERE p.buku_id = b.buku_id 
			 AND p.status = 'Dipinjam') as dipinjam
			FROM tbl_buku b
			LEFT JOIN tbl_buku_rusak br ON br.buku_id = b.id_buku
			GROUP BY b.id_buku
			ORDER BY b.id_buku DESC";

		$this->data['buku'] = $this->db->query($sql);
		$this->data['title_web'] = 'Data Buku';
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('buku/buku_view',$this->data);
		$this->load->view('footer_view',$this->data);
	}

	public function bukudetail()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_buku','id_buku',$this->uri->segment('3'));
		if($count > 0)
		{
			$this->data['buku'] = $this->M_Admin->get_tableid_edit('tbl_buku','id_buku',$this->uri->segment('3'));
			$this->data['kats'] =  $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
			$this->data['rakbuku'] =  $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC")->result_array();
			// Ambil data buku rusak untuk buku ini
			$this->data['buku_rusak'] = $this->db->query("SELECT br.*, l.nama as nama_petugas FROM tbl_buku_rusak br LEFT JOIN tbl_login l ON br.petugas_id = l.id_login WHERE br.buku_id = '".$this->uri->segment('3')."' ORDER BY br.tanggal DESC")->result_array();
		}else{
			redirect(base_url('data/bukurusak'));
			return;
		}

		$this->data['title_web'] = 'Data Buku Detail';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('buku/detail',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function perbaikan_buku($id)
	{
		// Cek level user
		if($this->session->userdata('level') !== 'Admin') {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda tidak memiliki hak akses!</div>');
			redirect('data/bukurusak');
			return;
		}

		// Ambil data buku rusak
		$buku_rusak = $this->db->get_where('tbl_buku_rusak', ['id' => $id])->row();
		if(!$buku_rusak) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data buku rusak tidak ditemukan!</div>');
			redirect('data/bukurusak');
			return;
		}

		// Mulai transaksi
		$this->db->trans_start();

		try {
			// Update stok buku (tambahkan jumlah yang diperbaiki)
			$this->db->set('jml', 'jml + ' . $buku_rusak->jumlah, FALSE);
			$this->db->where('id_buku', $buku_rusak->buku_id);
			$this->db->update('tbl_buku');

			// Hapus data buku rusak
			$this->db->where('id', $id);
			$this->db->delete('tbl_buku_rusak');

			// Hapus history terkait buku ini
			$this->db->where('buku_id', $buku_rusak->buku_id);
			$this->db->delete('tbl_history');

			// Catat ke history
			$this->db->insert('tbl_history', array(
				'tipe_transaksi' => 'Perbaikan Buku',
				'kode_transaksi' => 'PB' . date('YmdHis'),
				'buku_id' => $buku_rusak->buku_id,
				'jumlah' => $buku_rusak->jumlah,
				'keterangan' => 'Buku selesai diperbaiki',
				'petugas_id' => $this->session->userdata('ses_id')
			));

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Gagal melakukan perbaikan buku!');
			}

			$this->session->set_flashdata('pesan', '<div class="alert alert-success">Buku berhasil diperbaiki!</div>');
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">'.$e->getMessage().'</div>');
		}

		redirect('data/bukurusak');
	}

	public function bukuedit()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_buku','id_buku',$this->uri->segment('3'));
		if($count > 0)
		{
			
			$this->data['buku'] = $this->M_Admin->get_tableid_edit('tbl_buku','id_buku',$this->uri->segment('3'));
	   
			$this->data['kats'] =  $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
			$this->data['rakbuku'] =  $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC")->result_array();

		}else{
			echo '<script>alert("BUKU TIDAK DITEMUKAN");window.location="'.base_url('data').'"</script>';
		}

		$this->data['title_web'] = 'Data Buku Edit';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('buku/edit_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function bukutambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

		$this->data['kats'] =  $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
		$this->data['rakbuku'] =  $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC")->result_array();


        $this->data['title_web'] = 'Tambah Buku';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('buku/tambah_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function bukurusak()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['buku'] = $this->db->query("
			SELECT b.id_buku, b.buku_id, b.sampul, b.isbn, b.judul_buku, b.status,
				br.id, br.jumlah as jumlah_rusak, br.keterangan, br.tanggal as tgl_rusak,
				l.nama as nama_petugas
			FROM tbl_buku_rusak br
			INNER JOIN tbl_buku b ON br.buku_id = b.id_buku
			LEFT JOIN tbl_login l ON br.petugas_id = l.id_login
			ORDER BY br.tanggal DESC
		")->result_array();

		$this->data['title_web'] = 'Data Buku Rusak';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/bukurusak_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function inputbukurusak()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['buku'] = $this->db->query("SELECT * FROM tbl_buku ORDER BY judul_buku ASC")->result_array();
        $this->data['title_web'] = 'Input Buku Rusak';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('buku/input_rusak_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function prosesbukurusak()
	{
		// Cek apakah user adalah petugas atau admin
		if(!in_array($this->session->userdata('level'), ['Petugas', 'Admin'])) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Anda tidak memiliki hak akses!</div>');
			redirect(base_url('data/bukurusak'));
			return;
		}

		$buku_id = $this->input->post('buku_id');
		$jumlah_rusak = $this->input->post('jumlah_rusak');
		$keterangan = $this->input->post('keterangan');

		// Validasi input
		if(empty($buku_id) || empty($jumlah_rusak)) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Semua field harus diisi!</div>');
			redirect(base_url('data/inputbukurusak'));
			return;
		}

		// Ambil data buku
		$buku = $this->db->get_where('tbl_buku', ['id_buku' => $buku_id])->row();
		if(!$buku) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Buku tidak ditemukan!</div>');
			redirect(base_url('data/inputbukurusak'));
			return;
		}

		// Cek apakah jumlah buku rusak valid
		if ($jumlah_rusak > $buku->jml) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Jumlah buku rusak tidak boleh melebihi stok yang tersedia!</div>');
			redirect(base_url('data/inputbukurusak'));
			return;
		}

		// Mulai transaction
		$this->db->trans_start();

		try {
			// Update stok buku
			$stok_baru = $buku->jml - $jumlah_rusak;
			$this->db->where('id_buku', $buku_id);
			$this->db->update('tbl_buku', [
				'jml' => $stok_baru,
				'status' => $stok_baru == 0 ? 'Rusak' : 'Tersedia'
			]);

			// Tambahkan ke log buku rusak
			$data = array(
				'buku_id' => $buku_id,
				'jumlah' => $jumlah_rusak,
				'keterangan' => $keterangan,
				'tanggal' => date('Y-m-d H:i:s'),
				'petugas_id' => $this->session->userdata('ses_id')
			);
			$this->db->insert('tbl_buku_rusak', $data);

			// Hapus history terkait buku ini
			$this->db->where('buku_id', $buku_id);
			$this->db->delete('tbl_history');

			// Catat ke history
			$this->db->insert('tbl_history', array(
				'tipe_transaksi' => 'Buku Rusak',
				'kode_transaksi' => 'BR' . date('YmdHis'),
				'buku_id' => $buku_id,
				'jumlah' => $jumlah_rusak,
				'keterangan' => $keterangan,
				'petugas_id' => $this->session->userdata('ses_id')
			));

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Gagal menyimpan data!');
			}

			$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Buku berhasil ditandai sebagai rusak!</div>');
			redirect(base_url('data/bukurusak'));

		} catch (Exception $e) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Gagal mencatat buku rusak: ' . $e->getMessage() . '</div>');
			redirect(base_url('data/inputbukurusak'));
		}
	}


	public function prosesbuku()
	{
		if($this->session->userdata('masuk_perpus') != TRUE){
			$url=base_url('login');
			redirect($url);
		}

		// hapus aksi form proses buku
		if(!empty($this->input->get('buku_id')))
		{
        
			$buku = $this->M_Admin->get_tableid_edit('tbl_buku','id_buku',htmlentities($this->input->get('buku_id')));
			
			$sampul = './assets_style/image/buku/'.$buku->sampul;
			if(file_exists($sampul))
			{
				unlink($sampul);
			}
			// Hapus history terkait buku ini sebelum hapus buku
			$this->db->where('buku_id', $buku->id_buku);
			$this->db->delete('tbl_history');

			$this->M_Admin->delete_table('tbl_buku','id_buku',$this->input->get('buku_id'));
			
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
					<p> Berhasil Hapus Buku !</p>
				</div></div>');
			redirect(base_url('data'));  
		}

		// tambah aksi form proses buku
		if(!empty($this->input->post('tambah')))
		{
			$post= $this->input->post();
			// Validasi field wajib
			$required = [
				'kategori' => 'Kategori',
				'rak' => 'Rak',
				'isbn' => 'ISBN',
				'judul_buku' => 'Judul Buku',
				'pengarang' => 'Pengarang',
				'penerbit' => 'Penerbit',
				'thn' => 'Tahun',
				'jml' => 'Jumlah',
			];
			$empty_fields = [];
			foreach($required as $key => $label) {
				if(empty($post[$key])) {
					$empty_fields[] = $label;
				}
			}
			if(count($empty_fields) > 0) {
				$msg = '<div id="notifikasi"><div class="alert alert-danger"><p>Isi kolom pada daftar: <b>'.implode(', ', $empty_fields).'</b>!</p></div></div>';
				$this->session->set_flashdata('pesan', $msg);
				redirect(base_url('data/bukutambah'));
				return;
			}
			$buku_id = $this->M_Admin->buat_kode('tbl_buku','BK','id_buku','ORDER BY id_buku DESC LIMIT 1'); 
			$data = array(
				'buku_id'=>$buku_id,
				'id_kategori'=>htmlentities($post['kategori']), 
				'id_rak' => htmlentities($post['rak']), 
				'isbn' => htmlentities($post['isbn']), 
				'judul_buku'  => htmlentities($post['judul_buku']), 
				'pengarang'=> htmlentities($post['pengarang']), 
				'penerbit'=> htmlentities($post['penerbit']),    
				'thn_buku' => htmlentities($post['thn']), 
				'isi' => $this->input->post('ket'), 
				'jml'=> htmlentities($post['jml']),  
				'status' => htmlentities($this->input->post('status')),  
				'tgl_masuk' => date('Y-m-d H:i:s')
			);

			$this->load->library('upload',$config);
			if(!empty($_FILES['gambar']['name']))
			{
				// setting konfigurasi upload
				$config['upload_path'] = './assets_style/image/buku/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png'; 
				$config['encrypt_name'] = TRUE; //nama yang terupload nantinya
				// load library upload
				$this->load->library('upload',$config);
				$this->upload->initialize($config);

				if ($this->upload->do_upload('gambar')) {
					$this->upload->data();
					$file1 = array('upload_data' => $this->upload->data());
					$this->db->set('sampul', $file1['upload_data']['file_name']);
				}else{
					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
							<p> Edit Buku Gagal !</p>
						</div></div>');
					redirect(base_url('data')); 
				}
			}

			if(!empty($_FILES['lampiran']['name']))
			{
				// setting konfigurasi upload
				$config['upload_path'] = './assets_style/image/buku/';
				$config['allowed_types'] = 'pdf'; 
				$config['encrypt_name'] = TRUE; //nama yang terupload nantinya
				// load library upload
				$this->load->library('upload',$config);
				$this->upload->initialize($config);
				// script uplaod file kedua
				if ($this->upload->do_upload('lampiran')) {
					$this->upload->data();
					$file2 = array('upload_data' => $this->upload->data());
					$this->db->set('lampiran', $file2['upload_data']['file_name']);
				}else{

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
							<p> Edit Buku Gagal !</p>
						</div></div>');
					redirect(base_url('data')); 
				}
			}

			$this->db->insert('tbl_buku', $data);

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Tambah Buku Sukses !</p>
			</div></div>');
			redirect(base_url('data')); 
		}

		// edit aksi form proses buku
		if(!empty($this->input->post('edit')))
		{
			$post = $this->input->post();
			$data = array(
				'id_kategori'=>htmlentities($post['kategori']), 
				'id_rak' => htmlentities($post['rak']), 
				'isbn' => htmlentities($post['isbn']), 
				'judul_buku'  => htmlentities($post['judul_buku']),
				'pengarang'=> htmlentities($post['pengarang']), 
				'penerbit'=> htmlentities($post['penerbit']),  
				'thn_buku' => htmlentities($post['thn']), 
				'isi' => $this->input->post('ket'), 
				'jml'=> htmlentities($post['jml']),  
				'tgl_masuk' => date('Y-m-d H:i:s')
			);

			if(!empty($_FILES['gambar']['name']))
			{
				// setting konfigurasi upload
				$config['upload_path'] = './assets_style/image/buku/';
				$config['allowed_types'] = 'gif|jpg|jpeg|png'; 
				$config['encrypt_name'] = TRUE; //nama yang terupload nantinya
				// load library upload
				$this->load->library('upload',$config);
				$this->upload->initialize($config);

				if ($this->upload->do_upload('gambar')) {
					$this->upload->data();
					$gambar = './assets_style/image/buku/'.htmlentities($post['gmbr']);
					if(file_exists($gambar)) {
						unlink($gambar);
					}
					$file1 = array('upload_data' => $this->upload->data());
					$this->db->set('sampul', $file1['upload_data']['file_name']);
				}else{
					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
							<p> Edit Buku Gagal !</p>
						</div></div>');
					redirect(base_url('data')); 
				}
			}

			if(!empty($_FILES['lampiran']['name']))
			{
				// setting konfigurasi upload
				$config['upload_path'] = './assets_style/image/buku/';
				$config['allowed_types'] = 'pdf'; 
				$config['encrypt_name'] = TRUE; //nama yang terupload nantinya
				// load library upload
				$this->load->library('upload',$config);
				$this->upload->initialize($config);
				// script uplaod file kedua
				if ($this->upload->do_upload('lampiran')) {
					$this->upload->data();
					$lampiran = './assets_style/image/buku/'.htmlentities($post['lamp']);
					if(file_exists($lampiran)) {
						unlink($lampiran);
					}
					$file2 = array('upload_data' => $this->upload->data());
					$this->db->set('lampiran', $file2['upload_data']['file_name']);
				}else{

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
							<p> Edit Buku Gagal !</p>
						</div></div>');
					redirect(base_url('data')); 
				}
			}

			$this->db->where('id_buku',htmlentities($post['edit']));
			$this->db->update('tbl_buku', $data);

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Edit Buku Sukses !</p>
				</div></div>');
			redirect(base_url('data/bukuedit/'.$post['edit'])); 
		}
	}

	public function kategori()
	{
		
        $this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['kategori'] =  $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC");

		if(!empty($this->input->get('id'))){
			$id = $this->input->get('id');
			$count = $this->M_Admin->CountTableId('tbl_kategori','id_kategori',$id);
			if($count > 0)
			{			
				$this->data['kat'] = $this->db->query("SELECT *FROM tbl_kategori WHERE id_kategori='$id'")->row();
			}else{
				echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="'.base_url('data/kategori').'"</script>';
			}
		}

        $this->data['title_web'] = 'Data Kategori ';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('kategori/kat_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function katproses()
	{
		if(!empty($this->input->post('tambah')))
		{
			$post= $this->input->post();
			$data = array(
				'nama_kategori'=>htmlentities($post['kategori']),
			);

			$this->db->insert('tbl_kategori', $data);

			
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Tambah Kategori Sukses !</p>
			</div></div>');
			redirect(base_url('data/kategori'));  
		}

		if(!empty($this->input->post('edit')))
		{
			$post= $this->input->post();
			$data = array(
				'nama_kategori'=>htmlentities($post['kategori']),
			);
			$this->db->where('id_kategori',htmlentities($post['edit']));
			$this->db->update('tbl_kategori', $data);


			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Edit Kategori Sukses !</p>
			</div></div>');
			redirect(base_url('data/kategori')); 		
		}

		if(!empty($this->input->get('kat_id')))
		{
			$this->db->where('id_kategori',$this->input->get('kat_id'));
			$this->db->delete('tbl_kategori');

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p> Hapus Kategori Sukses !</p>
			</div></div>');
			redirect(base_url('data/kategori')); 
		}
	}

	public function rak()
	{
		// Batasi akses untuk petugas
		if($this->session->userdata('level') == 'Petugas'){
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Petugas tidak diizinkan mengakses menu rak!</div>');
			redirect(base_url('dashboard'));
			return;
		}
		
        $this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['rakbuku'] =  $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC");

		if(!empty($this->input->get('id'))){
			$id = $this->input->get('id');
			$count = $this->M_Admin->CountTableId('tbl_rak','id_rak',$id);
			if($count > 0)
			{	
				$this->data['rak'] = $this->db->query("SELECT *FROM tbl_rak WHERE id_rak='$id'")->row();
			}else{
				echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="'.base_url('data/rak').'"</script>';
			}
		}

        $this->data['title_web'] = 'Data Rak Buku ';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('rak/rak_view',$this->data);
        $this->load->view('footer_view',$this->data);
	}

	public function rakproses()
	{
		if(!empty($this->input->post('tambah')))
		{
			$post= $this->input->post();
			$data = array(
				'nama_rak'=>htmlentities($post['rak']),
			);

			$this->db->insert('tbl_rak', $data);

			
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Tambah Rak Buku Sukses !</p>
			</div></div>');
			redirect(base_url('data/rak'));  
		}

		if(!empty($this->input->post('edit')))
		{
			$post= $this->input->post();
			$data = array(
				'nama_rak'=>htmlentities($post['rak']),
			);
			$this->db->where('id_rak',htmlentities($post['edit']));
			$this->db->update('tbl_rak', $data);


			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Edit Rak Sukses !</p>
			</div></div>');
			redirect(base_url('data/rak')); 		
		}

		if(!empty($this->input->get('rak_id')))
		{
			$this->db->where('id_rak',$this->input->get('rak_id'));
			$this->db->delete('tbl_rak');

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p> Hapus Rak Buku Sukses !</p>
			</div></div>');
			redirect(base_url('data/rak')); 
		}
	}

	public function detailbukurusak($id)
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		// Ambil data buku rusak
		$rusak = $this->db->query("SELECT br.*, b.judul_buku, b.isbn, b.sampul, b.penerbit, b.pengarang, b.thn_buku, l.nama as nama_petugas FROM tbl_buku_rusak br LEFT JOIN tbl_buku b ON br.buku_id = b.id_buku LEFT JOIN tbl_login l ON br.petugas_id = l.id_login WHERE br.id = ?", array($id))->row();
		$this->data['rusak'] = $rusak;
		$this->data['title_web'] = 'Detail Buku Rusak';
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('buku/detail_bukurusak',$this->data);
		$this->load->view('footer_view',$this->data);
	}
}
