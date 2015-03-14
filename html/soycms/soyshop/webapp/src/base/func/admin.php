<?php
/**
 * ログイン権限があるか
 */
function soyshop_admin_login(){
	$session = SOY2ActionSession::getUserSession();

	//root user
	$root = $session->getAttribute("isdefault");
	if($root)return true;
	
	//auth level
	$level = soyshop_admin_auth_level();
	
	return ($level > 0);
}

/**
 * SOY Shopの権限レベルを取得
 */
function soyshop_admin_auth_level(){
	$session = SOY2ActionSession::getUserSession();
	$level = $session->getAttribute("app_shop_auth_level");
	
	if(is_null($level)){
		return 0;
	}else{
		return true;
	}
}

function print_update_date($time){
	if(date("Ymd") == date("Ymd",$time)){
		return date("H:i",$time);
	}

	return date("Y-m-d H:i", $time);
}

/**
 * 変数の文字列を数字に変換して返す。変数の文字列が数字でなかった場合は第二引数の値を返す
 * @param String, Integer
 * @return Integer
 */
function soyshop_convert_number($arg, $value){
	$arg = mb_convert_kana($arg, "a");
	if(strlen($arg) < 1 || !is_numeric($arg)){
		$arg = $value;
	}
	return $arg;
}

/**
 * 文字列の末尾のスラッシュを除く
 * @param String
 * @return String
 */
function soyshop_remove_close_slash($str){
	
	if(strrpos($str, "/") === strlen($str) - 1){
		$str = rtrim($str, "/");
	}
	
	return $str;
}
?>