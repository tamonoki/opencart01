<?php

class ItemReviewConfigFormPage extends WebPage{

    function ItemReviewConfigFormPage() {
    	SOY2DAOFactory::importEntity("SOYShop_DataSets");
    	SOY2::import("module.plugins.item_review.common.ItemReviewCommon");
    }
    
    function doPost(){
    	
    	if(soy2_check_token()){
    		$config = (isset($_POST["Config"])) ? $_POST["Config"] : array();
    		
    		$config["code"] = mb_convert_kana($config["code"], "a");
    		$config["code"] = str_replace("#", "", $config["code"]);
    		if(!preg_match("/^([a-fA-F0-9])/", $config["code"])){
    			$config["code"] = "ffff00";
    		}
    		
    		$config["login"] = (isset($config["login"])) ? 1 : null;
    		$config["publish"] = (isset($config["publish"])) ? 1 : null;
    		$config["edit"] = (isset($config["edit"])) ? 1 : null;
    		
    		SOYShop_DataSets::put("item_review.config", $config);
    		$this->config->redirect("updated");
    	}
    }
    
    function execute(){
    	$config = ItemReviewCommon::getConfig();
    	
    	WebPage::WebPage();
    	
    	$this->addModel("updated", array(
    		"visible" => (isset($_GET["updated"]))
    	));
    	
    	$this->addForm("form");
    	
    	$this->addInput("code", array(
    		"name" => "Config[code]",
    		"value" => (isset($config["code"])) ? $config["code"] : ""
    	));
    	
    	$this->addInput("nickname", array(
    		"name" => "Config[nickname]",
    		"value" => (isset($config["nickname"])) ? htmlspecialchars($config["nickname"],ENT_QUOTES,"UTF-8") : ""
    	));
    	
    	$this->addCheckBox("login_mode", array(
    		"name" => "Config[login]",
    		"value" => 1,
    		"selected" => (isset($config["login"]) && $config["login"] == 1),
    		"elementId" => "login_mode"
    	));
    	
    	$this->addCheckBox("publish_mode", array(
    		"name" => "Config[publish]",
    		"value" => 1,
    		"selected" => (isset($config["publish"]) && $config["publish"] == 1),
    		"elementId" => "publish_mode"
    	));
    	
    	$this->addCheckBox("edit_review", array(
    		"name" => "Config[edit]",
    		"value" => 1,
    		"selected" => (isset($config["edit"]) && $config["edit"] == 1),
    		"elementId" => "edit_review"
    	));
    }
        
    function setConfigObj($obj) {
		$this->config = $obj;
	}
}
?>