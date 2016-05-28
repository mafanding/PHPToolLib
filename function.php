<?php 
/**
 * @param  $key
 * @param  $var
 * @return boolean
 */
function cache($key,$value=null){
	if (is_null($value)) {
		//取缓存	
		return unserialize(file_get_contents($key));
	}else{
		//存缓存
		if (is_resource($value)) {
			return false;
		}
		$str=serialize($value);
		if(file_put_contents($key, $str)===false){
			return false;
		}else{
			return true;
		}
	}
}
/**
 * @param  $mixed
 * @return mixed
 */
function getUniqueContent($mixed){
  $crlf="\r\n";
  if (file_exists($mixed)) {
      //做文件处理
      return file_put_contents($mixed, implode($crlf, array_unique(explode($crlf,file_get_contents($mixed)))));
    }elseif (is_string($mixed)) {
      //直接处理
      return implode($crlf, array_unique(explode($crlf, $mixed)));
    }else{
      return false;
    }
}
?>