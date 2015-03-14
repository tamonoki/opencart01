<?php

class _EntryBlankPage extends CMSWebPageBase{

	var $labelIds = array();
	
	function setLabelIds($labelIds){
		$this->labelIds = $labelIds;
	}

	function _EntryBlankPage() {
    	WebPage::WebPage();
    }
    
    function execute(){
    	$this->createAdd("create_link","HTMLLink",array(
    		"link" => SOY2PageController::createLink("Entry.Create") . "/" .implode("/",$this->labelIds)
    	));
    }
}
?>