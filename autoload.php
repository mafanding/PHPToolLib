<?php 
function my_load($class){
	include_once 'function.php';
	include $class.'.class.php';
}

spl_autoload_register('my_load');
?>