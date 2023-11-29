<?php

class Streams_model extends CI_Model
{
	public function __construct() {
		$this->load->database();
	}
    
    public function get_streams($start_time,$end_time){
        $query = $this->db->query("SELECT DISTINCT `flow_server` FROM `report_url` WHERE `flow_server` <> '' AND `timestamp_recording` BETWEEN '$end_time' AND '$start_time'");
        return $query->result_array(); 
    }


    
 }