<?php

defined('BASEPATH') or exit('No direct script access allowed');

class TindakanPoliGrafik extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('TindakanGrafik_model');
        $this->load->helper('url');
        $this->output->set_content_type('application/json');
    }

    public function grafik()
    {
        $tahun = $this->input->get('tahun');
        $graph_data = $this->TindakanGrafik_model->grafik($tahun);
        // $this->output
        // ->set_content_type('application/json')
        // ->set_status_header(200)
        // ->set_output(json_encode($graph_data));


        // Validasi tahun (opsional)
        if (!is_numeric($tahun) || $tahun < 2000 || $tahun > 2100) {
            $this->output->set_status_header(400);
            echo json_encode(array('error' => 'Invalid year.'));
            return;
        }

        try {

            $formatted_data = array(
                'labels' => array(
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ),
                'datasets' => array()
            );

            $poli_list = array();

            foreach ($graph_data as $data) {
                $poli = $data['nama_poli'];
                if (!isset($poli_list[$poli])) {
                    $poli_list[$poli] = array_fill(0, 12, 0);
                }
                $bulan = intval($data['bulan']) - 1; // Mengubah bulan ke indeks array (0-11)
                $poli_list[$poli][$bulan] = intval($data['jumlah']);
            }

            // Membuat dataset untuk setiap nama_poli
            foreach ($poli_list as $poli => $jumlah_per_bulan) {
                $dataset = array(
                    'label' => $poli,
                    'data' => $jumlah_per_bulan,
                    // 'backgroundColor' => 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).',0.5)', // warna acak
                    // 'borderColor' => 'rgba('.rand(0,255).','.rand(0,255).','.rand(0,255).',1)', // warna acak
                    // 'borderWidth' => 1
                );
                $formatted_data['datasets'][] = $dataset;
            }


            $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($formatted_data));
        } catch (Exception $e) {
            $this->output->set_status_header(500);
            echo json_encode(array('error' => 'Error retrieving data from database.'));
        }
    }
}
