<?php
/**
 * @class Config.MyPageConfigPage
 * @date 2009-07-27T16:07:18+09:00
 * @author SOY2HTMLFactory
 */
class MyPageConfigPage extends WebPage{

	private $charsets = array("UTF-8", "Shift_JIS", "EUC-JP");

	function doPost(){
		if(soy2_check_token()){

			$mypage_id = $this->checkMyPageId($_POST["mypage_id"]);
			$mypage_url = $this->checkMyPageUrl($_POST["mypage_url"]);
			$mypage_top = $this->checkMyPageUrl($_POST["mypage_top"]);
			$mypage_charset = $_POST["mypage_charset"];
			
			$mobile_mypage_id = $this->checkMyPageId($_POST["mobile_mypage_id"]);
			$mobile_mypage_url = $this->checkMyPageUrl($_POST["mobile_mypage_url"]);
			$mobile_mypage_top = $this->checkMyPageUrl($_POST["mobile_mypage_top"]);
			$mobile_mypage_charset = $_POST["mobile_mypage_charset"];
			
			$smartphone_mypage_id = $this->checkMyPageId($_POST["smartphone_mypage_id"]);
			$smartphone_mypage_url = $this->checkMyPageUrl($_POST["smartphone_mypage_url"]);
			$smartphone_mypage_top = $this->checkMyPageUrl($_POST["smartphone_mypage_top"]);
			$smartphone_mypage_charset = $_POST["smartphone_mypage_charset"];
			
			$profile_resize = (isset($_POST["mypage_profile_resize"])) ? 1 : 0;
			
			SOYShop_DataSets::put("config.mypage.title", $_POST["mypage_title"]);

			SOYShop_DataSets::put("config.mypage.use_ssl", (int)$_POST["mypage_ssl"]);
			SOYShop_DataSets::put("config.mypage.ssl_url", $this->checkSSLMyPageUrl($_POST["mypage_ssl_url"]));

			SOYShop_DataSets::put("config.mypage.id", $mypage_id);
			SOYShop_DataSets::put("config.mypage.url", $mypage_url);
			SOYShop_DataSets::put("config.mypage.top", $mypage_top);
			SOYShop_DataSets::put("config.mypage.charset", $mypage_charset);

			SOYShop_DataSets::put("config.mypage.mobile.id", $mobile_mypage_id);
			SOYShop_DataSets::put("config.mypage.mobile.url", $mobile_mypage_url);
			SOYShop_DataSets::put("config.mypage.mobile.top", $mobile_mypage_top);
			SOYShop_DataSets::put("config.mypage.mobile.charset", $mobile_mypage_charset);
			
			SOYShop_DataSets::put("config.mypage.smartphone.id", $smartphone_mypage_id);
			SOYShop_DataSets::put("config.mypage.smartphone.url", $smartphone_mypage_url);
			SOYShop_DataSets::put("config.mypage.smartphone.top", $smartphone_mypage_top);
			SOYShop_DataSets::put("config.mypage.smartphone.charset", $smartphone_mypage_charset);
			
			SOYShop_DataSets::put("config.mypage.tmp_user_register", (int)$_POST["mypage_tmp_user_register"]);
			
			SOYShop_DataSets::put("config.mypage.profile_resize", $profile_resize);
			SOYShop_DataSets::put("config.mypage.profile_resize_width", $this->checkResizeValue($_POST["mypage_profile_resize_width"]));
		}

		SOY2PageController::jump("Config.MyPageConfig?updated");
	}

	function MyPageConfigPage(){
		WebPage::WebPage();

		$this->addForm("update_form");

		$this->addInput("mypage_title", array(
			"name" => "mypage_title",
			"value" => $this->getMyPageTitle()
		));

		$this->addInput("mypage_url", array(
			"name" => "mypage_url",
			"value" => $this->getMyPageUrl()
		));

		$this->addSelect("mypage_application", array(
			"name" => "mypage_id",
			"selected" => $this->getMyPageApplicationId(),
			"options" => $this->getMyPageApplications()
		));
		
		//toppage after loggedin
		$this->addInput("mypage_top", array(
			"name" => "mypage_top",
			"value" => $this->getMyPageTop()
		));
		
		$this->addSelect("mypage_charset", array(
			"name" => "mypage_charset",
			"selected" => $this->getMyPageCharset(),
			"options" => $this->charsets
		));

		$this->addInput("mobile_mypage_url", array(
			"name" => "mobile_mypage_url",
			"value" => $this->getMobileMyPageUrl()
		));

		$this->addSelect("mobile_mypage_application", array(
			"name" => "mobile_mypage_id",
			"selected" => $this->getMobileMyPageApplicationId(),
			"options" => $this->getMyPageApplications()
		));
		
		//toppage after loggedin
		$this->addInput("mobile_mypage_top", array(
			"name" => "mobile_mypage_top",
			"value" => $this->getMobileMyPageTop()
		));
		
		$this->addSelect("mobile_mypage_charset", array(
			"name" => "mobile_mypage_charset",
			"selected" => $this->getMobileMyPageCharset(),
			"options" => $this->charsets
		));
		
		$this->addInput("smartphone_mypage_url", array(
			"name" => "smartphone_mypage_url",
			"value" => $this->getSmartphoneMyPageUrl()
		));

		$this->addSelect("smartphone_mypage_application", array(
			"name" => "smartphone_mypage_id",
			"selected" => $this->getSmartphoneMyPageApplicationId(),
			"options" => $this->getMyPageApplications()
		));
		
		//toppage after loggedin
		$this->addInput("smartphone_mypage_top", array(
			"name" => "smartphone_mypage_top",
			"value" => $this->getSmartphoneMyPageTop()
		));
		
		$this->addSelect("smartphone_mypage_charset", array(
			"name" => "smartphone_mypage_charset",
			"selected" => $this->getSmartphoneMyPageCharset(),
			"options" => $this->charsets
		));

		$use_ssl = SOYShop_DataSets::get("config.mypage.use_ssl", 0);
		$this->addCheckBox("mypage_is_ssl", array(
			"name" => "mypage_ssl",
			"value" => 1,
			"selected" => $use_ssl,
			"label" => "SSLを使用する"
		));

		$this->addInput("mypage_ssl_url", array(
			"name" => "mypage_ssl_url",
			"value" => $this->getSSLMyPageUrl()
		));

		$this->addModel("mypage_ssl_url_input", array(
			"style" => ($use_ssl) ? "" : "display:none;"
		));
		
		
		//tmp user register
		$this->addCheckBox("mypage_tmp_user_register", array(
			"name" => "mypage_tmp_user_register",
			"value" => 1,
			"selected" => $this->getTmpUserRegister(),
			"label" => "仮登録処理を行う"
		));
		
		$this->addCheckBox("mypage_user_register", array(
			"name" => "mypage_tmp_user_register",
			"value" => 0,
			"selected" => (!$this->getTmpUserRegister()),
			"label" => "仮登録処理を行わない"
		));
		
		//resize
		$this->addCheckBox("mypage_profile_resize", array(
			"name" => "mypage_profile_resize",
			"value" => 1,
			"selected" => ($this->getProfileResize() == 1),
			"label" => "リサイズを行う"
		));
		
		$this->addInput("mypage_profile_resize_width", array(
			"name" => "mypage_profile_resize_width",
			"value" => $this->getProfileResizeWidth()
		));
	}

	function getMyPageUrl(){
		return SOYShop_DataSets::get("config.mypage.url", "user");
	}

	function getMobileMyPageUrl(){
		return SOYShop_DataSets::get("config.mypage.mobile.url", "mb/user");
	}
	
	function getSmartphoneMyPageUrl(){
		return SOYShop_DataSets::get("config.mypage.smartphone.url", "i/user");
	}

	function getSSLMyPageUrl(){
		$sslUrl = SOYShop_DataSets::get("config.mypage.ssl_url", null);
		if(is_null($sslUrl)){
			SOY2::import("domain.config.SOYShop_ShopConfig");
			$sslUrl = SOYShop_ShopConfig::load()->getSiteUrl();
		}
		return $sslUrl;
	}

	function getMyPageApplicationId(){
		return SOYShop_DataSets::get("config.mypage.id", "bryon");
	}
	
	function getMyPageCharset(){
		return SOYShop_DataSets::get("config.mypage.charset", "UTF-8");
	}

	function getMobileMyPageApplicationId(){
		return SOYShop_DataSets::get("config.mypage.mobile.id", "mobile");
	}
	
	function getMobileMyPageCharset(){
		return SOYShop_DataSets::get("config.mypage.mobile.charset", "Shift_JIS");
	}
	
	function getSmartphoneMyPageApplicationId(){
		return SOYShop_DataSets::get("config.mypage.smartphone.id", "smart");
	}
	
	function getSmartphoneMyPageCharset(){
		return SOYShop_DataSets::get("config.mypage.smartphone.charset", "UTF-8");
	}

	function getMyPageTitle(){
		return SOYShop_DataSets::get("config.mypage.title", "マイページ");
	}

	function getMyPageApplications(){
		$dir = SOY2::RootDir() . "mypage/";

		$files = scandir($dir);

		foreach($files as $file){
			if($file[0] == ".") continue;
			if($file[0] == "_") continue;

			$res[] = $file;
		}

		return $res;
	}

	function getMyPageTop(){
		return SOYShop_DataSets::get("config.mypage.top", "top");
	}
	
	function getMobileMyPageTop(){
		return SOYShop_DataSets::get("config.mypage.mobile.top", "mb/top");
	}
	
	function getSmartphoneMyPageTop(){
		return SOYShop_DataSets::get("config.mypage.smartphone.top","i/top");
	}
	
	function checkMyPageId($value){
		//対応するテンプレートが存在しない場合はここで作成する
		$this->makeTemplate($value);
		
		$values = $this->getMyPageApplications();
		return (in_array($value, $values)) ? $value : $this->getMyPageApplications();
	}
	
	function makeTemplate($value){
		$dir = SOYSHOP_SITE_DIRECTORY . ".template/mypage/";
		$iniFile = $dir . $value . ".ini";
		if(!file_exists($iniFile)){
			file_put_contents($iniFile, "name= \"" . $value . "\"");
			file_put_contents($dir . $value . ".html", "テンプレートの記述がありません。");
		}
	}

	function checkMyPageUrl($url){
		if(preg_match('/\/$/', $url)) $url = substr($url, 0, strlen($url)-1);
		if(strlen($url) < 1) return $this->getMyPageUrl();
		return $url;
	}
	function checkSSLMyPageUrl($url){
		if(strlen($url) < 1) return $this->getSSLMyPageUrl();
		return $url;
	}
	
	function getTmpUserRegister(){
		return SOYShop_DataSets::get("config.mypage.tmp_user_register", 1);
	}
	
	function getProfileResize(){
		return SOYShop_DataSets::get("config.mypage.profile_resize", 0);
	}
	function getProfileResizeWidth(){
		return SOYShop_DataSets::get("config.mypage.profile_resize_width", 120);
	}
	function checkResizeValue($int){
		$int = mb_convert_kana($int, "a");
		return (is_numeric($int)) ? (int)$int : 0;
	}
}
?>