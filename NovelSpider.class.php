<?php 
/**
* 
*/
class NovelSpider extends Spider
{
	protected $novel_list_file='novellist.txt';

	public function getNovelIndexInfo($pattern_novel_url='',$pattern_novel_index='',$scheme='',$start='',$is_fill_url=''){
		$url=file_get_contents($this->url_file);
		$url=explode("\r\n", $url);
		$arr=$tmp=array();
		foreach ($url as $k => $v) {
			curl_setopt($this->ch, CURLOPT_URL, $v);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
			$tmp[$k]=curl_exec($this->ch);
			$this->_writeUrl($tmp[$k],$pattern_novel_url);
			$arr[]=$this->_filterUrl($tmp[$k],$pattern_novel_index,$scheme,$start,$is_fill_url);
		}
		$arr=implode("\r\n", $arr);
		if (file_exists($this->novel_list_file)) {
			file_put_contents($this->novel_list_file, $arr, FILE_APPEND);
		}else{
			file_put_contents($this->novel_list_file, $arr);
		}
	}
}
?>