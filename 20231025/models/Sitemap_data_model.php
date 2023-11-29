<?php

class Sitemap_data_model extends CI_Model
{
	public function __construct() {
		$this->load->database();
	}
    
    public function test(){
        return "Test model - OK <br />";
    }
    
    public function save_new_url($new_url,$time_shtamp,$main_data,$prifex){
        $this->db->query("INSERT INTO `sitemap_url` (`id`, `url`, `status`, `timestamp_recording`, `hm_date`, `prifex`) VALUES (NULL, '$new_url', 'new', '$time_shtamp', '$main_data', '$prifex')");
	}


    
    
 }