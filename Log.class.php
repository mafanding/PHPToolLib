<?php
defined("ALLOW")||exit("not allow!");
class Log{
public static function report($st){
$filename=dirname(__DIR__)."/log/".date("Ym");
if(!is_dir($filename)){
mkdir($filename);
}
$path=$filename."/".date("dH").".log";
$fp=fopen($path,"ab");
$st=$st."\r\n";
fwrite($fp,$st);
fclose($fp);
}
}
?>