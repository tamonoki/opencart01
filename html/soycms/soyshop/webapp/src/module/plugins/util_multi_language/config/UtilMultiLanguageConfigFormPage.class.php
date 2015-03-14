<?php

class UtilMultiLanguageConfigFormPage extends WebPage{
	
	function UtilMultiLanguageConfigFormPage(){
		SOY2DAOFactory::importEntity("SOYShop_DataSets");
		SOY2::import("module.plugins.util_multi_language.util.UtilMultiLanguageUtil");
	}
	
	function doPost(){
		
		if(soy2_check_token() && isset($_POST["Config"])){
			UtilMultiLanguageUtil::saveConfig($_POST["Config"]);
			
			//英語用のテンプレートが無ければここでテンプレートを生成する
			$this->makeTemplate();
			$this->config->redirect("updated");
		}
	}
	
	function makeTemplate(){
		$config = UtilMultiLanguageUtil::getConfig();
		foreach($config as $key => $values){
			if(isset($values["prefix"]) && strlen($values["prefix"]) > 0){
				$this->copyCartTemplate($values["prefix"]);
				$this->copyMypageTemplate($values["prefix"]);
			}
		}
	}
	
	function copyCartTemplate($language){
		$dir = SOYSHOP_SITE_DIRECTORY . ".template/cart/";
		if(!file_exists($dir . soyshop_get_cart_id() . "_en.ini")){
			copy($dir . soyshop_get_cart_id() . ".html", $dir . soyshop_get_cart_id() . "_en.html");
			copy($dir . soyshop_get_cart_id() . ".ini", $dir . soyshop_get_cart_id() . "_en.ini");
			
			$iniFile = file_get_contents($dir . soyshop_get_cart_id() . "_en.ini");
			$iniFile = str_replace(soyshop_get_cart_id(), soyshop_get_cart_id() . "_en", $iniFile);
			file_put_contents($dir . soyshop_get_cart_id() . "_en.ini", $iniFile);
		}
	}
	
	function copyMypageTemplate($language){
		$dir = SOYSHOP_SITE_DIRECTORY . ".template/mypage/";
		if(!file_exists($dir . soyshop_get_mypage_id() . "_en.ini")){
			copy($dir . soyshop_get_mypage_id() . ".html", $dir . soyshop_get_mypage_id() . "_en.html");
			copy($dir . soyshop_get_mypage_id() . ".ini", $dir . soyshop_get_mypage_id() . "_en.ini");
			
			$iniFile = file_get_contents($dir . soyshop_get_mypage_id() . "_en.ini");
			$iniFile = str_replace(soyshop_get_mypage_id(), soyshop_get_mypage_id() . "_en", $iniFile);
			file_put_contents($dir . soyshop_get_mypage_id() . "_en.ini", $iniFile);
		}
	}
	
	function execute(){
		
		$config = UtilMultiLanguageUtil::getConfig();
		
		WebPage::WebPage();
		
		$this->addModel("updated", array(
			"visible" => isset($_GET["updated"])
		));
		
		$this->addForm("form");
		
		$languages = array("jp", "en");
		foreach($languages as $language){
			$text = (isset($config[$language]["prefix"])) ? $config[$language]["prefix"] : $language;
			
			$this->addInput($language . "_prefix", array(
				"name" => "Config[" . $language . "][prefix]",
				"value" => $text
			));
			
			$this->addLabel($language . "_prefix_text", array(
				"text" => (strlen($text)) ? "/" . $text : ""
			));
			
			$this->addLabel($language . "_user_cart_id", array(
				"text" => (strlen($text)) ? soyshop_get_cart_id() . "_" . $text : soyshop_get_cart_id()
			));
			
			$this->addLabel($language . "_user_mypage_id", array(
				"text" => (strlen($text)) ? soyshop_get_mypage_id() . "_" . $text : soyshop_get_mypage_id()
			));
		}
				
		$this->addLabel("domain", array(
			"text" => $_SERVER["HTTP_HOST"]
		));
		
		$this->addCheckBox("confirm_browser_language_config", array(
			"name" => "Config[check_browser_language_config]",
			"value" => 1,
			"selected" => (isset($config["check_browser_language_config"])) ? (int)$config["check_browser_language_config"] : 0,
			"label" => "確認する"
		));
	}
	
	function setConfigObj($obj) {
		$this->config = $obj;
	}
}
?>