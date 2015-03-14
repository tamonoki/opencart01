<?php
SOY2DAOFactory::importEntity("SOYShop_DataSets");
include_once(dirname(__FILE__) . "/common.php");

class FurikomiPaymentModule extends SOYShopPayment{

	function onSelect(CartLogic $cart){
		//属性の登録
		$cart->setOrderAttribute("payment_furikomi","支払方法","銀行振込でのお支払い");

		//後でチェックするため見えないモジュールを追加
		$module = new SOYShop_ItemModule();
		$module->setId("payment_furikomi");
		$module->setName("支払い方法：銀行振込");
		$module->setType("payment_module");	//typeを指定しておくといいことがある
		$module->setPrice(0);
		$module->setIsVisible(false);
		$cart->addModule($module);
	}

	function getName(){
		return "銀行振込";
	}

	function getDescription(){
		return nl2br($this->getAccount());
	}

	/**
	 * 振込先の取得
	 */
	function getAccount(){
		$array = PaymentFurikomiCommon::getConfigText();
		$res = (isset($array["text"])) ? $array["text"] : "";

		//replace
		if(isset($array["account"])){
			$res = str_replace("#ACCOUNT#", $array["account"], $res);
		}
		
		return $res;
	}

}
SOYShopPlugin::extension("soyshop.payment","payment_furikomi","FurikomiPaymentModule");
?>