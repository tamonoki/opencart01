<?php

class IndexPage extends SOYShopWebPage{

    function IndexPage() {
    	WebPage::WebPage();
    	
    	$dao = SOY2DAOFactory::create("SOYShop_SiteDAO");
    	try{
    		$sites = $dao->get();
    	}catch(Exception $e){
    		$sites = array();
    	}
    	
    	$this->createAdd("soyshop_list", "_common.SOYShop_SiteList", array(
    		"list" => $sites,
    		"logic" => SOY2Logic::createInstance("logic.ShopLogic")
    	));
    }
}

?>