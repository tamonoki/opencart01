<?php

class SOYShop_ListPageBase extends SOYShopPageBase{

	private $total = 0;
	private $currentPage = 1;
	private $limit = 10;


	function build($args){

		$page = $this->getPageObject();
		$obj = $page->getPageObject();
		
		$type = $obj->getType();
		$this->setLimit($obj->getLimit());

		if(count($args) > 0 && preg_match('/page-([0-9]+)[^0-9]*/', $args[count($args)-1], $tmp)){
			unset($args[count($args) - 1]);
			$args = array_values($args);
			$this->setCurrentPage($tmp[1]);

			//ページ部分の引数は取り除く
			$this->setArguments($args);
		}

		switch($type){

			case SOYShop_ListPage::TYPE_CUSTOM:
				list($items, $total) = $this->getItemsByCustom($obj, $args);
				break;

			case SOYShop_ListPage::TYPE_FIELD:
				list($items, $total) = $this->getItemsByField($args);
				break;

			case SOYShop_ListPage::TYPE_CATEGORY:
				list($items, $total) = $this->getItemsByCategory($args);
				break;

			default:

				break;
		}

		$this->setTotal($total);

		//item_list
		$this->createAdd("item_list", "SOYShop_ItemListComponent", array(
			"list" => $items,
			"soy2prefix" => "block"
		));

	}

	/**
	 * カテゴリを指定して取得
	 *
	 * @return array
	 */
	function getItemsByCategory($args){
		$res = array();
		$total = 0;

		$page = $this->getPageObject();
		$obj = $page->getPageObject();

		//SearchItemUtilの作成。ソート順作成のためlistPageオブジェクトを渡す
		$logic = SOY2Logic::createInstance("logic.shop.item.SearchItemUtil", array(
			"sort" => $obj
		));
		$categoryDAO = SOY2DAOFactory::create("shop.SOYShop_CategoryDAO");

		$limit = $this->limit;
		$offset = $limit * ($this->currentPage - 1);

		try{
			//指定している場合
			if(count($args) > 0){
				$categoryAlias = implode("/", $args);

				//argsが存在しないカテゴリの場合、デフォルトのカテゴリを設定する
				if(!is_null($obj->getDefaultCategory()) && !$categoryDAO->isAlias($categoryAlias)){
					try{
						$category = $categoryDAO->getById($obj->getDefaultCategory());
					}catch(Exception $e){
						$category = new SOYShop_Category();
					}
				}else{
					try{
						$category = $categoryDAO->getByAlias($categoryAlias);
					}catch(Exception $e){
						$category = new SOYShop_Category();
					}
				}
				
				//カテゴリが非公開の場合は表示しない
				if($category->getIsOpen() == SOYShop_Category::NO_OPEN){
					header("Location:" . soyshop_get_site_url());
				}

				//現在のカテゴリを保存
				$obj->setCurrentCategory($category);

				//使うカテゴリで制限かける場合
				if(!_empty($obj->getCategories())){
					if(in_array($category->getId(), $obj->getCategories())){
						list($res, $total) = $logic->getByCategoryIds($category->getId(), $offset, $limit);
					}else{
						$itemDAO = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
						list($res, $total) = array(array(), 0);
					}
				}else{
					list($res, $total) = $logic->getByCategoryIds($category->getId(), $offset, $limit);
				}


			}else{
				list($res, $total) = $logic->getByCategoryIds($obj->getDefaultCategory(), $offset, $limit);

				//現在のカテゴリを保存
				try{
					$category = $categoryDAO->getById($obj->getDefaultCategory());
				}catch(Exception $e){
					$category = new SOYShop_Category();
				}
				$obj->setCurrentCategory($category);
			}
		}catch(Exception $e){
			//
		}

		//keyword,descriptionの挿入
		if(!is_null($obj->getCurrentCategory())) $this->insertMeta();

		return array($res, $total);
	}

	/**
	 * カスタムフィールドで検索
	 */
	function getItemsByField($args){
		$res = array();
		$total = 0;
		
		$id = (isset($args[0])) ? $args[0] : null;

		$page = $this->getPageObject();
		$obj = $page->getPageObject();
		$value = ($obj->isUseParameter()) ? $id : $obj->getFieldValue();

		$logic = SOY2Logic::createInstance("logic.shop.item.SearchItemUtil", array(
			"sort" => $obj
		));

		$limit = $this->limit;
		$offset = $limit * ($this->currentPage - 1);

		$array = array(
			$obj->getFieldId() => $value
		);

		list($res, $total) = $logic->searchByAttribute($array, $offset, $limit);

		return array($res, $total);
	}

	/**
	 * その他
	 */
	function getItemsByCustom($obj, $args){
		$res = array();
		$total = 0;

		try{
			$dao = SOY2DAOFactory::create("plugin.SOYShop_PluginConfigDAO");
			try{
				$module = $dao->getByPluginId($obj->getModuleId());
			}catch(Exception $e){
				$module = new SOYShop_PluginConfig();
			}
			
			SOYShopPlugin::load("soyshop.item.list", $module);
			$delegetor = SOYShopPlugin::invoke("soyshop.item.list", array(
				"mode" => "search"
			));

			$limit = $this->limit;
			$offset = $limit * ($this->currentPage - 1);

			$res = $delegetor->getItems($obj, $offset, $limit);
			$total = $delegetor->getTotal($obj);
		}catch(Exception $e){
			//
		}

		return array($res, $total);
	}

	function getTotal() {
		return $this->total;
	}
	function setTotal($total) {
		$this->total = $total;
	}

	function getCurrentPage() {
		return $this->currentPage;
	}
	function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage;
	}
	function getLimit() {
		return $this->limit;
	}
	function setLimit($limit) {
		$this->limit = $limit;
	}

	function getPager(){
		return new SOYShop_ListPagePager($this);
	}

	/**
	 * keywords, descritionの挿入
	 */
	function insertMeta(){
		$page = $this->getPageObject();
		$obj = $page->getPageObject();
		$categoryDAO = SOY2DAOFactory::create("shop.SOYShop_CategoryDAO");
		$category = $obj->getCurrentCategory();
		$categoryId = $category->getId();
		$config = SOYShop_DataSets::get("common.category_navigation", array());


		//keywordsの挿入
		$keywords = array();
		//カテゴリツリーでの設定
		if(isset($config[$categoryId]["keyword"])){
			$keywords[] = $config[$categoryId]["keyword"];
		}
		//カテゴリ名を挿入
		$ancestry = $categoryDAO->getAncestry($category);
		foreach($ancestry as $parent){
			$keywords[] = $parent->getName();
		}
		$keywords = implode(",", $keywords);
		if(strlen($keywords)) $this->getHeadElement()->insertMeta("keywords", $keywords . ",");


		//descriptionの挿入
		if(isset($config[$categoryId]["description"])){
			$description = $config[$categoryId]["description"];
			$this->getHeadElement()->insertMeta("description", $description);
		}
	}
}

class SOYShop_ListPagePager extends SOYShop_PagerBase{

	private $page;

	function SOYShop_ListPagePager(SOYShop_ListPageBase $page){
		$this->page = $page;
	}

	function getCurrentPage(){
		return $this->page->getCurrentPage();
	}

	function getTotalPage(){
		$page = max(1, ceil($this->page->getTotal() / $this->page->getLimit()));
		return $page;
	}

	function getLimit(){
		return $this->page->getLimit();
	}

	private $_pagerUrl;

	function getPagerUrl(){
		if(!$this->_pagerUrl){
			$url = $this->page->getPageUrl(true);
			if($url[strlen($url) - 1] == "/")$url = substr($url, 0, strlen($url) - 1);
			$this->_pagerUrl = $url;
		}
		return $this->_pagerUrl;
	}

	function getNextPageUrl(){
		$url = $this->getPagerUrl();
		$next = $this->getCurrentPage() + 1;
		return $url . "/page-" . $next . ".html";
	}

	function getPrevPageUrl(){
		$url = $this->getPagerUrl();
		$prev = $this->getCurrentPage() - 1;
		if($prev < 0){
			return "";
		}elseif($prev == 0){
			return $url;
		}else{
			return $url . "/page-" . $prev . ".html";
		}
	}

	function hasNext(){ return $this->getTotalPage() >= ($this->getCurrentPage() + 1); }
	function hasPrev(){ return ($this->getCurrentPage() - 1) > 0; }
}
?>