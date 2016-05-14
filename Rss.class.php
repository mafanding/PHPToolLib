<?php
defined("ALLOW")||exit("not allow!");
class Rss{
	protected $templatedir='./rss.xml';
	protected $dom=null;
	protected $rssNode=null;
	protected $channelNode=null;
	protected $title='1';
	protected $link='2';
	protected $description='3';
	public function __construct(){
		$this->dom=new DOMDocument();
		$this->dom->load($this->templatedir);
		$this->rssNode=$this->dom->getElementsByTagName('rss')->item(0);
		$this->channelNode=$this->dom->createElement('channel');
		$this->rssNode->appendChild($this->channelNode);
	}
	public function display(){
		$title=$this->createEle('title',$this->title);
		$link=$this->createEle('link',$this->link);
		$description=$this->createEle('description',$this->description);
		$this->channelNode->appendChild($title);
		$this->channelNode->appendChild($link);
		$this->channelNode->appendChild($description);
		header('content-type:text/xml');
		echo $this->dom->saveXML();
	}
	protected function addItem($arr){
		$item=$this->dom->createElement('item');
		foreach ($arr as $k => $v) {
			$title=$this->createEle('title',$v['title']);
			$description=$this->createEle('description',$v['description']);
			$item->appendChild($title);
			$item->appendChild($description);
		}
		return $item;
	}
	protected function createEle($e,$t){
		$element=$this->dom->createElement($e);
		$text=$this->dom->createTextNode($t);
		$element->appendChild($text);
		return $element;
	}
}
?>