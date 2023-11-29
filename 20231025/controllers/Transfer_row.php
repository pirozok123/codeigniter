<?php

//  https://indexing.ctrbonus.ru/transfer_row

defined('BASEPATH') OR exit('No');

class Transfer_row extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model('transfer_row_model');
	}
    


    public function index() {
        error_reporting(0);

        $response = file_get_contents('php://input');
        $json = json_decode($response, true);
        $data = $json['data'];

        if($json) {
            $script_data = $json['refics'];
            $tupe_data = $json['tupe'];
            if($tupe_data == "send"){
                if(empty($script_data)){
                    $script_data = "";
                }
                if(!empty($script_data)){
                    $this->transfer_row_model->insertReport($data,$script_data);
                    echo json_encode(array("ok"));
                }
            }
            if($tupe_data == "multi"){
                if(empty($script_data)){
                    $script_data = "";
                }
                if(!empty($script_data)){
                    foreach ($data as $value_arry){
                        foreach ($value_arry as $value_row){
                            $this->transfer_row_model->insertMildiReport($value_row,$script_data);
                        }
                    }  
                } 
                echo json_encode(array("ok"));
            }
            exit();
        }


        $this->transfer_row_model->main_limit(); // квота всех ключей
        
        $rand_new_url = $this->transfer_row_model->get_rand_new_url();
        if(!empty($rand_new_url)){
            $rand_error_key = $this->transfer_row_model->rand_error_key();
            if(!empty($rand_error_key)){
                $result_send = $this->test_key($rand_new_url,$rand_error_key);
                if($result_send == "ok"){
                   $this->transfer_row_model->save_result_send($rand_new_url,$rand_error_key); 
                }
            }
        }
        $this->transfer_row_model->fix_limit_key(); // фикс по лимитам
        $this->transfer_row_model->db_cls();    //  чистим бд
    }
    



    public function test_key($url,$rand_key){
        include $_SERVER['DOCUMENT_ROOT'].'/google-api/google-api-php-client/vendor/autoload.php';
        $client = new Google_Client();
        $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'].'/google-api/'.$rand_key);
        $client->addScope('https://www.googleapis.com/auth/indexing');
        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
        if ($action == 'get') {
          $response = $httpClient->get('https://indexing.googleapis.com/v3/urlNotifications/metadata?url=' . urlencode($url));
        } else {
          $content = json_encode([
            'url' => $url,
            'type' => 'URL_UPDATED'
          ]);
          $response = $httpClient->post($endpoint, ['body' => $content]);
        }
        $data['body'] = (string) $response->getBody();
        $result = $data['body'];
        $posRes = strpos($result, "RESOURCE_EXHAUSTED");
        if ($posRes === false) {
            //  none
                $pos = strpos($result, "URL_UPDATED");
                if ($pos === false) {
                    //  none
                } else {
                    return "ok";
                }
        } else {
            return "error";
        }
    }
      
    
}