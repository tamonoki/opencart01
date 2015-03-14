<?php
class UtilMobileCheckConfigFormPage extends WebPage{
	
	function UtilMobileCheckConfigFormPage(){
		SOY2::import("module.plugins.util_mobile_check.util.UtilMobileCheckUtil");
		SOY2DAOFactory::importEntity("SOYShop_DataSets");
	}
	
	function doPost(){
		SOY2DAOFactory::importEntity("SOYShop_DataSets");

		if(isset($_POST["config"]) && soy2_check_token()){
			
			$config = $_POST["config"];
			
			$config["css"] = (isset($config["css"])) ? $config["css"] : 0;
			$config["cookie"] = (isset($config["cookie"])) ? $config["cookie"] : 0;
			
			$config["session"] = (isset($config["session"])) ? mb_convert_kana($config["session"], "a") : 5;
			if(!is_numeric($config["session"]))$config["session"] = 5;
			
			$config["redirect"] = (isset($config["redirect"])) ? (int)$config["redirect"] : 0;
			$config["redirect_iphone"] = (isset($config["redirect_iphone"])) ? (int)$config["redirect_iphone"] : 0;
			$config["redirect_ipad"] = (isset($config["redirect_ipad"])) ? (int)$config["redirect_ipad"] : 0;
			$config["not_redirect"] = (isset($config["not_redirect"])) ? $config["not_redirect"] : 0;

			SOYShop_DataSets::put("util_mobile_check.config", $config);
	
			$this->config->redirect("updated");
		}
		
	}
	
	function execute(){
		
		$config = UtilMobileCheckUtil::getConfig();
		
		WebPage::WebPage();
		
		
		$this->addModel("updated", array(
			"visible" => (isset($_GET["updated"]))
		));
		
		$this->addForm("form");
		
		$this->addInput("prefix", array(
			"name" => "config[prefix]",
			"value" => $config["prefix"]
		));
		
		$this->addLabel("domain", array(
			"text" => $_SERVER["HTTP_HOST"]
		));
		
		$this->addLabel("prefix_text", array(
			"text" => $config["prefix"]
		));
		
		$this->addInput("prefix_i", array(
			"name" => "config[prefix_i]",
			"value" => $config["prefix_i"]
		));
		
		$this->addLabel("prefix_i_text", array(
			"text" => $config["prefix_i"]
		));
		
		$this->addCheckBox("docomo_css", array(
			"name" => "config[css]",
			"value" => 1,
			"elementId" => "docomo_css",
			"selected" => $config["css"]
		));
		
		$this->addModel("is_docomo_css", array(
			"visible" => ($config["css"] == 1)
		));
		
		$this->addCheckBox("cookie", array(
			"name" => "config[cookie]",
			"value" => 1,
			"elementId" => "cookie",
			"selected" => $config["cookie"]
		));
		
		$this->addInput("session", array(
			"name" => "config[session]",
			"value" => $config["session"]
		));
		
		$this->addInput("url", array(
			"name" => "config[url]",
			"value" => $config["url"]
		));
		
		$this->addCheckBox("auto_redirect", array(
			"name" => "config[redirect]",
			"value" => 1,
			"elementId" => "auto_redirect",
			"selected" => $config["redirect"] == 1
		));
		
		$this->addModel("no_auto_redirect", array(
			"visible" => ($config["redirect"] == 0)
		));
		
		$this->addModel("is_auto_redirect", array(
			"visible" => ($config["redirect"] == 1)
		));
		
		$this->addTextArea("message", array(
			"name" => "config[message]",
			"value" => $config["message"]
		));
		
		$this->addInput("message_input", array(
			"name" => "config[message]",
			"value" => $config["message"]
		));
		
		$this->addCheckBox("do_not_redirect_ipad", array(
			"name" => "config[redirect_ipad]",
			"value" => 0,
			"elementId" => "do_not_redirect_ipad",
			"selected" => (isset($config["redirect_ipad"]) && $config["redirect_ipad"] == 0)
		));
		
		$this->addCheckBox("redirect_ipad_smartphone", array(
			"name" => "config[redirect_ipad]",
			"value" => 1,
			"elementId" => "redirect_ipad_smartphone",
			"selected" => (isset($config["redirect_ipad"]) && $config["redirect_ipad"] == 1)
		));
		
		$this->addCheckBox("do_not_redirect", array(
			"name" => "config[redirect_iphone]",
			"value" => 0,
			"elementId" => "do_not_redirect",
			"selected" => ($config["redirect_iphone"] == 0)
		));
		
		$this->addCheckBox("redirect_smartphone", array(
			"name" => "config[redirect_iphone]",
			"value" => 1,
			"elementId" => "redirect_smartphone",
			"selected" => (isset($config["redirect_iphone"]) && $config["redirect_iphone"] == 1)
		));
		
		$this->addCheckBox("redirect_mobile", array(
			"name" => "config[redirect_iphone]",
			"value" => 2,
			"elementId" => "redirect_mobile",
			"selected" => (isset($config["redirect_iphone"]) && $config["redirect_iphone"] == 2)
		));
	}
	
	function setConfigObj($obj) {
		$this->config = $obj;
	}
}
?>