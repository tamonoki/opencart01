<?php

class ButtonSocialConfigFormPage extends WebPage{
	
	private $config;
	
	function ButtonSocialConfigFormPage(){
		SOY2DAOFactory::importEntity("SOYShop_DataSets");
		SOY2::import("module.plugins.button_social.util.ButtonSocialUtil");
	}
	
	function doPost(){
		
		if(soy2_check_token() && isset($_POST["Config"])){			
			ButtonSocialUtil::saveConfig($_POST["Config"]);
			ButtonSocialUtil::savePageDisplayConfig($_POST["display_config"]);
			$this->config->redirect("updated");
		}
	}
	
	function execute(){
				
		WebPage::WebPage();
		
		$config = ButtonSocialUtil::getConfig();
		
		$this->addModel("updated", array(
			"visible" => (isset($_GET["updated"]))
		));
		
		$this->addForm("form");

		$this->addInput("app_id", array(
			"name" => "Config[app_id]",
			"value" => (isset($config["app_id"])) ? $config["app_id"] : "",
			"style" => "ime-mode:inactive;"
		));
		
		$this->addInput("admins", array(
			"name" => "Config[admins]",
			"value" => (isset($config["admins"])) ? $config["admins"] : "",
			"style" => "ime-mode:inactive;"
		));
		
		$this->addInput("image", array(
			"name" => "Config[image]",
			"value" => (isset($config["image"])) ? $config["image"] : ""
		));

		$this->addInput("check_key", array(
			"name" => "Config[check_key]",
			"value" => (isset($config["check_key"])) ? $config["check_key"] : "",
			"style" => "ime-mode:inactive;"
		));
		
		include_once(dirname(dirname(__FILE__)) . "/component/PageListComponent.class.php");
		$this->createAdd("page_list", "PageListComponent", array(
			"list" => $this->getPageList(),
			"displayConfig" => ButtonSocialUtil::getPageDisplayConfig()
		));
	}
	
	function getPageList(){
		$pageDao = SOY2DAOFactory::create("site.SOYShop_PageDao");
		try{
			$pages = $pageDao->get();
		}catch(Exception $e){
			return array();	
		}
		
		$list = array();
		foreach($pages as $page){
			if(is_null($page->getId())) continue;
			$list[$page->getId()] = $page->getName();
		}
		return $list;
	}

	function setConfigObj($obj) {
		$this->config = $obj;
	}
}
?>