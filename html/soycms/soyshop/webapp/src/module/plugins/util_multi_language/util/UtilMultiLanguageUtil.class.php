<?php

class UtilMultiLanguageUtil {
	
	public static function getConfig(){
		return SOYShop_DataSets::get("util_multi_language.config", array(
			"jp" => array(
							"prefix" => ""
						),
			"en" => array(
							"prefix" => "en"
						),
			"check_browser_language_config" => 0
		));
	}
	
	public static function saveConfig($values){
		if(!isset($values["check_browser_language_config"])) $values["check_browser_language_config"] = 0;
		SOYShop_DataSets::put("util_multi_language.config", $values);
	}
}
?>