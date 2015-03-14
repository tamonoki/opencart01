<?php
/**
 * @class Site.Page.IndexPage
 * @date 2009-11-16T19:23:12+09:00
 * @author SOY2HTMLFactory
 */
class IndexPage extends WebPage{

	//全ページリスト
	private $all = array();
	
	function IndexPage(){
		WebPage::WebPage();

		//すべてのページを取得
		$this->all = $this->getPages();
		$pageCount = (count($this->all));
	
		$this->createAdd("page_type_list", "_common.PagePluginTypeListComponent", array(
			"list" => $this->getPageList(),
		));
		
		$this->addModel("no_page", array(
			"visible" => (count($pageCount) == 0)
		));
	}

	function getPages(){

		$dao = SOY2DAOFactory::create("site.SOYShop_PageDAO");
		$pages = $dao->get();
		
		$res = array();
		foreach($pages as $page){
			$res[$page->getUri()] = $page;
		}

		ksort($res);

		return $res;
	}
	
	//タイプ別のページリストを取得
	function getPageList(){
		
		$configs = array();
		$list = array();
		
		//多言語化サイトプラグインがアクティブの時
		if(SOYShopPluginUtil::checkIsActive("util_multi_language")){
			SOY2::import("module.plugins.util_multi_language.util.UtilMultiLanguageUtil");
			$multiLangConfig = UtilMultiLanguageUtil::getConfig();
			
			foreach($multiLangConfig as $key => $values){
				if(isset($values["prefix"]) && strlen($values["prefix"])){
					$configs[$key] = $values["prefix"];
				}
			}
		}
		
		//携帯自動振り分けプラグインがアクティブの時
		if(SOYShopPluginUtil::checkIsActive("util_mobile_check")){
			SOY2::import("module.plugins.util_mobile_check.util.UtilMobileCheckUtil");
			$mobileCheckConfig = UtilMobileCheckUtil::getConfig();
			
			if(isset($mobileCheckConfig["prefix"]) && strlen($mobileCheckConfig["prefix"])){
				$configs["m"] = $mobileCheckConfig["prefix"];
			}
			
			if(isset($mobileCheckConfig["prefix_i"]) && strlen($mobileCheckConfig["prefix_i"])){
				$configs["i"] = $mobileCheckConfig["prefix_i"];
			}
		}
		
		foreach($configs as $key => $prefix){
			foreach($this->all as $page){
				if($page->getUri() == $prefix || preg_match('/^' . $prefix . '\//', $page->getUri())){
					$list[$prefix][$page->getUri()] = $page;
					unset($this->all[$page->getUri()]);
				}
			}
		}
		
		//最後に並べ替え
		$pageList["jp"] = $this->all;
		foreach($list as $key => $values){
			$pageList[$key] = $values;
		}

		return $pageList;
		
	}
}
?>