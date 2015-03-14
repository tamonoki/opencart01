<?php
/**
 * @class Site.Template.IndexPage
 * @date 2009-11-16T19:36:01+09:00
 * @author SOY2HTMLFactory
 */
class IndexPage extends WebPage{

	function IndexPage(){
		WebPage::WebPage();

		$this->buildTemplate();
		$this->buildModule();
		$this->buildHtmlModule();

	}
	
	function buildTemplate(){
		
		$dao = SOY2DAOFactory::create("site.SOYShop_PageDAO");
		
		$this->types = SOYShop_Page::getTypeTexts();

		$this->createAdd("template_category_list", "_common.Site.TemplateCategoryListComponent", array(
			"list" => $this->getTemplateList(),
			"typeTexts" => SOYShop_Page::getTypeTexts()
		));

//		$this->createAdd("template_list", "_common.Site.TemplateListComponent", array(
//			"list" => $this->getTemplateList()
//		));

		$this->createAdd("cart_template_list", "_common.Site.TemplateListComponent", array(
			"list" => $this->getCartTemplateList()
		));
		
		$this->createAdd("mypage_template_list", "_common.Site.TemplateListComponent", array(
			"list" => $this->getMypageTemplateList()
		));

		$custom = $this->getCustomTemplateList($dao->get());
		$this->createAdd("custom_template_list", "_common.Site.TemplateListComponent", array(
			"list" => $custom
		));

		$this->addModel("custom_template_list_empty", array(
			"visible" => (empty($custom))
		));

		$this->addModel("custom_template_list_exists", array(
			"visible" => !empty($custom)
		));	
	
	}
		
	function buildModule(){
		
		//モジュール
		$modules = $this->getModules();
		
		$this->addModel("module_list_exists", array(
			"visible" => (count($modules) > 0)
		));
		$this->addModel("module_list_empty", array(
			"visible" => (count($modules) < 1)
		));
		$this->createAdd("module_list", "_common.Site.ModuleListComponent", array(
			"list" => $modules,
			"moduleType" => "php"
		));		

	}
	
	function buildHtmlModule(){
		
		//モジュール
		$modules = $this->getHtmlModules();
	
		$this->addModel("html_module_list_exists", array(
			"visible" => (count($modules) > 0)
		));
		$this->addModel("html_module_list_empty", array(
			"visible" => (count($modules) < 1)
		));
		$this->createAdd("html_module_list","_common.Site.ModuleListComponent", array(
			"list" => $modules,
			"moduleType" => "html"
		));		

	}

	private $types;

	function getTemplateList(){

		$templateDir = SOYSHOP_SITE_DIRECTORY . ".template/";

		$res = array();
		foreach($this->types as $key => $name){
			$templates = array();

			$dir = $templateDir . $key . "/";
			
			if(is_dir($dir) && is_readable($dir)){
				$files = scandir($dir);
				foreach($files as $file){
					if($file[0] == ".") continue;
					if(preg_match('/(.*)\.html$/', $file, $tmp)){
	
						if(is_readable($dir . $tmp[1] . ".ini")){
							$array = parse_ini_file($dir . $tmp[1] . ".ini");
	
							$templates[$key ."_" . $file] = array(
								"path" => $key . "/" . $file,
								"type" => $name,
								"name" => (isset($array["name"]) && strlen($array["name"]) > 0) ? $array["name"] : $file,
							);
						}
					}
				}
				$res[$key] = $templates;
			}
		}

		return $res;
	}

	function getCartTemplateList(){
		$templateDir = SOYSHOP_SITE_DIRECTORY . ".template/cart/";

		$files = scandir($templateDir);
		$res = array();
		foreach($files as $file){
			if($file[0] == ".") continue;
			if(preg_match('/(.*)\.html$/', $file, $tmp)){

				if(file_exists($templateDir . $tmp[1] . ".ini")){
					$array = parse_ini_file($templateDir . $tmp[1] . ".ini");

					$res[$file] = array(
						"path" => "cart" . "/" . $file,
						"type" => $tmp[1],
						"name" => (isset($array["name"]) && strlen($array["name"]) > 0) ? $array["name"] : $file,
					);
				}

				//もう一階層
				if(file_exists($templateDir . $tmp[1] . "/")
					&& is_dir($templateDir . $tmp[1] . "/")){
					$cartId = $tmp[1];
					$subDir = $templateDir . $tmp[1] . "/";
					$sub_files = scandir($subDir);


					foreach($sub_files as $file){
						if($file[0] == ".") continue;
						if(preg_match('/(.*)\.html$/', $file, $tmp)){
							if(file_exists($subDir . $tmp[1] . ".ini")){
								$array = parse_ini_file($subDir . $tmp[1] . ".ini");

								$res[$cartId . "/" . $file] = array(
									"path" => "cart" . "/$cartId/" . $file,
									"type" => $cartId . " (" . $tmp[1] . ")",
									"name" => (isset($array["name"]) && strlen($array["name"]) > 0) ? $array["name"] : $file,
								);
							}
						}
					}
				}
			}/* end loop */
		}

		return $res;
	}
	
	function getMypageTemplateList(){
		$templateDir = SOYSHOP_SITE_DIRECTORY . ".template/mypage/";

		$files = scandir($templateDir);
		$res = array();
		foreach($files as $file){
			if($file[0] == ".") continue;
			if(preg_match('/(.*)\.html$/', $file, $tmp)){

				if(file_exists($templateDir . $tmp[1] . ".ini")){
					$array = parse_ini_file($templateDir . $tmp[1] . ".ini");

					$res[$file] = array(
						"path" => "mypage" . "/" . $file,
						"type" => $tmp[1],
						"name" => (isset($array["name"]) && strlen($array["name"]) > 0) ? $array["name"] : $file,
					);
				}

				//もう一階層
				if(file_exists($templateDir . $tmp[1] . "/")
					&& is_dir($templateDir . $tmp[1] . "/")){
					$mypageId = $tmp[1];
					$subDir = $templateDir . $tmp[1] . "/";
					$sub_files = scandir($subDir);


					foreach($sub_files as $file){
						if($file[0] == ".") continue;
						if(preg_match('/(.*)\.html$/', $file, $tmp)){
							if(file_exists($subDir . $tmp[1] . ".ini")){
								$array = parse_ini_file($subDir . $tmp[1] . ".ini");

								$res[$mypageId . "/" . $file] = array(
									"path" => "mypage" . "/$mypageId/" . $file,
									"type" => $mypageId . " (" . $tmp[1] . ")",
									"name" => (isset($array["name"]) && strlen($array["name"]) > 0) ? $array["name"] : $file,
								);
							}
						}
					}
				}
			}/* end loop */
		}

		return $res;
	}

	function getCustomTemplateList($pages){
		$res = array();

		foreach($pages as $page){
			if(file_exists($page->getCustomTemplateFilePath())){

				$res[$page->getId()] = array(
					"path" => $page->getCustomTemplateFilePath(false) . "?id=" . $page->getId(),
					"type" => $this->types[$page->getType()],
					"name" => $page->getName(),
					"url" => "/" . $page->getUri()
				);
			}
		}

		return $res;
	}
	
	function getModules(){
		$res = array();
		$moduleDir = SOYSHOP_SITE_DIRECTORY . ".module/";
		
		$files = soy2_scanfiles($moduleDir);
		
		foreach($files as $file){
			$moduleId  = str_replace($moduleDir, "", $file);
			if(!preg_match('/\.php$/', $file)) continue;
			if(!$this->checkModuleDir($moduleId)) continue;
			
			//一個目の/より前はカテゴリ
			$moduleId = preg_replace('/\.php$/', "", $moduleId);
			$moduleId = str_replace("/", ".", $moduleId);
			$name = $moduleId;
			
			//ini
			$iniFilePath = preg_replace('/\.php$/', ".ini", $file);
			if(file_exists($iniFilePath)){
				$array = parse_ini_file($iniFilePath);
				if(isset($array["name"])) $name = $array["name"];
			}
			
			$res[] = array(
				"name" => $name,
				"moduleId" => $moduleId,
			);	
		}
		
		return $res;
	}
	
	function getHtmlModules(){
		$res = array();
		$moduleDir = SOYSHOP_SITE_DIRECTORY . ".module/html/";
		
		$files = array();
		if(is_dir($moduleDir)){
			$files = soy2_scanfiles($moduleDir);
		}
		
		
		foreach($files as $file){
			$moduleId  = str_replace($moduleDir, "", $file);
			if(!preg_match('/\.php$/', $file)) continue;
	
			//一個目の/より前はカテゴリ
			$moduleId = preg_replace('/\.php$/', "", $moduleId);
			$moduleId = str_replace("/", ".", $moduleId);
			$name = $moduleId;
			
			//ini
			$iniFilePath = preg_replace('/\.php$/', ".ini", $file);
			if(file_exists($iniFilePath)){
				$array = parse_ini_file($iniFilePath);
				if(isset($array["name"]))$name = $array["name"];
			}
			
			$res[] = array(
				"name" => $name,
				"moduleId" => $moduleId,
			);	
		}
		
		return $res;
	}
	
	//モジュール群からcommonディレクトリにあるモジュールを除く
	function checkModuleDir($dir){
		$res = true;
		
		if(preg_match("/^common./", $dir)){
			$res = false;
		}
		if(preg_match("/^html./", $dir)){
			$res = false;
		}
		
		return $res;
	}
}
?>