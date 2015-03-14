<?php
/*
 * Created on 2010/07/24
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
SOYShopItemImportPlugin::register();

class SOYShopItemImportPlugin{
	
	const PLUGIN_ID = "SOYShopItemImport";
	private $siteId = "shop";
	
	function getId(){
		return self::PLUGIN_ID;	
	}
	
	function init(){

		CMSPlugin::addPluginMenu(self::PLUGIN_ID,array(
			"name"=>"SOYShop商品紹介プラグイン",
			"description"=>"SOY Shopで登録した商品をSOY CMSのブログで紹介する",
			"author"=>"日本情報化農業研究所",
			"url"=>"http://www.n-i-agroinformatics.com",
			"mail"=>"info@n-i-agroinformatics.com",
			"label" => "",
			"entry" => "",
			"version"=>"0.6"
		));
		
		//二回目以降の動作
		if(CMSPlugin::activeCheck($this->getId())){
			
			if(!class_exists("SOYShopUtil")) SOY2::import("util.SOYShopUtil");
			
			//SOY Shopがインストールされていれば動く
			if(SOYShopUtil::checkSOYShopInstall()){
				
				CMSPlugin::addPluginConfigPage(self::PLUGIN_ID,array(
					$this,"config_page"	
				));
				
				//管理画面側
				if(!defined("_SITE_ROOT_")){
					CMSPlugin::addCustomFieldFunction($this->getId(), "Entry.Detail", array($this, "onCallCustomField"));
					CMSPlugin::addCustomFieldFunction($this->getId(), "Blog.Entry", array($this, "onCallCustomField_inBlog"));
	
					//記事作成時にキーワードとdescriptinをDBに挿入する
					CMSPlugin::setEvent('onEntryUpdate', $this->getId(), array($this, "onEntryUpdate"));
					CMSPlugin::setEvent('onEntryCreate', $this->getId(), array($this, "onEntryUpdate"));
				//公開画面側
				}else{
					//公開側のページを表示させたときに動作する
					CMSPlugin::setEvent('onPageOutput', self::PLUGIN_ID, array($this, "onPageOutput"));
				}
			}
		}
	}
	
	/**
	 * 公開時はEntryテーブルの値をそのまま表示する
	 * 商品の価はシリアライズして保存しておいて、
	 * 公開時にアンシリアライズして表示する。
	 */
	function onPageOutput($obj){
		
		/** ここから下は詳細ページでしか動作しません **/
		if(property_exists($obj, "mode") && $obj->mode == "_entry_"){
			
			$item = $this->getItem((int)$obj->entry->getId());
			
			$old = SOYShopUtil::switchShopMode($this->siteId);
						
			if(!defined("SOYSHOP_SITE_PREFIX")) define("SOYSHOP_SITE_PREFIX", "cms");
			include_once(dirname(SOY2::RootDir()) . "/conf/common.conf.php");
			SOY2::import("logic.plugin.SOYShopPlugin");
			SOY2::import("base.site.classes.SOYShop_ItemListComponent");
			SOY2::import("base.func.common", ".php");
			SOY2::imports("domain.config.*");
			SOY2::imports("domain.shop.*");
				
			$obj->createAdd("item", "SOYShop_ItemListComponent", array(
				"list" => array($item),
				"soy2prefix" => "i_block",
				"visible" => (!is_null($item->getId()))
			));
			
			SOYShopUtil::resetShopMode($old);
		}
	}
	
	/**
	 * doPost代わり
	 * doPost時にの設定を変えて、ショップから商品情報を取得し、Entryテーブルに保存
	 */
	function onEntryUpdate($arg){
		
		$old = SOYShopUtil::switchShopMode($this->siteId);
			
		//商品コードの取得
		$code = trim($_POST["item_code"]);
			
		$itemDao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		try{
			$item = $itemDao->getByCode($code);
		}catch(Exception $e){
			$item = new SOYShop_Item();
		}
					
		//設定を元に戻す
		SOYShopUtil::resetShopMode($old);
				
		if(!is_null($item->getCode())){
			$entry = $arg["entry"];
				
			$entryAttributeDao = SOY2DAOFactory::create("cms.EntryAttributeDAO");
			try{
				$entryAttributeDao->delete($entry->getId(), self::PLUGIN_ID);
			}catch(Exception $e){
				//
			}
			
			$attr = new EntryAttribute();
			$attr->setEntryId($entry->getId());
			$attr->setFieldId(self::PLUGIN_ID);
			$attr->setValue($item->getCode());
			
			try{
				$entryAttributeDao->insert($attr);
			}catch(Exception $e){
				return false;
			}
		}
			
		return true;
	}
	
	function onCallCustomField(){
		$arg = SOY2PageController::getArguments();
		$entryId = (isset($arg[0])) ? (int)$arg[0] : null;	

		$item = $this->getItem($entryId);	
				
		ob_start();
		include(dirname(__FILE__) . "/form.php");
		$html = ob_get_contents();
		ob_end_clean();

		echo $html;		
	}
	
	function onCallCustomField_inBlog(){
		$arg = SOY2PageController::getArguments();
		$entryId = (isset($arg[1])) ? (int)$arg[1] : null;
		
		$item = $this->getItem($entryId);	

		ob_start();
		include(dirname(__FILE__) . "/form.php");
		$html = ob_get_contents();
		ob_end_clean();

		echo $html;
	}
		
	function getItem($entryId){
		
		$entryAttributeDao = SOY2DAOFactory::create("cms.EntryAttributeDAO");
		try{
			$attr = $entryAttributeDao->get($entryId, self::PLUGIN_ID);
		}catch(Exception $e){
			$attr = new EntryAttribute();
		}
		
		$old = SOYShopUtil::switchShopMode($this->siteId);
		$itemDao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");	

		try{
			$item = $itemDao->getByCode($attr->getValue());
		}catch(Exception $e){
			$item = new SOYShop_Item();
		}
		
		SOYShopUtil::resetShopMode($old);

		return $item;
	}
	
	function config_page(){
		include_once(dirname(__FILE__) . "/config/SOYShopItemImportConfigFormPage.class.php");
		$form = SOY2HTMLFactory::createInstance("SOYShopItemImportConfigFormPage");
		$form->setPluginObj($this);
		$form->execute();
		return $form->getObject();
	}
	
	public static function register(){
		
		$obj = CMSPlugin::loadPluginConfig(self::PLUGIN_ID);
		if(!$obj){
			$obj = new SOYShopItemImportPlugin();
		}
			
		CMSPlugin::addPlugin(self::PLUGIN_ID,array($obj, "init"));
	}
	
	function getSiteId(){
		return $this->siteId;
	}
	function setSiteId($siteId){
		$this->siteId = $siteId;
	}
}
?>