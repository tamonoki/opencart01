<?php
/*
 * Created on 2010/07/18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
SOY2::import("site_include.DateLabel");
class EntryComponent extends HTMLList{
	
	private $path;
	
	function setPath($path){
		$this->path = $path;
	}
	
	function  populateItem($entity){
		
		$link = $this->path.rawurlencode($entity->getAlias());
		
		$this->createAdd("entry_id","HTMLLabel", array(
			"text"=>$entity->getId(),
			"soy2prefix"=>"cms"
		));
		
		$this->createAdd("title","HTMLLabel", array(
			"html" => "<a href=\"$link\">".htmlspecialchars($entity->getTitle(), ENT_QUOTES, "UTF-8")."</a>",
			"soy2prefix" => "cms"
		));
		
		$this->createAdd("title_plain","HTMLLabel", array(
			"text" => $entity->getTitle(),
			"soy2prefix" => "cms"
		));
		
		$this->createAdd("content","HTMLLabel", array(
			"html" => $entity->getContent(),
			"soy2prefix" => "cms"
		));
		
		//作成日付 Y-m-d H:i:s
		$this->createAdd("create_date","DateLabel", array(
			"text" => $entity->getCdate(),
			"soy2prefix" => "cms"
		));

		//作成日付 Y-m-d
		$this->createAdd("create_ymd","DateLabel", array(
			"text"=>$entity->getCdate(),
			"soy2prefix"=>"cms",
			"defaultFormat"=>"Y-m-d"
		));
		
		$more = $entity->getMore();
		$this->createAdd("more","HTMLLabel", array(
			"html"=> '<a name="more"></a>'.$more,
			"soy2prefix"=>"cms",
		));
		
		//作成時刻 H:i
		$this->createAdd("create_time","DateLabel", array(
			"text"=>$entity->getCdate(),
			"soy2prefix"=>"cms",
			"defaultFormat"=>"H:i"
		));
		
		$this->createAdd("entry_link","HTMLLink", array(
			"soy2prefix"=>"cms",
			"link" => $link
		));
		
		$this->createAdd("more_link","HTMLLink", array(
			"soy2prefix"=>"cms",
			"link" => $link ."#more",
			"visible"=>(strlen($entity->getMore()) != 0)
		));
	}
}
?>
