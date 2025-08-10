<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

    public function ubahstatusbukuhilang($id)
    {
        // Validasi hak akses
        if (!in_array($this->session->userdata('level'), ['Petugas', 'Admin'])) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda tidak memiliki hak akses!</div>');
            redirect(base_url('data/bukuhilang'));
            return;
        }

        // Ambil data buku hilang
        $hilang = $this->db->get_where('tbl_buku_hilang', ['id' => $id])->row();
        if (!$hilang || !isset($hilang->pinjam_id)) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data buku hilang tidak valid atau tidak ditemukan!</div>');
            redirect(base_url('data/bukuhilang'));
            return;
        }

        // Memulai transaksi database
        $this->db->trans_start();

        // 1. Update status peminjaman menjadi 'Di Ganti'
        $this->db->where('pinjam_id', $hilang->pinjam_id);
        $this->db->update('tbl_pinjam', ['status' => 'Di Ganti']);

        // 2. Kembalikan stok buku (asumsi 1 buku yang hilang)
        $this->db->where('id_buku', $hilang->buku_id);
        $this->db->set('jml', 'jml + 1', FALSE); // Menambah stok buku
        $this->db->update('tbl_buku');

        // 3. Update laporan dari 'Buku Hilang' menjadi 'Mengganti Buku Baru'
        // Cari history dengan beberapa format kode_transaksi yang mungkin
        $formats_to_try = [
            $hilang->pinjam_id,                  // Format 1: hanya pinjam_id
            'BH' . $hilang->pinjam_id,           // Format 2: BH + pinjam_id
            'BH' . date('Y', strtotime($hilang->tgl_hilang)) . $hilang->pinjam_id, // Format 3: BH + tahun + pinjam_id
            'BH' . date('y', strtotime($hilang->tgl_hilang)) . $hilang->pinjam_id  // Format 4: BH + 2 digit tahun + pinjam_id
        ];
        
        $updated = false;
        foreach ($formats_to_try as $format) {
            $this->db->where('kode_transaksi', $format);
            $this->db->where('tipe_transaksi', 'Buku Hilang');
            $this->db->update('tbl_history', [
                'tipe_transaksi' => 'Mengganti Buku Baru',
                'keterangan'     => 'Buku telah diganti',
                'tanggal'        => date('Y-m-d H:i:s')
            ]);
            
            if ($this->db->affected_rows() > 0) {
                $updated = true;
                break;
            }
        }
        
        // Jika masih belum ketemu, coba cari berdasarkan buku_id dan tipe transaksi
        if (!$updated) {
            $this->db->where('buku_id', $hilang->buku_id);
            $this->db->where('tipe_transaksi', 'Buku Hilang');
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            $this->db->update('tbl_history', [
                'tipe_transaksi' => 'Mengganti Buku Baru',
                'keterangan'     => 'Buku telah diganti',
                'tanggal'        => date('Y-m-d H:i:s')
            ]);
        }

        // 4. Hapus catatan buku hilang
        $this->db->where('id', $id);
        $this->db->delete('tbl_buku_hilang');

        // Menyelesaikan transaksi
        $this->db->trans_complete();

        // Cek status transaksi dan berikan notifikasi
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Gagal memproses penggantian buku karena kesalahan database.</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Buku berhasil diganti dan laporan transaksi telah diperbarui!</div>');
        }

        redirect(base_url('data/bukuhilang?notif=1'));
    }

    public function detailbukuhilang($id)
    {
        $this->data['idbo'] = $this->session->userdata('ses_id');
        $detail = $this->db->query("SELECT h.*, h.tgl_hilang AS tanggal, b.judul_buku, b.isbn, b.sampul, l.nama as nama_petugas, a.nama as nama_anggota
            FROM tbl_buku_hilang h
            INNER JOIN tbl_buku b ON h.buku_id = b.id_buku
            LEFT JOIN tbl_login l ON h.petugas_id = l.id_login
            LEFT JOIN tbl_login a ON h.anggota_id = a.anggota_id
            WHERE h.id = ?", array($id))->row_array();
        if(!$detail) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data tidak ditemukan!</div>');
            redirect(base_url('data/bukuhilang'));
            return;
        }
        $this->data['detail'] = $detail;
        $this->data['title_web'] = 'Detail Buku Hilang';
        $this->load->view('header_view',$this->data);
        $this->load->view('sidebar_view',$this->data);
        $this->load->view('buku/detail_hilang_view',$this->data);
        $this->load->view('footer_view',$this->data);
    }
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
			 FROM tbl_buku_hilang bh
			 WHERE bh.buku_id = b.id_buku) as jumlah_hilang,
			(SELECT COUNT(*) 
			 FROM tbl_pinjam p 
			 WHERE p.buku_id = b.buku_id 
			 AND p.status = 'Dipinjam') as dipinjam
			FROM tbl_buku b
			LEFT JOIN tbl_buku_rusak br ON br.buku_id = b.id_buku
			GROUP BY b.id_buku
			ORDER BY b.id_buku DESC";

		$this->data['buku'] = $this->db->query($sql)->result_array();
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

		redirect(base_url('data/bukurusak?updated=1'));
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
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger">
					<p>BUKU TIDAK DITEMUKAN</p>
				</div></div>');
			redirect(base_url('data'));
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



			// Catat ke history transaksi
			$history_data = array(
				'tanggal' => date('Y-m-d H:i:s'),
				'tipe_transaksi' => 'Buku Rusak',
				'kode_transaksi' => 'BR' . date('YmdHis'),
				'buku_id' => $buku_id,
				'petugas_id' => $this->session->userdata('ses_id'),
				'jumlah' => $jumlah_rusak,
				'keterangan' => $keterangan
			);
			
			// Hanya tambahkan anggota_id jika kolom tersebut ada dan bisa NULL
			// Atau gunakan nilai default yang aman
			try {
				$this->db->insert('tbl_history', $history_data);
			} catch (Exception $e) {
				// Jika error karena anggota_id, coba tanpa kolom tersebut
				log_message('debug', 'History insert failed, trying without anggota_id: ' . $e->getMessage());
				
				// Hapus anggota_id dari data jika ada
				unset($history_data['anggota_id']);
				$this->db->insert('tbl_history', $history_data);
			}

			$this->db->trans_complete();

			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Gagal menyimpan data!');
			}

			$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Buku berhasil ditandai sebagai rusak!</div>');
			redirect(base_url('data/bukurusak?updated=1'));

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
			redirect(base_url('data?updated=1'));  
		}

		// tambah aksi form proses buku
		if(!empty($this->input->post('tambah')))
		{
			// Debug: Log input data
			log_message('debug', 'Tambah buku - Input data: ' . json_encode($this->input->post()));
			
			$post = $this->input->post();
			
			// Generate buku_id otomatis
			$last_book = $this->db->query("SELECT MAX(CAST(SUBSTRING(buku_id, 3) AS UNSIGNED)) as last_id FROM tbl_buku WHERE buku_id LIKE 'BK%'")->row();
			$next_id = ($last_book && $last_book->last_id) ? $last_book->last_id + 1 : 1;
			$buku_id = 'BK' . str_pad($next_id, 3, '0', STR_PAD_LEFT);
			
			$data = array(
				'buku_id' => $buku_id,
				'id_kategori'=>htmlentities($post['kategori']), 
				'id_rak' => htmlentities($post['rak']), 
				'isbn' => htmlentities($post['isbn']), 
				'title' => htmlentities($post['judul_buku']), // Gunakan 'title' bukan 'judul_buku'
				'pengarang'=> htmlentities($post['pengarang']), 
				'penerbit'=> htmlentities($post['penerbit']),    
				'thn_buku' => htmlentities($post['thn']), 
				'isi' => $this->input->post('ket'), 
				'jml'=> htmlentities($post['jml']),  
				'tgl_masuk' => date('Y-m-d H:i:s')
			);

			// Debug: Log prepared data
			log_message('debug', 'Tambah buku - Prepared data: ' . json_encode($data));

			// Initialize config array for uploads
			$config = array();
			// Use writable path for Railway deployment
			if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway') !== false) {
				// Railway production - use tmp directory
				$config['upload_path'] = '/tmp/';
			} else {
				// Local development
				$config['upload_path'] = './assets_style/image/buku/';
			}
			$config['allowed_types'] = 'gif|jpg|jpeg|png'; 
			$config['encrypt_name'] = TRUE;

			$this->load->library('upload',$config);
			if(!empty($_FILES['gambar']['name']))
			{
				// Update config for image upload
				$config['allowed_types'] = 'gif|jpg|jpeg|png'; 
				$this->upload->initialize($config);

				if ($this->upload->do_upload('gambar')) {
					$this->upload->data();
					$file1 = array('upload_data' => $this->upload->data());
					$this->db->set('sampul', $file1['upload_data']['file_name']);
					log_message('debug', 'Tambah buku - Image uploaded: ' . $file1['upload_data']['file_name']);
				}else{
					log_message('error', 'Tambah buku - Image upload failed: ' . $this->upload->display_errors());
					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger">
							<p> Tambah Buku Gagal ! Error upload gambar: ' . $this->upload->display_errors() . '</p>
						</div></div>');
					redirect(base_url('data')); 
				}
			}

			if(!empty($_FILES['lampiran']['name']))
			{
				// Update config for PDF upload
				$config['allowed_types'] = 'pdf'; 
				$this->upload->initialize($config);
				// script uplaod file kedua
				if ($this->upload->do_upload('lampiran')) {
					$this->upload->data();
					$file2 = array('upload_data' => $this->upload->data());
					$this->db->set('lampiran', $file2['upload_data']['file_name']);
					log_message('error', 'Tambah buku - PDF uploaded: ' . $file2['upload_data']['file_name']);
				}else{
					log_message('error', 'Tambah buku - PDF upload failed: ' . $this->upload->display_errors());
					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger">
							<p> Tambah Buku Gagal ! Error upload PDF: ' . $this->upload->display_errors() . '</p>
						</div></div>');
					redirect(base_url('data')); 
				}
			}

			// Debug: Log final data before insert
			log_message('debug', 'Tambah buku - Final data before insert: ' . json_encode($data));

			// Try to insert data with error handling
			try {
				$result = $this->db->insert('tbl_buku', $data);
				
				if ($result) {
					$insert_id = $this->db->insert_id();
					log_message('debug', 'Tambah buku - Insert successful, ID: ' . $insert_id);
					
					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p> Tambah Buku Sukses ! ID: ' . $insert_id . ' | Kode: ' . $buku_id . '</p>
					</div></div>');
				} else {
					log_message('error', 'Tambah buku - Insert failed: ' . $this->db->error()['message']);
					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger">
					<p> Tambah Buku Gagal ! Error database: ' . $this->db->error()['message'] . '</p>
					</div></div>');
				}
			} catch (Exception $e) {
				log_message('error', 'Tambah buku - Exception: ' . $e->getMessage());
				$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger">
				<p> Tambah Buku Gagal ! Exception: ' . $e->getMessage() . '</p>
				</div></div>');
			}

			redirect(base_url('data?updated=1')); 
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

			// Initialize config array for uploads in edit mode
			$config = array();
			// Use writable path for Railway deployment
			if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway') !== false) {
				// Railway production - use tmp directory
				$config['upload_path'] = '/tmp/';
			} else {
				// Local development
				$config['upload_path'] = './assets_style/image/buku/';
			}
			$config['allowed_types'] = 'gif|jpg|jpeg|png'; 
			$config['encrypt_name'] = TRUE;
			
			// Load upload library for edit mode
			$this->load->library('upload',$config);

			if(!empty($_FILES['gambar']['name']))
			{
				// Update config for image upload
				$config['allowed_types'] = 'gif|jpg|jpeg|png'; 
				$this->upload->initialize($config);

				if ($this->upload->do_upload('gambar')) {
					$this->upload->data();
					// Only try to delete file if it's local development
					if (!isset($_SERVER['HTTP_HOST']) || strpos($_SERVER['HTTP_HOST'], 'railway') === false) {
						$gambar = './assets_style/image/buku/'.htmlentities($post['gmbr']);
						if(file_exists($gambar)) {
							unlink($gambar);
						}
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
				// Update config for PDF upload
				$config['allowed_types'] = 'pdf'; 
				$this->upload->initialize($config);
				// script uplaod file kedua
				if ($this->upload->do_upload('lampiran')) {
					$this->upload->data();
					// Only try to delete file if it's local development
					if (!isset($_SERVER['HTTP_HOST']) || strpos($_SERVER['HTTP_HOST'], 'railway') === false) {
						$lampiran = './assets_style/image/buku/'.htmlentities($post['lamp']);
						if(file_exists($lampiran)) {
							unlink($lampiran);
						}
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
			redirect(base_url('data/bukuedit/'.$post['edit'].'?updated=1')); 
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
				$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger">
						<p>KATEGORI TIDAK DITEMUKAN</p>
					</div></div>');
				redirect(base_url('data/kategori'));
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
			redirect(base_url('data/kategori?updated=1'));  
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
			redirect(base_url('data/kategori?updated=1')); 		
		}

		if(!empty($this->input->get('kat_id')))
		{
			$this->db->where('id_kategori',$this->input->get('kat_id'));
			$this->db->delete('tbl_kategori');

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p> Hapus Kategori Sukses !</p>
			</div></div>');
			redirect(base_url('data/kategori?updated=1')); 
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
				$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-danger">
						<p>RAK TIDAK DITEMUKAN</p>
					</div></div>');
				redirect(base_url('data/rak'));
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
			redirect(base_url('data/rak?updated=1'));  
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
			redirect(base_url('data/rak?updated=1')); 		
		}

		if(!empty($this->input->get('rak_id')))
		{
			$this->db->where('id_rak',$this->input->get('rak_id'));
			$this->db->delete('tbl_rak');

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p> Hapus Rak Buku Sukses !</p>
			</div></div>');
			redirect(base_url('data/rak?updated=1')); 
		}
	}

	public function detailbukurusak($id)
	{
		// Batasi akses hanya untuk Admin dan Petugas
		if(!in_array($this->session->userdata('level'), ['Admin','Petugas'])){
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda tidak memiliki hak akses!</div>');
			redirect(base_url('data/bukurusak'));
			return;
		}
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
	public function bukuhilang()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		        $this->data['buku'] = $this->db->query("
                SELECT 
                    b.id_buku, b.buku_id, b.sampul, b.isbn, b.judul_buku, b.status,
                    h.id, h.keterangan, h.tgl_hilang as tgl_hilang, 
                    p.pinjam_id,
                    l.nama as nama_petugas, 
                    IFNULL(a.nama, CONCAT('ID: ', h.anggota_id)) as nama_anggota
                FROM tbl_buku_hilang h
                INNER JOIN tbl_buku b ON h.buku_id = b.id_buku
                LEFT JOIN tbl_pinjam p ON h.pinjam_id = p.id_pinjam
                LEFT JOIN tbl_login l ON h.petugas_id = l.id_login
                LEFT JOIN tbl_login a ON h.anggota_id = a.anggota_id
                ORDER BY h.tgl_hilang DESC
            ")->result_array();

		$this->data['title_web'] = 'Data Buku Hilang';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/bukuhilang_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function inputbukuhilang()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		// Ambil daftar peminjaman aktif (status Dipinjam)
		$pinjam = $this->db->query("
			SELECT p.id_pinjam, p.pinjam_id, p.buku_id as kode_buku, p.anggota_id,
				   b.id_buku, b.judul_buku, l.anggota_id as login_anggota_id, l.nama as nama_anggota
			FROM tbl_pinjam p
			JOIN tbl_buku b ON LOWER(TRIM(b.buku_id)) = LOWER(TRIM(p.buku_id))
			JOIN tbl_login l ON l.anggota_id = p.anggota_id
			WHERE LOWER(TRIM(p.status)) = 'dipinjam'
			ORDER BY b.judul_buku ASC
		")->result_array();
		$this->data['pinjam'] = $pinjam;
		$this->data['title_web'] = 'Input Buku Hilang';
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('buku/input_hilang_view',$this->data);
		$this->load->view('footer_view',$this->data);
	}

	public function prosesbukuhilang()
{
    if(!in_array($this->session->userdata('level'), ['Petugas', 'Admin'])) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Anda tidak memiliki hak akses!</div>');
        redirect(base_url('data/bukuhilang'));
        return;
    }

    // Ambil value langsung dari input hidden
    $id_buku = $this->input->post('id_buku');
    $anggota_id = $this->input->post('anggota_id');
    $keterangan = $this->input->post('keterangan');

    if(empty($id_buku) || empty($anggota_id)) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Semua field wajib diisi!</div>');
        redirect(base_url('data/inputbukuhilang'));
        return;
    }

    try {
        $this->db->trans_start();

        // Ambil kode buku dari id_buku (angka)
        $buku = $this->db->get_where('tbl_buku', ['id_buku' => $id_buku])->row();
        $kode_buku = $buku ? $buku->buku_id : null;

        // Cari pinjaman aktif untuk buku (pakai kode string) dan anggota
        $pinjam = $this->db->query("SELECT * FROM tbl_pinjam WHERE buku_id=? AND anggota_id=? AND LOWER(TRIM(status))='dipinjam' LIMIT 1", [$kode_buku, $anggota_id])->row();
        $pinjam_id = $pinjam ? $pinjam->id_pinjam : null;

        // Validasi: hanya boleh input jika ada pinjaman aktif
        if (!$pinjam_id) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Tidak ditemukan transaksi peminjaman aktif untuk buku dan anggota ini. Buku hilang hanya bisa dicatat jika ada peminjaman aktif!</div>');
            redirect(base_url('data/inputbukuhilang'));
            return;
        }

        // Simpan data buku hilang
        $data_hilang = [
            'buku_id' => $id_buku,
            'anggota_id' => $anggota_id,
            'keterangan' => $keterangan,
            'tgl_hilang' => date('Y-m-d H:i:s'),
            'petugas_id' => $this->session->userdata('ses_id'),
            'pinjam_id' => $pinjam_id
        ];
        $this->db->insert('tbl_buku_hilang', $data_hilang);

        // Ambil id_login dari anggota_id untuk disimpan ke history
        $user = $this->db->get_where('tbl_login', ['anggota_id' => $anggota_id])->row();
        $id_login_anggota = $user ? $user->id_login : null;

        // Catat ke history agar muncul di laporan
        if ($id_login_anggota) {
            $this->db->insert('tbl_history', array(
                'tipe_transaksi' => 'Buku Hilang',
                'kode_transaksi' => 'BH'.date('YmdHis'),	 
                'buku_id' => $id_buku,
                'anggota_id' => $id_login_anggota, // Gunakan id_login yang benar
                'petugas_id' => $this->session->userdata('ses_id'),
                'keterangan' => $keterangan,
                'tanggal' => date('Y-m-d H:i:s')
            ));
        }

        // Update status peminjaman jika ada
        if ($pinjam_id) {
            $this->db->where('id_pinjam', $pinjam_id);
            $this->db->update('tbl_pinjam', ['status' => 'Hilang']);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            throw new Exception('Gagal menyimpan data!');
        }

        $this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Buku berhasil ditandai sebagai hilang!</div>');
        redirect(base_url('data/bukuhilang?notif=1'));

    } catch (Exception $e) {
        $this->db->trans_rollback();
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Gagal mencatat buku hilang: ' . $e->getMessage() . '</div>');
        redirect(base_url('data/inputbukuhilang'));
    }
}

    // Form penggantian buku hilang
    public function gantibukuhilang($id)
    {
        if($this->session->userdata('level') !== 'Admin') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda tidak memiliki hak akses!</div>');
            redirect(base_url('data/bukuhilang'));
            return;
        }
        $this->data['idbo'] = $this->session->userdata('ses_id');
        $detail = $this->db->query("SELECT h.*, b.judul_buku, b.isbn, b.sampul FROM tbl_buku_hilang h INNER JOIN tbl_buku b ON h.id_buku = b.id_buku WHERE h.id = ?", array($id))->row_array();
        if(!$detail) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data tidak ditemukan!</div>');
            redirect(base_url('data/bukuhilang'));
            return;
        }
        $this->data['detail'] = $detail;
        $this->data['title_web'] = 'Ganti Buku Hilang';
        $this->load->view('header_view', $this->data);
        $this->load->view('sidebar_view', $this->data);
        $this->load->view('buku/ganti_buku_hilang_view', $this->data);
        $this->load->view('footer_view', $this->data);
    }

    // Proses penggantian buku hilang
    public function prosesgantibukuhilang()
    {
        $id = $this->input->post('id');
        $jumlah_ganti = $this->input->post('jumlah_ganti');
        $keterangan_ganti = $this->input->post('keterangan_ganti');
        $this->db->trans_start();
        
        // Ambil data buku hilang
        $row = $this->db->get_where('tbl_buku_hilang', ['id' => $id])->row();
        
        if($row) {
            $pinjam_id = $row->pinjam_id;
            
            // 1. Update stok buku - tambah ke stok tersedia
            $this->db->where('id_buku', $row->buku_id);
            $this->db->set('jml', 'jml + ' . (int)$jumlah_ganti, FALSE);
            
            // Kurangi dari stok yang dipinjam
            $this->db->set('dipinjam', 'GREATEST(0, dipinjam - ' . (int)$jumlah_ganti . ')', FALSE);
            
            $this->db->update('tbl_buku');
            
            // 2. Update status peminjaman menjadi 'Selesai' karena sudah diganti
            $this->db->where('pinjam_id', $pinjam_id);
            $this->db->update('tbl_pinjam', ['status' => 'Selesai']);
            
            // 3. Update history transaksi
            $this->db->where('kode_transaksi', $pinjam_id);
            $this->db->where('tipe_transaksi', 'Buku Hilang');
            $this->db->update('tbl_history', [
                'tipe_transaksi' => 'Mengganti Buku Baru',
                'keterangan' => $keterangan_ganti,
                'tanggal' => date('Y-m-d H:i:s')
            ]);

            // 4. Hapus data buku hilang
            $this->db->where('id', $id);
            $this->db->delete('tbl_buku_hilang');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || !$row) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Gagal memproses penggantian buku!</div>');
            redirect(base_url('data/bukuhilang?notif=1'));
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Buku pengganti berhasil diproses dan laporan telah diperbarui!</div>');
            redirect(base_url('data/bukuhilang?notif=1'));
        }
    }

	// Hapus Buku Hilang
	public function hapus_bukuhilang($id)
	{
		if(!in_array($this->session->userdata('level'), ['Petugas', 'Admin'])) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Anda tidak memiliki hak akses!</div>');
			redirect(base_url('data/bukuhilang'));
			return;
		}

		// Ambil data buku hilang
		$hilang = $this->db->get_where('tbl_buku_hilang', ['id' => $id])->row();
		if(!$hilang) {
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger" role="alert">Data buku hilang tidak ditemukan!</div>');
			redirect(base_url('data/bukuhilang?notif=1'));
			return;
		}

		// Kembalikan stok buku
		$buku = $this->db->get_where('tbl_buku', ['id_buku' => $hilang->buku_id])->row();
		if($buku) {
			$stok_baru = $buku->jml + $hilang->jumlah;
			$this->db->where('id_buku', $hilang->buku_id);
			$this->db->update('tbl_buku', [
				'jml' => $stok_baru,
				// Status bisa diupdate jika perlu
			]);
		}

		// Hapus history terkait (opsional)
		$this->db->where('tipe_transaksi', 'Buku Hilang', 'Mengganti Buku Baru');
		$this->db->where('buku_id', $hilang->buku_id);
		$this->db->where('anggota_id', $hilang->anggota_id);
		$this->db->where('jumlah', $hilang->jumlah);
		$this->db->delete('tbl_history');

		// Hapus data buku hilang
		$this->db->where('id', $id);
		$this->db->delete('tbl_buku_hilang');

		$this->session->set_flashdata('pesan', '<div class="alert alert-success" role="alert">Data buku hilang berhasil dihapus!</div>');
		redirect(base_url('data/bukuhilang'));
	}
}

	