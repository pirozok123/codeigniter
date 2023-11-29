<?php

class Central_model extends CI_Model
{
    public function __construct() {
        $this->load->database();
    }

      public function get_randomUrl($kols_links){
        $query = $this->db->query("SELECT * FROM `sitemap_url` WHERE `status` LIKE 'new' ORDER BY rand() LIMIT $kols_links");
        return $query->result_array(); 
      }
    
}