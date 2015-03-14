<?php
class MobileCheckPrepareAction extends SOYShopSitePrepareAction{

	//スマートフォンの転送先設定用定数
	const CONFIG_SP_REDIRECT_PC = 0;//PCサイト
	const CONFIG_SP_REDIRECT_SP = 1;//スマートフォンサイト
	const CONFIG_SP_REDIRECT_MB = 2;//ケータイサイト

	const REDIRECT_PC = 0;//PCサイト表示（何もしない）
	const REDIRECT_SP = 1;//スマートフォンサイト転送
	const REDIRECT_MB = 2;//ケータイサイト転送

	private $config;

	/**
	 * @return string
	 */
	function prepare(){

		SOY2::import("module.plugins.util_mobile_check.util.UtilMobileCheckUtil");

		$redirect = self::REDIRECT_PC;
		$isMobile = false;
		$isSmartPhone = false;

		//二度実行しない
		if(defined("SOYSHOP_IS_MOBILE")){
			return;
		}

		$this->config = UtilMobileCheckUtil::getConfig();
		$config = $this->config;


		//クッキー非対応機種の設定
		define("SOYSHOP_COOKIE", ( isset($config["cookie"]) && $config["cookie"]== 1 ));


		//セッションIDを再生成しておく（DoCoMo i-mode1.0 限定）
		if(
			$this->isMobile() && defined("SOYSHOP_MOBILE_CARRIER") && SOYSHOP_MOBILE_CARRIER == "DoCoMo" && SOYSHOP_COOKIE
			&&
			(isset($_GET[session_name()])||isset($_POST[session_name()])) && !isset($_COOKIE[session_name()])
		){

			$session_time = $config["session"] * 60;

			ini_set("session.gc_maxlifetime", $session_time);

			if(isset($_POST[session_name()])){
				session_id($_POST[session_name()]);
			}else{
				session_id($_GET[session_name()]);
			}

			session_start();
			session_regenerate_id(true);
			output_add_rewrite_var(session_name(), session_id());
		}

		//ケータイ
		if($this->isMobile()){
			$redirect = self::REDIRECT_MB;
			$isMobile = true;
		}

		//iPad
		if($this->isIpad()){
			if($config["redirect_ipad"] == self::CONFIG_SP_REDIRECT_SP){
				$redirect = self::REDIRECT_SP;
				$isSmartPhone = true;
			}else{
				//PC
				$redirect = self::REDIRECT_PC;
			}
		}

		//スマートフォン(iPadだった場合はチェックしない)
		if(defined("SOYSHOP_IS_IPAD") && SOYSHOP_IS_IPAD == false && $this->isSmartPhone()){
			if($config["redirect_iphone"] == self::CONFIG_SP_REDIRECT_SP){
				$redirect = self::REDIRECT_SP;
				$isSmartPhone = true;
			}elseif($config["redirect_iphone"] == self::CONFIG_SP_REDIRECT_MB){
				//ケータイ
				$redirect = self::REDIRECT_MB;
				$isMobile = true;
			}else{
				//PC
				$redirect = self::REDIRECT_PC;
			}
		}
		
		//ここで一旦定義を行う
		if(!defined("SOYSHOP_IS_MOBILE")){
			define("SOYSHOP_IS_MOBILE", $isMobile);
		}
		
		if(!defined("SOYSHOP_IS_SMARTPHONE")){
			define("SOYSHOP_IS_SMARTPHONE", $isSmartPhone);
		}
		

		//PCの場合はここで処理を終了
		if($redirect != self::REDIRECT_PC){
			//prefixの決定
			if($redirect == self::REDIRECT_MB && strlen($config["prefix"])){
				$prefix = $config["prefix"];
				define("SOYSHOP_DOCOMO_CSS",$config["css"]);
				//カートのお買物に戻るリンクの設定
				define("SOYSHOP_RETURN_LINK",$config["url"]);

				//au用の設定
				if(defined("SOYSHOP_MOBILE_CARRIER") && SOYSHOP_MOBILE_CARRIER == "KDDI"){
					header("Pragma: no-cache");
					header("Cache-Control: no-cache");
					header("Expires: -1");
				}
			}
			if($redirect == self::REDIRECT_SP && strlen($config["prefix_i"])){
				$prefix = $config["prefix_i"];
			}

			//リダイレクト先の絶対パス
			$path = $this->getRedirectPath($prefix);

			if($path){
				//if do not work Location header
				ob_start();
				echo "<a href=\"".htmlspecialchars($path, ENT_QUOTES, "UTF-8")."\">".htmlspecialchars($config["message"], ENT_QUOTES, "UTF-8")."</a>";

				//リダイレクト
				if($config["redirect"])SOY2PageController::redirect($path);

				exit;
			}
		}
	}

	function isMobile(){
		$carrier = "PC";
		$isMobile = false;

		$agent = @$_SERVER['HTTP_USER_AGENT'];

		//DoCoMo MOVA
		if(preg_match("/^DoCoMo\/1.0/i", $agent)){
		   $isMobile = true;
		   $carrier = "DoCoMo";

		//DoCoMo FOMA
		}elseif(preg_match("/^DoCoMo\/2.0/i", $agent)){
			$isMobile = true;
			//i-modeブラウザ2.0かチェック
			if(strpos($agent,"c500") !== false){
				$carrier = "i-mode2.0";
			}else{
				$carrier = "DoCoMo";
			}

		//SoftBank
		}elseif(preg_match("/^(J-PHONE|Vodafone|MOT-[CV]|SoftBank)/i", $agent)){
		   $isMobile = true;
		   $carrier = "SoftBank";

		//au
		}elseif(preg_match("/^KDDI-/i", $agent) || preg_match("/UP\.Browser/i", $agent)){
		   $isMobile = true;
		   $carrier = "KDDI";

		//それ以外はスルー
		}else{

		}

		//別キャリアを見ている場合は一旦PCにとばす
		if($isMobile==false){
			$this->checkCarrier($this->config["prefix"]);
		}

		if(!defined("SOYSHOP_MOBILE_CARRIER")){
			define("SOYSHOP_MOBILE_CARRIER",$carrier);
		}

		return $isMobile;
	}
	
	/**
	 * iPadからのアクセスかどうか
	 */
	function isIpad(){
		$isIpad = false;
		
		$agent = @$_SERVER['HTTP_USER_AGENT'];
		
		if(preg_match("/iPad/",$agent)){
			$isIpad = true;
		}
		
		if(!defined("SOYSHOP_IS_IPAD")){
			define("SOYSHOP_IS_IPAD",$isIpad);
		}
		
		//iPadだった場合、スマホの設定を見ないことにする
		if($isIpad==true){
			if($this->config["redirect_ipad"] == self::CONFIG_SP_REDIRECT_SP){
				$isSmartPhone = true;
			}else{
				$isSmartPhone = false;
			}
			
			//別キャリアを見ている場合は一旦PCにとばす
			if($isSmartPhone==false){
				$this->checkCarrier($this->config["prefix_i"]);
			}
		}
		
		return $isIpad;
	}

	/**
	 * スマートフォンからのアクセスかどうか
	 */
	function isSmartPhone(){
		$isSmartPhone = false;

		if($this->config["redirect_iphone"]==self::CONFIG_SP_REDIRECT_SP){
			$agent = @$_SERVER['HTTP_USER_AGENT'];
	
			if(preg_match("/iPhone/i", $agent)){
				$isSmartPhone = true;
			}elseif(preg_match("/Mobile/", $agent) && preg_match("/Safari/", $agent)){
				$isSmartPhone = true;
			}elseif(preg_match("/Android/i", $agent)){
				$isSmartPhone = true;
			}elseif(preg_match("/Windows Phone/i", $agent)){
				$isSmartPhone = true;
			}
	
			//別キャリアを見ている場合は一旦PCにとばす
			if($isSmartPhone==false){
				$this->checkCarrier($this->config["prefix_i"]);
			}
		}

		return $isSmartPhone;
	}

	/**
	 * キャリア判定でパスとキャリアが間違っている時、
	 * 一旦PCサイトにリダイレクトさせてから、サイドキャリアに対応したサイトにリダイレクト
	 */
	function checkCarrier($prefix){
		$pathInfo = @$_SERVER['PATH_INFO'];
		if($pathInfo === "/" . $prefix || strpos($pathInfo,"/" . $prefix."/") === 0){
			$path = $this->getRedirectPcPath($prefix);
			SOY2PageController::redirect($path);
			exit;
		}
	}

	/**
	 * URLにプレフィックスを付けた絶対パスを返す
	 */
	function getRedirectPath($prefix){
		//REQUEST_URI
		$requestUri = rawurldecode($_SERVER['REQUEST_URI']);
		//getの値を取り除く
		if(strpos($requestUri,"?") !== false){
			$requestUri = substr($requestUri,0,strpos($requestUri,"?"));
		}

		//PATH_INFO
		$pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "" ;
		//先頭はスラッシュ
		if(strlen($pathInfo) && $pathInfo[0]!=="/"){
			$pathInfo = "/" . $pathInfo;
		}

		//無限ループになるときはfalseを返す
		if( $pathInfo === "/" . $prefix || strpos($pathInfo,"/" . $prefix."/")===0 ){
			return false;
		}

		//サイトID：最初と最後に/を付けておく
		$siteDir = strlen($pathInfo) ? strtr($requestUri, array($pathInfo => "")) : $requestUri ;//strtrのキーは空文字列であってはいけない
		if($siteDir[0]!== "/"){
			$siteDir = "/" . $siteDir;
		}
		if(substr($siteDir, -1) !== "/"){
			$siteDir = $siteDir. "/";
		}

		//prefixを付ける
		$path = $siteDir. $prefix. $pathInfo;

		//絶対パスにQuery Stringを追加する
		if(isset($_SERVER["QUERY_STRING"]) && strlen($_SERVER["QUERY_STRING"]) > 0){
			$path = rtrim($path,"/");
			if(strpos($_SERVER["QUERY_STRING"],session_name() . "=") !== false){
				$querystring = preg_replace("/".session_name() . "=[A-Za-z0-9]*/",session_name() . "=".session_id(),$_SERVER["QUERY_STRING"]);
			}else{
				$querystring = $_SERVER["QUERY_STRING"];
			}

			$path .= "?" . $querystring;
		}

		return $path;
	}

	/**
	 * 各キャリアのprefixを除いたパスを返す
	 */
	function getRedirectPcPath($prefix){
		//REQUEST_URI
		$requestUri = rawurldecode($_SERVER['REQUEST_URI']);

		//getの値を取り除く
		if(strpos($requestUri,"?") !== false){
			$requestUri = substr($requestUri,0,strpos($requestUri,"?"));
		}

		//PATH_INFO
		$pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "" ;
		//先頭はスラッシュ
		if(strlen($pathInfo) && $pathInfo[0]!=="/"){
			$pathInfo = "/" . $pathInfo;
		}

		//サイトID：最初と最後に/を付けておく
		$path = strlen($pathInfo) ? strtr($requestUri, array($pathInfo => "")) : $requestUri ;//strtrのキーは空文字列であってはいけない
		$path = $requestUri;
		if($path[0]!== "/"){
			$path = "/" . $path;
		}
		if(substr($path, -1) !== "/"){
			$path = $path. "/";
		}

		//各キャリアのprefixを除いたものを返す
		if(strrpos($path,"/" . $prefix)==strlen($path)-strlen($prefix)-1){
			$path = str_replace("/" . $prefix,"",$path);
		}else{
			$path = str_replace("/" . $prefix."/","/",$path);
		}

		//絶対パスにQuery Stringを追加する
		if(isset($_SERVER["QUERY_STRING"]) && strlen($_SERVER["QUERY_STRING"]) > 0){
			$path = rtrim($path,"/");
			$path .= "?" . $_SERVER["QUERY_STRING"];
		}

		return $path;
	}
}

SOYShopPlugin::extension("soyshop.site.prepare", "util_mobile_check", "MobileCheckPrepareAction");