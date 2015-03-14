<?php

class TemplateCategoryListComponent extends HTMLList{
	
	private $typeTexts;
	
	protected function populateItem($entity, $key){
		
		$this->addLabel("template_type", array(
			"text" => $this->typeTexts[$key]
		));
		
		$this->createAdd("template_list", "_common.Site.TemplateListComponent", array(
			"list" => $entity
		));
		
		$this->addLink("create_link", array(
			"link" => SOY2PageController::createLink("Site.Template.Create?type=" . $key)
		));
	}
	
	function setTypeTexts($typeTexts){
		$this->typeTexts = $typeTexts;
	}
}
?>