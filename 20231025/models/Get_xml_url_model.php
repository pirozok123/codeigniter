<?php

class Get_xml_url_model extends CI_Model
{
	public function __construct() {
		$this->load->database();
	}
    
    public function test(){
        return "Test model - OK <br />";
    }
    
    public function test_db(){
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `status` = 'test'");
        $id = $query->result_array();
        $id = $id[0];
        return $id['url']; 
    }

    public function test_dubl_url($test_url){
        $count_xml = $this->db->count_all("sitemap_url WHERE `url` LIKE '$test_url'");
        if($count_xml > 0){
            return "error";
        }
        if($count_xml < 1){
            $count_report = $this->db->count_all("report_url WHERE `url` LIKE '$test_url'");
            if($count_report > 0){
                return "error";
            }
        }
        //return $count_xml;
	}
    
    public function save_new_url($test_url,$time_shtamp,$main_data,$prifex){
        $this->db->query("INSERT INTO `sitemap_url` (`id`, `url`, `status`, `timestamp_recording`, `hm_date`, `prifex`) VALUES (NULL, '$test_url', 'new', '$time_shtamp', '$main_data', '$prifex')");
	}
    
    public function reset_limit(){
        $this->db->query("UPDATE `key_files` SET limit_send = '1'");
    }
    
    public function main_limit(){
        $query = $this->db->query("SELECT * FROM `key_files` ORDER BY `key_files`.`id` DESC");
        $arry = $query->result_array();
        $data = 0;
        foreach ($arry as $value_arry){
            $project_keys = $value_arry['quotas']; 
            $data = $data + $project_keys;
        }  
        $this->db->query("UPDATE `settings` SET `all_quotas`= '$data' WHERE `id` = '1'"); 
    }

    public function get_xml_list(){
        $query = $this->db->query("SELECT * FROM `pars_sitemap` ORDER BY `pars_sitemap`.`id` ASC");
        return $query->result_array();       
    }
    
        
    public function xml_url_test($sitemap_url,$prifex,$row_cont){
        $query = $this->db->query("SELECT * FROM `pars_sitemap` WHERE `url` LIKE '$sitemap_url'");
        $id = $query->result_array();
        $id = $id[0];
        if(!empty($id['url'])){
            $this->db->query("UPDATE `pars_sitemap` SET `cols`= '$row_cont' WHERE `url` LIKE '$sitemap_url'"); 
        }else{
            $main_data = date('d-m-Y',  time());
            $this->db->query("INSERT INTO `pars_sitemap` (`id`, `url`, `prifex`, `cols`, `hm_date`) VALUES (NULL, '$sitemap_url', '$prifex', '$row_cont', '$main_data')");
        }
    }
    
    public function new_map_url($new_url,$prifex){
            $timeshtamp = time();
            $main_data = date('d-m-Y', $timeshtamp);
            $this->db->query("INSERT INTO `sitemap_url` (`id`, `url`, `status`, `timestamp_recording`, `hm_date`, `prifex`) VALUES (NULL, '$new_url', 'new', '$timeshtamp', '$main_data', '$prifex')");
            return "ok";
       
    }
    
    public function get_slug_factory_arry_num(){
        $query = $this->db->query("SELECT * FROM `pars_sitemap` WHERE `prifex` LIKE '%slug-factory%'");
        $arry = $query->result_array(); 
            $value_arr = array();
            foreach ($arry as $slug_factory_row){
                $value = str_replace("slug-factory", "", $slug_factory_row['prifex']);
                array_push($value_arr, $value);   
            }
            krsort($value_arr);   
        return $value_arr;
    }
    
    public function test_slug_factory_prifex($new_RAND_prifex){
        $timeShtamp = time();
        $timeStart = $timeShtamp - 8500;
        $query = $this->db->query("SELECT * FROM `report_url` WHERE `timestamp_recording` BETWEEN '$timeStart' AND '$timeShtamp' AND `prifex` LIKE '$new_RAND_prifex'");
        $id = $query->result_array();
        $id = $id[0]; 
        if(empty($id['url'])){
            return "ok";    
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
 }