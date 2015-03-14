<?php
/*
 * soyshop.site.beforeoutput.php
 * Created: 2010/03/11
 */

class SOYShopSortButtonBeforeOutput extends SOYShopSiteBeforeOutputAction{

	function beforeOutput($page){
		
		//カート内の場合は動作しない
		$className = get_class($page);
		if($className == "item_list_page" || $className == "search_html_page"){
			
			$pageObject = $page->getPageObject();
			
			$pageUrl = soyshop_get_page_url($pageObject->getUri());
			
			include_once(dirname(__FILE__) . "/util/SortButtonUtil.class.php");
			
			$columnList = SortButtonUtil::getColumnList();
			
			//検索ページで使う場合
			$query = (isset($_GET["type"]) && isset($_GET["q"])) ? "&type=" . trim($_GET["type"]) . "&q=" . trim($_GET["q"]) : "";

			foreach($columnList as $key => $column){
				
				$page->addLink("sort_" . $key . "_desc", array(
					"soy2prefix" => SOYSHOP_SITE_PREFIX,
					"link" => $pageUrl . "?sort=" . $key . "&r=1" . $query
				));
				
				$page->addLink("sort_" . $key . "_asc", array(
					"soy2prefix" => SOYSHOP_SITE_PREFIX,
					"link" => $pageUrl . "?sort=" . $key . "&r=0" . $query
				));				
			}
		}
	}
}
SOYShopPlugin::extension("soyshop.site.beforeoutput", "common_sort_button", "SOYShopSortButtonBeforeOutput");
?>