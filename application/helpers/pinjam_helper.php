<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('format_pinjam_id')) {
    function format_pinjam_id($pinjam_id, $tgl_pinjam) {
        // Format: PJ-YYYYMM-XXXX
        // PJ = Prefix Pinjam
        // YYYYMM = Tahun dan Bulan Pinjam
        // XXXX = Nomor Urut
        
        $date = new DateTime($tgl_pinjam);
        $yearMonth = $date->format('Ym');
        
        // Ambil nomor urut dari pinjam_id (asumsi pinjam_id adalah angka)
        $urut = str_pad($pinjam_id, 4, '0', STR_PAD_LEFT);
        
        return "PJ-{$yearMonth}-{$urut}";
    }
}
