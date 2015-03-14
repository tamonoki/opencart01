<?php

class CommonPointPayment extends SOYShopPointPaymentBase{
	private $config;
	
	function doPost($param, $userId){
		
		$cart = $this->getCart();
		
		if(isset($param) && $param == 1){

			$module = new SOYShop_ItemModule();
			$module->setId("point_payment");
			$module->setName(MessageManager::get("MODULE_NAME_POINT_PAYMENT"));
			$module->setType("point_payment_module");	//typeを指定すると同じtypeのモジュールは同時使用できなくなる
	
			$point = $this->getPoint($userId);
	
			$module->setPrice(0 - $point);//負の値
		
			if($point > 0){
				$cart->addModule($module);
			}else{
				$cart->removeModule("point_payment");
			}

			//属性の登録
			$cart->setAttribute("point_payment", $point);
			$cart->setOrderAttribute("point_payment", MessageManager::get("MODULE_NAME_POINT_PAYMENT"), MessageManager::get("MODULE_DESCRIPTION_POINT_PAYMENT", array("point" => $point)));
		}
	}
	
	function clear(){
		
		$cart = $this->getCart();
		
		$cart->clearAttribute("point_payment");
		$cart->clearOrderAttribute("point_payment");
		$cart->removeModule("point_payment");
	}
	
	function order(){
		$cart = $this->getCart();
		$cart->clearOrderAttribute("point_paiment");
	}

	function hasError($param){
		$cart = $this->getCart();
	}
	
	function getError(){
		$cart = $this->getCart();
		return $cart->getAttribute("point_payment.error");
	}

	function getName(){
		return MessageManager::get("MODULE_NAME_POINT_PAYMENT");
	}
	
	function getDescription($userId){
		
		$cart = $this->getCart();
		$point = $this->displayPoint($userId);				
		$value = $cart->getAttribute("point_payment");
	
		$html = array();
		if(isset($value)){
			$html[] = "<input type=\"checkbox\" name=\"point_module\" value=\"1\" id=\"point_payment\" checked=\"checked\">";
		}else{
			$html[] = "<input type=\"checkbox\" name=\"point_module\" value=\"1\" id=\"point_payment\">";
		}
		$html[] = "<label for=\"point_payment\">" . MessageManager::get("MODULE_DESCRIPTION_POINT_PAYMENT", array("point" => $point)) . "</label>";

		return implode("", $html);
	}
	
	function displayPoint($userId){
		$logic = SOY2Logic::createInstance("module.plugins.common_point_base.logic.PointBaseLogic");
		return $logic->getPointByUserId($userId)->getPoint();
	}
	
	function getPoint($userId){
		$cart = $this->getCart();
		
		$logic = SOY2Logic::createInstance("module.plugins.common_point_base.logic.PointBaseLogic");
		$point = $logic->getPointByUserId($userId)->getPoint();
		
		$total = $cart->getTotalPrice();
		
		if($point <= $total){
			$price = $point;
		}else{
			$price = $total;
		}
		
		return $price;
	}
}
SOYShopPlugin::extension("soyshop.point.payment", "common_point_payment", "CommonPointPayment");
?>