<?php

class UtilMultiLanguagePrepareAction extends SOYShopSitePrepareAction{

	function prepare(){
		
		//端末振り分けプラグインの方とのバッティングを避けるため、モバイルもしくはスマホのリダイレクトの状態ならば処理を止める
		if((defined("SOYSHOP_IS_MOBILE") && SOYSHOP_IS_MOBILE) || (defined("SOYSHOP_IS_SMARTPHONE") && SOYSHOP_IS_SMARTPHONE)) return;
		
		//既に設定している場合は処理を止める
		if(defined("SOYSHOP_PUBLISH_LANGUAGE")) return;
		
		SOY2::import("module.plugins.util_multi_language.util.UtilMultiLanguageUtil");
		$config = UtilMultiLanguageUtil::getConfig();
		
		//ブラウザの言語設定を確認するモード
		if($config["check_browser_language_config"]){
			$language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
			
			if(preg_match('/^en/i', $language)) {
				$languageConfig = "en";
			}else{
				$languageConfig = "jp";
			}
			
		//言語切替ボタンを使うモード
		}else{
			$userSession = SOY2ActionSession::getUserSession();
			
			//言語切替ボタンを押したとき
			if(isset($_GET["language"])){
				$languageConfig = trim($_GET["language"]);
				$userSession->setAttribute("soyshop_publish_language", $languageConfig);
			//押してないとき
			}else{
				$languageConfig = $userSession->getAttribute("soyshop_publish_language");
				if(is_null($languageConfig)){
					//SOY CMSの方の言語設定も確認する
					$languageConfig = $userSession->getAttribute("soycms_publish_language");
					
					if(is_null($languageConfig)){
						$languageConfig = "jp";
						$userSession->setAttribute("soyshop_publish_language", $languageConfig);
					}
				}
			}			
		}

		if(!defined("SOYSHOP_PUBLISH_LANGUAGE")) define("SOYSHOP_PUBLISH_LANGUAGE", $languageConfig);
		
		$this->defineApplicationId($config);
		
		$redirectPath = $this->getRedirectPath($config);
		
		if($this->checkRedirectPath($redirectPath)){
			SOY2PageController::redirect($redirectPath);
			exit;
		}
	}
	
	function getRedirectPath($config){
		//REQUEST_URI
		$requestUri = rawurldecode($_SERVER['REQUEST_URI']);
		//getの値を取り除く
		if(strpos($requestUri, "?") !== false){
			$requestUri = substr($requestUri, 0, strpos($requestUri, "?"));
		}
		
		//PATH_INFO
		$pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "" ;
		//先頭はスラッシュ
		if(strlen($pathInfo) && $pathInfo[0] !== "/"){
			$pathInfo = "/" . $pathInfo;
		}
		
		$prefix = (isset($config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"])) ? $config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"] : "";
		
		//無限ループになるときはfalseを返す
		if( $pathInfo === "/" . $prefix || strpos($pathInfo, "/" . $prefix . "/") === 0 ){
			return false;
		}
		
		//サイトID：最初と最後に/を付けておく
		$siteDir = strlen($pathInfo) ? strtr($requestUri, array($pathInfo => "")) : $requestUri ;//strtrのキーは空文字列であってはいけない
		if($siteDir[0]!== "/"){
			$siteDir = "/" . $siteDir;
		}
		if(substr($siteDir, -1) !== "/" && strlen($prefix)){
			$siteDir = $siteDir . "/";
		}
		
		//prefixが0文字の場合はpathInfoの値から他のprefixがないかを調べる
		if(strlen($prefix) === 0){
			$pathInfo = $this->removeInsertPrefix($pathInfo, $config);
		}
		
		//prefixを付ける
		$path = $siteDir. $prefix. $pathInfo;
		
		//スラッシュが二つになった場合は一つにする
		$path = str_replace("//", "/", $path);
		

		//絶対パスにQuery Stringを追加する
		if(isset($_SERVER["QUERY_STRING"]) && strlen($_SERVER["QUERY_STRING"]) > 0){
			$path = rtrim($path, "/");
			if(strpos($_SERVER["QUERY_STRING"], session_name() . "=") !== false){
				$querystring = preg_replace("/" . session_name() . "=[A-Za-z0-9]*/", session_name() . "=" . session_id(), $_SERVER["QUERY_STRING"]);
			}else{
				$querystring = $_SERVER["QUERY_STRING"];
			}

			$path .= "?" . $querystring;
		}

		return $path;
	}
	
	function removeInsertPrefix($path, $config){
		if(isset($config["check_browser_language_config"])) unset($config["check_browser_language_config"]);
		foreach($config as $conf){
			if(!isset($conf["prefix"])) continue;
			if(preg_match('/\/' . $conf["prefix"] . '\//', $path) || $path == "/" . $conf["prefix"]){
				$path = str_replace("/" . $conf["prefix"], "", $path);
				break;
			}
		}
		return $path;
	}
	
	//リダイレクトを行う必要があるか調べる
	function checkRedirectPath($path){
		if($path === false) return false;
		
		$path = $this->formatPath($path);
		$requestUri = $this->formatPath($_SERVER["REQUEST_URI"]);
		
		return ($path !== $requestUri);
	}
	
	function formatPath($path){
		if(strpos($path, "/") !== 0){
			$path = "/" . $path;
		}
		
		if(strpos($path, "/?") !== 0){
			$path = str_replace("/?", "?", $path);
		}
		
		return $path;		
	}
	
	//カートページかマイページを表示している時
	function defineApplicationId($config){
		if(isset($config[SOYSHOP_PUBLISH_LANGUAGE]) && strlen($config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"]) > 0){
			define("SOYSHOP_CURRENT_CART_ID", soyshop_get_cart_id() . "_" . $config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"]);
			define("SOYSHOP_CURRENT_MYPAGE_ID", soyshop_get_mypage_id() . "_" . $config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"]);
		}
	}
}
SOYShopPlugin::extension("soyshop.site.prepare", "util_multi_languare", "UtilMultiLanguagePrepareAction");
?>