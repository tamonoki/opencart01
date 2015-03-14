<?php

class CommonMailbuilder extends SOYShopOrderMailBuilder{

	private $itemDao;

	/**
	 * 注文者向け注文情報を作る
	 */
	public function buildOrderMailBodyForUser(SOYShop_Order $order, SOYShop_User $user){
		return $this->buildOrderMailBody($order, $user, "user");
	}

	/**
	 * 管理者向け注文情報を作る
	 */
	public function buildOrderMailBodyForAdmin(SOYShop_Order $order, SOYShop_User $user){
		return $this->buildOrderMailBody($order, $user, "admin");
	}
	
	/**
	 * メールの文面を作成する
	 * @param object SOYShop_Order, object SOYShop_User, string type
	 * @return string content
	 */
	protected function buildOrderMailBody(SOYShop_Order $order, SOYShop_User $user, $type = "user"){
		
		$this->prepare();

		$content = CommonMailbuilderCommon::getMailContent($type);
		
		$logic = SOY2Logic::createInstance("logic.order.OrderLogic");
		$orderItems = $logic->getItemsByOrderId($order->getId());
		
		
		list($itemContent, $subprice) = $this->buildItemOrderInfo($orderItems);
		list($moduleContent, $moduleprice) = $this->buildModuleInfo($order);
		$totalprice = $subprice + $moduleprice;
		
			
		
		/** ここからひたすら文字列の置換 **/
		$content = str_replace("#TRACKING_NUMBER#", $order->getTrackingNumber(), $content);
		$content = str_replace("#ORDER_DATE#", date("Y-m-d (D) H:i:s", $order->getOrderDate()), $content);		
		$content = str_replace("#ITEM_ORDER_LIST#", $itemContent, $content);
		$content = str_replace("#SUBTOTAL#", $subprice, $content);
		$content = str_replace("#MODULE_LIST#", $moduleContent, $content);
		$content = str_replace("#TOTAL#", $totalprice, $content);
		
		$addressTypes = array("send", "claimed");
		foreach($addressTypes as $addressType){			
			$array = $this->buildAddressArray($order, $addressType);
			$prefix = strtoupper($addressType);
			
			$content = str_replace("#" . $prefix . "_OFFICE#", $array["office"], $content);
			$content = str_replace("#" . $prefix . "_NAME#", $array["name"], $content);
			$content = str_replace("#" . $prefix . "_READING#", $array["reading"], $content);
			$content = str_replace("#" . $prefix . "_ZIPCODE#", $array["zipCode"], $content);
			$content = str_replace("#" . $prefix . "_AREA#", $array["area"], $content);
			$content = str_replace("#" . $prefix . "_ADDRESS1#", $array["address1"], $content);
			$content = str_replace("#" . $prefix . "_ADDRESS2#", $array["address2"], $content);
			$content = str_replace("#" . $prefix . "_TELEPHONE#", $array["telephoneNumber"], $content);
		}
		
		//メールアドレス
		$content = str_replace("#CLAIMED_MAILADDRESS#", $user->getMailAddress(), $content);
		
		//備考
		$content = str_replace("#MEMO#", $this->buildMemo($order), $content);
		
		return $content;
	}
	
	/**
	 * 商品リストと小計を返す
	 * @param array SOYShop_Ordersの配列
	 * @return string content, integer subprice
	 */
	protected function buildItemOrderInfo($orderItems){
		
		$contents = array();
		$subprice = 0;
		
		$itemColumnSize = 0;
		foreach($orderItems as $orderItem){
			$itemColumnSize = max($itemColumnSize,mb_strwidth($orderItem->getItemName()));
		}
		$itemColumnSize += "5";
		
		foreach($orderItems as $orderItem){
			try{
				$item = $this->itemDao->getById($orderItem->getItemId());
			}catch(Exception $e){
				$item = new SOYShop_Item();
				$item->getName($orderItem->getItemName());
				$item->getCode("-");
			}
		
			$str  = $this->printColumn($orderItem->getItemName(),"left",$itemColumnSize);
			$str .= $this->printColumn($item->getCode(),"left");
			$str .= $this->printColumn(number_format($orderItem->getItemCount())." 点");
			$str .= $this->printColumn(number_format($orderItem->getItemPrice())." 円");

			$subprice += $orderItem->getTotalPrice();

			$contents[] = $str;
		}
		
		return array(implode("\n", $contents), $subprice);
	}
	
	protected function buildModuleInfo(SOYShop_Order $order){
		
		$contents = array();
		
		$modules = $order->getModuleList();
		$moduleprice = 0;

		foreach($modules as $module){
			if(!$module->isVisible()) continue;
			$str = $module->getName();
			$space = (mb_strlen($module->getName()) < 4) ? "\t\t" : "\t";
			$str .= $space . number_format($module->getPrice())." 円";

			$contents[] = $str;
			
			$moduleprice += $module->getPrice();
		}
		
		return array(implode("\n", $contents), $moduleprice);
	}
	
	protected function buildAddressArray(SOYShop_Order $order, $type = "address"){
		
		if($type == "claimed"){
			$array = $order->getClaimedAddressArray();
		}else{
			$array = $order->getAddressArray();
		}
		
		//念のために空のキーをチェックしておく
		$array["name"] = (isset($array["name"])) ? $array["name"] : "";
		$array["reading"] = (isset($array["reading"])) ? $array["reading"] : "";
		$array["zipCode"] = (isset($array["zipCode"])) ? $array["zipCode"] : "";
		$array["area"] = (isset($array["area"])) ? SOYShop_Area::getAreaText($array["area"]) : "";
		$array["address1"] = (isset($array["address1"])) ? $array["address1"] : "";
		$array["address2"] = (isset($array["address2"])) ? $array["address2"] : "";
		$array["telephoneNumber"] = (isset($array["telephoneNumber"])) ? $array["telephoneNumber"] : "";
		$array["office"] = (isset($array["office"])) ? $array["office"] : "";
		
		return $array;
	}
	
	/**
	 * 備考
	 * @return Array
	 */
	protected function buildMemo(SOYShop_Order $order){
		$contents = array();

		$attr = $order->getAttributeList();
		if(!isset($attr["memo"])) return "";

		$memo = $attr["memo"];
		if(empty($memo["value"])) return "";

    	$contents[] = $memo["value"];

		return implode("\n", $contents);
	}
	
	protected function prepare(){
		SOY2::import("module.plugins.common_mailbuilder.common.CommonMailbuilderCommon");
		SOY2::import("domain.config.SOYShop_Area");
		$this->itemDao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
	}
}

SOYShopPlugin::extension("soyshop.order.mailbuilder", "common_mailbuilder", "CommonMailbuilder");