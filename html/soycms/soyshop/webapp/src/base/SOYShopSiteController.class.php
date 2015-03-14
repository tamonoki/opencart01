<?php
/**
 * ユーザ側 Controllerクラス
 */
class SOYShopSiteController extends SOY2PageController{

	private $timer = array();
	private $startTime;

	function execute(){
		$this->countTimer("Start");

		SOY2::import("logic.cart.CartLogic");
		SOY2::import("logic.mypage.MyPageLogic");

		$dao = SOY2DAOFactory::create("site.SOYShop_PageDAO");
		
		/* init event */
		SOYShopPlugin::load("soyshop.site.prepare");
		SOYShopPlugin::invoke("soyshop.site.prepare");
		
		/*
		 * ページのURIとパラメータを取得する
		 */
		list($uri, $args) = $this->getUriAndArgs();
		
		//カートかマイページを開いているか調べる
		$this->checkDisplayApplicationPage($uri);
		
		//カート・マイページ関連の定数
		if(!defined("SOYSHOP_CURRENT_CART_ID")) define("SOYSHOP_CURRENT_CART_ID", soyshop_get_cart_id());
		if(!defined("SOYSHOP_CURRENT_MYPAGE_ID")) define("SOYSHOP_CURRENT_MYPAGE_ID", soyshop_get_mypage_id());

		/*
		 * カート、マイページ
		 * notificationやdownloadでのexitを含む
		 */
		//カートページ、もしくはマイページを開いた場合
		if(SOYSHOP_APPLICATION_MODE){
			if( $this->doApplication($uri, $args) ){
				//正常に実行されればここで処理を完了する
				return;
			}
		}
		
		//https → http
		$this->redirectToNonSSLURL($uri, $args);

		try{
			//URIからページを取得
			try{
				$page = $dao->getByUri($uri);
			}catch(Exception $e){
				//ページが存在しない場合
				$page = $dao->getByUri(SOYSHOP_404_PAGE_MARKER);
			}
			
			//ページIDを放り込んでおく
			define("SOYSHOP_PAGE_ID", $page->getId());

			//404
			if(SOYSHOP_404_PAGE_MARKER == $page->getUri()){
				SOYShopPlugin::load("soyshop.site.404notfound");
				SOYShopPlugin::invoke("soyshop.site.404notfound");
				header("HTTP/1.0 404 Not Found");
			}

			/*
			 * 出力
			 * soyshop.site.onload
			 * soyshop.site.beforeoutput
			 * soyshop.site.onoutput
			 */
			$this->outputPage($uri, $args, $page);

		}catch(Exception $e){
			$this->onError($e);
		}

	}

	/**
	 * 予期しないエラーが発生した場合
	 */
	function onError(Exception $e){
		header("HTTP/1.0 500 Internal Server Error");
		echo "<h1>500 Internal Server Error</h1>";
		if(DEBUG_MODE){
			echo "<pre>";
			var_dump($e);
			echo "</pre>";
		}
	}

	/**
	 * @return
	 */
	function &getPathBuilder(){
		static $builder;

		if(!$builder){
			$builder = new SOYShopPathInfoBuilder();
		}

		return $builder;
	}

	/**
	 * PATH_INFOをページのURIとパラメータに分離する
	 * @return Array(String, Array)
	 */
	private function getUriAndArgs(){
		/*
		 * パスからURIと引数に変換
		 * 対応するページが存在すれば$uriに値が入る
		 */
		$pathBuilder = $this->getPathBuilder();
		$uri  = $pathBuilder->getPath();
		$args = $pathBuilder->getArguments();

		/*
		 * 対応するページがない場合
		 */
		if( empty($uri) ){
			if(empty($args)){
				/*
				 * ルート直下へのアクセスはトップページ
				 */
				$uri = SOYSHOP_TOP_PAGE_MARKER;
			}elseif( is_array($args) && count($args) && strlen($args[0]) && 0 === strpos($args[0], "index.") ){
				/*
				 * http://domain.com/index.*へのアクセスはトップページへリダイレクトする
				 */
				array_shift($args);
				$args = implode($args,"/");
				SOY2PageController::redirect(soyshop_get_site_url(true) . $args);
			}
		}else{
			/*
			 * http://domain.com/_homeへのアクセスはトップページへリダイレクトする
			 */
			if(SOYSHOP_TOP_PAGE_MARKER == $uri){
				$args = implode($args,"/");
				SOY2PageController::redirect(soyshop_get_site_url(true) . $args);
			}
			
			//携帯のリダイレクトの場合 カートもしくはマイページを開く直前に変数に値を入れ直す(多言語サイト分も追加)
			if(
				(defined("SOYSHOP_IS_MOBILE") && SOYSHOP_IS_MOBILE) || 
				(defined("SOYSHOP_IS_SMARTPHONE") && SOYSHOP_IS_SMARTPHONE) ||
				(defined("SOYSHOP_PUBLISH_LANGUAGE") && SOYSHOP_PUBLISH_LANGUAGE != "jp")
			){
				$pcCartUri = SOYShop_DataSets::get("config.cart.cart_url", "cart");
				$pcMyPageUri = SOYShop_DataSets::get("config.mypage.url", "user");
				if(isset($args[0]) && ($args[0] == $pcCartUri || $args[0] == $pcMyPageUri)){
					$uri .= "/" . $args[0];
					$args[0] = (isset($args[1])) ? $args[1] : "";
				}
			}			
		}

		return array($uri, $args);
	}
	
	function checkDisplayApplicationPage($uri){
		$isApp = false;
		$isCart = false;
		$isMypage = false;
		
		//多言語サイトプラグインをアクティブにしていないもしくはスマホページか日本語ページの時
		if(!defined("SOYSHOP_PUBLISH_LANGUAGE") || SOYSHOP_PUBLISH_LANGUAGE == "jp"){
			if($uri == soyshop_get_cart_uri()){
				$isApp = true;
				$isCart = true;
			}elseif($uri == soyshop_get_mypage_uri()){
				$isApp = true;
				$isMypage = true;
			}
		
		//多言語サイトプラグインをアクティブにしていて、多言語サイトを見ている時
		}elseif(defined("SOYSHOP_PUBLISH_LANGUAGE")){
			SOY2::import("module.plugins.util_multi_language.util.UtilMultiLanguageUtil");
			$config = UtilMultiLanguageUtil::getConfig();
			if(isset($config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"])){
				$cartUri = SOYShop_DataSets::get("config.cart.cart_url", "cart");
				$mypageUri = SOYShop_DataSets::get("config.mypage.url", "user");
				if($uri == $config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"] . "/" . $cartUri){
					$isApp = true;
					$isCart = true;
				}elseif($uri == $config[SOYSHOP_PUBLISH_LANGUAGE]["prefix"] . "/" . $mypageUri){
					$isApp = true;
					$isMypage = true;
				}
			}
		}
		
		define("SOYSHOP_APPLICATION_MODE", $isApp);
		define("SOYSHOP_CART_MODE", $isCart);
		define("SOYSHOP_MYPAGE_MODE", $isMypage);
	}

	/**
	 * ページ出力
	 * @param String $uri
	 * @param Array $args
	 * @param WebPage $page
	 */
	private function outputPage($uri, $args, $page){

		$this->countTimer("Search");

		$webPage = $page->getWebPageObject($args);
		$webPage->setArguments($args);

		/* Event OnLoad */
		SOYShopPlugin::load("soyshop.site.onload");
		SOYShopPlugin::invoke("soyshop.site.onload", array("page" => $webPage));

		$webPage->build($args);
		$this->countTimer("Build");

		$webPage->main($args);
		$webPage->common_execute();

		$this->countTimer("Main");
		$this->appendDebugInfo($webPage);

		/* Event BeforeOutput */
		SOYShopPlugin::load("soyshop.site.beforeoutput");
		SOYShopPlugin::invoke("soyshop.site.beforeoutput", array("page" => $webPage));

		ob_start();
		$webPage->display();
		$html = ob_get_contents();
		ob_end_clean();

		$this->countTimer("Render");
		$this->replaceRenderTime($html);

		/* EVENT onOutput */
		SOYShopPlugin::load("soyshop.site.onoutput");
		$delegate = SOYShopPlugin::invoke("soyshop.site.onoutput", array("html" => $html, "page" => $webPage));
		$html = $delegate->getHtml();

		echo $html;

	}

	/**
	 * カートやマイページの処理を行う
	 * @param String $uri
	 * @param Array $args
	 * @return Boolean
	 */
	private function doApplication($uri, $args){

		//カート マイページ 共通化
		SOY2::import("component.backward.BackwardUserComponent");
		SOY2::import("component.UserComponent");
		
		//カートの多言語化
		SOY2::import("message.MessageManager");

		//カート
		if(defined("SOYSHOP_CART_MODE") && SOYSHOP_CART_MODE){
			
			MessageManager::addMessagePath("cart");
			
			//notify event
			if(isset($_GET["soyshop_notification"])){
				$this->executeNotificationAction($_GET["soyshop_notification"]);
				exit;
			}

			$this->executeCartApplication($args);
			return true;
		}

		//マイページ
		if(defined("SOYSHOP_MYPAGE_MODE") && SOYSHOP_MYPAGE_MODE){
			
			MessageManager::addMessagePath("mypage");
			
			//download_event
			if(isset($_GET["soyshop_download"])){
				$this->executeDownloadAction($_GET["soyshop_download"]);
				exit;
			}
			$this->executeUserApplication($args);
			return true;
		}
	}

	/**
	 * カート実行
	 */
	function executeCartApplication($args){
		
		$webPage = SOY2HTMLFactory::createInstance("SOYShop_CartPage", array(
			"arguments" => array(SOYSHOP_CURRENT_CART_ID)
		));
		
		if(count($args) > 0 && $args[0] == "operation"){
			$webPage->doOperation();
			exit;
		}else{

			SOY2HTMLPlugin::addPlugin("src", "SrcPlugin");
			SOY2HTMLPlugin::addPlugin("display","DisplayPlugin");

			SOYShopPlugin::load("soyshop.site.onload");
			SOYShopPlugin::invoke("soyshop.site.onload", array("page" => $webPage));

			$webPage->common_execute();

			SOYShopPlugin::load("soyshop.site.beforeoutput");
			SOYShopPlugin::invoke("soyshop.site.beforeoutput", array("page" => $webPage));

			ob_start();
			$webPage->display();
			$html = ob_get_contents();
			ob_end_clean();

			SOYShopPlugin::load("soyshop.site.user.onoutput");
			$delegate = SOYShopPlugin::invoke("soyshop.site.user.onoutput", array("html" => $html));
			$html = $delegate->getHtml();

			echo $html;
		}
	}

	/**
	 * 通知イベント(決済など)
	 * @param string $pluginId $_GET["soyshop_notification"]
	 */
	function executeNotificationAction($pluginId){
		
		try{
			$moduleDAO = SOY2DAOFactory::create("plugin.SOYShop_PluginConfigDAO");
			$paymentModule = $moduleDAO->getByPluginId($pluginId);

			SOYShopPlugin::load("soyshop.notification", $paymentModule);
			SOYShopPlugin::invoke("soyshop.notification");
		}catch(Exception $e){
			//
		}
	}

	/**
	 * マイページ実行
	 */
	function executeUserApplication($args){

		$webPage = SOY2HTMLFactory::createInstance("SOYShop_UserPage", array(
			"arguments" => array(SOYSHOP_CURRENT_MYPAGE_ID, $args)
		));
		
		SOY2HTMLPlugin::addPlugin("src","SrcPlugin");
		SOY2HTMLPlugin::addPlugin("display","DisplayPlugin");

		SOYShopPlugin::load("soyshop.site.onload");
		SOYShopPlugin::invoke("soyshop.site.onload", array("page" => $webPage));

		$webPage->common_execute();

		SOYShopPlugin::load("soyshop.site.beforeoutput");
		SOYShopPlugin::invoke("soyshop.site.beforeoutput", array("page" => $webPage));

		ob_start();
		$webPage->display();
		$html = ob_get_contents();
		ob_end_clean();

		SOYShopPlugin::load("soyshop.site.user.onoutput");
		$delegate = SOYShopPlugin::invoke("soyshop.site.user.onoutput", array("html" => $html));
		$html = $delegate->getHtml();

		echo $html;
	}

	/**
	 * ダウンロード販売
	 */
	function executeDownloadAction($pluginId){
		try{
			$moduleDAO = SOY2DAOFactory::create("plugin.SOYShop_PluginConfigDAO");
			$downloadModule = $moduleDAO->getByPluginId($pluginId);

			SOYShopPlugin::load("soyshop.download",$downloadModule);
			SOYShopPlugin::invoke("soyshop.download");
		}catch(Exception $e){
			//
		}
	}

	/**
	 * SSLチェック
	 * ショップのURLがSSLを使わない設定のときにhttpsでアクセスされた場合はhttpにリダイレクトする
	 * @param String $uri
	 * @param Array $args
	 */
	private function redirectToNonSSLURL($uri, $args){
		if(isset($_SERVER["HTTPS"]) && strpos(strtolower(soyshop_get_site_url(true)), "https") !== 0){
			if($uri != SOYSHOP_TOP_PAGE_MARKER) array_unshift($args, $uri);
			$args = implode($args,"/");
			SOY2PageController::redirect(soyshop_get_site_url(true) . $args);
			exit;
		}
	}

	/**
	 * タイマーに記録する（デバッグモードのみ）
	 * @param String $label
	 */
	private function countTimer($label){
		if(DEBUG_MODE){
			$this->timer[$label] = microtime(true);
			if(!$this->startTime){
				$this->startTime = $this->timer[$label];
			}
		}
	}

	/**
	 * デバッグ情報をHTMLの末尾に付け足す（デバッグモードのみ）
	 * @param WebPage $webPage
	 */
	private function appendDebugInfo($webPage){
		if(DEBUG_MODE){
			$debugInfo = "";

			$previous = null;
			foreach($this->timer as $label => $time){
				if(!$previous){
					$previous = $time;
					continue;
				}
				$debugInfo .= "<p>".$label.": " . ($time - $previous) . " 秒</p>";
				$previous = $time;
			}
			$debugInfo .= "<p><b>Total: " . ($previous - $this->startTime) . " 秒</b></p>";
			$debugInfo .= "<p>Render: ##########RENDER_TIME######### 秒</p>";

			$ele = $webPage->getBodyElement();
			$ele->appendHTML($debugInfo);
		}
	}

	/**
	 * レンダリング時間を置換する
	 * @param String $html (リファレンス渡し)
	 */
	private function replaceRenderTime(&$html){
		if(DEBUG_MODE){
			$html = str_replace("##########RENDER_TIME#########", $this->timer["Render"] - $this->timer["Main"], $html);
		}
	}
}
?>