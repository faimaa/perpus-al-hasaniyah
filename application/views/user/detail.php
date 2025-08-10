<?php
error_reporting(0);
    if(!empty($_GET['download'] == 'doc')){
        header("Content-Type: application/vnd.ms-word");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("content-disposition: attachment;filename=".date('d-m-Y')."_laporan_rekam_medis.doc");
    }
    if(!empty($_GET['download'] == 'xls')){
        header("Content-Type: application/force-download");
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");
        header("content-disposition: attachment;filename=".date('d-m-Y')."_laporan_rekam_medis.xls");
    }
?>
<?php
        $tgla = $user->tgl_bergabung;
        $tglk = $user->tgl_lahir;
        $bulan = array(
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        );
    
        $array1=explode("-",$tgla);
        $tahun=$array1[0];
        $bulan1=$array1[1];
        $hari=$array1[2];
        $bl1 = $bulan[$bulan1];
		$tgl1 = $hari.' '.$bl1.' '.$tahun;
		

        $array2=explode("-",$tglk);
        $tahun2=$array2[0];
        $bulan2=$array2[1];
        $hari2=$array2[2];
        $bl2 = $bulan[$bulan2];
        $tgl2 = $hari2.' '.$bl2.' '.$tahun2;
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" href="<?php echo base_url();?>assets_style/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo base_url();?>assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css">
		<title><?= $title_web;?></title>
		<style>
			body {
				background: rgba(0,0,0,0.2);
			}
			page[size="A4"] {
				background: white;
				width: 21cm;
				height: 29.7cm;
				display: block;
				margin: 0 auto;
				margin-bottom: 0.5pc;
				box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
				padding-left:2.54cm;
				padding-right:2.54cm;
				padding-top:1.54cm;
				padding-bottom:1.54cm;
			}
			@media print {
				body, page[size="A4"] {
					margin: 0;
					box-shadow: 0;
				}
			}
		</style>
	</head>
	<body>
        <div class="container">
            <br/> 
            <div class="pull-left">
                Codekop - Preview HTML to DOC [ size paper A4 ]
            </div>
            <div class="pull-right"> 
            <button type="button" class="btn btn-success btn-md" onclick="printDiv('printableArea')">
                <i class="fa fa-print"> </i> Print File
            </button>
            </div>
        </div>
        <br/>
        <div id="printableArea">
            <page size="A4">
                <div style="max-width:370px;margin:48px auto;padding:32px 28px 24px 28px;border-radius:22px;box-shadow:0 8px 32px 0 rgba(31,38,135,0.18);background:linear-gradient(135deg,#2193b0 0%,#6dd5ed 100%);border:1.5px solid #6dd5ed;backdrop-filter:blur(8px);position:relative;overflow:hidden;">
                    <div style="text-align:center;margin-bottom:18px;">
                        <div style="font-family:'Montserrat',sans-serif;font-size:20px;font-weight:700;letter-spacing:1.5px;color:#2d3a4b;">KARTU ANGGOTA</div>
                        <div style="font-size:13px;color:#6a4b8a;font-weight:500;margin-bottom:8px;">PERPUSTAKAAN AL-HASANIYAH</div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:center;position:relative;">
                        <div style="width:110px;height:110px;display:flex;align-items:center;justify-content:center;position:relative;margin-bottom:10px;">
                            <div style="position:absolute;top:0;left:0;width:110px;height:110px;border-radius:50%;background:conic-gradient(from 180deg at 50% 50%, #ffd700 0%, #fffbe0 30%, #ffd700 60%, #fffbe0 100%);box-shadow:0 0 16px 4px #ffe066,0 2px 8px rgba(0,0,0,0.10);filter:blur(0.5px);"></div>
                            <?php echo get_user_photo($user->foto, $user->nama, 'lg'); ?>
							</div>
                        <div style="font-size:19px;font-weight:700;color:#2d3a4b;letter-spacing:0.5px;margin-bottom:2px;z-index:4;">
                            <?= $user->nama; ?>
							</div>
                        <div style="font-size:12px;color:#6a4b8a;font-weight:600;letter-spacing:1px;margin-bottom:10px;z-index:4;">Anggota Aktif</div>
						</div>
                    <table style="width:100%;font-size:13px;color:#2d3a4b;margin-bottom:10px;">
                        <tr><td style="width:110px;">ID Anggota</td><td>:</td><td><?= $user->id_login;?></td></tr>
                        <tr><td>TTL</td><td>:</td><td><?= $user->tempat_lahir;?>, <?= $tgl2 ;?></td></tr>
                        <tr><td>Alamat</td><td>:</td><td><?= $user->alamat;?></td></tr>
                        <tr><td>Bergabung</td><td>:</td><td><?= $tgl1;?></td></tr>
                        <tr><td>Masa Berlaku</td><td>:</td><td><?= date('d M Y', strtotime($user->tgl_bergabung.' +3 years'));?></td></tr>
                    </table>
                    <div style="margin:10px 0 0 0;display:flex;justify-content:space-between;align-items:center;">
                        <div style="font-size:11px;color:#6a4b8a;font-weight:600;letter-spacing:1px;">Status: Aktif</div>
                        <div style="font-size:11px;color:#2d3a4b;">ID: <?= $user->id_login;?></div>
					</div>
				</div>
            </page>
        </div>
  </body>
  <script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
  </script>
</html>
