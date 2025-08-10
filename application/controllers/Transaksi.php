<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {
	function __construct(){
	 parent::__construct();
	 	//validasi jika user belum login
		$this->data['CI'] =& get_instance();
		$this->load->helper(array('form', 'url', 'pinjam'));
		$this->load->model('M_Admin');
		$this->load->library(array('cart'));
		if($this->session->userdata('masuk_perpus') != TRUE){
			$url=base_url('login');
			redirect($url);
		}
	 }
	 
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function hapus_pinjam()
	{
		$id_pinjam = $this->input->get('id_pinjam');
		if($id_pinjam) {
			// Hapus data dari tbl_pinjam berdasarkan id_pinjam
			$this->db->where('id_pinjam', $id_pinjam);
			$delete = $this->db->delete('tbl_pinjam');

			if($delete) {
				$this->session->set_flashdata('pesan', '<div class="alert alert-success">Data Peminjaman berhasil dihapus!</div>');
			} else {
				$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data Peminjaman gagal dihapus!</div>');
			}
		}
		redirect(base_url('transaksi?updated=1'));
	}

	public function index()
	{	
		$this->data['title_web'] = 'Data Pinjam Buku';
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$tanggal_awal = $this->input->get('tanggal_awal');
		$tanggal_akhir = $this->input->get('tanggal_akhir');
		if($this->session->userdata('level') == 'Anggota'){
			if ($tanggal_awal && $tanggal_akhir) {
				$this->data['pinjam'] = $this->db->query("
					SELECT p.*, peng.tgl_kembali
					FROM tbl_pinjam p
					LEFT JOIN tbl_pengembalian peng ON peng.id_pinjam = p.id_pinjam
					WHERE p.status = 'Dipinjam'
					AND p.anggota_id = ? AND DATE(p.tgl_pinjam) >= ? AND DATE(p.tgl_pinjam) <= ?
					ORDER BY p.pinjam_id DESC
				", array($this->session->userdata('anggota_id'), $tanggal_awal, $tanggal_akhir));
			} else {
				$this->data['pinjam'] = $this->db->query("
					SELECT p.*, peng.tgl_kembali
					FROM tbl_pinjam p
					LEFT JOIN tbl_pengembalian peng ON peng.id_pinjam = p.id_pinjam
					WHERE p.status = 'Dipinjam'
					AND p.anggota_id = ?
					ORDER BY p.pinjam_id DESC
				", array($this->session->userdata('anggota_id')));
			}
		}else{
			if ($tanggal_awal && $tanggal_akhir) {
				$this->data['pinjam'] = $this->db->query("
					SELECT p.*, peng.tgl_kembali
					FROM tbl_pinjam p
					LEFT JOIN tbl_pengembalian peng ON peng.id_pinjam = p.id_pinjam
					WHERE p.status = 'Dipinjam'
					AND DATE(p.tgl_pinjam) >= ? AND DATE(p.tgl_pinjam) <= ?
					ORDER BY p.pinjam_id DESC
				", array($tanggal_awal, $tanggal_akhir));
			} else {
				$this->data['pinjam'] = $this->db->query("
					SELECT p.*, peng.tgl_kembali
					FROM tbl_pinjam p
					LEFT JOIN tbl_pengembalian peng ON peng.id_pinjam = p.id_pinjam
					WHERE p.status = 'Dipinjam'
					ORDER BY p.pinjam_id DESC
				");
			}
		}
		
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('pinjam/pinjam_view',$this->data);
		$this->load->view('footer_view',$this->data);
	}

	private function get_history_data($tanggal_awal = null, $tanggal_akhir = null)
	{
		$where = '';
		$params = array();
		if ($tanggal_awal && $tanggal_akhir) {
			$where = 'WHERE DATE(h.tanggal) >= ? AND DATE(h.tanggal) <= ?';
			$params[] = $tanggal_awal;
			$params[] = $tanggal_akhir;
		}
		$query = "
			SELECT 
				h.*,
				b.judul_buku,
				b.isbn,
				l1.nama as nama_petugas,
				COALESCE(l2.nama, a.nama, CONCAT('[ID:', h.anggota_id, ']')) as nama_anggota,
				d.denda as harga_denda
			FROM tbl_history h
			LEFT JOIN tbl_buku b ON h.buku_id = b.id_buku
			LEFT JOIN tbl_login l1 ON h.petugas_id = l1.id_login
			LEFT JOIN tbl_login l2 ON h.anggota_id = l2.id_login
			LEFT JOIN tbl_anggota a ON h.anggota_id = a.anggota_id
			LEFT JOIN tbl_denda d ON d.pinjam_id = h.kode_transaksi
			$where
			ORDER BY h.tanggal DESC
		";
		return $this->db->query($query, $params)->result_array();
	}

	public function history()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['title_web'] = 'History Transaksi';
		$tanggal_awal = $this->input->get('tanggal_awal');
		$tanggal_akhir = $this->input->get('tanggal_akhir');
		if ($tanggal_awal && $tanggal_akhir) {
			$this->data['history'] = $this->get_history_data($tanggal_awal, $tanggal_akhir);
		} else {
		$this->data['history'] = $this->get_history_data();
		}
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('transaksi/history_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function download_history()
	{
		$tanggal_awal = $this->input->get('tanggal_awal');
		$tanggal_akhir = $this->input->get('tanggal_akhir');
		if ($tanggal_awal && $tanggal_akhir) {
			$history = $this->get_history_data($tanggal_awal, $tanggal_akhir);
		} else {
		$history = $this->get_history_data();
		}
		$filename = 'history_transaksi_' . date('Y-m-d_His') . '.csv';
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		$output = fopen('php://output', 'w');
		fputs($output, "\xEF\xBB\xBF");
		fputcsv($output, array(
			'Tanggal',
			'Tipe Transaksi',
			'Kode Transaksi',
			'Judul Buku',
			'ISBN',
			'Anggota',
			'Petugas',
			'Keterangan'
		));
		foreach ($history as $row) {
			fputcsv($output, array(
				$row['tanggal'],
				$row['tipe_transaksi'],
				$row['kode_transaksi'],
				$row['judul_buku'],
				$row['isbn'],
				$row['nama_anggota'],
				$row['nama_petugas'],
				$row['keterangan']
			));
		}
		fclose($output);
		exit;
	}

	public function kembali()
	{	
		$this->data['title_web'] = 'Data Pengembalian Buku ';
		$this->data['idbo'] = $this->session->userdata('ses_id');

		if($this->session->userdata('level') == 'Anggota'){
			// Query untuk anggota, join ke tbl_pengembalian dan ambil denda
			$this->data['pinjam'] = $this->db->query("
				SELECT p.*, peng.denda, peng.tgl_kembali
				FROM tbl_pinjam p
				LEFT JOIN tbl_pengembalian peng ON peng.id_pinjam = p.id_pinjam
				WHERE p.anggota_id = ? AND p.status = 'Di Kembalikan'
				ORDER BY p.id_pinjam DESC
			", array($this->session->userdata('anggota_id')));

			$this->data['items'] = $this->db->query("
				SELECT DISTINCT p.id_pinjam, p.pinjam_id, p.buku_id, 
					b.buku_id, b.judul_buku, b.penerbit, b.thn_buku 
				FROM tbl_pinjam p 
				INNER JOIN tbl_buku b ON b.buku_id = p.buku_id 
				WHERE p.anggota_id = ? AND p.status = 'Di Kembalikan'",
				array($this->session->userdata('anggota_id')));
		}else{
			// Query untuk admin/petugas, join ke tbl_pengembalian dan ambil denda
			$this->data['pinjam'] = $this->db->query("
				SELECT p1.*, peng.denda, peng.tgl_kembali, l.nama
				FROM tbl_pinjam p1
				LEFT JOIN tbl_pengembalian peng ON peng.id_pinjam = p1.id_pinjam
				JOIN tbl_login l ON l.anggota_id = p1.anggota_id
				WHERE p1.status = 'Di Kembalikan'
				  AND p1.id_pinjam = (
					SELECT MAX(p2.id_pinjam)
					FROM tbl_pinjam p2
					WHERE p2.anggota_id = p1.anggota_id
					  AND p2.status = 'Di Kembalikan'
				  )
				ORDER BY p1.id_pinjam DESC
			");

			$this->data['items'] = array();
		}
		
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('kembali/home',$this->data);
		$this->load->view('footer_view',$this->data);
	}


	public function pinjam()
	{	

		$this->data['nop'] = $this->M_Admin->buat_kode('tbl_pinjam','PJ','id_pinjam','ORDER BY id_pinjam DESC LIMIT 1'); 
		$this->data['idbo'] = $this->session->userdata('ses_id');
        $this->data['user'] = $this->M_Admin->get_table('tbl_login');
		$this->data['buku'] =  $this->db->query("SELECT * FROM tbl_buku ORDER BY id_buku DESC");

		$this->data['title_web'] = 'Tambah Pinjam Buku ';

		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('pinjam/tambah_view',$this->data);
		$this->load->view('footer_view',$this->data);
	}

	public function detailpinjam($id = null)
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['title_web'] = 'Detail Peminjaman';
		$id = $this->uri->segment(3);
		$count = $this->M_Admin->CountTableId('tbl_pinjam', 'id_pinjam', $id);
		if($count > 0)
		{
			$this->data['pinjam'] = $this->db->query("SELECT DISTINCT p.id_pinjam, p.pinjam_id, p.anggota_id, 
				p.status, p.tgl_pinjam, p.lama_pinjam, p.tgl_balik, peng.tgl_kembali
				FROM tbl_pinjam p
				LEFT JOIN tbl_pengembalian peng ON peng.id_pinjam = p.id_pinjam
				WHERE p.id_pinjam = '$id'")->row();

			$this->data['items'] = $this->db->query("SELECT p.*, peng.tgl_kembali
    FROM tbl_pinjam p
    LEFT JOIN tbl_pengembalian peng ON peng.id_pinjam = p.id_pinjam
    WHERE p.id_pinjam = '$id'")->result_array();

			$this->data['anggota'] = $this->db->query("SELECT * FROM tbl_login WHERE anggota_id = '". $this->data['pinjam']->anggota_id ."'")->row();

			if($this->session->userdata('level') == 'Anggota'){
				$this->load->view('header_view', $this->data);
				$this->load->view('sidebar_view', $this->data);
				$this->load->view('pinjam/detail', $this->data);
				$this->load->view('footer_view', $this->data);
			}else{
				$this->load->view('header_view', $this->data);
				$this->load->view('sidebar_view', $this->data);
				$this->load->view('pinjam/detail', $this->data);
				$this->load->view('footer_view', $this->data);
			}
		}else{
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data Peminjaman tidak ditemukan!</div>');
			redirect(base_url('transaksi'));
		}
	}

	public function kembalipinjam()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$id = $this->uri->segment('3');
		$count = $this->M_Admin->CountTableId('tbl_pinjam','id_pinjam',$id);
		if($count > 0)
		{
			$this->data['pinjam'] = $this->db->query("SELECT DISTINCT p.id_pinjam, p.pinjam_id, 
				p.anggota_id, p.status, p.tgl_pinjam, p.lama_pinjam, 
				p.tgl_balik, peng.tgl_kembali 
				FROM tbl_pinjam p 
				LEFT JOIN tbl_pengembalian peng ON peng.id_pinjam = p.id_pinjam
				WHERE p.id_pinjam = '$id' 
				LIMIT 1")->row();

			$this->data['items'] = $this->db->query("SELECT DISTINCT p.id_pinjam, p.pinjam_id, p.buku_id, 
				b.buku_id, b.judul_buku, b.penerbit, b.thn_buku 
				FROM tbl_pinjam p 
				INNER JOIN tbl_buku b ON b.buku_id = p.buku_id 
				WHERE p.id_pinjam = '$id'")->result();

			$this->data['title_web'] = 'Detail Pengembalian Buku';
			$this->load->view('header_view',$this->data);
			$this->load->view('sidebar_view',$this->data);
			$this->load->view('pinjam/kembali',$this->data);
			$this->load->view('footer_view',$this->data);
		}else{
			$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data Peminjaman tidak ditemukan!</div>');
			redirect(base_url('transaksi'));
		}

	}

	public function prosespinjam()
	{
		$post = $this->input->post();
		if(!empty($post['tambah'])){
			// Validasi kolom wajib
			if(empty($post['anggota_id']) || empty($post['tgl']) || empty($post['lama']) || empty($post['buku_id'])) {
				$this->session->set_flashdata('notif_kosong', 'Ada kolom yang masih kosong, silakan lengkapi dulu!');
				redirect(base_url('transaksi/pinjam'));
				return;
			}
			$tgl = $post['tgl'];
			$tgl2 = date('Y-m-d', strtotime('+'.$post['lama'].' days', strtotime($tgl)));

			// Ambil pinjam_id terbaru
			$pinjam_id_baru = $this->M_Admin->generate_pinjam_id();

			$hasil_cart = array_values(unserialize($this->session->userdata('cart')));
			$data = array();
			foreach($hasil_cart as $isi)
			{
				$data[] = array(
					'pinjam_id'=> $pinjam_id_baru, 
					'anggota_id'=>htmlentities($post['anggota_id']), 
					'buku_id' => $isi['id'], 
					'status' => 'Dipinjam', 
					'tgl_pinjam' => htmlentities($post['tgl']), 
					'lama_pinjam' => htmlentities($post['lama']), 
					'tgl_balik'  => $tgl2
				);
			}
			$total_array = count($data);
			if($total_array != 0)
			{
				$this->db->insert_batch('tbl_pinjam',$data);

				// Kurangi stok buku
				foreach($data as $item){
					$buku = $this->db->get_where('tbl_buku', ['buku_id' => $item['buku_id']])->row();
					if($buku && $buku->jml > 0){
						$this->db->set('jml', 'jml - 1', FALSE);
						$this->db->where('id_buku', $buku->id_buku);
						$this->db->update('tbl_buku');
					}
				}

				// Catat ke history
				foreach($data as $item){
					// Ambil id_buku dari tbl_buku berdasarkan buku_id
					$buku = $this->db->get_where('tbl_buku', ['buku_id' => $item['buku_id']])->row();
					// Ambil id_login dari tbl_login berdasarkan anggota_id
					$anggota = $this->db->get_where('tbl_login', ['anggota_id' => $post['anggota_id']])->row();
					if($buku && $anggota) {
						$this->db->insert('tbl_history', array(
							'tipe_transaksi' => 'Peminjaman',
							'kode_transaksi' => $pinjam_id_baru,
							'buku_id' => $buku->id_buku,
							'anggota_id' => $anggota->id_login,
							'petugas_id' => $this->session->userdata('ses_id'),
							'keterangan' => 'Peminjaman buku selama ' . $post['lama'] . ' hari'
						));
					}
				}

				// Clear cart
				$this->session->unset_userdata('cart');

				$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
				<p> Tambah Pinjam Buku Sukses !</p>
				</div></div>');
				redirect(base_url('transaksi?updated=1')); 
			}
		}

		if($this->input->get('pinjam_id'))
		{
			$this->M_Admin->delete_table('tbl_pinjam','pinjam_id',$this->input->get('pinjam_id'));
			$this->M_Admin->delete_table('tbl_denda','pinjam_id',$this->input->get('pinjam_id'));

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p>  Hapus Transaksi Pinjam Buku Sukses !</p>
			</div></div>');
			redirect(base_url('transaksi?updated=1')); 
		}

		if($this->input->get('kembali'))
		{
			$id = $this->input->get('kembali');
			$id_pinjam = $this->input->get('id_pinjam');

			// Ambil data pinjam untuk buku spesifik yang akan dikembalikan
			$pinjam = $this->db->query("SELECT p.*, b.id_buku, b.judul_buku 
				FROM tbl_pinjam p 
				INNER JOIN tbl_buku b ON b.buku_id = p.buku_id 
				WHERE p.pinjam_id = ? AND p.id_pinjam = ? 
				AND (p.status IS NULL OR p.status != 'Di Kembalikan')
				LIMIT 1", array($id, $id_pinjam))->row();

			if($pinjam) {
				// Hitung denda untuk buku ini
				$date1 = new DateTime(date('Y-m-d'));
				$date2 = new DateTime($pinjam->tgl_balik);

				$harga_denda = 0;
				$lama_waktu = 0;

				if($date1 > $date2) {
					$diff = $date1->diff($date2)->days;
					$dd = $this->M_Admin->get_tableid_edit('tbl_biaya_denda','stat','Aktif'); 
					$harga_denda = $dd->harga_denda * $diff; // Denda per buku
					$lama_waktu = $diff;
				}

				// Update status peminjaman untuk buku ini saja
				$this->db->where('id_pinjam', $id_pinjam);
				$this->db->update('tbl_pinjam', array(
					'status' => 'Di Kembalikan'
				));

				// Insert ke tbl_pengembalian
				$data_pengembalian = array(
					'id_pinjam'   => $id_pinjam,
					'tgl_kembali' => date('Y-m-d'),
					'denda'       => $harga_denda
				);
				$this->db->insert('tbl_pengembalian', $data_pengembalian);

				// Ambil data anggota
				$anggota = $this->db->get_where('tbl_login', ['anggota_id' => $pinjam->anggota_id])->row();

				if($anggota) {
					// Catat ke history
					$this->db->insert('tbl_history', array(
						'tipe_transaksi' => 'Pengembalian',
						'kode_transaksi' => $id,
						'buku_id' => $pinjam->id_buku,
						'anggota_id' => $anggota->id_login,
						'petugas_id' => $this->session->userdata('ses_id'),
						'jumlah' => 1,
						'keterangan' => 'Pengembalian buku ' . $pinjam->judul_buku,
						'tanggal' => date('Y-m-d H:i:s')
					));

					// Catat denda jika ada
					if($harga_denda > 0) {
						$data_denda = array(
							'pinjam_id' => $id,
							'denda' => $harga_denda,
							'lama_waktu' => $lama_waktu,
							'tgl_denda' => date('Y-m-d'),
						);
						$this->db->insert('tbl_denda', $data_denda);
					}

					// Tambah stok buku yang dikembalikan
					$this->db->set('jml', 'jml + 1', FALSE);
					$this->db->where('id_buku', $pinjam->id_buku);
					$this->db->update('tbl_buku');

					$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
					<p>Pengembalian Buku '. $pinjam->judul_buku .' Sukses!</p>
					</div></div>');
				}
			}
			
			redirect(base_url('transaksi?updated=1'));
		}
	}

	public function denda()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');	

		$this->data['denda'] =  $this->db->query("SELECT * FROM tbl_biaya_denda ORDER BY id_biaya_denda DESC");

		if(!empty($this->input->get('id'))){
			$id = $this->input->get('id');
			$count = $this->M_Admin->CountTableId('tbl_biaya_denda','id_biaya_denda',$id);
			if($count > 0)
			{			
				$this->data['den'] = $this->db->query("SELECT *FROM tbl_biaya_denda WHERE id_biaya_denda='$id'")->row();
			}else{
				echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="'.base_url('transaksi/denda').'"</script>';
			}
		}

		$this->data['title_web'] = ' Denda ';
		$this->load->view('header_view',$this->data);
		$this->load->view('sidebar_view',$this->data);
		$this->load->view('denda/denda_view',$this->data);
		$this->load->view('footer_view',$this->data);
	}

	public function dendaproses()
	{
		if(!empty($this->input->post('tambah')))
		{
			$post= $this->input->post();
			if(empty($post['harga'])) {
				$this->session->set_flashdata('pesan', '<div class="alert alert-danger">Isi kolom harga denda!</div>');
				redirect(base_url('transaksi/denda?updated=1'));
				return;
			}
			$data = array(
				'harga_denda'=>$post['harga'],
				'stat'=>'Tidak Aktif',
				'tgl_tetap' => date('Y-m-d')
			);

			$this->db->insert('tbl_biaya_denda', $data);
			
			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Tambah  Harga Denda  Sukses !</p>
			</div></div>');
			redirect(base_url('transaksi/denda?updated=1')); 
		}

		if(!empty($this->input->post('edit')))
		{
			$dd = $this->M_Admin->get_tableid('tbl_biaya_denda','stat','Aktif');
			foreach($dd as $isi)
			{
				$data1 = array(
					'stat'=>'Tidak Aktif',
				);
				$this->db->where('id_biaya_denda',$isi['id_biaya_denda']);
				$this->db->update('tbl_biaya_denda', $data1);
			}

			$post= $this->input->post();
			$data = array(
				'harga_denda'=>$post['harga'],
				'stat'=>$post['status'],
				'tgl_tetap' => date('Y-m-d')
			);

			$this->db->where('id_biaya_denda',$post['edit']);
			$this->db->update('tbl_biaya_denda', $data);


			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-success">
			<p> Edit Harga Denda  Sukses !</p>
			</div></div>');
			redirect(base_url('transaksi/denda?updated=1')); 	
		}

		if(!empty($this->input->get('denda_id')))
		{
			$this->db->where('id_biaya_denda',$this->input->get('denda_id'));
			$this->db->delete('tbl_biaya_denda');

			$this->session->set_flashdata('pesan','<div id="notifikasi"><div class="alert alert-warning">
			<p> Hapus Harga Denda Sukses !</p>
			</div></div>');
			redirect(base_url('transaksi/denda?updated=1')); 
		}
	}


	public function result()
    {	
		
		$user = $this->M_Admin->get_tableid_edit('tbl_login','anggota_id',$this->input->post('kode_anggota'));
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
        
	}

	public function buku()
    {	
		$id = $this->input->post('kode_buku');
		$row = $this->db->query("SELECT * FROM tbl_buku WHERE buku_id ='$id'");
		
		if($row->num_rows() > 0)
		{
			$tes = $row->row();
			$item = array(
				'id'      => $id,
				'qty'     => 1,
                'price'   => '1000',
				'name'    => $tes->judul_buku,
				'options' => array('isbn' => $tes->isbn,'thn' => $tes->thn_buku,'penerbit' => $tes->penerbit)
			);
			if(!$this->session->has_userdata('cart')) {
				$cart = array($item);
				$this->session->set_userdata('cart', serialize($cart));
			} else {
				$index = $this->exists($id);
				$cart = array_values(unserialize($this->session->userdata('cart')));
				if($index == -1) {
					array_push($cart, $item);
					$this->session->set_userdata('cart', serialize($cart));
				} else {
					$cart[$index]['qty']++;
					$this->session->set_userdata('cart', serialize($cart));
				}
			}
		}else{

		}
        
	}

	public function buku_list()
	{
	?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>No</th>
					<th>Judul buku</th>
					<th>Penerbit</th>
					<th>Tahun</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			<?php $no=1;
				$cart = array_values(unserialize($this->session->userdata('cart')));
				if(!empty($cart)):
				foreach($cart as $items){?>
				<tr>
					<td><?= $no;?></td>
					<td><?= $items['name'];?></td>
					<td><?= $items['options']['penerbit'];?></td>
					<td><?= $items['options']['thn'];?></td>
					<td style="width:17%">
					<a href="javascript:void(0)" id="delete_buku<?=$no;?>" data-id="<?= $items['id'];?>" class="btn btn-danger btn-sm delete-buku">
						<i class="fa fa-trash"></i></a>
					</td>
				</tr>
			<?php $no++;}endif;?>
			</tbody>
		</table>
		<?php if(!empty($cart)):foreach($cart as $items){?>
			<input type="hidden" value="<?= $items['id'];?>" name="idbuku[]">
		<?php }endif;?>
		<div id="tampil"></div>
		<script>
			$(document).ready(function(){
				$(".delete-buku").click(function (e) {
					var id = $(this).data('id');
					$.ajax({
						type: "POST",
						url: "<?php echo base_url('transaksi/del_cart');?>",
						data: {kode_buku: id},
						success: function(response){
							$("#result_buku").html(response);
						}
					});
				});
			});
		</script>
	<?php
	}

	public function del_cart()
    {
		error_reporting(0);
        $id = $this->input->post('kode_buku');
        $index = $this->exists($id);
        if($index >= 0) {
            $cart = array_values(unserialize($this->session->userdata('cart')));
            unset($cart[$index]);
            $this->session->set_userdata('cart', serialize(array_values($cart)));
        }
        $this->buku_list();
    }

public function history_bukuhilang()
{
    // Ambil semua data history
    $all_history = $this->get_history_data();
    // Filter hanya yang tipe_transaksi Buku Hilang
    $history_bukuhilang = array();
    foreach ($all_history as $isi) {
        if (isset($isi['tipe_transaksi']) && $isi['tipe_transaksi'] === 'Buku Hilang') {
            $history_bukuhilang[] = $isi;
        }
    }
    $data['history'] = $history_bukuhilang;
    $data['title_web'] = 'Laporan Buku Hilang';
    $this->load->view('header_view', $data);
    $this->load->view('sidebar_view', $data);
    $this->load->view('transaksi/history_bukuhilang_view', $data);
    $this->load->view('footer_view', $data);
}
	public function print_full_history_view()
	{
		$tanggal_awal = $this->input->get('tanggal_awal');
		$tanggal_akhir = $this->input->get('tanggal_akhir');
		if ($tanggal_awal && $tanggal_akhir) {
			$data['history'] = $this->get_history_data($tanggal_awal, $tanggal_akhir);
			$data['periode'] = 'Periode: '.date('d-m-Y', strtotime($tanggal_awal)).' s/d '.date('d-m-Y', strtotime($tanggal_akhir));
		} else {
			$data['history'] = $this->get_history_data();
			$data['periode'] = '';
		}
		$data['title_web'] = 'Cetak History Transaksi';
		$this->load->view('transaksi/print_full_history_view', $data);
    }

	// Method untuk mengecek apakah item sudah ada di cart
	private function exists($id)
	{
		if(!$this->session->has_userdata('cart')) {
			return -1;
		}
		
		$cart = array_values(unserialize($this->session->userdata('cart')));
		
		foreach($cart as $index => $item) {
			if($item['id'] == $id) {
				return $index;
			}
		}
		
		return -1;
	}

}
