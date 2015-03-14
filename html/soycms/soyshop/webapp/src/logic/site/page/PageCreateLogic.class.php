<?php
SOY2::import("logic.site.page.PageLogic");

class PageCreateLogic extends PageLogic{

	private $errors = array();

    function validate($obj){

		$errors = array();

		if(strlen($obj->getName()) < 1){
			$errors["name"] = MessageManager::get("ERROR_REQUIRE");
		}

		if(strlen($obj->getUri()) < 1){
			$errors["uri"] = MessageManager::get("ERROR_REQUIRE");
		}else if(!preg_match('/^[a-zA-Z0-9\.\/\_-]+$/', $obj->getUri())){
			$errors["uri"] = Message::ERROR_INVALID;
		}

		if(strlen($obj->getType()) < 1){
			$errors["type"] = MessageManager::get("ERROR_REQUIRE");
		}

		//unique check
		$dao = SOY2DAOFactory::create("site.SOYShop_PageDAO");
		if(true == $dao->checkUri($obj->getUri())){
			$errors["uri"] = MessageManager::get("ERROR_INVALID");
		}

		$this->setErrors($errors);

		return (empty($errors));
    }

    function create($obj){
		$dao = SOY2DAOFactory::create("site.SOYShop_PageDAO");
		$id = $dao->insert($obj);

		$obj->setId($id);
		$this->onUpdate($obj);

		return $id;
    }

    function getErrors() {
    	return $this->errors;
    }
    function setErrors($errors) {
    	$this->errors = $errors;
    }
}
?>