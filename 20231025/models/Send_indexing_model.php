<?php

class Send_indexing_model extends CI_Model
{
	public function __construct() {
		$this->load->database();
	}
    
    public function test(){
        return "Test model - OK <br />";
    }
    
   	public function get_rand_key(){
        $query = $this->db->query("SELECT * FROM `key_files` WHERE `limit_send` < `quotas` order by rand() LIMIT 1");
        $id = $query->result_array();
        if(!empty($id[0]['key_path'])){
            return $id[0]['key_path'];   
        }else{
            return "error";
        }
	}
    
   	public function get_rand_max_key(){
        $query = $this->db->query("SELECT * FROM `key_files` WHERE `limit_send` < `quotas` ORDER BY `key_files`.`quotas` DESC limit 1");
        $id = $query->result_array();
        return $id[0]['key_path'];
	}

   	public function get_rand_url(){
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `status` LIKE 'new' ORDER BY `id` DESC limit 1");
        $id = $query->result_array();
        return $id[0]['url'];   
	}

   	public function get_prifex_url($rand_url){
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `url` LIKE '$rand_url'");
        $prefix = $query->result_array();
        return $prefix[0]['prifex'];   
	}
    
   	public function get_new_DB_rand_url(){
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `status` LIKE 'new' ORDER BY rand() limit 1");
        $id = $query->result_array();
        return $id[0]['url'];   
	}
    
    public function save_result_send($rand_url,$rand_key,$prifex){
        if(!empty($rand_url)){
            $timestamp_recording = time();
            $hm_date = $main_data = date('d-m-Y', $timestamp_recording);
            $this->db->query("INSERT INTO `report_url` (`id`, `url`, `timestamp_recording`, `hm_date`, `prifex`, `flow_server`) VALUES (NULL, '$rand_url', '$timestamp_recording', '$hm_date', '$prifex', '01')");
            $this->db->query("DELETE FROM `sitemap_url` WHERE `url` = '$rand_url'");   
            $this->db->query("UPDATE `key_files` SET limit_send = limit_send + 1 WHERE `key_path` = '$rand_key'");        
        }
    }
    
    public function get_transfer_url($rand_url, $id = 0){
        if(!empty($rand_url)){
            $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `url` LIKE '$rand_url'");
            $data = $query->result_array();
            $id = $data[0]['id'];
            if (!empty($id)){
                $prifex = $data[0]['prifex'];
                $new_rand_url = $data[0]['url'];
                $timestamp_recording = time();
                $hm_date = $main_data = date('d-m-Y', $timestamp_recording);
                $this->db->query("INSERT INTO `report_url` (`id`, `url`, `timestamp_recording`, `hm_date`, `prifex`, `flow_server`) VALUES (NULL, '$new_rand_url', '$timestamp_recording', '$hm_date', '$prifex', '01')");
                $this->db->query("DELETE FROM `sitemap_url` WHERE `id` = '$id'");   
            } 
        }
    }
    
    public function error_limit_send($rand_key){
        $query = $this->db->query("SELECT * FROM `key_files` WHERE `key_path` LIKE '$rand_key'");
        $id = $query->result_array();
        $quotas = $id[0]['quotas']-5;   
        $this->db->query("UPDATE `key_files` SET limit_send = '$quotas' WHERE `key_path` = '$rand_key'");
    }
    
    public function update_key($rand_url,$rand_key){
        $query = $this->db->query("SELECT * FROM `key_files` WHERE `key_path` LIKE '$rand_key'");
        $id = $query->result_array();
        $quotas = $id[0]['quotas']/4;   
        $this->db->query("UPDATE `key_files` SET limit_send = '$quotas' WHERE `key_path` = '$rand_key'");
        $timeShtamp = time();
        $main_data = date('d-m-Y', $timeShtamp);
        $this->db->query("UPDATE `sitemap_url` SET status = 'ok', timestamp_recording = '$timeShtamp', hm_date = '$main_data' WHERE `url` = '$rand_url'");   
    }
    
    public function db_arr_url(){
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `status` LIKE 'new' ORDER BY `id` DESC limit 50");
        $data = $query->result_array();
        return array($data[0]['url'],$data[1]['url'],$data[2]['url'],$data[3]['url'],$data[4]['url'],$data[5]['url'],$data[6]['url'],$data[7]['url'],$data[8]['url'],$data[9]['url'],$data[10]['url'],$data[11]['url'],$data[12]['url'],$data[13]['url'],$data[14]['url'],$data[15]['url'],$data[16]['url'],$data[17]['url'],$data[18]['url'],$data[19]['url'],$data[20]['url'],$data[21]['url'],$data[22]['url'],$data[23]['url'],$data[24]['url'],$data[25]['url'],$data[26]['url'],$data[27]['url'],$data[28]['url'],$data[29]['url'],$data[30]['url'],$data[31]['url'],$data[32]['url'],$data[33]['url'],$data[34]['url'],$data[35]['url'],$data[36]['url'],$data[37]['url'],$data[38]['url'],$data[39]['url'],$data[40]['url'],$data[41]['url'],$data[42]['url'],$data[43]['url'],$data[44]['url'],$data[45]['url'],$data[46]['url'],$data[47]['url'],$data[48]['url'],$data[49]['url']);
    }
    
    
 }