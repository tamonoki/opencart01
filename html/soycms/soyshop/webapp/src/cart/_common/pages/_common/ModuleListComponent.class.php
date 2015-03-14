<?php
/**
 * @class ModuleList
 * @generated by SOY2HTML
 */
class ModuleListComponent extends HTMLList{

	protected function populateItem($entity){

		$this->addLabel("module_name", array(
			"text" => $entity->getName(),
		));

		$this->createAdd("module_price", "NumberFormatLabel", array(
			"text" => $entity->getPrice(),
		));

		return $entity->isVisible();
	}
}
?>