<?php

class Speed_model extends CI_Model
{
	public function __construct() {
		$this->load->database();
	}
    
    public function get_speed($start_time,$end_time){
        $count = $this->db->count_all("report_url WHERE `timestamp_recording` BETWEEN '$end_time' AND '$start_time'");
        return $count; 
    }



    
 }