<?php
//define
define("SOYSHOP_ROOT",str_replace("\\","/",dirname(dirname(dirname(__FILE__)))) . "/");
define("SOYSHOP_WEBAPP",SOYSHOP_ROOT . "webapp/");
define("SOYSHOP_SITE_PREFIX","cms");
define("SOY2_NOW",time());	//現在時刻

mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_regex_encoding(mb_internal_encoding());

//include SOY2
include(SOYSHOP_WEBAPP . "lib/soy2_build.php");
include_once(SOYSHOP_WEBAPP . "lib/magic_quote_gpc.php");

//configure SOY2
SOY2::RootDir(SOYSHOP_WEBAPP . "src/");

//configure SOY2HTML
SOY2HTMLConfig::CacheDir(SOYSHOP_SITE_DIRECTORY . ".cache/");

//configure SOY2DAO
if(defined("SOYSHOP_SITE_DSN")){
	SOY2DAOConfig::Dsn(SOYSHOP_SITE_DSN);
	SOY2DAOConfig::user(SOYSHOP_SITE_USER);
	SOY2DAOConfig::pass(SOYSHOP_SITE_PASS);
}else{
	SOY2DAOConfig::Dsn("sqlite:" . SOYSHOP_SITE_DIRECTORY . ".db/sqlite.db");
}
SOY2DAOConfig::DaoDir(SOYSHOP_WEBAPP . "src/domain/");
SOY2DAOConfig::EntityDir(SOYSHOP_WEBAPP . "src/domain/");

//import
SOY2::import("domain.config.SOYShop_DataSets");
SOY2::import("base.SOYShopSiteController");
SOY2::import("base.define", ".php");
SOY2::import("base.func.common", ".php");
SOY2::import("logic.plugin.SOYShopPlugin");
SOY2::imports("base.site.*");
SOY2::imports("base.site.pages.*");
SOY2::imports("base.site.classes.*");

//init controller
SOY2PageController::init("SOYShopSiteController");

//debug switch
define("SOYSHOP_"."DEVELOPING_MODE", true);
define("DEBUG_MODE", false);
define("SOY2HTML_AUTO_GENERATE", false);

if(DEBUG_MODE){
	ini_set("display_errors", "On");
	error_reporting(E_ALL);
	define("SOY2HTML_CACHE_FORCE", true);
}

//document rootの末尾は/で終わる�?�を期�?
if(function_exists("soy2_realpath")){
	$_SERVER["DOCUMENT_ROOT"] = soy2_realpath($_SERVER["DOCUMENT_ROOT"]);
}


//ルートドメインに設定して�?るかど�?�?
$file = @file_get_contents($_SERVER["DOCUMENT_ROOT"] . "index.php");
if(isset($file) && preg_match('/\("(.*)\//', $file, $res)){
	$isRoot = ($res[1] == SOYSHOP_ID) ? true : false;
}else{
	$isRoot = false;
}
define("SOYSHOP_IS_ROOT", $isRoot);

$config = SOYShop_ShopConfig::load();
define("SOYSHOP_CONSUMPTION_TAX_MODE", ($config->getConsumptionTax() == SOYShop_ShopConfig::CONSUMPTION_TAX_MODE_ON));
define("SOYSHOP_CONSUMPTION_TAX_INCLUSIVE_PRICING_MODE", ($config->getConsumptionTaxInclusivePricing() == SOYShop_ShopConfig::CONSUMPTION_TAX_MODE_ON));

//SOYShop側のサイトを表示して�?るか?�?
define("DISPLAY_SOYSHOP_SITE", true);
?>