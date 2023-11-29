<?php

    //  http://indexing.ctrbonus.ru/streams


defined('BASEPATH') OR exit('No');

class Streams extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model('streams_model');
	}
    
    public function index(){
        $stack = array();
        $strtt_date = time();
        $streams_arr = $this->streams_model->get_streams($strtt_date,$strtt_date-90000);
        echo count($streams_arr);
    }     
    
}

