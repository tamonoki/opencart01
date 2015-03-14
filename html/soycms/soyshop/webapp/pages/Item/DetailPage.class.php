<?php
SOY2::import("domain.config.SOYShop_ShopConfig");
class DetailPage extends WebPage{

	function doPost(){
		
		if(!empty($_FILES) && empty($_POST)){
			$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
			$attrDAO = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
			$item = $dao->getById($this->id);
			
			$res = array();
			foreach($_FILES["custom_field"]["name"] as $key => $filename){
				$filename = strtolower(str_replace("%","",rawurlencode($filename)));
				$tmpname = $_FILES["custom_field"]["tmp_name"][$key];
				$pathinfo = pathinfo($filename);
				
				//get unique file name
				$counter = 0;
				$filepath = "";
				$name = "";
				while(true){
					$name = ($counter > 0) ? $pathinfo["filename"] . "_" . $counter . "." . $pathinfo["extension"] : $pathinfo["filename"] . "." . $pathinfo["extension"];
					$filepath = $item->getAttachmentsPath() . $name;
		
					if(!file_exists($filepath)){
						break;
					}
					$counter++;
				}
				
				//一回でも失敗した場合はfalseを返して終了（rollbackは無し）
				$result = move_uploaded_file($tmpname,$filepath);
				@chmod($filepath,0604);
				
				if(!$result){
					$res = array(
						"result" => -1,
						"message" => "失敗しました"
					);
					break;
				}
				
				$res = array(
					"result" => filesize($filepath),
					"url" => $item->getAttachmentsUrl() . $name,
					"message" => "アップロードしました\nURL=" . $item->getAttachmentsUrl() . $name.""
				);
				
				try{
					$field = $attrDAO->get($this->id,$key);
					$field->setValue($item->getAttachmentsUrl() . $name);
					$attrDAO->update($field);
				}catch(Exception $e){
					$field = new SOYShop_ItemAttribute();
					$field->setItemId($item->getId());
					$field->setFieldId($key);
					$field->setValue($item->getAttachmentsUrl() . $name);
					$attrDAO->insert($field);
				}
			}
			
			echo json_encode($res);
			
			exit;
		}

		if(isset($_POST["Item"]) && soy2_check_token()){
			
			//マルチカテゴリモードの時、カテゴリ配列から一番最初の値を取得しておく
			if(isset($_POST["Item"]["multi"])){
				$categories = explode(",", $_POST["Item"]["multi"]["categories"]);
				//配列を綺麗にする
				$array = array();
				foreach($categories as $category){
					if(strlen($category) > 0)$array[] = $category;
				}
				$categories = $array;
				sort($categories);
				$_POST["Item"]["category"] = $categories[0];
			}

			$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
			$logic = SOY2Logic::createInstance("logic.shop.item.ItemLogic");

			$newItem = $_POST["Item"];
			
			//公開期限をタイムスタンプに変換
			if(isset($newItem["openPeriodStart"]) && strlen($newItem["openPeriodStart"]) > 0){
				$newItem["openPeriodStart"] = $this->convertTimestampByDate($newItem["openPeriodStart"]);
			}
			
			if(isset($newItem["openPeriodEnd"]) && strlen($newItem["openPeriodEnd"]) > 0){
				$newItem["openPeriodEnd"] = $this->convertTimestampByDate($newItem["openPeriodEnd"]);
			}

			$item = $dao->getById($this->id);
			
			//在庫のチェック
			$oldStock = $item->getStock();
			
			$obj = (object)$newItem;

			SOY2::cast($item, $obj);

			$alias = null;
			if(isset($_POST["custom_alias"])){
				$alias = $_POST["custom_alias"];
				$item->setAlias($alias);
			}

			if($logic->validate($item)){

				if(isset($_POST["do_close"])){
					$item->setIsOpen(SOYShop_Item::NO_OPEN);
				}
				if(isset($_POST["do_open"])){
					$item->setIsOpen(SOYShop_Item::IS_OPEN);
				}

				$logic->update($item,$alias);
				$id = $this->id;

				SOYShopPlugin::load("soyshop.item.customfield");
				SOYShopPlugin::invoke("soyshop.item.customfield", array(
					"item" => $item
				));
				
				//マルチカテゴリモード
				if(isset($categories) && is_array($categories)){
					$logic->updateCategories($categories, $id);
				}
				
				SOYShopPlugin::load("soyshop.item.update");
				SOYShopPlugin::invoke("soyshop.item.update", array(
					"item" => $item,
					"old" => $oldStock
				));

				SOY2PageController::jump("Item.Detail.$id?updated");

				exit;
			}


			$this->obj = $item;
			$this->errors = $logic->getErrors();
		}

		if(isset($_POST["upload"])){
			$urls = $this->uploadImage();

			echo "<html><head>";
			echo "<script type=\"text/javascript\">";
			if($urls !== false){
				foreach($urls as $url){
					echo 'window.parent.ImageSelect.notifyUpload("' . $url . '");';
				}
			}else{
				echo 'alert("failed");';
			}
			echo "</script></head><body></body></html>";
			exit;
		}
	}

	var $id;
	var $errors = array();
	var $obj;
	var $config;

	function DetailPage($args) {
		$this->id = (isset($args[0])) ? (int)$args[0] : null;
		
		$this->config = SOYShop_ShopConfig::load();

		WebPage::WebPage();

		$this->addForm("update_form");

		$this->buildForm($this->id);
		//入荷通知周り
		$this->buildNoticeButton();
		$this->buildFavoriteButton();
	}

	function buildForm($id){
		
		$session = SOY2ActionSession::getUserSession();
		$appLimit = $session->getAttribute("app_shop_auth_limit");
		
		//appLimitがfalseの場合は、在庫以外の項目をreadOnlyにする
		$readOnly = (!$appLimit) ? true : false;
		
		$itemDAO = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		try{
			$item = ($this->obj) ? $this->obj : $itemDAO->getById($id);
		}catch(Exception $e){
			SOY2PageController::jump("Item");
			exit;
		}
		
		//削除フラグのチェック
		if($item->getIsDisabled() == SOYShop_Item::IS_DISABLED){
			SOY2PageController::jump("Item");
		}

		$pageDAO = SOY2DAOFactory::create("site.SOYShop_PageDAO");

		$this->addLabel("open_text", array(
			"text" => "[" . $item->getPublishText() . "]",
			"visible" => ($item->getIsOpen() < SOYShop_Item::IS_OPEN)
		));

		$this->addModel("is_sale", array(
			"visible" => ($item->isOnSale())
		));

		$this->addModel("item_name_wrap", array(
			"visible" => ($item->getIsOpen() < SOYShop_Item::IS_OPEN) || ($item->isOnSale())
		));

		$this->addLabel("item_name_text", array(
			"text" => $item->getName()
		));

		$this->addInput("item_name", array(
			"name" => "Item[name]",
			"value" => $item->getName(),
			"readonly" => $readOnly
		));

		$this->addInput("item_code", array(
			"name" => "Item[code]",
			"value" => $item->getCode(),
			"readonly" => $readOnly
		));

		$this->addInput("item_stock", array(
			"name" => "Item[stock]",
			"value" => $item->getStock()
		));

		//注文数
		$this->addLabel("item_order_count", array(
			"text" => $this->getOrderConunt($item)
		));

		//通常価格
		$this->addInput("item_normal_price", array(
			"name" => "Item[price]",
			"value" => $item->getPrice(),
			"readonly" => $readOnly
		));

		//セール価格
		$this->addInput("item_sale_price", array(
			"name" => "Item[salePrice]",
			"value" => $item->getSalePrice(),
			"readonly" => $readOnly
		));

		$this->addCheckBox("item_is_sale", array(
			"elementId" => "item_is_sale",
			"name" => "Item[saleFlag]",
			"value" => SOYShop_Item::IS_SALE,
			"isBoolean" => true,
			"selected" => ($item->isOnSale()),
		));

		//定価
		$this->addInput("item_list_price", array(
			"name" => "Item[config][list_price]",
			"value" => (int)$item->getAttribute("list_price"),
			"readonly" => $readOnly
		));


		$detailPageId = $item->getDetailPageId();
		$editable = false;
		try{
			$page = $pageDAO->getById($detailPageId);
			$url = soyshop_get_page_url($page->getUri(), $item->getAlias());
			$url = str_replace($item->getAlias(), "<b>" . $item->getAlias() . "</b>", $url);
			$editable = true;
		}catch(Exception $e){
			$url = MessageManager::get("ERROR_ITEM_SELECT_DETAIL_PAGE");
		}

		$this->addLabel("item_url_text", array(
			"html" => $url
		));

		$this->addModel("item_alias_edit", array(
			"visible" => $editable
		));

		$this->addInput("item_alias", array(
			"name" => "custom_alias",
			"value" => (isset($_POST["custom_alias"])) ? $_POST["custom_alias"] : $item->getAlias(),
			"readonly" => $readOnly
		));

		$this->addModel("custom_alias_input", array(
			"style" => (isset($this->errors["alias"])) ? "" : "display:none;"
		));

		$detailPages = $pageDAO->getByType(SOYShop_Page::TYPE_DETAIL);
		$this->addSelect("detail_page_list", array(
			"name" => "Item[detailPageId]",
			"options" => $detailPages,
			"selected" => $item->getDetailPageId(),
			"property" => "name"
		));

		/* category */
		$dao = SOY2DAOFactory::create("shop.SOYShop_CategoryDAO");
		$array = $dao->get();

		$this->createAdd("category_tree","_base.MyTreeComponent", array(
			"list" => $array,
			"selected" => array($item->getCategory())
		));

		$this->addInput("item_category", array(
			"name" => "Item[category]",
			"value" =>$item->getCategory(),
			"attr:id" => "item_category"
		));		
		
		$category = (isset($array[$item->getCategory()]))? $array[$item->getCategory()] : null;
		$this->addLabel("item_category_choice", array(
			"text" => $this->getCategoryRelation($dao, $category),
			"attr:id" => "item_category_text"
		));
		
		$config = SOYShop_ShopConfig::load();
		$this->addModel("item_category_area", array(
			"visible" => (!$item->isChild() && $config->getMultiCategory() != 1)
		));
		
		$this->addModel("multi_category_area", array(
			"visible" => (!$item->isChild() && $config->getMultiCategory() != 0)
		));
		$this->addInput("multi_category", array(
			"name" => "Item[multi][categories]",
			"value" => implode(",",$this->getCategoryIds()),
			"attr:id" => "multi_category"
		));
		
		$this->addLabel("multi_category_text", array(
			"text" => $this->getCategoriesName($array),
			"attr:id" => "multi_category_text"
		));

		$this->createAdd("multi_category_tree", "_base.MyTreeComponent", array(
			"list" => $array,
			"selected" => $this->getCategoryIds(),
			"func" => "onMultiClickLeaf"
		));
		
		/* parent item */
		$this->addModel("item_parent_area", array(
			"visible" => ($item->isChild())
		));
		try{
			$parentItem = $itemDAO->getById($item->getType());
		}catch(Exception $e){
			$parentItem = false;
		}
		$this->addLink("parent_item_link", array(
			"link" => SOY2PageController::createLink("Item.Detail." . $item->getType()),
			"text" => ($parentItem) ? $parentItem->getName() : "[この商品グループは削除されています]"
		));
		
		/* child item */
		$childItems = $itemDAO->getByTypeNoDisabled($item->getId());
		$this->addModel("child_item_list_area", array(
			"visible" => ($item->getType() == "group")
		));
		$this->addLink("add_child_item", array(
			"link" => SOY2PageController::createLink("Item.Create") . "?parent=" . $item->getId()
		));
		$this->createAdd("child_item_list","HTMLList", array(
			"list" => $childItems,
			'populateItem:function($entity,$key)' => '$this->createAdd("item_detail_link","HTMLLink", array(' .
					'"link" => "'.SOY2PageController::createLink("Item.Detail").'/" . $entity->getId(),' .
					'"text" => $entity->getName()));'
		));


		/* config */
		$this->addTextArea("item_description", array(
			"name" => "Item[config][description]",
			"value" => $item->getAttribute("description"),
			"readonly" => $readOnly
		));

		$this->addInput("item_keywords", array(
			"name" => "Item[config][keywords]",
			"value" => $item->getAttribute("keywords"),
			"readonly" => $readOnly
		));

		$this->createAdd("item_small_image","_common.Item.ImageSelectComponent", array(
			"domId" => "item_small_image",
			"name" => "Item[config][image_small]",
			"value" => $item->getAttribute("image_small")
		));

		$this->createAdd("item_large_image","_common.Item.ImageSelectComponent", array(
			"domId" => "item_large_image",
			"name" => "Item[config][image_large]",
			"value" => $item->getAttribute("image_large")
		));

		//error
		foreach(array("name","code","alias") as $key){
			$this->addLabel("error_$key", array(
				"text" => (isset($this->errors[$key])) ? $this->errors[$key] : "",
				"visible" => (isset($this->errors[$key]) && strlen($this->errors[$key]))
			));
		}

		SOYShopPlugin::load("soyshop.item.customfield");
		$html = SOYShopPlugin::display("soyshop.item.customfield", array(
			"item" => $item
		));

		$this->addLabel("custom_field", array(
			"html" => $html
		));

		//upload
		$this->addForm("upload_form");

		$this->createAdd("image_list","_common.Item.ItemImageListComponent", array(
			"list" => $this->getAttachments($item)
		));
		
		//管理制限の権限を取得し、権限がない場合は表示しない
		$this->addModel("app_limit_function", array(
			"visible" => $appLimit
		));
		
		$this->addInput("item_open_period_start", array(
			"name" => "Item[openPeriodStart]",
			"value" => $this->convertDateByTimestamp($item->getOpenPeriodStart())
		));
		
		$this->addInput("item_open_period_end", array(
			"name" => "Item[openPeriodEnd]",
			"value" => $this->convertDateByTimestamp($item->getOpenPeriodEnd())
		));
		
		$histories = $this->getHistories($item);
		$this->addModel("is_change_history", array(
			"visible" => (count($histories) > 0)
		));
		
		$this->createAdd("history_list", "_common.Item.ChangeHistoryListComponent", array(
			"list" => $histories
		));
	}
	
	//入荷通知周り
	function buildNoticeButton(){
		
		$isNoticeArrival = (class_exists("SOYShopPluginUtil") && (SOYShopPluginUtil::checkIsActive("common_notice_arrival")));
		
		//プラグインがアクティブでないと、顧客数を取得しにいかない
		if($isNoticeArrival){
			$noticeLogic = SOY2Logic::createInstance("module.plugins.common_notice_arrival.logic.NoticeLogic");
			$users = $noticeLogic->getUsersByItemId($this->id, SOYShop_NoticeArrival::NOT_SENDED, SOYShop_NoticeArrival::NOT_CHECKED);
			$isNoticeArrival = (count($users));
		}
		
		//プラグインがアクティブになっていること、顧客数が一人以上いる場合に表示する
		$this->addModel("is_notice_arrival", array(
			"visible" => ($isNoticeArrival)
		));
	}
	
	//入荷通知周り
	function buildFavoriteButton(){
		
		$isFavorite = (class_exists("SOYShopPluginUtil") && (SOYShopPluginUtil::checkIsActive("common_favorite_item")));
		
		//プラグインがアクティブでないと、顧客数を取得しにいかない
		if($isFavorite){
			$favoriteLogic = SOY2Logic::createInstance("module.plugins.common_favorite_item.logic.FavoriteLogic");
			$users = $favoriteLogic->getUsersByFavoriteItemId($this->id);
			$isFavorite = (count($users));
		}
		
		//プラグインがアクティブになっていること、顧客数が一人以上いる場合に表示する
		$this->addModel("is_favorite", array(
			"visible" => ($isFavorite)
		));
	}

	/**
	 * 添付ファイル取得
	 */
	function getAttachments(SOYShop_Item $item){
		return $item->getAttachments();
	}

	function getOrderConunt($item){
		
		$logic = SOY2Logic::createInstance("logic.order.OrderLogic");
		$childItemStock = $this->config->getChildItemStock();
		
		//子商品の在庫管理設定をオン(子商品の注文数合計を取得する)
		if($childItemStock){
			//子商品のIDを取得する
			$ids = $this->getChildItemIds($item->getId());
			$count = 0;
			if(count($ids) > 0){
				
				foreach($ids as $id){
					try{
						$count = $count + $logic->getOrderCountByItemId($id);
					}catch(Exception $e){
						
					}
				}
				return $count;	
			}
		}
		
		return $logic->getOrderCountByItemId($item->getId());
	}
	
	function getChildItemIds($itemId){
		
		$ids = array();
		
		$dao = new SOY2DAO();
		$sql = "select id from soyshop_item where item_type = :id";
		$binds = array(":id" => $itemId);
		try{
			$result = $dao->executeQuery($sql,$binds);
		}catch(Exception $e){
			return 0;
		}
		$ids = array();
		foreach($result as $value){
			$ids[] = $value["id"];
		}
		
		return $ids;
	}
	
	//拡張ポイントのsoyshop.item.update関連の処理
	function getHistories(SOYShop_Item $item){
		SOYShopPlugin::load("soyshop.item.update");
		$delegate = SOYShopPlugin::invoke("soyshop.item.update", array(
			"item" => $item
		));
		
		$histories = array();
		if(count($delegate->getList()) > 0){
			foreach($delegate->getList() as $key => $values){
				if(isset($values)){
					foreach($values as $value){
						$array = array("date" => $value->getCreateDate(), "content" => $value->getMemo());
						$histories[] = $array;
					}
				}
			}
		}
		
		return $histories;
	}

	function getSubMenu(){
		$key = "Item.SubMenu.DetailMenuPage";

		try{
			$subMenuPage = SOY2HTMLFactory::createInstance($key, array(
				"arguments" => array($this->id)
			));
			return $subMenuPage->getObject();
		}catch(Exception $e){
			//
			exit;
			return null;
		}
	}

	function getScripts(){
		$root = SOY2PageController::createRelativeLink("./js/");
		return array(
			$root . "ImageSelect.js",
			$root . "jquery/treeview/jquery.treeview.pack.js",
			$root . "tools/soy2_date_picker.pack.js"
		);
	}

	function getCSS(){
		$root = SOY2PageController::createRelativeLink("./js/");
		return array(
			$root . "jquery/treeview/jquery.treeview.css",
			$root . "tree.css",
			$root . "tools/soy2_date_picker.css"
		);
	}
	
	function convertDateByTimestamp($value){
		if($value==SOYShop_Item::PERIOD_START || $value == SOYShop_Item::PERIOD_END){
			$date = "";
		}else{
			$date = date("Y-m-d", $value);
		}
		
		return $date;
	}
	
	function convertTimestampByDate($value){
		$array = explode("-", $value);
		return mktime(0, 0, 0, $array[1], $array[2], $array[0]);
	}
	
	/**
	 * 画像のアップロード
	 *
	 * @return url
	 * 失敗時には false
	 */
	function uploadImage(){
		$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		$item = $dao->getById($this->id);
		
		$urls = array();
		
		foreach($_FILES as $upload){
			foreach($upload["name"] as $key => $value){
				//replace invalid filename
				$upload["name"][$key] = strtolower(str_replace("%","",rawurlencode($upload["name"][$key])));
		
				$pathinfo = pathinfo($upload["name"][$key]);
				if(!isset($pathinfo["filename"]))$pathinfo["filename"] = str_replace("." . $pathinfo["extension"], $pathinfo["basename"]);
		
				//get unique file name
				$counter = 0;
				$filepath = "";
				$name = "";
				while(true){
					$name = ($counter > 0) ? $pathinfo["filename"] . "_" . $counter . "." . $pathinfo["extension"] : $pathinfo["filename"] . "." . $pathinfo["extension"];
					$filepath = $item->getAttachmentsPath() . $name;
		
					if(!file_exists($filepath)){
						break;
					}
					$counter++;
				}
				
				//一回でも失敗した場合はfalseを返して終了（rollbackは無し）
				$result = move_uploaded_file($upload["tmp_name"][$key], $filepath);
				@chmod($filepath,0604);
	
				if($result){
					$url = $item->getAttachmentsUrl() . $name;
					$urls[] = $url;
				}else{
					return false;
				}	
			}
		}
		
		return $urls;
	}
	
	function getCategoryIds(){
		$categoriesDao = SOY2DAOFactory::create("shop.SOYShop_CategoriesDAO");
		try{
			$categories = $categoriesDao->getByItemId($this->id);
		}catch(Exception $e){
			$categories = array();
		}
		
		$array = array();
		foreach($categories as $category){
			$array[] = $category->getCategoryId();
		}
		
		return $array;
	}
	
	function getCategoriesName($obj){
		$categoryIds =$this->getCategoryIds($this->id);
		
		$array = array();
		foreach($categoryIds as $categoryId){
			$array[] = $obj[$categoryId]->getNameWithStatus();
		}
		
		return implode(",",$array);	
	}
	
	function getCategoryRelation($dao, $category){
		$array = array();
		
		try{
			if(isset($category)){
				$array[] = $category->getNameWithStatus();
				if(!is_null($category->getParent())){
					$parent = $dao->getById($category->getParent());
					$array[] = $parent->getNameWithStatus();
					if(!is_null($parent->getParent())){
						$grandParent = $dao->getById($parent->getParent());
						$array[] = $grandParent->getNameWithStatus(); 
					}
				}
			}
		}catch(Exception $e){
			//do nothing
		}
		
		if(array_key_exists(0, $array)){
			$text = implode(" > ",array_reverse($array));
		}else{
			$text = "選択してください";
		}
		
		return $text;
	}
}
?>