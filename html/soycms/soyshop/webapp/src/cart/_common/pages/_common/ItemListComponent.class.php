<?php
/**
 * @class ItemList
 * @generated by SOY2HTML
 */
class ItemListComponent extends HTMLList{

	private $DAO;
	private $pageDAO;

	private $ignoreStock;

	protected function populateItem($entity, $key){

		try{
			$item = $this->getDAO()->getById($entity->getItemId());
		}catch(Exception $e){
			$item = new SOYShop_Item();
		}

		$pageDAO = $this->getPageDAO();
		$detailPageId = $item->getDetailPageId();
		try{
			$page = $pageDAO->getById($detailPageId);
			$url = soyshop_get_page_url($page->getUri(), $item->getAlias());
		}catch(Exception $e){
			$url = null;
		}

		$this->addLink("item_link", array(
			"link" => $url
		));

		$this->addLink("item_name", array(
			"text" => $entity->getItemName(),
			"link" => $url
		));
		$this->addLabel("item_name_plain", array(
			"text" => $entity->getItemName(),
		));

		$this->createAdd("item_price", "NumberFormatLabel", array(
			"text" => $entity->getItemPrice(),
		));

		$this->addLabel("item_id", array(
			"text" => $item->getCode(),
		));

		$this->addLabel("item_code", array(
			"text" => $item->getCode(),
		));

		$this->addImage("item_small_image", array(
			"src" => $item->getAttribute("image_small")
		));

		$this->addImage("item_large_image", array(
			"src" => $item->getAttribute("image_large")
		));

		SOYShopPlugin::invoke("soyshop.item.customfield", array(
			"item" => $item,
			"htmlObj" => $this
		));

		SOYShopPlugin::load("soyshop.item.option");
		$delegate = SOYShopPlugin::invoke("soyshop.item.option", array(
			"mode" => "item",
			"index" => $key,
			"htmlObj" => $this
		));

		$this->addLabel("item_option", array(
			"html" => $delegate->getHtmls()
		));

		$this->addInput("order_number", array(
			"name" => "ItemCount[" . $key . "]",
			"value" => $entity->getItemCount()
		));

		$this->createAdd("order_number_text", "NumberFormatLabel", array(
			"text" => $entity->getItemCount()
		));

		$this->createAdd("item_total", "NumberFormatLabel", array(
			"text" => $entity->getTotalPrice()
		));

		$this->addLink("item_delete", array(
			"link" => soyshop_get_cart_url(true) . "?a=remove&index=" . $key
		));

		$itemCount = $entity->getItemCount();
		$openStock = $item->getOpenStock();

		//子商品の在庫管理設定をオン(子商品購入時に親商品の在庫数で購入できるか判断する)
		$config = SOYShop_ShopConfig::load();
		$childItemStock = $config->getChildItemStock();
		if($childItemStock&&is_numeric($item->getType())){
			//親商品の残り在庫数を取得
			$parent = $this->getParentItem($item->getType());
			$openStock = $parent->getStock();

			//子商品の注文数の合算を取得
			$itemCount = $this->getChildItemOrders($parent->getId());
		}

		$this->addLabel("item_stock_error", array(
			"visible" => ($itemCount > $openStock && !$this->ignoreStock),
			"text" => MessageManager::get("STOCK_NOTICE", array("stock" => $openStock))
		));

		//item++
		$this->addLink("add_item_link", array(
			"link" => soyshop_get_cart_url(true) . "?a=add&item=" . $item->getId() ."&count=1",
			"visible" => ($itemCount > $entity->getItemCount())
		));

		//item--
		$this->addLink("sub_item_link", array(
			"link" => soyshop_get_cart_url(true) . "?a=add&item=" . $item->getId() . "&count=-1",
			"visible" => ($itemCount > 1)
		));
	}

	function getChildItemOrders($itemId){
		$cart = CartLogic::getCart();

		$itemCount = 0;

		$items = $cart->getItems();
		if(count($items) > 0){
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

			foreach($items as $item){
				if(in_array($item->getItemId(),$ids)){
					$itemCount = $itemCount + $item->getItemCount();
				}
			}
		}

		return $itemCount;
	}
	function getParentItem($itemId){
		try{
			$parent = $this->getDAO()->getById($itemId);
		}catch(Exception $e){
			$parent = new SOYShop_Item();
		}

		return $parent;
	}

	function getDAO() {
		if(is_null($this->DAO)){
			$this->DAO = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		}
		return $this->DAO;
	}
	function setDAO($DAO) {
		$this->DAO = $DAO;
	}
	function getPageDAO(){
		if(!$this->pageDAO){
			$this->pageDAO = SOY2DAOFactory::create("site.SOYShop_PageDAO");
		}
		return $this->pageDAO;
	}

	function setIgnoreStock($ignoreStock){
		$this->ignoreStock = $ignoreStock;
	}
	function getIgnoreStock(){
		return $this->ignoreStock;
	}
}
?>