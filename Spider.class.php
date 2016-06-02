<?php 
/**
* CURLOPT_RETURNTRANSFER
*/
class Spider
{
	protected $url='';
	protected $pattern='/\<a.*?href=.*?\>/';
	protected $url_file='url.txt';
	protected $web_content_dir='../webContent/';
	protected $ch=null;

	public function __construct($url='')
	{
		$this->ch=curl_init();
		if (!empty($url)) {
			$this->url=$url;
		}
	}

	public function crawlerWbeContent(){
		$url=file_get_contents($this->url_file);
		$url=explode("\r\n", $url);
		foreach ($url as $k => $v) {
			curl_setopt($this->ch, CURLOPT_URL, $v);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
			$tmp=curl_exec($this->ch);
			if (file_exists($this->web_content_dir.$k)) {
				file_put_contents($this->web_content_dir.$k, $tmp, FILE_APPEND);
			}else{
				file_put_contents($this->web_content_dir.$k, $tmp);
			}	
			$this->_writeUrl($tmp);
		}
		$this->_close();
	}

	public function getUrlFile($pattern='/href=\".*?\"/',$scheme='http',$start=6){
		curl_setopt($this->ch, CURLOPT_URL, $this->url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		$str=curl_exec($this->ch);
		$this->_writeUrl($str,$pattern,$scheme,$start);
	}

	public function __call($name,$argument){
		if (stripos($name, 'set_')==0) {
			$variable=substr($name, 4);
			if (isset($this->$variable)) {
				$this->$variable=$argument[0];
			}else{
				echo 'Invalid variable';
			}	
		}
	}

	protected function _writeUrl($str,$pattern='/href=\".*?\"/',$scheme='http',$start=6){
		$tmp=$this->_filterUrl($str,$pattern,$scheme,$start);
		if (file_exists($this->url_file)) {
			file_put_contents($this->url_file, $tmp, FILE_APPEND);
		}else{
			file_put_contents($this->url_file, $tmp);
		}
	}

	protected function _filterUrl($string,$pattern='/href=\".*?\"/',$scheme='http',$start=6,$is_fill_url=false){
		preg_match_all($this->pattern, $string, $arr);
		$url=array();
		foreach ($arr[0] as $k => $v) {
			if(preg_match($pattern, $v, $tmp)){
				if (stripos($tmp[0], $scheme)) {
					$tmp=substr($tmp[0], $start, -1);
					if ($is_fill_url) {
						$host=curl_getinfo($this->ch,CURLINFO_EFFECTIVE_URL);
						$tmp=parse_url($host,PHP_URL_HOST).$tmp;
					}
					if (!empty($tmp)) {
						$url[]=$tmp;
					}
				}
			}
		}
		$url=array_unique($url);
		$url=array_filter($url);
		$url=implode("\r\n", $url);
		return $url;
	}

	protected function _close(){
		curl_close($this->ch);
	}
}
?>