<?php
defined("ALLOW")||exit("not allow!");
class Http{
	protected $url=null;
	protected $method=null;
	protected $fh=null;
	public function __construct($method,$url){
		$this->url=parse_url($url);
		$this->method=$method;
		if (!isset($this->url['port'])) {
			$this->url['port']='80';
		}
		if (isset($this->url['query'])&&!empty($this->url['query'])) {
			$this->url['query']='?'.$this->url['query'];
		}else{
			$this->url['query']='';
		}
		$this->fh=fsockopen($this->url['host'],$this->url['port']);
	}
	public function request(array $head=array(),array $body=array(),$close=false){
		$requeststart=$this->method." ".$this->url['path'].$this->url['query']." HTTP/1.1";
		$requesthead[0]='Host:'.$this->url['host'];
		if (!empty($body)) {
			$body=http_build_query($body);
			$requesthead[1]='Content-length:'.strlen($body);
		}
		if (!empty($head)) {
			foreach ($head as $k => $v) {
				$requesthead[]=$k.':'.$v;
			}
		}
		$requesthead=implode("\r\n", $requesthead);
		$requestbody=empty($body)?'':$body;
		$request=$requeststart."\r\n".$requesthead."\r\n\r\n".$requestbody;
		//echo $request;
		$result=fwrite($this->fh, $request);
		/*
		$result='';
		while (!feof($this->fh)) {
			$result.=fread($this->fh, 1024);
		}
		*/
		if ($close) {
			fclose($this->fh);
		}
		return $result;
	}
	function close(){
		fclose($this->fh);
	}
}
?>