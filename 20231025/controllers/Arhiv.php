<?php

    //  http://indexing.ctrbonus.ru/arhiv


defined('BASEPATH') OR exit('No');

class Arhiv extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model('arhiv_model');
        $this->load->helper('file');
	}
    


    public function index() {
        session_start();
        error_reporting(0);

        
        $start_time = time() - 86400 * 3;  //  сутки
        $past_time = $start_time - 86400 * 2;
        
        $file_name_data = date('d_m_Y_', $start_time); 
        $file_name = "./temp/".$file_name_data.md5(time()).".json";
        $get_arhiv_row_arry = $this->arhiv_model->arhiv_row_arry($past_time,$start_time); 
        if(!empty($get_arhiv_row_arry)){
            $arhiv_data_arry = array(
                "date" => date('d-m-Y', $start_time),
                "count" => count($get_arhiv_row_arry),
                "data" => $get_arhiv_row_arry
            ); 
            $arhiv_data_arry_json = json_encode($arhiv_data_arry); 
            if ( ! write_file($file_name, $arhiv_data_arry_json)){
                    //return 'none'; 
            }else{
                $n_date = date('d-m-Y', $start_time);
                $result = $this->arhiv_model->save_arhiv_row($file_name,$n_date,$start_time); 
                if($result == "ok"){
                    foreach ($get_arhiv_row_arry as $value_data){
                        $x = 0;
                        foreach ($value_data as $value_row){
                            $x++;
                            if($x == 1){
                                if(!empty($value_row)){ 
                                    $this->arhiv_model->del_old_row($value_row);
                                } 
                            }
                        }
                    }
                }
            }
        }

        
    }



      
    
}

