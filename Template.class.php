<?php
defined("ALLOW")||exit("not allow!");
class Template{
	private $templateDir="./temp/";
	private $compileDir="./comp/";
	private $arr=array();

	public function display($file){
		$path=$this->compile($file);
		include $path;
	}

	public function assign($k,$v){
		$this->arr[$k]=$v;
	}

	public function compile($file){
		$temp=$this->templateDir.$file;
		$comp=$this->compileDir.$file.".php";
		if (file_exists($comp)&&filemtime($temp)<filemtime($comp)) {
			return $comp;
		}
		$source=file_get_contents($temp);
		$source=str_replace("{\$", "<?php echo \$this->arr['", $source);
		$source=str_replace("}", "']; ?>", $source);
		file_put_contents($comp, $source);
		return $comp;
	}
}
?>