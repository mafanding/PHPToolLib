<?php
defined("ALLOW")||exit("not allow!");
class Page {
	protected $page=0;
	protected $pagesize=0;
	protected $total=0;
	public function __construct($total,$pagesize,$page){
		$this->total=$total;
		$this->pagesize=$pagesize;
		$this->page=$page;
	}
	public function show(){
		$uri=$_SERVER['REQUEST_URI'];
		$uri=parse_url($uri);
		$query=isset($uri['query'])?$uri['query']:"";
		$arr_query=array();
		parse_str($query,$arr_query);
		if (isset($arr_query['page'])) {
			unset($arr_query['page']);
		}
		$query=http_build_query($arr_query);
		if (!empty($query)) {
			$query.="&";
		}
		$uri=$uri['path']."?".$query;
		$cnt=ceil($this->total/$this->pagesize);
		$nav=array();
		$nav[0]="<span>".$this->page."</span>";
		for ($left=$this->page-1,$right=$this->page+1; count($nav)<5&&($left>=1||$right<=$cnt) ; ) { 
			if ($left>=1) {
				array_unshift($nav, "<a href='".$uri."page=".$left."'>[".$left."]</a>");
				$left--;
			}
			if ($right<=$cnt) {
				array_push($nav,  "<a href='".$uri."page=".$right."'>[".$right."]</a>");
				$right++;
			}
		}
		return implode($nav);
	}
}
?>