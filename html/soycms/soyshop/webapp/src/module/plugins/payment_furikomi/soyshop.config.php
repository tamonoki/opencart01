<?php
include_once(dirname(__FILE__) . "/common.php");

class PaymentFurikomiConfig extends SOYShopConfigPageBase{

	/**
	 * @return string
	 */
	function getConfigPage(){
		$form = SOY2HTMLFactory::createInstance("PaymentFurikomiConfigFormPage");
		$form->setConfigObj($this);
		$form->execute();
		return $form->getObject();
	}

	/**
	 * @return string
	 */
	function getConfigPageTitle(){
		return "銀行振込の設定";
	}



}
SOYShopPlugin::extension("soyshop.config","payment_furikomi","PaymentFurikomiConfig");



class PaymentFurikomiConfigFormPage extends WebPage{
	
	private $config;
	
	function PaymentFurikomiConfigFormPage(){
		SOY2DAOFactory::importEntity("SOYShop_DataSets");
	}
	
	function doPost(){
		if(isset($_POST["payment_furikomi"])){
			$array = $_POST["payment_furikomi"];
			SOYShop_DataSets::put("payment_furikomi.text",$array);
			$this->config->redirect("updated");
		}
	}
	
	function execute(){
		WebPage::WebPage();

		$configText = PaymentFurikomiCommon::getConfigText();

		$this->createAdd("account","HTMLTextArea", array(
			"value" => $configText["account"],
			"name"  => "payment_furikomi[account]"
		));
		$this->createAdd("text","HTMLTextArea", array(
			"value" => $configText["text"],
			"name"  => "payment_furikomi[text]"
		));
		$this->createAdd("mail","HTMLTextArea", array(
			"value" => $configText["mail"],
			"name"  => "payment_furikomi[mail]"
		));
		
		
		$this->createAdd("updated", "HTMLModel", array(
			"visible" => isset($_GET["updated"])
		));
		
	}
	
	function getTemplateFilePath(){
		return dirname(__FILE__) . "/soyshop.config.html";
	}

	function setConfigObj($obj) {
		$this->config = $obj;
	}
}