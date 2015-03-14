<?php
SOY2::import("logic.csv.ExImportLogicBase");
class ExImportLogic extends ExImportLogicBase{

	private $func;
	private $customFields = array();
	
	//作業用
	private $userAttributeDAO;

	/**
	 * CSV,TSVに変換
	 */
	function export($object){
		if(!$this->_func)$this->buildExFunc($this->getItems());
		$array = call_user_func($this->_func,$object,$this->getAttributes($object->getId()));
		return $this->encodeTo($this->implodeToLine($array));
	}

	/**
	 * CSV,TSVの一行からオブジェクトに変換
	 */
	function import($line){
		$line = $this->encodeFrom($line);
		$items = $this->explodeLine($line);
		if(!$this->_func)$this->buildImFunc($this->getItems());
		return call_user_func($this->_func,$items);
	}

	/**
	 * import用のfunction
	 */
	function buildImFunc($items){
		$function = array();
		$function[] = '$res = array();$attributes = array();$point = null;';

		$items = array_keys($items);
		foreach($items as $key => $item){
			if(!$item)continue;

			$function[] = 'if(isset($items['.$key.'])){ ';
			$function[] = '$item = trim($items['.$key.']);';

			if(preg_match('/customfield\(([^\)]+)\)/',$item,$tmp)){
				$function[] = '$attributes["'.$tmp[1].'"] = $item;';
			}else if(preg_match('/plugins\((.*)\)$/',$item,$tmp)){
				$function[] = '$plugins["'.$tmp[1].'"] = $item;';
			}else if(preg_match('/point/',$item,$tmp)){
				$function[] = '$point = $item;';
			}else{
				$function[] = '$res["'.$item.'"] = $item;';
			}
			$function[] = '}';
		}

		$function[] = 'return array($res,$attributes,$point);';
		$this->_func = create_function('$items',implode("\n", $function));
	}

	/**
	 * export用のfunction
	 */
	function buildExFunc($items){
		$labels = $this->getLabels();
		$usedLabels = array();
		$function = array();
		$function[] = '$res = array();';
		foreach($items as $key => $item){
			if(!$item)continue;

			if(preg_match('/customfield\(([^\)]+)\)/',$key,$tmp)){

				$fieldId = $tmp[1];

				if(isset($this->customFields[$fieldId])){
					$function[] = '$res[] = (isset($attributes["'.$fieldId.'"])) ? $attributes["'.$fieldId.'"]->getValue() : "";';
					$label = $this->customFields[$tmp[1]]->getLabel();
				}else{
					$function[] = '$res[] = "";';
					$label = "";
				}

			}else if(preg_match('/plugins\((.*)\)$/',$key,$tmp)){

				$pluginId = $tmp[1];

				$function[] = '$res[] = (isset($modules["'.$pluginId.'"])) ? $modules["'.$pluginId.'"]["plugin"]->export($obj->getId()) : "";';
				$label = (isset($this->modules[$pluginId])) ? $this->modules[$pluginId]["label"] : "";

			}else{
				$getter = "get" . ucwords($key);
				$function[] = '$res[] = (method_exists($obj,"'.$getter.'")) ? $obj->' . $getter . '() : "";';

				$label = $labels[$key];
			}

			//ラベル
			$usedLabels[] = $label;
		}
		$function[] = 'return $res;';

		$this->_func = create_function('$obj,$attributes',implode("\n", $function));//array($obj,$attributes,$modules)
		$this->setLabels($usedLabels);
	}

	function getAttributes($id){
		if(!$this->userAttributeDAO)$this->userAttributeDAO = SOY2DAOFactory::create("user.SOYShop_UserAttributeDAO");
		return $this->userAttributeDAO->getByUserId($id);
	}

	function getCustomFields() {
		return $this->customFields;
	}
	function setCustomFields($customFields) {
		$this->customFields = $customFields;
	}

}
?>