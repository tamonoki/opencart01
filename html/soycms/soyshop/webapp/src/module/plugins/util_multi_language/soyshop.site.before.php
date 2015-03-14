<?php
/*
 * soyshop.site.beforeoutput.php
 * Created: 2010/03/11
 */

class UtilMultiLanguageBeforeOutput extends SOYShopSiteBeforeOutputAction{

	function beforeOutput($page){
		
		$languages = array("jp", "en");
		
		foreach($languages as $language){
			$page->addLink("language_" . $language . "_link", array(
				"soy2prefix" => SOYSHOP_SITE_PREFIX,
				"link" => "?language=" . $language
			));
		}
	}
}

SOYShopPlugin::extension("soyshop.site.beforeoutput", "util_multi_language", "UtilMultiLanguageBeforeOutput");