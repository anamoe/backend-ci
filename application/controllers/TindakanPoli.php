
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TindakanPoli extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tindakan_model');
        $this->load->library('pagination');
        $this->load->helper('url');
    }

    public function get_all()
    {
        $nama_poli = $this->input->get('nama_poli');

        if ($nama_poli) {
            $encoded_nama_poli = urlencode($nama_poli);

            $config['base_url'] = base_url('TindakanPoli/get_all?nama_poli=' . $encoded_nama_poli);
        }

      
        $config['total_rows'] = $this->Tindakan_model->count_tindakan($nama_poli);
        $config['per_page'] = 10;
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';

        $this->pagination->initialize($config);
        $page = $this->input->get('page');
        if ($page === null || !ctype_digit($page)) {
            $page = 1;
        }
        $offset = ($page - 1) * $config['per_page'];

        $data['tindakan'] = $this->Tindakan_model->get_all($config['per_page'], $offset, $nama_poli);

        $response = [
            'text' => '200',
            'type' => 'success',
            'data' => $data['tindakan']->result_array(),
            'pagination' => $this->pagination->create_links()
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }
}
