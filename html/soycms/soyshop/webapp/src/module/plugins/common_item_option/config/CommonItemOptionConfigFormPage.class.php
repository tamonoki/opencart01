<?php
class CommonItemOptionConfigFormPage extends WebPage{

	private $types = array("select" => "セレクトボックス", "radio" => "ラジオボタン");

    function CommonItemOptionConfigFormPage() {
    	SOY2DAOFactory::importEntity("SOYShop_DataSets");
    }
    
    function doPost(){
    	$logic = SOY2Logic::createInstance("module.plugins.common_item_option.logic.ItemOptionLogic");
    	
    	if(isset($_POST["create"])){
			
			$array = $logic->getOptions();
			
			$obj["name"] = $_POST["option_new_name"];
			$obj["type"] = $_POST["option_type"];
			
			$array[$_POST["option_id"]] = $obj;
			
			SOYShop_DataSets::put("item_option", soy2_serialize($array));
			
			SOY2PageController::jump("Config.Detail?plugin=common_item_option&updated=created");
			
		}
		
		if(isset($_POST["update_submit"])){
			$optionId = $_POST["update_submit"];

			$array = $logic->getOptions();
			$array[$optionId]["name"] = $_POST["obj"]["name"];
			$array[$optionId]["type"] = $_POST["obj"]["type"];
			
			SOYShop_DataSets::put("item_option", soy2_serialize($array));
		}
		
		//delete
		if(isset($_POST["delete_submit"])){
			$optionId = $_POST["delete_submit"];

			$array = $logic->getOptions();
			$array[$optionId] = null;
			unset($array[$optionId]);
			
			SOYShop_DataSets::put("item_option", soy2_serialize($array));
		}

		SOY2PageController::jump("Config.Detail?plugin=common_item_option&updated");
    }
    
    function execute(){
    	WebPage::WebPage();
    	
    	$this->addForm("create_form");
    	
    	$this->addModel("updated", array(
			"visible" => (isset($_GET["updated"]))
		));
		
		$this->addModel("error", array(
			"visible" => (isset($_GET["error"]))
		));
		
		$logic = SOY2Logic::createInstance("module.plugins.common_item_option.logic.ItemOptionLogic");
		
		$this->addSelect("option_type_select", array(
			"options" => $logic->getTypes(),
			"name" => "option_type"
		));
		
		$list = $logic->getOptions();
		$this->createAdd("option_list", "OptionList", array(
			"list" => $list,
			"types" => $this->types
		));
    }

    function setConfigObj($obj) {
		$this->config = $obj;
	}
}

class OptionList extends HTMLList{
	
	private $types;

	protected function populateItem($entity, $key){
		
		/* 情報表示用 */
		$this->addLabel("label", array(
			"text" => (isset($entity["name"])) ? $entity["name"] : "",
			"attr:id" => "label_text_" . $key,
		));
		
		$this->addLabel("type", array(
			"text"=> (isset($entity["type"])) ? $this->types[$entity["type"]] : "セレクトボックス",
			"attr:id" => "type_text_" . $key,
		));

		$this->addLabel("field_text", array(
			"text"=> (isset($key)) ? $key : "",
		));

		$this->addLabel("display_form", array(
			"text"=>'cms:id="' . $key . '"'
		));
		
		/* 設定変更用 */
		$this->addLink("toggle_update", array(
			"link" => "javascript:void(0)",
			"onclick" => '$(\'#label_input_' . $key . '\').show();' .
						'$(\'#label_text_' . $key . '\').hide();' .
						'$(\'#type_select_' . $key . '\').show();' .
						'$(\'#type_text_' . $key . '\').hide();' .
						'$(\'#update_link_' . $key . '\').show();' .
						'$(this).hide();'
		));

		$this->addLink("update_link", array(
			"link" => "javascript:void(0)",
			"attr:id" => "update_link_" . $key,
			"onclick" => '$(\'#update_submit_' . $key . '\').click();' .
						'return false;'
		));

		$this->addInput("update_submit", array(
			"name" => "update_submit",
			"value" => $key,
			"attr:id" => "update_submit_" . $key
		));

		$this->addInput("label_input", array(
			"name" => "obj[name]",
			"attr:id" => "label_input_" . $key,
			"value" => (isset($entity["name"])) ? $entity["name"] : "",
		));
		
		$this->addSelect("type_select", array(
			"name" => "obj[type]",
			"options" => $this->types,
			"attr:id" => "type_select_" . $key,
			"selected" => (isset($entity["type"])) ? $entity["type"] : "セレクトボックス" 
		));
		
		/* 順番変更用 */
		$this->addInput("option_id", array(
			"name" => "option_id",
			"value" => $key,
		));

		/* 削除用 */
		$this->addInput("delete_submit", array(
			"name" => "delete_submit",
			"value" => $key,
			"attr:id" => "delete_submit_" . $key
		));

		$this->addLink("delete", array(
			"text"=>"削除",
			"link"=>"javascript:void(0);",
			"onclick"=>'if(confirm("delete \"' . $entity["name"] . '\"?")){$(\'#delete_submit_' . $key . '\').click();}return false;'
		));
	}

	function getTypes() {
		return $this->types;
	}
	function setTypes($types) {
		$this->types = $types;
	}
}
?>