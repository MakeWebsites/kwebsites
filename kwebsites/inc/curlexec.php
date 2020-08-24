<?php

class curlexec {
   private $_res; 
   
   public function __construct($url) {
    $curlSession = curl_init();
    curl_setopt($curlSession, CURLOPT_URL, $url);
    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);

    $content = curl_exec($curlSession);
    $this->_res = $content;
    
   curl_close($curlSession); }

public function get_res() {
    return $this->_res; 
    }   

}

