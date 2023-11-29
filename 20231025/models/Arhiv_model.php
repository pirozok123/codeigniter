<?php

class Arhiv_model extends CI_Model
{
	public function __construct() {
		$this->load->database();
	}
    
    public function test(){
        return "Test model - OK <br />";
    }
    

    public function arhiv_row_arry($past_time,$start_time){
        $query = $this->db->query("SELECT * FROM `report_url` WHERE `timestamp_recording` BETWEEN '$past_time' AND '$start_time'");
        return $query->result_array(); 
    }

    public function save_arhiv_row($file_name,$n_date,$start_time){
        $this->db->query("INSERT INTO `arhiv` (`id`, `file_patch`, `timestamp_recording`, `hm_date`) VALUES (NULL, '$file_name', '$start_time', '$n_date')");
        return "ok";
    }

    public function del_old_row($value_id){
        $this->db->query("DELETE FROM `report_url` WHERE `id` = '$value_id'"); 
    }
    
    
    
 }