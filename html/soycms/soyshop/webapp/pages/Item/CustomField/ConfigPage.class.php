<?php 
/**
 * @class Item.CustomField.ConfigPage
 * @date 2010-02-16T21:14:24+09:00
 * @author SOY2HTMLFactory
 */ 
class ConfigPage extends WebPage{
	
	function doPost(){
		
		if(isset($_POST["import"]) && strlen(trim($_POST["configure"])) > 0){
			$value = trim($_POST["configure"]);
			$value = base64_decode($value);
			
			$dao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
			$config = soy2_unserialize($value);
			if(is_array($config)){
				SOYShop_ItemAttributeConfig::save($config);
			}else{
				SOY2PageController::jump("Item.CustomField.Config?failed");
			}
			
			SOY2PageController::jump("Item.CustomField?updated");
			exit;
		}
		
	}	
	
	function ConfigPage(){
		WebPage::WebPage();
		
		$this->addForm("import_form");
		
		$dao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		$config = SOYShop_ItemAttributeConfig::load();
		$value = base64_encode(soy2_serialize($config));		
		
		$this->addTextArea("export_value", array(
			"value" => $value,
			"style" => "height:200px;",
			"onclick" => "this.select();"
		));
		
		$this->addTextArea("import_value", array(
			"name" => "configure",
			"style" => "height:200px;"
		));
	}
}
?>