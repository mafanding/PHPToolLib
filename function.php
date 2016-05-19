<?php 
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
?>