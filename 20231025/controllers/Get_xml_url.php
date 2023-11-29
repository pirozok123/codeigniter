<?php

    //  https://indexing.ctrbonus.ru/get_xml_url


defined('BASEPATH') OR exit('No');

class Get_xml_url extends CI_Controller{
	public function __construct() {
		parent::__construct();
		$this->load->model('get_xml_url_model');
	}


    public function index() {
        error_reporting(0);
      
        //  https://indexing.ctrbonus.ru/get_xml_url?daily=true
        $prifex_key = $this->input->get('daily');  
        if(!empty($prifex_key)){
            $sitemap_url = "https://dzen.ru/sitemaps/daily/daily-sitemap-1.xml";
            $prifex = "daily";
            $this->start_xml_parsing($sitemap_url,$prifex);
        }
        
        //  https://indexing.ctrbonus.ru/get_xml_url?native=true
        $prifex_key = $this->input->get('native');  
        if(!empty($prifex_key)){
            $url = "https://dzen.ru/sitemaps/native/sitemap.xml";
            $xml_content = file_get_contents($url);
            if(!empty($xml_content)){
                $xml_arry = explode("<loc>", $xml_content);
                $loc_arry = $xml_arry[1];
                $xml_url_arry = explode("</loc>", $loc_arry);
                $xml_url = $xml_url_arry[0];
            }
            $sitemap_url = trim($xml_url);
            $prifex = "native_".str_replace("https://dzen.ru/sitemaps/native/native-sitemap-", "", str_replace(".xml", "", "$sitemap_url"));
            $this->start_xml_parsing($sitemap_url,$prifex);
        }
        
        
        //  https://indexing.ctrbonus.ru/get_xml_url?slug_factory=true
        $slug_factory = $this->input->get('slug_factory');  
        if(!empty($slug_factory)){    
            $last_numb_file = 275;
            $new_url = $this->findLastFile($last_numb_file);
            if(!empty($new_url)){ 
                
                $new_numb = str_replace("https://dzen.ru/sitemaps/slug-factory/slug-factory-sitemap-", "", str_replace(".xml", "", "$new_url")) - 1;
                $new_prifex = trim("slug-factory".$new_numb);
                    $this->start_xml_parsing($new_url,$new_prifex);
                    

                        
            }
                
        }

       
    }
    
    
    public function findLastFile($minRange = 0, $maxRange = 750) {
    
        define("BASE_URL", 'https://dzen.ru/sitemaps/slug-factory/');
    
        $mid = floor(($minRange + $maxRange) / 2); 
        $fileName = "slug-factory-sitemap-$mid.xml";
    
        $text = @file_get_contents(BASE_URL.$fileName,false,null,0,400);
        $isExistFile = false;
        if ($text === false || strpos($text, '<loc>') === false) {
            $maxRange = $mid-1;
        } else {
            $minRange = $mid+1;
            $isExistFile = true;
        }
    
        if ($maxRange - $minRange <= 0) {
            if ($isExistFile) {
                return BASE_URL.$fileName;
            } else {
                $mid--;
                return BASE_URL."slug-factory-sitemap-$mid.xml";
            }
        }
    
        return $this->findLastFile($minRange, $maxRange);
    }
        
    
    public function start_xml_parsing($sitemap_url,$prifex){
        error_reporting(0);
        $sitemap_arry = $this->pars_xml_map($sitemap_url);
        $row_cont = count($sitemap_arry);
        $this->get_xml_url_model->xml_url_test($sitemap_url,$prifex,$row_cont);
        foreach ($sitemap_arry as $new_url){
                $new_url = trim($new_url);
                if(!empty($new_url)){
                    $this->get_xml_url_model->new_map_url($new_url,$prifex);
                }
        }
    }
    
    
    public function pars_xml_map($url) {
        error_reporting(0);
        $xml = file_get_contents($url);
        $startPos = 0;
        $links = [];
        while(true) {
            $pos1 = strpos($xml,'<loc>', $startPos);
            if ($pos1 === false) break;
            $pos1 += 5;
            $pos2 = strpos($xml, '</loc>', $pos1);
            $str = substr($xml, $pos1, $pos2-$pos1);
            $links[] = $str;
            $startPos = $pos2+6;
        }
        return $links;
    }
    
    

      
    
}

