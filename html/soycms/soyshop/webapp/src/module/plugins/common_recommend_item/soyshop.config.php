<?php
class CommonRecommendItemConfig extends SOYShopConfigPageBase{

	/**
	 * @return string
	 */
	function getConfigPage(){

		$form = SOY2HTMLFactory::createInstance("CommonRecommendItemConfigFormPage");
		$form->setConfigObj($this);
		$form->execute();
		return $form->getObject();
	}

	/**
	 * @return string
	 */
	function getConfigPageTitle(){
		return "おすすめ商品";
	}
	
}
SOYShopPlugin::extension("soyshop.config","common_recommend_item","CommonRecommendItemConfig");

class CommonRecommendItemConfigFormPage extends WebPage{
		
	function CommonRecommendItemConfigFormPage(){

	}
		
	function execute(){
		WebPage::WebPage();
		
	}
	
	function getTemplateFilePath(){
		return dirname(__FILE__) . "/soyshop.config.html";
	}

	function setConfigObj($obj) {
		$this->config = $obj;
	}
		
}

?>