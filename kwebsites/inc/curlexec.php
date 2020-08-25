<?php

class curlexec {
   private $_res;  
   
   public function __construct($url) {
    

    $curlSession = curl_init();
    curl_setopt($curlSession, CURLOPT_URL, $url);
    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);

    $content = curl_exec($curlSession);
    

    $this->_res = $content;
    curl_close($curlSession);   
    }

public function get_res() {
    return $this->_res; 
    }   

}

