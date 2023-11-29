<?php

    //  http://indexing.ctrbonus.ru/central


defined('BASEPATH') OR exit('No');

class Central extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model(array('gates_model', 'central_model','send_indexing_model'));
	}
    


    public function index() {
        $kols_links = $this->config->item('number_of_links_to_process');  // количество ссылок для обработки (из конфига)
        $response = file_get_contents('php://input');
        $json = json_decode($response, true);
        if( array_key_exists('get_list_array' , $json) && $json['get_list_array'] == true){
            $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $randoms = $this->central_model->get_randomUrl($kols_links);
                    $randoms = base64_encode(json_encode($randoms));
                    if($randoms) {
                    $rand_key = $this->send_indexing_model->get_rand_key();
                    if($rand_key == "error"){
                        echo json_encode(array('bad response - error key file'));
                        exit();
                    }
                    if(!empty($rand_key)){
                      $data = array("data"  => $randoms, "key_name" => $rand_key);
                      echo json_encode($data);
                    }
                    else 
                       echo json_encode(array('bad response')); 
                   }
                }
            }
        }

        else 
                   echo json_encode(array('bad response'));

    }     
    
}

