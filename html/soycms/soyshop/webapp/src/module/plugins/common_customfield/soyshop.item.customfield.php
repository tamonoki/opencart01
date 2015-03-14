<?php
/*
 */
class CommonItemCustomField extends SOYShopItemCustomFieldBase{

	private $itemAttributeDao;

	function doPost(SOYShop_Item $item){
		$this->prepare();

		$list = (isset($_POST["custom_field"])) ? $_POST["custom_field"] : array();
		$extraFields = (isset($_POST["custom_field_extra"])) ? $_POST["custom_field_extra"] : null;

		$array = $this->itemAttributeDao->getByItemId($item->getId());

		$configs = SOYShop_ItemAttributeConfig::load(true);
		
		foreach($list as $key => $value){

			if(!isset($configs[$key])) continue;
			$extra = (isset($extraFields[$key])) ? $extraFields[$key] : array();

			try{
				if(isset($array[$key])){
					$obj = $array[$key];
					$obj->setValue($value);
					$obj->setExtraValuesArray($extra);
					$this->itemAttributeDao->update($obj);
				}else{
					$obj = new SOYShop_ItemAttribute();
					$obj->setItemId($item->getId());
					$obj->setFieldId($key);
					$obj->setValue($value);
					$obj->setExtraValuesArray($extra);

					$this->itemAttributeDao->insert($obj);
				}
			}catch(Exception $e){
				//
			}
		
			if($configs[$key]->isIndex()){
				//毎回DAOを読み込まなければソート用のカラムに値が入ってくれない
				$itemDAO = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
				$itemDAO->updateSortValue($item->getId(), $key, $value);
			}
		}
		
		//チェックボックスが非選択時の処理
		foreach($configs as $key => $value){
			
			try{			
				if(!isset($list[$key]) && isset($array[$key])){
					$obj = $array[$key];
					$obj->setValue("");
					$this->itemAttributeDao->update($obj);
				}
			}catch(Exception $e){
				//
			}
		}

	}

	function getForm(SOYShop_Item $item){
		
		$this->prepare();

		try{
			$array = $this->itemAttributeDao->getByItemId($item->getId());
		}catch(Exception $e){
			echo $e->getPDOExceptionMessage();
		}

		$html = array();
		$list = SOYShop_ItemAttributeConfig::load();

		foreach($list as $config){
			$value = (isset($array[$config->getFieldId()])) ? $array[$config->getFieldId()]->getValue() : null;
			$extraValues = (isset($array[$config->getFieldId()])) ? $array[$config->getFieldId()]->getExtraValuesArray() : null;

			$html[] = $config->getForm($value, $extraValues);
		}

		return implode("\n", $html);
	}

	/**
	 * onOutput
	 */
	function onOutput($htmlObj, SOYShop_Item $item){
		
		$this->prepare();
		
		$array = array();
		if(!is_null($item->getId())){
			try{
				$array = $this->itemAttributeDao->getByItemId($item->getId());
			}catch(Exception $e){
			}
		}

		$list = SOYShop_ItemAttributeConfig::load();
		
		foreach($list as $config){
			$value = (isset($array[$config->getFieldId()])) ? $array[$config->getFieldId()]->getValue() : null;
			
			$htmlObj->addModel($config->getFieldId() . "_visible", array(
				"visible" => (strlen(strip_tags($value)) > 0),
				"soy2prefix" => SOYSHOP_SITE_PREFIX
			));

			switch($config->getType()){

				case "image":
					$extraValues = (isset($array[$config->getFieldId()])) ? $array[$config->getFieldId()]->getExtraValuesArray() : array();
					if(strlen($config->getOutput() > 0)){
						$class = "HTMLModel";
						$attr = array(
							"attr:" . htmlspecialchars($config->getOutput()) => $value,
							"soy2prefix" => SOYSHOP_SITE_PREFIX
						);
						foreach($extraValues as $key => $extraValue){
							$attr["attr:" . trim(htmlspecialchars($key))] = trim(htmlspecialchars($extraValue));
						}
					}else{
						$class = "HTMLImage";
						$attr = array(
							"src" => $value,
							"soy2prefix" => SOYSHOP_SITE_PREFIX,
							"visible" => ($value) ? true : false
						);
						foreach($extraValues as $key => $extraValue){
							$attr[trim(htmlspecialchars($key))] = trim(htmlspecialchars($extraValue));
						}
					}
					
					
					$htmlObj->createAdd($config->getFieldId(), $class, $attr);
					
					$htmlObj->addLink($config->getFieldId() . "_link",  array(
						"link" => $value,
						"soy2prefix" => SOYSHOP_SITE_PREFIX
					));
					
					$htmlObj->addLabel($config->getFieldId() . "_text", array(
						"text" => $value,
						"soy2prefix" => SOYSHOP_SITE_PREFIX
					));
					break;
					
				case "textarea":
					if(strlen($config->getOutput()) > 0){
						$htmlObj->addModel($config->getFieldId(), array(
							"attr:" . htmlspecialchars($config->getOutput()) => nl2br($value),
							"soy2prefix" => SOYSHOP_SITE_PREFIX
						));
					}else{
						$htmlObj->addLabel($config->getFieldId(), array(
							"html" => nl2br($value),
							"soy2prefix" => SOYSHOP_SITE_PREFIX
						));
					}
					break;
				case "link":
					if(strlen($config->getOutput()) > 0){
						$htmlObj->addModel($config->getFieldId(), array(
							"attr:" . htmlspecialchars($config->getOutput()) => nl2br($value),
							"soy2prefix" => SOYSHOP_SITE_PREFIX
						));
					}else{
						$htmlObj->addLink($config->getFieldId(), array(
							"link" => nl2br($value),
							"soy2prefix" => SOYSHOP_SITE_PREFIX
						));
					}
					
					$htmlObj->addLabel($config->getFieldId() . "_text", array(
						"text" => $value,
						"soy2prefix" => SOYSHOP_SITE_PREFIX
					));
					
					break;

				default:
					if(strlen($config->getOutput()) > 0){
						if($config->getOutput() == "href" && $config->getType() != "link"){
							$htmlObj->addLink($config->getFieldId(), array(
								"link" => $value,
								"soy2prefix" => SOYSHOP_SITE_PREFIX
							));
						}else{
							$htmlObj->addModel($config->getFieldId(), array(
								"attr:" . htmlspecialchars($config->getOutput()) => $value,
								"soy2prefix" => SOYSHOP_SITE_PREFIX
							));	
						}
					}else{
						$htmlObj->addLabel($config->getFieldId(), array(
							"html" => $value,
							"soy2prefix" => SOYSHOP_SITE_PREFIX
						));
					}
			}
		}
	}

	function onDelete($id){
		$attributeDAO = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		$attributeDAO->deleteByItemId($id);
	}

	function prepare(){
		if(!$this->itemAttributeDao){
			$this->itemAttributeDao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		}
	}
}

SOYShopPlugin::extension("soyshop.item.customfield","common_customfield","CommonItemCustomField");
?>