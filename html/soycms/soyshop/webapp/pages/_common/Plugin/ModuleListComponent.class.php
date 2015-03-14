<?php

class ModuleListComponent extends HTMLList{

	protected function populateItem($entity){
		$this->addLabel("module_id", array(
			"text" => $entity->getId()
		));

		$type = $entity->getType();

		$detailLink = SOY2PageController::createLink("Plugin.Detail." . $entity->getId());
		$this->addLink("module_name", array(
			"text" => $entity->getName(),
			"link" => $detailLink
		));
		$this->addLabel("module_is_active", array(
			"text" => (($entity->getIsActive())? "インストール済み" : "未インストール")
		));
		$this->addLink("module_detail_link", array(
			"link" => $detailLink
		));

		return (strlen($entity->getName()) > 0);
	}

}

