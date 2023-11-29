<?php

//  https://indexing.ctrbonus.ru/load_key

defined('BASEPATH') OR exit('No');

class Load_key extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model('load_key_model');
        $this->load->helper('file');
	}
    


    public function index() {
        session_start();
        error_reporting(0);

        //$test = $this->load_key_model->test();    
        //if(!empty($test)){
        //    echo $test;   //  test
        //} 

        $load_form = $this->input->post('load_form');
        if(empty($load_form)){
            echo '<br />
            <form enctype="multipart/form-data" action="#" method="POST">
                <input type="hidden" name="load_form" value="true" />
                <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                Ключ Файл (JSON): <input name="keyfile" type="file" />
                <input type="submit" value="Загрузить файл" />
            </form>'; 
        }else{
            $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/dashboard/google-api/';
            $uploadfile = $uploaddir . basename($_FILES['keyfile']['name']);
            if (move_uploaded_file($_FILES['keyfile']['tmp_name'], $uploadfile)) {
                $load_file_name = $_FILES['keyfile']['name'];
                $test_get_rand_url = "https://dzen.ru/".rand(123,456);
                $result_test_file = $this->postKeysindexingPageSend($test_get_rand_url,$load_file_name);  
                if($result_test_file == "ok"){
                    $this->load_key_model->save_result_send($test_get_rand_url);

//dzen-ru2e6a37fc8abc921504-db0594682ec4.json
//dzen-ru2e6a37fc8abc921504@dzen-ru2e6a37fc8abc921504.iam.gserviceaccount.com     

//dzen-rubb-064c8e11918e.json
//dzen-rubb@dzen-rubb.iam.gserviceaccount.com  

$new_id = "";
$tmp_file_name = $load_file_name;
$tmp_file_name = str_replace(".json", "", "$tmp_file_name");
$new_array = array_filter(explode("-", $tmp_file_name));
array_pop($new_array);
foreach ($new_array as $value){
$new_id .= $value."-";
}
$new_id = substr("$new_id", 0, -1);
            
$access_mail= $new_id."@".$new_id.".iam.gserviceaccount.com";
                    
                    //$this->load_key_model->save_new_key($load_file_name,$access_mail);
                    echo '<script>alert( "Ключ загружен успешно!" ); document.location.href="https://indexing.ctrbonus.ru/load_key";</script>';
                    exit();
                }  
                if($result_test_file == "error"){
                    // ошибка работы ключа
                    echo '<script>alert( "ERROR - ошибка работы ключа!" ); document.location.href="https://indexing.ctrbonus.ru/load_key";</script>';
                    exit();
                }             
            } else {
                echo "ERROR - ошибка загрузки!\n";
            }
        }


        
    }
    
    
    
    public function postKeysindexingPageSend($url,$rand_key){
        session_start();
        error_reporting(0);
        include $_SERVER['DOCUMENT_ROOT'].'/google-api/google-api-php-client/vendor/autoload.php';
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
    