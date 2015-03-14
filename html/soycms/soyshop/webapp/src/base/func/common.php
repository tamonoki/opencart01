<?php

/**
 * ページのURLを取得する
 */
function soyshop_get_page_url($uri, $suffix = null){

	if($suffix){
		return soyshop_get_site_url(true) . $uri . "/" . $suffix;
	}

	return soyshop_get_site_url(true) . $uri;

}

/**
 * サイトのURLを取得する
 */
function soyshop_get_site_url($isAbsolute = false){
	$url = SOYSHOP_SITE_URL;

	//ルート設定の場合、ショップIDを削る
	if(defined("SOYSHOP_IS_ROOT") && SOYSHOP_IS_ROOT){
		$id = "/" . SOYSHOP_ID . "/";
		$posId = strrpos($url, $id);
		if((strlen($url) - strlen($id)) == $posId){
			$url = substr($url, 0, $posId) . "/";
		}
	}

	if($isAbsolute){
		return $url;
	}else{
		return preg_replace('/^h[a-z]+:\/\/[^\/]+/', '', $url);
	}
}

/**
 * httpから始まる画像のフルパスを取得する
 */
function soyshop_get_image_full_path($imagePath){
	$url = str_replace("/" . SOYSHOP_ID . "/", "", SOYSHOP_SITE_URL);
	return $url . $imagePath;
}

/**
 * サイトのURIを取得する
 */
function soyshop_get_site_path(){
	return str_replace($_SERVER["DOCUMENT_ROOT"], '/', SOYSHOP_SITE_DIRECTORY);
}

/**
 * 商品詳細のURLを取得する
 */
function soyshop_get_item_detail_link(SOYShop_Item $item){
	$urls = SOYShop_DataSets::get("site.url_mapping", array());
	$url = "";
	if(isset($urls[$item->getDetailPageId()])){
		$url = $urls[$item->getDetailPageId()]["uri"];
	}else{
		foreach($urls as $array){
			if($array["type"] == "detail"){
				$url = $array["uri"];
				break;
			}
		}
	}
	return soyshop_get_page_url($url, $item->getAlias());
}

/**
 * 金額を表示する
 */
function soyshop_display_price($price){
	return number_format((int)$price);
}

/**
 * カートのURLを取得
 */
function soyshop_get_cart_url($operation = false, $isAbsolute = false){
	$isUseSSL = SOYShop_DataSets::get("config.cart.use_ssl", 0);

	if($isUseSSL){
		$url = SOYShop_DataSets::get("config.cart.ssl_url");
		$url .= soyshop_get_cart_uri();
	}else{
		$url = soyshop_get_site_url($isAbsolute) . soyshop_get_cart_uri();
	}

	if($operation){
		return $url . "/operation";
	}else{
		return $url;
	}
}

/**
 * カートのIDを取得
 */
function soyshop_get_cart_id(){
	if(defined("SOYSHOP_IS_MOBILE") && SOYSHOP_IS_MOBILE){
		return SOYShop_DataSets::get("config.cart.mobile_cart_id", "mobile");
	}elseif(defined("SOYSHOP_IS_SMARTPHONE") && SOYSHOP_IS_SMARTPHONE){
		return SOYShop_DataSets::get("config.cart.smartphone_cart_id", "smart");
	}else{
		return SOYShop_DataSets::get("config.cart.cart_id", "bryon");
	}
}

/**
 * カートのURIを取得
 */
function soyshop_get_cart_uri(){
	
	if(!defined("SOYSHOP_CART_URI")){
		if(defined("SOYSHOP_IS_MOBILE") && SOYSHOP_IS_MOBILE){
			$cartUri = SOYShop_DataSets::get("config.cart.mobile_cart_url", "mb/cart");
		}elseif(defined("SOYSHOP_IS_SMARTPHONE") && SOYSHOP_IS_SMARTPHONE){
			$cartUri = SOYShop_DataSets::get("config.cart.smartphone_cart_url", "i/cart");
		}else{
			$cartUri = SOYShop_DataSets::get("config.cart.cart_url", "cart");
		}
		
		//多言語化対応
		if(defined("SOYSHOP_PUBLISH_LANGUAGE")){
			SOY2::import("module.plugins.util_multi_language.util.UtilMultiLanguageUtil");
			$config = UtilMultiLanguageUtil::getConfig();
			if(isset($config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"]) && strlen($config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"])){
				$cartUri = $config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"] . "/" . $cartUri;
			}
		}
				
		define("SOYSHOP_CART_URI", $cartUri);
	}

	return SOYSHOP_CART_URI;

}

/**
 * カートページにリダイレクトする
 */
function soyshop_redirect_cart($param = null){
	$url = soyshop_get_cart_url();
	if($param) $url .= "?" . $param;
	header("Location: ". $url);
	exit;
}


/**
 * カートページにリダイレクトする
 */
function soyshop_redirect_cart_with_anchor($anchor = null){
	$url = soyshop_get_cart_url();
	if($anchor)$url .= "#" . $anchor;
	header("Location: " . $url);
	exit;
}

/**
 * メールアドレスの形式チェック
 * @param string $mail
 * @return boolean 正しければtrue
 */
function soyshop_valid_email($email){
	$ascii  = '[a-zA-Z0-9!#$%&\'*+\-\/=?^_`{|}~.]';//'[\x01-\x7F]';
	$domain = '(?:[-a-z0-9]+\.)+[a-z]{2,10}';//'([-a-z0-9]+\.)*[a-z]+';
	$d3     = '\d{1,3}';
	$ip     = $d3. '\.'. $d3. '\.'. $d3. '\.'. $d3;
	$validEmail = "^$ascii+\@(?:$domain|\\[$ip\\])$";

	if(! preg_match('/' . $validEmail . '/i', $email) ) {
		return false;
	}

	return true;
}

/**
 * 住所検索の郵便番号で全角や-を除く
 */
function soyshop_cart_address_validate($zipcode){
	$zipcode = mb_convert_kana($zipcode, "a");
	$zipcode = mb_convert_kana($zipcode, "s");
	$zipcode = str_replace("-", "", $zipcode);
	$zipcode = str_replace(" ", "", $zipcode);
	return $zipcode;
}

/**
 * trim
 */
function soyshop_trim($str){
	return trim($str);
}

/**
 * カタカナの変換
 * @param string $str
 * @return string カナ変換後
 */
function soyshop_conver_kana($str){
	$str = trim($str);
	return mb_convert_kana($str, "CK", "UTF-8");
}


/**
 * マイページのID取得
 */
function soyshop_get_mypage_id(){
	if(defined("SOYSHOP_IS_MOBILE") && SOYSHOP_IS_MOBILE){
		return SOYShop_DataSets::get("config.mypage.mobile.id", "mobile");
	}elseif(defined("SOYSHOP_IS_SMARTPHONE") && SOYSHOP_IS_SMARTPHONE){
		return SOYShop_DataSets::get("config.mypage.smartphone.id", "smart");
	}else{
		return SOYShop_DataSets::get("config.mypage.id", "bryon");
	}
}

/**
 * マイページ
 */
function soyshop_get_mypage_uri(){
	
	if(!defined("SOYSHOP_MYPAGE_URI")){
		if(defined("SOYSHOP_IS_MOBILE") && SOYSHOP_IS_MOBILE){
			$mypageUri = SOYShop_DataSets::get("config.mypage.mobile.url", "mb/user");
		}elseif(defined("SOYSHOP_IS_SMARTPHONE") && SOYSHOP_IS_SMARTPHONE){
			$mypageUri = SOYShop_DataSets::get("config.mypage.smartphone.url", "i/user");
		}else{
			$mypageUri = SOYShop_DataSets::get("config.mypage.url", "user");
		}
	
		//多言語化対応
		if(defined("SOYSHOP_PUBLISH_LANGUAGE")){
			SOY2::import("module.plugins.util_multi_language.util.UtilMultiLanguageUtil");
			$config = UtilMultiLanguageUtil::getConfig();
			if(isset($config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"]) && strlen($config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"])){
				$mypageUri = $config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"] . "/" . $mypageUri;
			}
		}

		define("SOYSHOP_MYPAGE_URI", $mypageUri);
	}

	return SOYSHOP_MYPAGE_URI;
}

/**
 * マイページのURL
 */
function soyshop_get_mypage_url($isAbsolute = false){

	$isUseSSL = SOYShop_DataSets::get("config.mypage.use_ssl", 0);

	if($isUseSSL){
		$url = SOYShop_DataSets::get("config.mypage.ssl_url");
		$url .= soyshop_get_mypage_uri();

	}else{
		$url = soyshop_get_site_url($isAbsolute) . soyshop_get_mypage_uri();
	}

	return $url;
}

/**
 * マイページトップのURL
 */
function soyshop_get_mypage_top_url($isAbsolute = false){

	$url = soyshop_get_mypage_url($isAbsolute);

	if(strrpos($url, "/") == 0) $url = rtrim($url, "/");

	if(defined("SOYSHOP_IS_MOBILE") && SOYSHOP_IS_MOBILE){
		return $url . "/" . SOYShop_DataSets::get("config.mypage.mobile.top", "mb/top");
	}elseif(defined("SOYSHOP_IS_SMARTPHONE") && SOYSHOP_IS_SMARTPHONE){
		return $url . "/" . SOYShop_DataSets::get("config.mypage.smartphone.top", "i/top");
	}else{
		return $url . "/" . SOYShop_DataSets::get("config.mypage.top", "top");
	}
}

/**
 * マイページにリダイレクトする
 */
function soyshop_redirect_mypage($param = null){
	$url = soyshop_get_mypage_url();
	if($param)$url .= "?" . $param;
	header("Location: ". $url);
	exit;
}

/**
 * ログインページにリダイレクトする
 */
function soyshop_redirect_login_form($param = null){
	$url = soyshop_get_mypage_url() . "/login";
	if($param)$url .= "?" . $param;
	header("Location: ". $url);
	exit;
}

/**
 * プロフィールページからリダイレクトする
 */
function soyshop_redirect_from_profile(){
	$referer = (isset($_SERVER["HTTP_REFERER"]) && strlen($_SERVER["HTTP_REFERER"]) > 0) ? $_SERVER["HTTP_REFERER"] : soyshop_get_site_url();
	header('Location:' . $referer);
	exit;
}

/**
 * マイページにログインしているかどうか
 * @TODO 実装。カートと共通させるか？
 * @return boolean
 */
function soyshop_is_login_mypage(){
	return false;
}

/**
 * ログイン後に指定したページにリダイレクトする
 */
function soyshop_redirect_designated_page($param, $postfix = null){
	$location = "Location: ". rawurldecode($param);
	
	if(isset($postfix) && strlen($postfix)){
		$location .= "?" . $postfix;
	}
	header($location);
	exit;
}

function soyshop_remove_get_value($param){	
	if(strpos($param, "?")){
		$param = substr($param, 0, strrpos($param, "?"));
	}
	
	return $param;
}

/**
 * ページャのリンクの出力の際に検索やソートも考慮するURLを作る
 * @param string url
 * @return string url
 */
function soyshop_add_get_value($url){
	if(count($_GET) > 0){
		$query = http_build_query($_GET);
		if(strpos($url, "?")){
			$url .= "&" . $query;
		}else{
			$url .= "?" . $query;
		}
	}
	
	return $url;
}

if(!function_exists("_empty")){
	function _empty($arg){
		return empty($arg);
	}
}
?>