<?php

class Gates_model extends CI_Model
{
	public function __construct() {
		$this->load->database();
	}

	    public function get_entries($code)
        {
            $query = $this->db->get_where('settings', array('md5_key' => $code));
            return $query->result();
        }

        public function getTgBotData($data)
        {
            $this->db->select($data);
            $this->db->from("settings");
            $query = $this->db->get();
            return $query->result_array();
        }

      public function getSitemapList()
        {
            $this->db->select("*");
            $this->db->from("sitemap_list");
            $query = $this->db->get();
            return $query->result();
        }


      public function get_keyfiles()
        {   
            $this->db->select("*");
            $this->db->from("key_files");
            $query = $this->db->get();
            return $query->result();
        }

      public function updateSitemapList($data)
        {
          $this->db->where("sitemap_url", $data["sitemap_url"]);
          $this->db->set("status", $data["status"]);
          $this->db->set("update_status", $data["update_status"]);
          $this->db->set("get_list", $data["get_list"]);
          $this->db->update('sitemap_list'); 
        }   

      public function update_entries($data)
      {
        $key = array_keys($data)[0];
        $value = array_values($data)[0];
        $this->db->set($key, $value);
        $this->db->update('settings'); 
      }   
          
      public function siteMapListInsert($data){

       $data = array(
          'sitemap_url' => $data['sitemap_url'],
          'status' => $data['status'],
          'update_status' => $data['update_status'],
          'get_list' => $data['get_list'],
	    );

        return $this->db->insert('sitemap_list', $data);
      }

        public function insert_data($data){

       $data = array(
          'key_path' => $data['key_path'],
          'limit_send' => $data['limit_send'],
          'quotas' => $data['quotas'],
          'access_mail' => $data['access_mail'],
      );

        return $this->db->insert('key_files', $data);
      }

      public function deleteRecord($keyPath){

        $this->db->where('key_path', $keyPath);
        return $this->db->delete('key_files');
      }

      public function deleteSiteMapList($siteMapUrl){

        $this->db->where('sitemap_url', $siteMapUrl);
        return $this->db->delete('sitemap_list');
      }
    
    
    public function get_quotas_data(){
        $query = $this->db->query("SELECT * FROM `settings` WHERE `id` = 1");
        $data = $query->result_array();
        return array('all_quotas' =>$data[0]['all_quotas'],'used_quota' =>$data[0]['used_quota'],'available_quota' =>$data[0]['available']);
    }
    
  
    
    
    
}