<?php
class UtilMultiLanguageConfig extends SOYShopConfigPageBase{
	
	/**
	 * @return string
	 */
	function getConfigPage(){
		include_once(dirname(__FILE__)  . "/config/UtilMultiLanguageConfigFormPage.class.php");
		$form = SOY2HTMLFactory::createInstance("UtilMultiLanguageConfigFormPage");
		$form->setConfigObj($this);
		$form->execute();
		return $form->getObject();
	}
	
	/**
	 * @return string
	 */
	function getConfigPageTitle(){
		return "多言語サイト設定";
	}
}
SOYShopPlugin::extension("soyshop.config", "util_multi_language", "UtilMultiLanguageConfig");
?>