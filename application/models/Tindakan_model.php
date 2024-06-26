<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tindakan_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all($limit, $offset, $nama_poli = null)
    {
        $this->db->select("*");
        $this->db->from("tindakan_poli");
        $this->db->limit($limit, $offset);

        if ($nama_poli !== null) {
            $this->db->where('nama_poli', $nama_poli);
        }
        $this->db->order_by("tanggal", "DESC");
        $query = $this->db->get();
        return $query;
    }

    public function count_tindakan($nama_poli = null)
    {
        $this->db->from('tindakan_poli');
        
        if ($nama_poli !== null) {
            $this->db->where('nama_poli', $nama_poli);
        }
        return $this->db->count_all_results();
    }
}
