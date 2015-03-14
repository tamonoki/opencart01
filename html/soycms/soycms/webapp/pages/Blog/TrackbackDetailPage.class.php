<?php

class TrackbackDetailPage extends CMSWebPageBase{

    function TrackbackDetailPage($arg) {
    	$trackbackId = @$arg[0];
    	
    	$result = $this->run("EntryTrackback.TrackbackDetailAction",array("trackbackId"=>$trackbackId));
    	
    	if(!$result->success()){
    		echo CMSMessageManager::get("SOYCMS_ERROR");
    		exit;
    	}
    	
    	WebPage::WebPage();
    	$trackback = $result->getAttribute("entity");
    	
    	$result = $this->run("Entry.EntryDetailAction",array("id"=>$trackback->getEntryId()));
    	
    	if(!$result->success()){
    		echo CMSMessageManager::get("SOYCMS_ERROR");
    		exit;
    	}
    	
    	$entry = $result->getAttribute("Entry");
    	$title = $trackback->getTitle();
    	
    	if(strlen($title) == 0){
    		$title = CMSMessageManager::get("SOYCMS_NO_TITLE");
    	}
    	
    	$this->createAdd("title","HTMLLabel",array(
    		"text"=>$title
    	));
    	
    	$this->createAdd("blogName","HTMLLabel",array(
    		"text"=>$trackback->getBlogName()
    	));
    	
    	$this->createAdd("entry_title","HTMLLabel",array(
    		"text"=>$entry->getTitle()
    	));
    	
    	$this->createAdd("blogAddress","HTMLLabel",array(
    		"text"=>$trackback->getUrl()
    	));
    	
    	$this->createAdd("submit_date","HTMLLabel",array(
    		"text"=>date("Y-m-d H:i:s",$trackback->getSubmitDate())
    	));
    	
    	$this->createAdd("state","HTMLLabel",array(
    		"text"=>($trackback->getCertification() == 0)? CMSMessageManager::get("SOYCMS_DENY") : CMSMessageManager::get("SOYCMS_ALLOW")
    	));
    	
    	$this->createAdd("content","HTMLLabel",array(
    		"text"=>$trackback->getExcerpt()
    	));
    	
    	//記事テーブルのCSS
		HTMLHead::addLink("entrytree",array(
			"rel" => "stylesheet",
			"type" => "text/css",
			"href" => SOY2PageController::createRelativeLink("./css/entry/table.css")
		));
    	
    	//記事テーブルのCSS
		HTMLHead::addLink("style_CSS",array(
			"rel" => "stylesheet",
			"type" => "text/css",
			"href" => SOY2PageController::createRelativeLink("./css/entry/style.css")
		));
    	
    }
}
?>