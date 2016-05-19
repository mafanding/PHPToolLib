<?php 
class ContentUnique{
	const CRLF="\r\n";
	public function getUnique($mixed){
		if (file_exists($mixed)) {
			//做文件处理
			return implode(self::CRLF, array_unique(explode(self::CRLF,file_get_contents($mixed))));
		}elseif (is_string($mixed)) {
			//直接处理
			return implode(self::CRLF, array_unique(explode(self::CRLF, $mixed)));
		}else{
			return false;
		}
	}
}
?>