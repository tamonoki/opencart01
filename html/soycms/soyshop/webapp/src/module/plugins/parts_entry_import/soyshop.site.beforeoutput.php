<?php
/*
 * soyshop.site.beforeoutput.php
 * Created: 2010/03/11
 */

class EntryImportBeforeOutput extends SOYShopSiteBeforeOutputAction{

	function beforeOutput($page){

		include_once(dirname(__FILE__) . "/common.php");
		
		$siteConfig = EntryImportCommon::getSiteConfig();
		$common = new EntryImportCommon();
		$common->prepareSOYShopConfig();
		$common->changeSOYCMSDir();//SOY CMSのディレクトリへ
		$common->changeCmsDSN();//SOY CMS管理用DBへ切り替え
		$common->changeSiteDSN($siteConfig);//SOY CMSサイトDBへ切り替え


		/* サイト → ブログ → 記事一覧 */
		$entries = $common->getBlogEntiryList($siteConfig);
		$blogPath = $common->getBlogPath($siteConfig);
		include_once(dirname(__FILE__) . "/class.php");
		/**
		 * @ToDo
		 * CMS側のentry.phpからBlogEntryComponentを読み込みたい
		 */
		$page->createAdd("entry_list","EntryComponent", array(
			"soy2prefix" => "block",
			"list" => $entries,
			"path" => $blogPath
		));
		
		
		//元に戻す
		$common->setSOYShopConfig();
	}
}

SOYShopPlugin::extension("soyshop.site.beforeoutput","parts_entry_import","EntryImportBeforeOutput");