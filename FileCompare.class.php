<?php 
/**
*author mfd
*date 2016-05-13
*compare file 
**/
class FileCompare{
	protected $fh1=null;
	protected $fh2=null;
	protected $is_different=null;
	protected $difference=array();
	protected static $intersect=array();
	public function __construct($path1,$path2){
		$this->fh1=fopen($path1,'r');
		$this->fh2=fopen($path2,'r');
	}
	public function isDifferent(){
		if (!is_null($this->is_different)) {
			return $this->is_different;
		}
		$str1=$str2='';
		while (!feof($this->fh1)) {
			$str1.=fgets($this->fh1);
		}
		while (!feof($this->fh2)) {
			$str2.=fgets($this->fh2);
		}
		if ($str1==$str2) {
			$this->is_different=true;
		}else{
			$this->is_different=false;
		}
		$this->_close();
		return $this->is_different;
	}
	public function getDifferent(){
		if (!empty($this->difference)) {
			return $this->difference;
		}
		$arr1=$arr2=array();
		while (!feof($this->fh1)) {
			$arr1[]=trim(fgets($this->fh1),"\r\n");
		}
		while (!feof($this->fh2)) {
			$arr2[]=trim(fgets($this->fh2),"\r\n");
		}
		self::$intersect=array_intersect($arr1, $arr2);
		$arr1=array_filter($arr1,'self::_filter');
		$arr2=array_filter($arr2,'self::_filter');
		$this->difference=array($arr1,$arr2);
		$this->_close();
		return $this->difference;
	}
	protected function _close($fh=''){
		if (empty($hf)) {
			fclose($this->fh1);
			fclose($this->fh2);
		}else{
			fclose($fh);
		}
	}
	protected function _filter($input){
			if (in_array($input, self::$intersect)) {
				return false;
			}
			return true;
		}
}
?>