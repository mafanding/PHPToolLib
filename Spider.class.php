<?php 
/**
* 
*/
class Spider
{
	protected $url='';
	protected $pattern='/\<a.*?href=.*?\>/';
	protected $url_file='url.txt';
	protected $web_content_dir='../webContent/';
	public function __construct($url)
	{
		$this->url=$url;
	}

	public function crawlerWbeContent(){
		$url=file_get_contents($this->url_file);
		$url=explode("\r\n", $url);
		foreach ($url as $k => $v) {
			$tmp=file_get_contents($v);
			file_put_contents($this->web_content_dir.$k, $tmp);
			$this->_writeUrl($tmp);
		}
	}

	public function getUrlFile(){
		$str=file_get_contents($this->url);
		$this->_writeUrl($str);
	}

	public function setUrlFile($string){
		$this->url_file=$string;
	}

	public function setUrl($string){
		$this->url=$string;
	}

	protected function _writeUrl($str){
		$tmp=$this->_filterUrl($str);
		if (file_exists($this->url_file)) {
			file_put_contents($this->url_file, $tmp, FILE_APPEND);
		}else{
			file_put_contents($this->url_file, $tmp);
		}
	}

	protected function _filterUrl($string){
		preg_match_all($this->pattern, $string, $arr);
		$pattern='/href=\".*?\"/';
		$url=array();
		foreach ($arr[0] as $k => $v) {
			if(preg_match($pattern, $v, $tmp)){
				if (stripos($tmp[0], 'http')) {
					$url[]=substr($tmp[0], 6, -1);
				}
			}
		}
		$url=array_unique($url);
		$url=array_filter($url);
		$url=implode("\r\n", $url);
		return $url;
	}
}
?>