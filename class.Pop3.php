<?php
class Pop3{
  const CRLF="\r\n";
  protected $host='';
  protected $port='';
  protected $user='';
  protected $pass='';
  protected $fh=null;
  public function __construct($h,$p=110){
    $this->host=$h;
    $this->port=$p;
    set_time_limit(0);
    $this->fh=fsockopen($this->host,$this->port);
  }
  public function login($u,$p){
    $this->sendString('user '.$u.self::CRLF);
    $this->sendString('pass '.$p.self::CRLF);
    $this->user=$u;
    $this->pass=$p;
  }
  public function getallfrom(){
    $this->getstat();
    $stat=$this->getstring();
    $this->quit();
    $search='/^\+OK [0-9]+\b/';
    $tmp2=$tmp1=$email=$tmp=$arr=array();
    $msgcount=0;
    foreach ($stat as $k  => $v) {
      if (preg_match_all($search,$v,$tmp)) {
        $tmp=explode(' ',$tmp[0][0]);
        $msgcount=$tmp[1];
        break;
      }
    }
    $this->fh=fsockopen($this->host,$this->port);
    $this->login($this->user,$this->pass);
    for ($i=1; $i <= $msgcount; $i++) {
      $this->gettop($i);
    }
    $search='/From: .*\<.*\>/';
    $tmp=$this->getstring();
    /* */
    foreach ($tmp as $k  => $v) {
      if (preg_match_all($search,$v,$tmp1)) {
        if(preg_match_all('/[0-9a-zA-Z_\.\-]+@[0-9a-zA-Z_\.\-]+\.com/',$tmp1[0][0],$tmp2)){
        $email[]=$tmp2[0][0];
        }
      }
    }

    return $email;
  }
  public function getstat(){
    $this->sendString('stat'.self::CRLF);
  }
  public function gettop($i){
    $this->sendString('top '.$i.' 1'.self::CRLF);
  }
  public function getstring(){
      $arr=array();
      while (!feof($this->fh)) {
        $arr[]=fgets($this->fh);
      }
      return $arr;
  }
  public function setdele($i){
    $this->sendString('dele '.$i.self::CRLF);
  }
  public function quit(){
    $this->sendString('quit'.self::CRLF);
    fclose($this->fh);
  }
  protected function sendString($string){
      return fwrite($this->fh, $string, strlen($string));
      return 0;
  }
}
 ?>
