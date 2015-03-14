<?php

class CreatePage extends WebPage{

	function doPost(){

		if(isset($_POST["Item"]) && soy2_check_token()){
			$item = (object)$_POST["Item"];

			$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
			$logic = SOY2Logic::createInstance("logic.shop.item.ItemLogic");

			$item = SOY2::cast("SOYShop_Item",$item);
			$item->setType($_POST["ItemType"]);

			//
			if($item->getType() == "child"){
				if(isset($_POST["group_item_id"]))$item->setType($_POST["group_item_id"]);
			}

			if($item->getType() == "download"){
				$dir = SOYSHOP_SITE_DIRECTORY . "download/" . $item->getCode() . "/";
				mkdir($dir, 0777, true);

				//.htaccessを作成する
				file_put_contents($dir.".htaccess","deny from all");
				//index.html
				file_put_contents($dir."index.html","<!-- empty -->");
			}

			if($logic->validate($item)){

				$id = $logic->create($item);

				SOY2PageController::jump("Item.Detail.$id?updated=created");
				exit;
			}


			$this->obj = $item;
			$this->errors = $logic->getErrors();
		}
	}

	var $obj;
	var $errors = array();

    function CreatePage() {
    	
    	$session = SOY2ActionSession::getUserSession();
		$appLimit = $session->getAttribute("app_shop_auth_limit");
    	
    	//管理制限者で商品の追加を開こうとしたとき、商品一覧にリダイレクト
		if($appLimit == false){
			SOY2PageController::jump("Item");
		}
    	
    	WebPage::WebPage();

		$this->createAdd("create_form","HTMLForm");

    	$this->buildForm();
    }

    function buildForm(){

		$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		$obj = ($this->obj) ? $this->obj : new SOYShop_Item();

		if(isset($_GET["parent"])){
			$obj->setType($_GET["parent"]);
		}

		$this->createAdd("item_name","HTMLInput", array(
    		"name" => "Item[name]",
    		"value" => $obj->getName()
    	));

    	$this->createAdd("item_code","HTMLInput", array(
    		"name" => "Item[code]",
    		"value" => $obj->getCode()
    	));

    	$this->createAdd("item_stock","HTMLInput", array(
    		"name" => "Item[stock]",
    		"value" => $obj->getStock()
    	));

    	$this->createAdd("item_price","HTMLInput", array(
    		"name" => "Item[price]",
    		"value" => $obj->getPrice()
    	));

		$config = $obj->getConfigObject();
    	$this->createAdd("item_description","HTMLTextArea", array(
    		"name" => "Item[config][description]",
    		"value" => @$config["description"]
    	));

    	$categoryDAO = SOY2DAOFactory::create("shop.SOYShop_CategoryDAO");
		$array = $categoryDAO->get();

		$this->createAdd("category_tree", "_base.MyTreeComponent", array(
			"list" => $array,
			"selected" => $obj->getCategory()
		));

		$this->createAdd("item_category","HTMLInput", array(
			"name" => "Item[category]",
			"value" =>$obj->getCategory(),
			"attr:id" => "item_category"
		));

		$this->createAdd("item_category_text","HTMLLabel", array(
			"text" => (isset($array[$obj->getCategory()])) ? $array[$obj->getCategory()]->getName() : "選択してください",
			"attr:id" => "item_category_text"
		));

		/*
		 * グループ周り
		 */
		$itemType = (is_numeric($obj->getType())) ? "child" : $obj->getType();
		$this->createAdd("item_type_hidden","HTMLInput", array(
			"name" => "ItemType",
			"value" => $itemType
		));
		$this->createAdd("radio_type_normal","HTMLCheckbox", array(
			"elementId" => "radio_type_normal",
			"name" => "item_type",
			"value" => "single",
			"selected" => ($obj->getType() == "single"),
			"onclick" => '$(\'#item_type_hidden\').val("single");'
		));
		$this->createAdd("radio_type_group","HTMLCheckbox", array(
			"elementId" => "radio_type_group",
			"name" => "item_type",
			"value" => "group",
			"selected" => ($obj->getType() == "group"),
			"onclick" => '$(\'#item_type_hidden\').val("group");'
		));
		$this->createAdd("radio_type_child","HTMLCheckbox", array(
			"elementId" => "radio_type_child",
			"name" => "item_type",
			"value" => "child",
			"selected" => ($itemType == "child"),
			"onclick" => '$(\'#item_type_hidden\').val("child");$(\'#group_item_div\').show();'
		));
		$this->createAdd("radio_type_download","HTMLCheckbox", array(
			"elementId" => "radio_type_download",
			"name" => "item_type",
			"value" => "download",
			"selected" => ($itemType == "download"),
			"onclick" => '$(\'#item_type_hidden\').val("download");'
		));

		$groupItems = $dao->getByType("group");

		$this->createAdd("group_item_select","HTMLSelect", array(
			"name" => "group_item_id",
			"options" => $groupItems,
			"property" => "name",
			"selected" => $obj->getType()
		));

		$this->createAdd("group_item_exists","HTMLModel", array(
			"visible" => (count($groupItems) > 0)
		));

		//ダウンロード販売プラグインがアクティブの時に表示
		$this->createAdd("download_exists","HTMLModel", array(
			"visible" => class_exists("SOYShopPluginUtil") && (SOYShopPluginUtil::checkIsActive("download_assistant"))
		));

    	//error
		foreach(array("name","code") as $key){
			$this->createAdd("error_$key","HTMLLabel", array(
				"text" => @$this->errors[$key],
				"visible" => (strlen(@$this->errors[$key]))
			));
		}
    }

    function getScripts(){
		$root = SOY2PageController::createRelativeLink("./js/");
		return array(
			$root . "jquery/treeview/jquery.treeview.pack.js",
		);
	}

	function getCSS(){
		$root = SOY2PageController::createRelativeLink("./js/");
		return array(
			$root . "jquery/treeview/jquery.treeview.css",
			$root . "tree.css",
		);
	}
}
?>