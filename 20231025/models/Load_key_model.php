<?php

class Load_key_model extends CI_Model
{
	public function __construct() {
		$this->load->database();
	}
    
    public function test(){
        return "Test model - OK <br />";
    }
    
    public function get_rand_url(){
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `status` LIKE 'new' ORDER BY rand() LIMIT 1");
        $id = $query->result_array();
        return $id[0]['url'];  
    }


    public function save_result_send($rand_url){ 
        $this->db->query("UPDATE `sitemap_url` SET status = 'ok' WHERE `url` = '$rand_url'");
        $timeShtamp = time();
        $main_data = date('d-m-Y', $timeShtamp);
        $this->db->query("UPDATE `sitemap_url` SET timestamp_recording = '$timeShtamp' WHERE `url` = '$rand_url'");
        $this->db->query("UPDATE `sitemap_url` SET hm_date = '$main_data' WHERE `url` = '$rand_url'");
    }
    
    public function save_new_key($load_file_name,$access_mail){
        $this->db->query("INSERT INTO `key_files` (`id`, `key_path`, `limit_send`, `quotas`, `access_mail`) VALUES (NULL, '$load_file_name', '2', '200', '$access_mail')");
    }
    
    
 }