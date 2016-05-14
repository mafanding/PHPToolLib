<?php
defined("ALLOW")||exit("not allow!");
class File{
	protected $allowExt="jpg,txt,png,jpeg,gif";
	protected $maxFileSize=1;
	protected $errno=null;
	protected $error=array();
	protected $ext=null;
	protected $path=null;
	protected $rename=null;
	public function upload($filename,$size,$tmpname){
        $this->getExt($filename);
        $this->newname();
       if(!$this->is_allowExt()){
       	$this->errno=5;
       	return false;
       }
       if(!$this->is_allowSize($size)){
       	$this->errno=6;
       	return false;
       }
       if(!$this->mk_dir()){
       	$this->errno=7;
       	return false;
       }
       if(!move_uploaded_file($tmpname, $this->path."/".$this->rename)){
       	$this->errno=8;
       	return false;
       }
       $dir=$this->path."/".$this->rename;
       $dir=str_replace(ROOT, "", $dir);
       return $dir;
	}
	protected function getExt($filename){
		$arr=explode(".", $filename);
		$this->ext=end($arr);
	}
	protected function is_allowExt(){
		$arr=explode(",",$this->allowExt);
		return in_array($this->ext,$arr);
	}
	protected function is_allowSize($size){
		return $size<=$this->maxFileSize*1024*1024;
	}
	protected function mk_dir(){
		$this->path=ROOT."data/".$this->ext."/".date("ymd");
		if (is_dir($this->path)) {
			return true;
		}
		return mkdir($this->path,077,true);
	}
	protected function newname(){
		$this->rename=substr(md5(time()),0,10).".".$this->ext;
	}
}
?>