<?php

class Transfer_row_model extends CI_Model
{
	public function __construct() {
		$this->load->database();
	}
    
    //test list
    public function get_rand_test_list(){
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `status` LIKE 'new' ORDER BY rand() LIMIT 350");
        return $query->result_array(); 
    }
    
    public function main_limit($data = 0,$data2 = 0){
        $query = $this->db->query("SELECT * FROM `key_files` ORDER BY `key_files`.`id` DESC");
        $arry = $query->result_array();
        foreach ($arry as $value_arry){
            $project_keys = $value_arry['quotas']; 
            $data = $data + $project_keys;
                $limit_send = $value_arry['limit_send']; 
                $data2 = $data2 + $limit_send;   
        }                 
        $available = $data - $data2;
        $this->db->query("UPDATE `settings` SET `all_quotas`= '$data', `used_quota`= '$data2', `available`= '$available' WHERE `id` = '1'");
    }
    
    public function save_cols_link($xml_map_patch,$x){
        $this->db->query("UPDATE `pars_sitemap` SET `cols`= '$x' WHERE `url` = '$xml_map_patch'"); 
    }

    public function rand_error_key(){
        $query = $this->db->query("SELECT * FROM `key_files` WHERE `limit_send` = `quotas` order BY rand() limit 1");
        $id = $query->result_array();
        return $id[0]['key_path'];  
    }
    
    public function get_rand_new_url(){
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `status` LIKE 'new' ORDER BY rand() limit 1");
        $id = $query->result_array();
        return $id[0]['url'];  
    }
    
    public function save_result_send($rand_url,$rand_key){
        $this->db->query("UPDATE `sitemap_url` SET status = 'ok' WHERE `url` = '$rand_url'");
        $timeShtamp = time();
        $main_data = date('d-m-Y', $timeShtamp);
        $this->db->query("UPDATE `sitemap_url` SET timestamp_recording = '$timeShtamp', hm_date = '$main_data' WHERE `url` = '$rand_url'");
        $query = $this->db->query("SELECT * FROM `key_files` WHERE `key_path` LIKE '$rand_key'");
        $id = $query->result_array();
        $db_quotas = $id[0]['quotas'];  
        $new_quotas = $db_quotas / 5;
        $this->db->query("UPDATE `key_files` SET limit_send = '$new_quotas' WHERE `key_path` = '$rand_key'"); 
    }
    

    public function fix_limit_key(){
        $query = $this->db->query("SELECT * FROM `key_files` WHERE `limit_send` > `quotas`");
        $arry = $query->result_array();
        foreach ($arry as $value_arry){
            $tmp_id = $value_arry['id'];
            $q_keys = $value_arry['quotas']; 
            $this->db->query("UPDATE `key_files` SET limit_send = '$q_keys' WHERE `id` = '$tmp_id'"); 
        } 
    }
    
    public function db_cls(){
        $this->db->query("DELETE FROM `sitemap_url`  WHERE `url` LIKE '%xml version%'");  
        $this->db->query("DELETE FROM `report_url` WHERE `url` LIKE ''"); 
        // дубли ссылок 
        $this->db->query("DELETE `sitemap_url` FROM `sitemap_url` LEFT OUTER JOIN (SELECT MIN(`id`) AS `id`, `url` FROM `sitemap_url` GROUP BY `url`) AS `tmp` ON `sitemap_url`.`id` = `tmp`.`id` WHERE `tmp`.`id` IS NULL");
        //  status - ok
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `status` LIKE 'ok'");
        $arry = $query->result_array();
        foreach ($arry as $value_arry){
            $id = $value_arry['id'];
            if (!empty($id)){
                $prifex = $value_arry['prifex'];
                $get_url = $value_arry['url'];
                $timestamp_recording = time();
                $hm_date = $main_data = date('d-m-Y', $timestamp_recording);
                $this->db->query("INSERT INTO `report_url` (`id`, `url`, `timestamp_recording`, `hm_date`, `prifex`, `flow_server`) VALUES (NULL, '$get_url', '$timestamp_recording', '$hm_date', '$prifex', '001')");
                $this->db->query("DELETE FROM `sitemap_url` WHERE `id` = '$id'");   
            } 
        } 
    }


    public function insertReport($data,$script_data){
        $rand_url = $data['url'];
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `url` LIKE '$rand_url'");
        $id = $query->result_array();
        $main_prifex = $id[0]['prifex'];  
        $prifex = $main_prifex;
        if (empty($prifex)){
            $prifex = $script_data;
        }
        $timestamp_recording = time();
        $hm_date = $main_data = date('d-m-Y', $timestamp_recording);
        $this->db->query("INSERT INTO `report_url` (`id`, `url`, `timestamp_recording`, `hm_date`, `prifex`, `flow_server`) VALUES (NULL, '$rand_url', '$timestamp_recording', '$hm_date', '$prifex', '$script_data')");
        $this->db->query("DELETE FROM `sitemap_url` WHERE `url` = '$rand_url'"); 
   }
   
   public function insertMildiReport($data,$script_data){
        $timestamp_recording = time();
        $hm_date = $main_data = date('d-m-Y', $timestamp_recording);
        $this->db->query("INSERT INTO `report_url` (`id`, `url`, `timestamp_recording`, `hm_date`, `prifex`, `flow_server`) VALUES (NULL, '$data', '$timestamp_recording', '$hm_date', '$script_data', '$script_data')");
        $this->db->query("DELETE FROM `sitemap_url` WHERE `url` = '$data'"); 
   }
    
 }