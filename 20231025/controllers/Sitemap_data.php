<?php

    //  http://indexing.ctrbonus.ru/sitemap_data


defined('BASEPATH') OR exit('No');

class Sitemap_data extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model('sitemap_data_model');
	}
    
    public function index(){
        error_reporting(0);
        
        $sitemap_data_tue = $this->input->post('sitemap_data');
        if(!empty($sitemap_data_tue)){
            $prifex = $this->input->post('prifex');
            if(!empty($prifex)){
                $prifex = trim($prifex);
                $links_arry = $this->input->post('links_arry');
                if(!empty($links_arry)){
                    foreach ($links_arry as $new_url){
                        $new_url = trim($new_url);
                        if(!empty($new_url)){
                            $time_shtamp = time();
                            $main_data = date('d-m-Y', $time_shtamp);
                            $this->sitemap_data_model->save_new_url($new_url,$time_shtamp,$main_data,$prifex);
                        }
                    }
                }    
            }   
        }
        
    }     
    
}

