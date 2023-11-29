<?php

    //  http://indexing.ctrbonus.ru/speed


defined('BASEPATH') OR exit('No');

class Speed extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model('speed_model');
	}
    
    public function index(){
        $strtt_date = time();
        echo $this->speed_model->get_speed($strtt_date,$strtt_date-59);
        
    }     
    
}

