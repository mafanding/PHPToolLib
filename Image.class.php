<?php
defined("ALLOW")||exit("not allow!");
class Image{
	static protected $allow_ext=array("jpeg","png","gif","gd","gd2");
    static protected function imgInfo($path){
	if(!is_file($path)){
		return false;
	}
	$filename=basename($path);
	$ext=substr($filename, strrpos($filename, ".")+1);
	if(!in_array($ext, self::$allow_ext)){
		return false;
	}
	$arr=getimagesize($path);
	$arr['ext']=$ext;
	$arr['filename']=$filename;
	return $arr;
}
    public function watermark($dpath,$spath,$postion=3,$save="",$alpha=50){
        $dinfo=self::imgInfo($dpath);
        $sinfo=self::imgInfo($spath);
        if($sinfo[0]>$dinfo[0]||$sinfo[1]>$dinfo[1]){
        	return false;
        }
        $dimagecreate="imagecreatefrom".$dinfo['ext'];
        $simagecreate="imagecreatefrom".$sinfo['ext'];
        $dim=$dimagecreate ($dpath);
        $sim=$simagecreate ($spath);
        switch ($postion) {
        	case 1:
        		$dst_x=0;
        		$dst_y=0;
        		break;
        	case 2:
        		$dst_x=$dinfo[0]-$sinf[0];
        		$dst_y=0;
        		break;
        	case 4:
        		$dst_x=0;
        		$dst_y=$dinfo[1]-$sinfo[1];
        		break;
        	default:
        		$dst_x=$dinfo[0]-$sinfo[0];
        		$dst_y=$dinfo[1]-$sinfo[1];
        		break;
        }
        //bool imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )
        if(!imagecopymerge($dim, $sim, $dst_x, $dst_y, 0, 0, $sinfo[0], $sinfo[1], $alpha)){
        	return false;
        }
        $image="image".$dinfo['ext'];
        if($save==""){
        	$save=$dpath;
        }
        $image($dim,$save);
        imagedestroy($dim);
        imagedestroy($sim);
        return true;
    }
    public function thumbnail($spath,$dw,$dh,$save=""){
    	$sinfo=self::imgInfo($spath);
    	$imgcreate="imagecreatefrom".$sinfo['ext'];
    	$sim=$imgcreate($spath);
    	$dim=imagecreatetruecolor($dw, $dh);
    	$white=imagecolorallocate($dim, 255, 255, 255);
    	imagefill($dim, 0, 0, $white);
    	if($dw/$sinfo[0]<$dh/$sinfo[1]){
    		$k=$dw/$sinfo[0];
    		$dst_w=$dw;
    		$dst_h=$k*$sinfo[1];
    		$dst_x=0;
    		$dst_y=($dh-$dst_h)/2;
    	}else{
    		$k=$dh/$sinfo[1];
    		$dst_w=$k*$sinfo[0];
    		$dst_h=$dh;
    		$dst_x=($dw-$dst_w)/2;
    		$dst_y=0;
    	}
    	//bool imagecopyresized ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
    	if(!imagecopyresized($dim, $sim, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $sinfo[0], $sinfo[1])){
    		return false;
    	}
    	if($save==""){
    		$save=$spath;
    	}
    	$image="image".$sinfo['ext'];
    	$image($dim,$save);
    	imagedestroy($sim);
    	imagedestroy($dim);
    	return true;
    }
    public function identify($w,$h,$str_x,$str_y,$fontsize=4){
        $im=imagecreatetruecolor($w, $h);
        $dim=imagecreatetruecolor($w, $h);
        $gray=imagecolorallocate($im, 220, 220, 220);
        $dgray=imagecolorallocate($dim, 220, 220, 220);
        $randcolor=imagecolorallocate($im, rand(0,120), rand(0,120), rand(0,120));
        $randcolor1=imagecolorallocate($dim, rand(0,120), rand(0,120), rand(0,120));
        $randcolor2=imagecolorallocate($dim, rand(0,120), rand(0,120), rand(0,120));
        imagefill($im, 0, 0, $gray);
        imagefill($dim, 0, 0, $dgray);
        $str="abcdedghjkmnpqrstuvwxyABCDEFGHJKLMNPQRSTUVWXY23456789";
        $str=substr(str_shuffle($str), 0,4); 
        imagestring($im, $fontsize, $str_x, $str_y, $str, $randcolor); 
        //上下扭曲
        $tx=2;
        $length=3;
        for ($i=0; $i < $w ; $i++) { 
            $dst_y=round(sin(2*$tx*$i*M_PI/$w)*$length);
            imagecopy($dim, $im, $i, $dst_y, $i, 0, 1, $h);
        }
        //        
        imageline($dim, 0, rand(0,$h), $w, rand(0,$h), $randcolor1);
        imageline($dim, 0, rand(0,$h), $w, rand(0,$h), $randcolor2);
        header("Content-type:image/png");
        imagepng($dim);
        imagedestroy($im);
        imagedestroy($dim);
    }
}
?>