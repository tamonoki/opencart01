<?php
SOY2::import("module.site.common.output_item", ".php");

class SOYShop_DetailPageBase extends SOYShopPageBase{

	private $item;
	private $nextItem;
	private $prevItem;
	private $currentIndex = 1;
	private $totalItemCount = 0;

	function build($args){

		$page = $this->getPageObject();
		$obj = $page->getPageObject();

		$alias = implode("/", $args);

		try{
			$itemDAO = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
			$item = $itemDAO->getByAlias($alias);

			//削除フラグのチェック
			if($item->getIsDisabled() == SOYShop_Item::IS_DISABLED){
				//header("HTTP/1.1 410 Gone");
				header("HTTP/1.0 404 Not Found");
				echo "error";
				exit;
			}

			//子商品だった場合は、親商品の詳細ページにリダイレクト
			if(is_numeric($item->getType())){
				$config = SOYShop_ShopConfig::load();
				$displayDetail = $config->getDisplayChildItem();
				if($displayDetail == 0){
					$parent = $itemDAO->getById($item->getType());
					header("Location: " . soyshop_get_page_url($page->getUri(),$parent->getAlias()));
				}
			}

			$this->setItem($item);

			//現在の商品を保存
			$obj->setCurrentItem($item);

			if(strlen($item->getDetailPageId()) > 0 && $item->getDetailPageId() != $page->getId()){
				throw new Exception();
			}

			$logic = SOY2Logic::createInstance("logic.shop.item.SearchItemUtil");
			list($items, $total) = $logic->getByCategoryId($item->getCategory());
			$this->setTotalItemCount($total);

			$counter = 1;
			$prev_item = null;
			$next_item = false;
			foreach($items as $tmp){
				if($tmp->getId() == $item->getId()){
					$next_item = true;
					continue;
				}

				if($next_item){
					$next_item = $tmp;
					break;
				}
				$prev_item = $tmp;
				$counter++;
			}

			$this->setCurrentIndex($counter);
			$this->setPrevItem($prev_item);
			if($next_item && $next_item !== true) $this->setNextItem($next_item);

			//keywords
			$keywords = $item->getAttribute("keywords");
			if(strlen($keywords)) $this->getHeadElement()->insertMeta("keywords", $keywords . ",");

			//description
			$description = $item->getAttribute("description");
			if(strlen($description)) $this->getHeadElement()->insertMeta("description", $description . " ");

		}catch(Exception $e){
			header("HTTP/1.0 500 Internal Server Error");
			echo "error";
			exit;
		}

		//item
		$this->createAdd("item", "SOYShop_ItemListComponent", array(
			"list" => array($item),
			"obj" => $obj,
			"soy2prefix" => "block",
			"forAdminOnly" => $this->getForAdminOnly($item) //商品詳細ページ確認モード
		));
	}
	
	/**
	 * 商品が非公開で商品詳細ページ確認モードのフラグが立っている場合はページを表示するか調べる
	 * @param object SOYShop_Item
	 * @return booleanもしくはnull
	 */
	function getForAdminOnly($item){
		
		if(!$item->isPublished() && isset($_GET["foradminonly"])){
			$session = SOY2ActionSession::getUserSession();
			$forAdminOnly = (!is_null($session->getAttribute("loginid")));
		}else{
			$forAdminOnly = null;
		}
		return $forAdminOnly;
	}

	function getNextItem() {
		return $this->nextItem;
	}
	function setNextItem($nextItem) {
		$this->nextItem = $nextItem;
	}
	function getPrevItem() {
		return $this->prevItem;
	}
	function setPrevItem($prevItem) {
		$this->prevItem = $prevItem;
	}
	function getCurrentIndex() {
		return $this->currentIndex;
	}
	function setCurrentIndex($currentIndex) {
		$this->currentIndex = $currentIndex;
	}

	function getTotalItemCount() {
		return $this->totalItemCount;
	}
	function setTotalItemCount($totalItemCount) {
		$this->totalItemCount = $totalItemCount;
	}

	function getItem() {
		return $this->item;
	}
	function setItem($item) {
		$this->item = $item;
	}

	function getPager(){
		return new SOYShop_DetailPagePager($this);
	}
}

class SOYShop_DetailPagePager extends SOYShop_PagerBase{

	private $page;

	function SOYShop_DetailPagePager(SOYShop_DetailPageBase $page){
		$this->page = $page;
	}

	function getCurrentPage(){
		return $this->page->getCurrentIndex();
	}

	function getTotalPage(){
		return $this->page->getTotalItemCount();
	}

	function getLimit(){
		return 1;	//detail page's limiy is always 1;
	}

	private $_pagerUrl;

	function getPagerUrl(){
		if(!$this->_pagerUrl){
			$url = $this->page->getPageUrl();
			if($url[strlen($url) - 1] == "/") $url = substr($url, 0, strlen($url) - 1);
			$this->_pagerUrl = $url;
		}
		return $this->_pagerUrl;
	}

	function getNextPageUrl(){
		$url = $this->getPagerUrl();
		$page = $this->page;
		$next_link = ($page->getNextItem()) ? $url . "/" . ($page->getNextItem()->getAlias()) : "-";
		return $next_link;
	}

	function getPrevPageUrl(){
		$url = $this->getPagerUrl();
		$page = $this->page;

		$prev_link = ($page->getPrevItem()) ? $url . "/" . ($page->getPrevItem()->getAlias()) : "-";
		return $prev_link;
	}

	function hasNext(){
		return ($this->page->getNextItem()) ? true : false;
	}

	function hasPrev(){
		return ($this->page->getPrevItem()) ? true : false;
	}
}
?>