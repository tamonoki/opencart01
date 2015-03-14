<?php
/**
 * @class IndexPage
 * @date 2008-10-29T18:46:55+09:00
 * @author SOY2HTMLFactory
 */
class ListPage extends WebPage{

	private $logic;

	function ListPage(){
		
		SOY2::import("domain.plugin.SOYShop_PluginConfig");
		WebPage::WebPage();
		
		$this->addActionLink("plugin_ini_button", array(
			"link" => SOY2PageController::createLink("Plugin.List"),
			"visible" => (SOYShopPluginUtil::checkPluginListFile()),
			"onclick" => "return confirm('プラグイン一覧を初期化します。よろしいですか？');"
		));

    	$logic = SOY2Logic::createInstance("logic.plugin.SOYShopPluginLogic");
		$logic->prepare();
		
		$this->logic = $logic;
		
		//plugin.iniに記載されている内容で初期化
		if(soy2_check_token()){
			$logic->initModuleByPluginIni();
			SOY2PageController::jump("Plugin?updated");
		}

		//一旦モジュールをすべて読み込む
		$logic->searchModules();
		
		$pluginTypeList = SOYShop_PluginConfig::getPluginTypeList();
		foreach($pluginTypeList as $key => $value){
			$this->buildModulesTypeList($key, $value);
		}
		
		//sort
//		usort($list, array("ListPage","SortByType"));

	}
	
	function buildModulesTypeList($type = null, $value = null){
		if(isset($type)){
			$list = $this->logic->getModulesByType($type);
			
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

	private static function SortByType($a,$b){
		return ($a->getType() >= $b->getType());
	}
}

