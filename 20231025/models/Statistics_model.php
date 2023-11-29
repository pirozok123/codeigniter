<?php

class Statistics_model extends CI_Model
{
	public function __construct() {
		$this->load->database();
	}
    
    public function test(){
        return "Test model - OK <br />";
    }
    
    public function cound_all_row($start_timestamp,$end_timestamp){
        $countSend = $this->db->count_all("report_url WHERE `timestamp_recording` BETWEEN '$start_timestamp' AND '$end_timestamp'");
        $countGet = $this->db->count_all("sitemap_url WHERE `timestamp_recording` BETWEEN '$start_timestamp' AND '$end_timestamp'");
        return $countSend + $countGet; 
    }

    public function cound_received_row($start_timestamp,$end_timestamp){
        $count = $this->db->count_all("report_url WHERE `timestamp_recording` BETWEEN '$start_timestamp' AND '$end_timestamp'");
        return $count; 
    }
    
    public function get_prifex_arry(){
        $query = $this->db->query("SELECT DISTINCT `prifex` FROM pars_sitemap");
        return $query->result_array(); 
    }
    
    public function count_prifex_row($start_timestamp,$end_timestamp,$db_prifex){
        $count = $this->db->count_all("report_url  WHERE `prifex` LIKE '$db_prifex' and `timestamp_recording` BETWEEN '$start_timestamp' AND '$end_timestamp'");
        return $count; 
    }

    public function count_stat(){

        $date = strtotime(date('Y-m-d H:i:s'));

        $count_all = 0;

        $count_all += $this->db->count_all("report_url  WHERE `timestamp_recording` BETWEEN (".$date." - 86400) AND ".$date."");
        $count_all += $this->db->count_all("sitemap_url  WHERE `timestamp_recording` BETWEEN (".$date." - 86400) AND ".$date."");

        $report_count = $this->db->count_all("report_url  WHERE `timestamp_recording` BETWEEN (".$date." - 86400) AND ".$date."");

        $this->db->select('prifex, count(id) as cnt');
        $this->db->where('timestamp_recording >=', $date - 86400);
        $this->db->group_by('prifex');
        //$this->db->order_by('id', 'DESC');
        $query = $this->db->get('report_url');

        return array("count_all" => $count_all, "report_count" => $report_count, "uniq_prefix" => $query->result_array()); 
    }
    
    public function save_statistic($n_date,$send_url_cols,$json_body){
        $this->db->query("INSERT INTO `statistics` (`id`, `hm_date`, `send_url`, `json_body`) VALUES (NULL, '$n_date', '$send_url_cols', '$json_body')");
    }

    public function get_staticsData($query){

            if(array_key_exists('date_list', $query))
              $this->db->select("hm_date"); 

            elseif(array_key_exists('json_body', $query)){
              $this->db->select("json_body");
              $this->db->where(array('hm_date' => $query['hm_date']));
            }

            elseif(array_key_exists('graphic', $query)){
              $this->db->select("hm_date, send_url");
              $this->db->order_by("hm_date", "ASC");
            }
            

            $query = $this->db->get('statistics');
            return $query->result_array();

        }

 }