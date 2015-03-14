<?php

class ExportTemplatePage extends CMSWebPageBase{

	var $pageId;

    function ExportTemplatePage($arg) {
    	$this->pageId = isset($arg[0])? $arg[0] : null;
    	if(is_null($this->pageId)){
    		header('Content-Disposition: attachment;filename=blank.html;');
    		echo "";
    	}else{
    		WebPage::WebPage();
    		$page = $this->getPageObject($this->pageId);
    		
    		if($page->getPageType() == Page::PAGE_TYPE_BLOG){
    			header('Content-Disposition: attachment;filename=blank.html;');
    			echo "";
    		}else{
    		
	    		$filename = "template_".str_replace("/","_",$page->getUri()).".html";
	    		
	    		header('Content-Disposition: attachment;filename='.$filename.';');
	    		echo $page->getTemplate();
    		}
    		
    	}
    	
    	exit;
    }
    
    function getTemplate(){
    	return "";
    }
    
    function getPageObject($id){
    	return SOY2ActionFactory::createInstance("Page.DetailAction",array(
    		"id" => $id
    	))->run()->getAttribute("Page");
    }
}
?>