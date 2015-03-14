<?php
/**
 * @class IndexPage
 * @date 2008-10-29T18:46:55+09:00
 * @author SOY2HTMLFactory
 */
class IndexPage extends WebPage{
	
	private $logic;

	function IndexPage(){
		SOY2::import("domain.plugin.SOYShop_PluginConfig");
		WebPage::WebPage();

    	$logic = SOY2Logic::createInstance("logic.plugin.SOYShopPluginLogic");
		$logic->prepare();

		$this->logic = $logic;
		
		$pluginTypeList = SOYShop_PluginConfig::getPluginTypeList();
		foreach($pluginTypeList as $key => $value){
			$this->buildModulesTypeList($key, $value);
		}
		
    	$this->addLink("search_modules", array(
    		"link" => SOY2PageController::createLink("Plugin.List")
    	));
	}
	
	function buildModulesTypeList($type = null, $value = null){
		if(isset($type)){
			$list = $this->logic->getInstalledModules($type);
			
			$this->addModel($type . "_is_module_list", array(
	    		"visible" => (count($list) > 0)
	    	));
	    	
	    	$this->addLabel($type . "_module_type_name", array(
	    		"text" => $value
	    	));
			
			$this->createAdd($type . "_module_list", "_common.Plugin.ModuleListComponent", array(
	    		"list" => $list
	    	));
		}
	}
}


function my_sort_by_type($a,$b){
	return ($a->getType() >= $b->getType());
}

?>