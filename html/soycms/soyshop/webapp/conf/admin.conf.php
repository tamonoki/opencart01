<?php

//Load SOY2 settings
include(dirname(__FILE__) . "/common.conf.php");

//Load functions and utilily classes
SOY2::import("base.func.admin",".php");
SOY2::imports("util.*");

//カート�?�多�?語化
SOY2::import("message.MessageManager");
MessageManager::addMessagePath("admin");

//APPLICATION_ID
if(!defined("APPLICATION_ID")){
	define("APPLICATION_ID", "shop");
}

//Designate a shop to manage
$session = SOY2ActionSession::getUserSession();
if(isset($_GET["site_id"])){
	$session->setAttribute("soyshop.shop.id", $_GET["site_id"]);
	SOY2PageController::redirect("");
}

//Check your authority to the shop
$shopId = $session->getAttribute("soyshop.shop.id");
if(!$shopId || !$session->getAuthenticated() || !soyshop_admin_login()){
	SOY2PageController::redirect("../admin/");
}

//Load the database setting
define("SOYSHOP_SITE_CONFIG_FILE",str_replace("\\", "/", dirname(__FILE__) . "/shop/${shopId}.conf.php"));
require(SOYSHOP_SITE_CONFIG_FILE);
soyshop_load_db_config();

//debug switch
define("SOYSHOP_"."DEVELOPING_MODE", true);
define("DEBUG_MODE", false);
define("SOY2HTML_AUTO_GENERATE", false);
if(DEBUG_MODE){
	ini_set("display_errors", "On");
	error_reporting(E_ALL);
	//既に定義されて�?る�?�合がある
	if(!defined("SOY2HTML_CACHE_FORCE")) define("SOY2HTML_CACHE_FORCE", true);
}

//ルートドメインに設定して�?るかど�?�?
$file = @file_get_contents($_SERVER["DOCUMENT_ROOT"]."index.php");
if(isset($file) && preg_match('/\("(.*)\//', $file, $res)){
	$isRoot = ($res[1]==SOYSHOP_ID) ? true : false;
}else{
	$isRoot = false;
}
define("SOYSHOP_IS_ROOT",$isRoot);

//SOY CMSのuser.config.phpを読み込�?
if(file_exists(dirname(SOYSHOP_ROOT) . "/common/config/user.config.php")){
	include_once(dirname(SOYSHOP_ROOT) . "/common/config/user.config.php");
}

//管�?画面側
if(!defined("SOYSHOP_ADMIN_PAGE")){
	define("SOYSHOP_ADMIN_PAGE", true);
}
