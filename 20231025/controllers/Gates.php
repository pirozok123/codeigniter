<?php

    //  http://indexing.ctrbonus.ru/gates


defined('BASEPATH') OR exit('No');

class Gates extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model(array('gates_model', 'statistics_model'));
	}
    


    public function index() {

        $response = file_get_contents('php://input');
        $json = json_decode($response, true);

        if($json) {

             // echo '<pre>';
       // print_r($json);
       // echo '</pre>';

        if( array_key_exists('get_pin' , $json) && $json['get_pin']== true ){
            $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  
                    echo json_encode(array("pin_code" => $result[0]->pin_code));
            }
        }

          elseif( array_key_exists('tg_bot_key' , $json) && $json['tg_bot_key'] == true){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->gates_model->getTgBotData('tg_bot_key');
                    if(!$result) {
                        echo json_encode(array('bad response'));
                    }
                    else {
                        echo json_encode($result);
                    }
                }
            }
        }

        elseif( array_key_exists('tg_bot_name' , $json) && $json['tg_bot_name'] == true){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->gates_model->getTgBotData('tg_bot_name');
                    if(!$result) {
                        echo json_encode(array('bad response'));
                    }
                    else {
                        echo json_encode($result);
                    }
                }
            }
        }

         elseif( array_key_exists('tg_sender_list' , $json) && $json['tg_sender_list'] == true){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->gates_model->getTgBotData('tg_sender_list');
                    if(!$result) {
                        echo json_encode(array('bad response'));
                    }
                    else {
                        echo json_encode($result);
                    }
                }
            }
        }

        elseif( array_key_exists('edit_tg_bot_key' , $json) && $json['edit_tg_bot_key'] == true && !empty($json['new_tg_bot_key'])){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->gates_model->update_entries(array('tg_bot_key' => $json['new_tg_bot_key'])
                    ); 
                    if(!$result) {
                        echo json_encode(array('bad response'));
                    }
                    else {
                        echo json_encode(array("ok"));
                    }
                }
            }
        }

        elseif( array_key_exists('edit_tg_sender_list' , $json) && $json['edit_tg_sender_list'] == true && !empty($json['new_tg_sender_list'])){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->gates_model->update_entries(array('tg_sender_list' => $json['new_tg_sender_list'])
                    ); 
                    if(!$result) {
                        echo json_encode(array('bad response'));
                    }
                    else {
                        echo json_encode(array("ok"));
                    }
                }
            }
        }


        elseif( array_key_exists('add_sitemap_url' , $json) && $json['add_sitemap_url'] == true && !empty($json['sitemap_url']) && !empty($json['status']) && !empty($json['update_status']) && !empty($json['get_list'])){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->gates_model->siteMapListInsert(array(

                    'sitemap_url' => $json['sitemap_url'],
                    'status' => $json['status'],
                    'update_status' => $json['update_status'],
                    'get_list' => ($json['get_list'] == 'ok' ? $json['get_list'] : 'none')
                    
                      )
                    ); 
                    if(!$result) {
                        echo json_encode(array('bad response'));
                    }
                    else {
                        echo json_encode(array("ok"));
                    }
                }
            }
        }

         elseif( array_key_exists('get_sitemap_url_list' , $json) && $json['get_sitemap_url_list'] == true){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->gates_model->getSitemapList();
                    if(!$result) {
                        echo json_encode(array('bad response'));
                    }
                    else {
                        echo json_encode(array($result));
                    }
                }
            }
        }

         elseif( array_key_exists('edit_sitemap_url_row' , $json) && $json['edit_sitemap_url_row'] == true && !empty($json['sitemap_url']) && !empty($json['status']) && !empty($json['update_status']) && !empty($json['get_list'])){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $sitemap_url = $json['sitemap_url'];
                    $status = $json['status'];
                    $update_status = $json['update_status'];
                    $get_list = $json['get_list'] == 'ok' ? $json['get_list'] : 'none';
                    $result = $this->gates_model->updateSitemapList(array("sitemap_url" => $sitemap_url, "status" => $status, "update_status" => $update_status, 'get_list' => $get_list)); 
                    if(!$result) {
                        echo json_encode(array('bad response'));
                    }
                    else {
                        echo json_encode(array("ok"));
                    }
                }
            }
        }

        elseif( array_key_exists('del_sitemap_url_row' , $json) && $json['del_sitemap_url_row'] == true && !empty($json['sitemap_url'])){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $sitemap_url = $json['sitemap_url'];
                    $result = $this->gates_model->deleteSiteMapList($sitemap_url); 
                    if(!$result) {
                        echo json_encode(array('bad response'));
                    }
                    else {
                        echo json_encode(array("ok"));
                    }
                }
            }
        }


        elseif( array_key_exists('del_key_file' , $json) && $json['del_key_file']== true && !empty($json['key_file_name'])){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->gates_model->deleteRecord($json['key_file_name']);
                    if(!$result) {
                        echo json_encode(array('bad response'));
                    }
                    else {
                        rename($_SERVER["DOCUMENT"]."/google-api/".$json['key_file_name'], $_SERVER["DOCUMENT"]."/google-api/old_".$json['key_file_name']);
                        echo json_encode($result);
                    }
                }
            }
        }

        elseif( array_key_exists('get_statistic_chart' , $json) && $json['get_statistic_chart']== true ){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->statistics_model->get_staticsData(array('graphic'=> true));
                    if(!$result) echo json_encode(array('bad response'));
                    else echo json_encode($result);
                }
            }
        }

        elseif( array_key_exists('get_prefix' , $json) && $json['get_prefix']== true ){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  
                    echo json_encode(array("project_prefix" => $result[0]->project_prefix));
            }
        }

        elseif( array_key_exists('get_statistic_date' , $json) && $json['get_statistic_date']== true ){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->statistics_model->get_staticsData(array('date_list'=> true));
                    if(!$result) echo json_encode(array('bad response'));
                    else echo json_encode($result);
                }
            }
        }

        elseif( array_key_exists('get_statistic_row' , $json) && $json['get_statistic_row']== true ){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $hm_date = $json['date'];
                    $result = $this->statistics_model->get_staticsData(array('json_body' => true, 'hm_date' => $hm_date));
                    if(!$result) echo json_encode(array('bad response'));
                    else 
                    echo json_encode($result);
                }
            }
        }

        elseif( array_key_exists('get_project_domen' , $json) && $json['get_project_domen']== true ){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  
                    echo json_encode(array("project_domen" => $result[0]->project_domen));
            }
        }

        elseif( array_key_exists('get_all_quota' , $json) && $json['get_all_quota'] == true ){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  
                    echo json_encode(array("all_quotas" => $result[0]->all_quotas));
            }
        }

        elseif(array_key_exists('edit_pin', $json) && !empty($json['edit_pin']) ){
             $newpin = $json["edit_pin"];
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $this->gates_model->update_entries(array("pin_code" => $newpin)); 
                    echo json_encode(array("ok"));
                }
            }
        }

        elseif(array_key_exists('edit_prefix', $json) && !empty($json['edit_prefix'])){
             $newprefix = $json['edit_prefix'];
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $this->gates_model->update_entries(array("project_prefix" => $newprefix)); 
                    echo json_encode(array("ok"));
                }
            }
        }

        elseif(array_key_exists('edit_project_domen', $json) && !empty($json['edit_project_domen'])){
             $new_project_domain = $json['edit_project_domen'];
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $this->gates_model->update_entries(array('project_domen' => $new_project_domain)); 
                    echo json_encode(array("ok"));
                }
            }
        }

        elseif(array_key_exists('get_key_files_list', $json) && $json['get_key_files_list']  == true){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->gates_model->get_keyfiles(); 
                    echo json_encode($result);
                }
            }
        }
        
        elseif(array_key_exists('get_all_quotas', $json) && $json['get_all_quotas']  == true){
             $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key)
                    echo json_encode(array('bad response'));
                else  {
                    $result = $this->gates_model->get_quotas_data(); 
                    echo json_encode($result);
                }
            }
        }


          elseif((array_key_exists('add_key', $json) && $json['add_key'] == true) &&
                 (array_key_exists('key_path', $json) && !empty($json['key_path'])) &&
                 (array_key_exists('limit_send', $json) &&  !empty($json['limit_send'])) &&
                 (array_key_exists('quotas', $json) && !empty($json['quotas'])) &&
                 (array_key_exists('access_mail', $json) && !empty($json['access_mail']) ) 

        ){
              $result = $this->gates_model->get_entries($json['key']);
            if(!$result) {
                echo json_encode(array('bad response'));
            }
            else {
                if($json['key'] != $result[0]->md5_key){
                    echo json_encode(array('bad response'));
                }
                else  {
                   
                    $result = $this->gates_model->insert_data(array(

                    'key_path' => $json['key_path'],
                    'limit_send' => $json['limit_send'],
                    'quotas' => $json['quotas'],
                    'access_mail' => $json['access_mail']
                    
                      )
                    ); 
                     
                    if($result) echo json_encode(array("ok"));
                    else echo json_encode(array('bad response'));

                }
            }
        }



        else 
                   echo json_encode(array('bad response'));


    } 

    else echo json_encode(array('bad response'));
    
  }
    
}

