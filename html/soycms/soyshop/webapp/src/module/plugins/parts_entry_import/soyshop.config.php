<?php
/**
 * プラグイン 詳細設定画面
 */
class EntryImportConfig extends SOYShopConfigPageBase{

	/**
	 * @return string
	 */
	function getConfigPage(){
		$form = SOY2HTMLFactory::createInstance("EntryImportConfigFormPage");
		$form->setConfigObj($this);
		$form->execute();
		return $form->getObject();
	}

	/**
	 * @return string
	 */
	function getConfigPageTitle(){
		return "ブログ記事表示設定";
	}
}
SOYShopPlugin::extension("soyshop.config", "parts_entry_import", "EntryImportConfig");


class EntryImportConfigFormPage extends WebPage{
	
	private $config;
	
	function EntryImportConfigFormPage(){
		SOY2DAOFactory::importEntity("SOYShop_DataSets");
	}
	
	function doPost(){
		
		if(soy2_check_token()){
			
			if(isset($_POST["confirm"]) || isset($_POST["confirm_x"])){
				SOYShop_DataSets::put("parts.entry.import", $_POST["site"]);
				$this->config->redirect("updated");
			}
			
			
			if(isset($_POST["complete"]) || isset($_POST["complete_x"])){
				//var_dump($_POST);exit;
				if(is_numeric($_POST["site"]["count"])){
					SOYShop_DataSets::put("parts.entry.import", $_POST["site"]);
					$this->config->redirect("updated");
				}else{
					$this->config->redirect("error");
				}
			}
		}
		
	}
	
	function execute(){
		
		include_once(dirname(__FILE__) . "/common.php");
		$siteConfig = EntryImportCommon::getSiteConfig();
		
		WebPage::WebPage();

		$common = new EntryImportCommon();
		$common->prepareSOYShopConfig();
		$common->changeSOYCMSDir();//SOY CMSのディレクトリへ
		$common->changeCmsDSN();//SOY CMS管理用DBへ切り替え
		
		//SOY CMS サイト一覧
		$sites = $common->getSiteList($siteConfig); 
		
		$this->addForm("form");
		$this->addLabel("site", array(
			"html" => $this->getSiteForm($sites, $siteConfig)
		));

		
		/** ここから、記事インポートの詳細設定を記述する **/
		
		
		//サイトIDを設定した後に表示する
		$this->addModel("config", array(
			"visible" => isset($siteConfig["siteId"])
		));

		//SOY CMSサイトDBに切り替える
		if(isset($siteConfig["siteId"])){
			$site = $common->getSite($siteConfig);
			$dsn = $site->getDataSourceName();
			SOY2DAOConfig::Dsn($dsn);
		}
		
		//ブログ一覧
		$blogs = $common->getBlogList();
		
		//元に戻す
		$common->setSOYShopConfig();


		/* 詳細設定画面 */
		$this->addForm("config_form");
		
		$this->addInput("site_id", array(
			"name" => "site[siteId]",
			"value" => (isset($siteConfig["siteId"])) ? $siteConfig["siteId"] : ""
		));
		
		$this->addLabel("page", array(
			"html" => $this->getPageForm($blogs, $siteConfig)
		));
		
		$this->addInput("entry_count", array(
			"name" => "site[count]",
			"value" => (isset($siteConfig["count"])) ? $siteConfig["count"] : 0,
			"size" => 3,
			"style" => "text-align:right;ime-mode:disabled;"
		));
		
		
		//詳細を設定した後に表示する
		$this->addModel("example", array(
			"visible" => isset($siteConfig["blogId"])
		));
		
		$this->addModel("updated", array(
			"visible" => isset($_GET["updated"])
		));
		$this->addModel("error", array(
			"visible" => isset($_GET["error"])
		));
		
	}
	
	function getTemplateFilePath(){
		return dirname(__FILE__) . "/soyshop.config.html";
	}

	function setConfigObj($obj) {
		$this->config = $obj;
	}
	
	/**
	 * サイト一覧 ラジオ HTML
	 * @param array $sites Site
	 * @param array $siteConfig このプラグインの設定 array(["siteId"],["blogId"],["count"])
	 * @return string $html
	 */
	function getSiteForm($sites, $siteConfig){
		
		$html = array();
		foreach($sites as $site){
			
			//サイト選択のラジオ
			if(isset($siteConfig["siteId"]) && $site->getSiteId() == $siteConfig["siteId"]){
				$html[] = "<input type=\"radio\" name=\"site[siteId]\" value=\"" . $site->getSiteId() . "\" id=\"" . $site->getId() . "\" checked=\"checked\" /><label for=\"" . $site->getId() . "\">" . $site->getSiteName() . "(ID:" . $site->getSiteId() . ")</label>";
			}else{
				$html[] = "<input type=\"radio\" name=\"site[siteId]\" value=\"" . $site->getSiteId() . "\" id=\"" . $site->getId() . "\" /><label for=\"" . $site->getId() . "\">" . $site->getSiteName() . "(ID:" . $site->getSiteId() . ")</label>";
			}
			
		}
		
		return implode("<br />\n", $html);
	}
	
	/**
	 * ブログ一覧 ラジオ HTML
	 * @param array $blogs array(BlogPage)
	 * @param array $siteConfig このプラグインの設定 array(["siteId"],["blogId"],["count"])
	 * @return string $html
	 */
	function getPageForm($blogs, $siteConfig){

		$html = array();
		foreach($blogs as $blog){
			
			//ブログの選択ラジオ
			if($blog->getId() == @$siteConfig["blogId"]){
				$html[] = "<input type=\"radio\" name=\"site[blogId]\" value=\"" . $blog->getId() . "\" id=\"" . $blog->getUri() . "\" checked=\"checked\" /><label for=\"" . $blog->getUri() . "\">" . $blog->getTitle() . "(ID:" . $blog->getUri() . ")</label>";
			}else{
				$html[] = "<input type=\"radio\" name=\"site[blogId]\" value=\"" . $blog->getId() . "\" id=\"" . $blog->getUri() . "\" /><label for=\"" . $blog->getUri() . "\">" . $blog->getTitle() . "(ID:" . $blog->getUri() . ")</label>";
			}	
		}
		return implode("<br />\n", $html);
	}
}
?>