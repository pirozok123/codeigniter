<?php

    //  http://indexing.ctrbonus.ru/statistics


defined('BASEPATH') OR exit('No');

class Statistics extends CI_Controller{

    public $statistic_file = 'statistic_json';

	public function __construct() {
		parent::__construct();
		$this->load->model('statistics_model');
        $this->load->helper('file');
	}
    
    public function saveStatistic(){
        $statfile = $_SERVER['DOCUMENT_ROOT'].'/log/'.$this->statistic_file; 
        $file = fopen($statfile, 'w');
        $result  = $this->statistics_model->count_stat(); 
        $count_all =  $result['count_all'];
        $report_count = $result['report_count'];
        $uniq_prefix = $result['uniq_prefix'];
        $data = array("all_url_24hours" => $count_all,"send_utl_24hours" => $report_count);
        $info = array();
        foreach($uniq_prefix as $uniq){ 
          $info = array_merge($info, array($uniq['prifex'] => $uniq['cnt']));
        }
        $newinfo = array();
        $newinfo["info"] = $info;
        $data = array_merge($data, $newinfo);
        $json = json_encode($data); 
        fwrite($file, $json);
        fclose($file);
        echo json_encode(array("send_utl_24hours" => $data['send_utl_24hours']));
    }

    public function index() {
        session_start();
        error_reporting(0);
        $n_date = date('d-m-Y', time());
        $statfile = $_SERVER['DOCUMENT_ROOT'].'/log/'.$this->statistic_file; 
        $json_body = read_file($statfile);
        $arr = json_decode($json_body);
        $send_utl_24hours = $arr->send_utl_24hours;
        $this->statistics_model->save_statistic($n_date,$send_utl_24hours,$json_body);     
    }
    
    

    
    
    
    


      
    
}

