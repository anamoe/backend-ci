<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TindakanGrafik_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function grafik($tahun)
    {
        $query = $this->db->query("
            SELECT nama_poli, 
                   MONTH(tanggal) AS bulan, 
                   YEAR(tanggal) AS tahun, 
                   COUNT(id_tindakan) AS jumlah
            FROM tindakan_poli
            WHERE YEAR(tanggal) = ?
            GROUP BY nama_poli, bulan,tahun
        ", array($tahun));

        return $query->result_array();
    }
}
