<?php

//  https://indexing.ctrbonus.ru/send_indexing

defined('BASEPATH') OR exit('No');

class Send_indexing extends CI_Controller{
    
	public function __construct() {
		parent::__construct();
		$this->load->model('send_indexing_model');
	}
    
    public function google_api_vendor(){
        ob_start();
        include $_SERVER['DOCUMENT_ROOT'].'/google-api/google-api-php-client/vendor/autoload.php';
        return ob_get_clean();
    }

    public function index($x = 0) {
        $number_of_links = $this->config->item('number_of_links_to_process_server');
        while ($x<$number_of_links){
            $x++;
            $rand_key = $this->send_indexing_model->get_rand_key();  
            if(!empty($rand_key)){
                $rand_url = $this->send_indexing_model->get_rand_url();  
                if(!empty($rand_url)){
                    $result_send = $this->postKeysindexingPageSend($rand_url,$rand_key);
                    if($result_send == "error"){
                        $this->send_indexing_model->error_limit_send($rand_key);
                    }
                    if($result_send == "ok"){
                        $rand_url = trim($rand_url);
                        if(!empty($rand_url)){
                            $prifex_url = $this->send_indexing_model->get_prifex_url($rand_url);
                            if(!empty($prifex_url)){
                                $this->send_indexing_model->save_result_send($rand_url,$rand_key,$prifex_url); 
                            }
                        }
                    }
                }
            }
        }
        $this->keys_limit_fix();
    }
    
    public function keys_limit_fix(){
        error_reporting(0);
        $rand_url = $this->send_indexing_model->get_rand_url();
        $rand_key = $this->send_indexing_model->get_rand_max_key(); 
        $result_send = $this->postKeysindexingPageSend($rand_url,$rand_key);
        if($result_send = "ok"){
            $this->send_indexing_model->update_key($rand_url,$rand_key); 
        }else{
            $rand_url = $this->send_indexing_model->get_rand_url();
            $rand_key = $this->send_indexing_model->get_rand_max_key(); 
            $result_send = $this->postKeysindexingPageSend($rand_url,$rand_key);
            if($result_send = "ok"){
                $this->send_indexing_model->update_key($rand_url,$rand_key); 
            }else{
                $rand_url = $this->send_indexing_model->get_rand_url();
                $rand_key = $this->send_indexing_model->get_rand_max_key(); 
                $result_send = $this->postKeysindexingPageSend($rand_url,$rand_key);
                if($result_send = "ok"){
                    $this->send_indexing_model->update_key($rand_url,$rand_key); 
                }else{
                    exit();
                }
            }  
        }
    }

    public function postKeysindexingPageSend($url,$rand_key){
        error_reporting(0);
        echo  $this->google_api_vendor();
        $client = new Google_Client();
        $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'].'/google-api/'.$rand_key);
        $client->addScope('https://www.googleapis.com/auth/indexing');
        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
        $content = json_encode([
            'url' => $url,
            'type' => 'URL_UPDATED'
        ]);
        $response = $httpClient->post($endpoint, ['body' => $content]);
        $data['body'] = (string) $response->getBody();
        $pos = strpos($data['body'], "URL_UPDATED");
        if ($pos === false) {
            return "error";
        } else {
            return "ok";
        }
    }
      
    
}