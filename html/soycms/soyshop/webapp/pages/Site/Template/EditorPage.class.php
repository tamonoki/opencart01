<?php
/**
 * @class Site.Template.EditorPage
 * @date 2009-11-27T03:36:27+09:00
 * @author SOY2HTMLFactory
 */
class EditorPage extends WebPage{

	function doPost(){

		if(soy2_check_token()){
			file_put_contents($this->iniFilepath,"name= \"" . $_POST["template_name"] . "\"");
			file_put_contents($this->filepath,$_POST["template_content"]);
		}

		$url = SOY2PageController::createLink("Site.Template.Editor") . "/-/" . $this->value . "?updated";
		if(isset($_GET["id"])) $url .= "&id=" . (int)($_GET["id"]);

		SOY2PageController::redirect($url);

	}

	private $filepath;
	private $iniFilePath;
	private $value;

	function EditorPage($args){
		
		$value = implode("/", $args);
		$this->value = $value;

		$templateDir = SOYSHOP_SITE_DIRECTORY . ".template/";
		$filepath = $templateDir . $value;
		$iniFilepath = str_replace(".html", ".ini", $filepath);
		$file = basename($filepath);
		$dir = dirname($filepath) . "/";

		$this->filepath = $filepath;
		$this->iniFilepath = $iniFilepath;

		WebPage::WebPage();

		if(preg_match('/(.*)\.html$/', $file, $tmp)){

			if(file_exists($dir . $tmp[1] . ".ini")){
				$array = parse_ini_file($dir . $tmp[1] . ".ini");
			}
		}

		if(isset($_GET["id"])){
			try{
				$pageDAO = SOY2DAOFactory::create("site.SOYShop_PageDAO");
				$page = $pageDAO->getById($_GET["id"]);
				$array = array(
					"id" => $page->getId(),
					"name" => $page->getName()
				);
			}catch(Exception $e){

			}
		}

		$this->addLink("page_link", array(
			"link" => (isset($array["id"])) ? SOY2PageController::createLink("Site.Pages.Detail." . $array["id"]) : "",
		));
		$this->addModel("is_custom_template", array(
			"visible" => (isset($array["id"]))
		));

		$this->addForm("update_form");
		
		$this->addInput("template_name_input", array(
			"name" => "template_name",
			"value" => (isset($array["name"])) ? $array["name"] : ""
		));

		$this->addLabel("template_path", array(
			"text" => $value
		));

		$this->addLabel("template_name", array(
			"text" => (isset($array["name"])) ? $array["name"] : ""
		));

		$this->addTextArea("template_content", array(
			"name" => "template_content",
			"value" => file_get_contents($filepath)
		));
		
		/** タグサンプル **/
		SOY2::import("domain.config.SOYShop_ShopConfig");
		$config = SOYShop_ShopConfig::load();
		if($config->getDisplayUsableTagList()){
			SOY2::import("module.CMSTagManager");
			CMSTagManager::addTemplateType($args[0]);
			$tagList = CMSTagManager::get();
		}else{
			$tagList = array();
		}
		
		$this->addModel("show_tag_sample_list", array(
			"visible" => (count($tagList) > 0)
		));
		
		$this->createAdd("tag_sample_list", "_common.Site.TemplateTagSampleComponent", array(
			"list" => $tagList
		));
	}
}
?>