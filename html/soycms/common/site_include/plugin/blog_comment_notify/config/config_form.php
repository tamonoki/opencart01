<?php
class config_form extends WebPage{
	
	private $pluginObj;
	
	function doPost(){

    	if(soy2_check_token()){
			
			//ブログ設定
			if(isset($_POST["blog_config"]) && isset($_POST["BlogConfig"]) && is_array($_POST["BlogConfig"])){
				$list = $_POST["BlogConfig"];
				$this->pluginObj->setBlogConfig($list);
			}
			
			CMSPlugin::savePluginConfig(SOYCMS_BlogCommnetNotifyPlugin::PLUGIN_ID, $this->pluginObj);
			CMSPlugin::redirectConfigPage();
			
    	}
    	
	}
	
	function config_form(){}
	
	function execute(){
		WebPage::WebPage();
		
		$this->buildBlogListForm();//一覧
		
	}
	
	/**
	 * 一覧の表示、有効/無効の設定
	 */
	function buildBlogListForm(){
		$plugin = $this->pluginObj;
		$blogConfig = $plugin->getBlogConfig();
		
		$this->addForm("blog_form");
		
		$result = SOY2ActionFactory::createInstance("Blog.BlogListAction")->run();
		$list = $result->getAttribute("list");
		$this->createAdd("blog_list", "blog_list_component", array(
			"list" => $list,
			"blogConfig" => $blogConfig
		));
		
	}
	
	function getTemplateFilePath(){
		return dirname(__FILE__)."/config_form.html";
	}

	function setPluginObj($pluginObj) {
		$this->pluginObj = $pluginObj;
	}
}


class blog_list_component extends HTMLList{
	
	private $blogConfig = array();
	
	function populateItem($entity, $key, $index){
		
		//初期値
		if(isset($this->blogConfig[$entity->getId()])){
			$config = $this->blogConfig[$entity->getId()];
		}else{
			$config = array(
				"flg" => false,//有効,
				"mail_to" => null,//送り先
				"mail_title" => null,//メールタイトル
				"mail_content"=> null,//メール本文
			);
		}
		
		//ブログ名
		$this->addLabel("blog_name", array(
			"text" => $entity->getTitle()
		));
		
		//チェックボックス
		$this->addCheckbox("blog_checkbox", array(
			"name" => "BlogConfig[" . $entity->getId() . "][flg]",
			"value" => true,
			"selected" => $config["flg"],
			"isBoolean" => true,
			"elementId" => "blog_checkbox_". $entity->getId(),
			"onClick" => "toggle_area(". $entity->getId(). ")"
		));
		
		//label
		$this->addModel("blog_checkbox_label", array(
			"for" => "blog_checkbox_". $entity->getId()
		));

		//メール 送り先
		$this->addTextarea("mail_to", array(
			"name" => "BlogConfig[" . $entity->getId() . "][mail_to]",
			"value" => (isset($config["mail_to"])) ? $config["mail_to"] : "",
		));
		
		//メール タイトル
		$this->addInput("mail_title", array(
			"name" => "BlogConfig[" . $entity->getId() . "][mail_title]",
			"value" => (isset($config["mail_title"])) ? $config["mail_title"] : "",
		));
		
		//メール 本文
		$this->addTextarea("mail_content", array(
			"name" => "BlogConfig[" . $entity->getId() . "][mail_content]",
			"value" => (isset($config["mail_content"])) ? $config["mail_content"] : "",
		));
		
		/* toggle関係 */
		$this->addModel("toggle_area", array(
			"attr:id" => "toggle_area_". $entity->getId()
		));
		
		$this->addLabel("toggle_id", array(
			"text" => $entity->getId()
		));
	}
	
	public function getBlogConfig() {
		return $this->blogConfig;
	}
	public function setBlogConfig($blogConfig) {
		$this->blogConfig = $blogConfig;
	}
}


?>