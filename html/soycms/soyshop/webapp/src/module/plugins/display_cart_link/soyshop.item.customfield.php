<?php
/*
 */
class DisplayCartLink extends SOYShopItemCustomFieldBase{

	function doPost(SOYShop_Item $item){
			
		$dao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		$itemDAO = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		$array = $dao->getByItemId($item->getId());
		
		$configs = SOYShop_ItemAttributeConfig::load(true);
			
		$key = "display_cart_link_plugin";
		$value = 1;

		try{
			$dao->delete($item->getId(), $key);
		}catch(Exception $e){
			//	
		}

		if(isset($_POST["display_cart_link_plugin"])){
			
			try{
				$obj = new SOYShop_ItemAttribute();
				$obj->setItemId($item->getId());
				$obj->setFieldId($key);
				$obj->setValue($value);
	
				$dao->insert($obj);
			}catch(Exception $e){
				//
			}
		}			
	}

	function getForm(SOYShop_Item $item){

		$dao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		try{
			$obj = $dao->get($item->getId(), "display_cart_link_plugin");
		}catch(Exception $e){
			$obj = new SOYShop_ItemAttribute();;
		}

		$value = ($obj->getValue() == 1);

		$html = array();
		
		$html[] = "<dt>カートに入れるボタンの設定</dt>";

		$html[] = "<dd>";
		if($value){
			$html[] = "<input type=\"checkbox\" name=\"display_cart_link_plugin\" value=\"1\" id=\"display_cart_link\" checked=\"checked\" />";
		}else{
			$html[] = "<input type=\"checkbox\" name=\"display_cart_link_plugin\" value=\"1\" id=\"display_cart_link\" />";
		}
		$html[] = "<label for\"display_cart_link\">カートに入れるボタンを非表示にする</label>";
		$html[] = "</dd>";
		
		return implode("\n", $html);
	}

	/**
	 * onOutput
	 */
	function onOutput($htmlObj, SOYShop_Item $item){
		$dao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		try{
			$obj = $dao->get($item->getId(), "display_cart_link_plugin");
		}catch(Exception $e){
			$obj = new SOYShop_ItemAttribute();
		}
		
		//カートを表示する場合はtrue
		$hasCart = ($obj->getValue() == 1) ? false : true;
		
		$htmlObj->addModel("has_cart_link", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"visible" => ($hasCart === true)
		));
		
		$htmlObj->addModel("no_cart_link", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"visible" => ($hasCart === false)
		));

	}

	function onDelete($id){
		$attributeDAO = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		$attributeDAO->deleteByItemId($id);
	}

}

SOYShopPlugin::extension("soyshop.item.customfield", "display_cart_link", "DisplayCartLink");
?>