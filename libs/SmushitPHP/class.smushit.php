<?php
// smushit-php - a PHP client for Yahoo!'s Smush.it web service
//
// Janu 15, 2012
// Endika Iglesias <endika2@gmail.com>
// June 24, 2010
// Tyler Hall <tylerhall@gmail.com>
class SmushIt{
	const SMUSH_URL = 'http://www.smushit.com/ysmush.it/ws.php?';
	public $filename;
	public $url;
	public $compressedUrl;
	public $size;
	public $compressedSize;
	public $savings;
	public $error;
	 public function __construct($data = null){
	  if(!is_null($data)){
	   if(preg_match('/https?:\/\//', $data) == 1)
	    $this->smushURL($data);
	   else
	    $this->smushFile($data);
	  }
	 }
	public function smushURL($url){
	  $this->url = $url;
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, self::SMUSH_URL . 'img=' . $url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	  $json_str = curl_exec($ch);
	  curl_close($ch);
	return $this->parseResponse($json_str);}

	public function smushFile($filename){
		$this->filename = $filename;
		if(!is_readable($filename)){
		$this->error = 'Could not read file';
		return false;}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::SMUSH_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('files' => '@' . $filename));
		$json_str = curl_exec($ch);
		curl_close($ch);
		return $this->parseResponse($json_str);
	}
	 private function parseResponse($json_str){
	            $this->error = null;
	            $json = json_decode($json_str);
	            if(is_null($json)){
	                $this->error = 'Bad response from Smush.it web service';
	                return false;
	            }
	            if(isset($json->error)){
	                $this->error = $json->error;
	                return false;
	            }
	            $this->filename       = substr (strrchr ($json->src, '/'), 1 );
	            $this->size           = $json->src_size;
	            $this->compressedUrl  = $json->dest;
	            $this->compressedSize = $json->dest_size;
	            $this->savings        = $json->percent;
	 return true;}
	 function saveImage($path) {
		$url=$this->compressedUrl;
		$c = curl_init();
		      curl_setopt($c,CURLOPT_URL,$url);
		      curl_setopt($c,CURLOPT_HEADER,0);
		      curl_setopt($c,CURLOPT_RETURNTRANSFER,true);
		      $s = curl_exec($c);
		      curl_close($c);
		      $f = fopen($path, 'wb');
		      $z = fwrite($f,$s);
		      if ($z != false) return true;
		return false;
	}
}