<?php
class CommonItemOptionConfig extends SOYShopConfigPageBase{

	/**
	 * @return string
	 */
	function getConfigPage(){
		//下記で取得しているConfig用のページのクラスファイルを読み込み、対になるHTMLファイルを出力する
		include_once(dirname(__FILE__) . "/config/CommonItemOptionConfigFormPage.class.php");
		$form = SOY2HTMLFactory::createInstance("CommonItemOptionConfigFormPage");
		$form->setConfigObj($this);
		$form->execute();
		return $form->getObject();
	}

	/**
	 * @return string
	 */
	function getConfigPageTitle(){
		return "商品オプションプラグインの設定";
	}
}
SOYShopPlugin::extension("soyshop.config", "common_item_option", "CommonItemOptionConfig");
?>