<?php

/**
 * @table soyshop_order_attribute
 */
class SOYShop_OrderAttribute {

	public static function getTableName(){
		return "soyshop_order_attribute";
	}
		
	const CUSTOMFIELD_TYPE_INPUT = "input";			//一行テキスト
	const CUSTOMFIELD_TYPE_TEXTAREA = "textarea";	//複数行テキスト
	const CUSTOMFIELD_TYPE_CHECKBOX = "checkbox";	//チェックボックス
	const CUSTOMFIELD_TYPE_RADIO = "radio";			//ラジオ
	const CUSTOMFIELD_TYPE_SELECT = "select";		//セレクトボックス
	const CUSTOMFIELD_TYPE_RICHTEXT = "richtext";	//リッチテキスト
	
	const CUSTOMFIELD_ATTRIBUTE_OTHER = 1;
	
	//必須項目
	const IS_REQUIRED = 1;
	const NO_REQUIRED = 0;

	/**
	 * @column order_id
	 */
	private $orderId;

	/**
	 * @column order_field_id
	 */
	private $fieldId;

	/**
	 * @column order_value1
	 */
	private $value1;

	/**
	 * @column order_value2
	 */
	private $value2;
	
	/**
	 * @column order_extra_values
	 */
	private $extraValues;

	function getOrderId() {
		return $this->orderId;
	}
	function setOrderId($orderId) {
		$this->orderId = $orderId;
	}
	function getFieldId() {
		return $this->fieldId;
	}
	function setFieldId($fieldId) {
		$this->fieldId = $fieldId;
	}
	function getValue1() {
		return $this->value1;
	}
	function setValue1($value1) {
		$this->value1 = $value1;
	}
	function getValue2() {
		return $this->value2;
	}
	function setValue2($value2) {
		$this->value2 = $value2;
	}
	function getExtraValues(){
		return $this->extraValues;
	}
	function setExtraValues($extraValues){
		$this->extraValues = $extraValues;
	}
	
	function getExtraValuesArray() {
		$res = soy2_unserialize($this->extraValues);
		if(is_array($res)){
			return $res;
		}else{
			return array();
		}
	}
	function setExtraValuesArray($extraValues) {
		if(is_array($extraValues)){
			$this->extraValues = soy2_serialize($extraValues);
		}else{
			$this->extraValues = soy2_serialize(array());
		}
	}
}


class SOYShop_OrderAttributeConfig{

	const DATASETS_KEY = "config.order.attributes";
	const DATASETS_INDEX = "config.order.indexed_attributes";

	public static function save($array){
		$array = array_values($array);

		$list = array();
		$indexed = array();
		foreach($array as $key => $config){
			if(strlen($config->getFieldId()) < 1){
				$config->setFieldId("customfield_" . $key);
			}

//			if($config->isIndex()){
//				$indexed[] = $config->getFieldId();
//			}

			$list[$config->getFieldId()] = $config;
		}

		$array = array_values($list);
		SOYShop_DataSets::put(self::DATASETS_KEY, $array);
//		$old = self::getIndexFields();

//		self::updateIndexFields($indexed,$old);
	}

	/**
	 * @return array
	 * @param boolean is map
	 */
	public static function load($flag = false){
		$array = SOYShop_DataSets::get(self::DATASETS_KEY, array());

		if(!$flag) return $array;

		$map = array();
		foreach($array as $config){
			$map[$config->getFieldId()] = $config;
		}

		return $map;
	}

	/**
	 * index
	 */
	public static function getIndexFields(){
		$array = SOYShop_DataSets::get(self::DATASETS_INDEX, array());
		return $array;
	}

	/**
	 * update ndex field
	 */
	private static function updateIndexFields($new, $old){
		$dao = SOY2DAOFactory::create("order.SOYShop_OrderDAO");

		//drop
		$drop = array_diff($old, $new);
		foreach($drop as $name){
			try{
				$dao->dropSortColumn($name);
			}catch(Exception $e){
				//
			}
		}

		//create
		$create = array_diff($new, $old);

		foreach($create as $name){
			try{
				$dao->createSortColumn($name);
			}catch(Exception $e){
				//
			}
		}

		$new = array_values($new);
		SOYShop_DataSets::put(self::DATASETS_INDEX, $new);

	}

	public static function getTypes(){

		return array(
			"input" => "一行テキスト",
			"textarea" => "複数行テキスト",
			"checkbox" => "チェックボックス",
			"radio" => "ラジオボタン",
			"select" => "セレクトボックス",
//			"richtext" => "リッチテキスト",
		);
	}

	private $fieldId;
	private $label;
	private $type;
	
	private $attributeDescription;
	private $attributeOther;
	private $attributeOtherText;

	private $defaultValue;
	private $emptyValue;
	
	//必須項目であるか
	private $isRequired;
	private $config;

	function getFieldId() {
		return $this->fieldId;
	}
	function setFieldId($fieldId) {
		$this->fieldId = $fieldId;
	}
	function getLabel() {
		return $this->label;
	}
	function setLabel($label) {
		$this->label = $label;
	}
	function getType() {
		return $this->type;
	}
	function setType($type) {
		$this->type = $type;
	}
	function getConfig() {
		return $this->config;
	}
	function setConfig($config) {
		$this->config = $config;
	}

	/* config method */
	function getAttributeDescription(){
		return $this->config["attributeDescription"];
	}
	function setAttributeDescription($attributeDescription){
		$this->attributeDescription = $attributeDescription;
	}
	function getAttributeOther(){
		return @$this->config["attributeOther"];
	}
	function setAttributeOther($attributeOther){
		$this->attributeOther = $attributeOther;
	}
	function getAttributeOtherText(){
		return @$this->config["attributeOtherText"];
	}
	function setAttributeOtherText($attributeOtherText){
		$this->attributeOtherText = $attributeOtherText;
	}

	function getOption() {
		return (isset($this->config["option"])) ? $this->config["option"] : "";
	}
	function setOption($option) {
		$this->config["option"] = $option;
	}
	function hasOption(){
		return (boolean)($this->getType() == SOYShop_OrderAttribute::CUSTOMFIELD_TYPE_RADIO || $this->getType() == SOYShop_OrderAttribute::CUSTOMFIELD_TYPE_SELECT || $this->getType() == SOYShop_OrderAttribute::CUSTOMFIELD_TYPE_CHECKBOX);
	}
	function hasRadioOption(){
		return (boolean)($this->getType() == SOYShop_OrderAttribute::CUSTOMFIELD_TYPE_RADIO);
	}

	function getFormName(){
		return 'customfield_module['.$this->getFieldId().']';
	}
	function getFormId(){
		return 'custom_field_'.$this->getFieldId();
	}

	function getForm($value){

		$h_formName = htmlspecialchars($this->getFormName(), ENT_QUOTES, "UTF-8");
		$h_formID = htmlspecialchars($this->getFormId(), ENT_QUOTES, "UTF-8");

		switch($this->getType()){
			case SOYShop_OrderAttribute::CUSTOMFIELD_TYPE_CHECKBOX:
				//DefaultValueがあればそれを使う
				$checkbox_value = (is_null($value) && strlen($this->getDefaultValue()) > 0) ? (explode(",", $this->getDefaultValue())) : explode(",", $value);
				$options = explode("\n", str_replace(array("\r\n", "\r"), "\n", $this->getOption()));
				$body = "";
				foreach($options as $key => $option){
					$body .= '<input type="checkbox" class="custom_field_checkbox"'
					       .' id="' . $h_formID . '_' . $key . '"'
					       .' name="' . $h_formName . '[]"'
					       .' value="' . htmlspecialchars($option, ENT_QUOTES, "UTF-8") . '"'
					       .( (in_array($option, $checkbox_value)) ? ' checked="checked"' : ""  )
					       .' />';
					$body .= '<label for="' . $h_formID . '_' . $key . '">' . $option . '</label>';
					$body .= "\n";
				}

				break;
				
			case SOYShop_OrderAttribute::CUSTOMFIELD_TYPE_RADIO:
				$options = explode("\n", str_replace(array("\r\n", "\r"), "\n", $this->getOption()));
				if(is_null($value)){
					/**
					 * radioの場合、valueは配列で["value"]と["other"]の値がある
					 */
					$value["value"] = $this->getDefaultValue();
					$value["other"] = null;
				}

				$body = "";
				foreach($options as $key => $option){
					$option = trim($option);
					if(strlen($option) > 0){
						$h_option = htmlspecialchars($option, ENT_QUOTES, "UTF-8");
						$id = 'custom_field_radio_' . $this->getFieldId() . '_' . $key;

						$body .= '<input type="radio" class="custom_field_radio"' .
								 ' name="' . $h_formName . '"' .
								 ' id="' . $id . '"'.
								 ' value="' . $h_option . '"' .
								 (($option == $value["value"]) ? ' checked="checked"' : "") .
								 ' />';
						$body .= '<label for="' . $id.'">' . $h_option . '</label>';
						$body .= "\n";
					}
				}
				
				$other = $this->getAttributeOther();
				$otherText = $this->getAttributeOtherText();
				
				if(isset($other) && $other == 1){
					$body .= '<input type="radio" class="custom_field_radio"' .
							 ' name="' . $h_formName . '"' .
							 ' id="custom_field_radio_' . $this->getFieldId() . '_other"'.
							 ' value="' . $this->getAttributeOtherText() . '"' .
							 (($otherText == $value["value"]) ? ' checked="checked"' : "") .
							 ' />';
					$body .= '<label for="custom_field_radio_' . $this->getFieldId() . '_other">' . $this->getAttributeOtherText() . '</label>'.
							 '<input type="text" name="customfield_module[custom_radio_other_text]" value="' .
							 (isset($value["other"]) ? $value["other"] : "") . '" />';
					$body .= "\n";
				}

				break;
				
			case SOYShop_OrderAttribute::CUSTOMFIELD_TYPE_SELECT:
				$options = explode("\n", str_replace(array("\r\n", "\r"), "\n", $this->getOption()));
				$value = (is_null($value)) ? $this->getDefaultValue() : $value ;

				$body = '<select class="cstom_field_select" name="' . $h_formName.'" id="' . $h_formID . '">';
				$body .= '<option value="">----</option>';
				foreach($options as $option){
					$option = trim($option);
					if(strlen($option) > 0){
						$h_option = htmlspecialchars($option, ENT_QUOTES, "UTF-8");
						$body .= '<option value="' . $h_option . '" ' .
								 (($option == $value) ? 'selected="selected"' : "") .
								 '>' . $h_option . '</option>' . "\n";
					}
				}
				$body .= '</select>';

				break;
				
			case SOYShop_OrderAttribute::CUSTOMFIELD_TYPE_TEXTAREA:
				$value = (is_null($value)) ? $this->getDefaultValue() : $value;
				$h_value = htmlspecialchars($value, ENT_QUOTES, "UTF-8");
				$body = '<textarea class="custom_field_textarea" style="width:100%;"'
				        .' id="' . $h_formID . '"'
				        .' name="' . $h_formName . '"'
				        .'>'
						.$h_value.'</textarea>';
				break;
				
			case SOYShop_OrderAttribute::CUSTOMFIELD_TYPE_RICHTEXT:
				$value = (is_null($value)) ? $this->getDefaultValue() : $value;
				$h_value = htmlspecialchars($value, ENT_QUOTES, "UTF-8");
				$body = '<textarea class="custom_field_textarea mceEditor" style="width:100%;"'
				        .' id="' . $h_formID . '"'
				        .' name="' . $h_formName . '"'
				        .'>'
						.$h_value.'</textarea>';
				break;
				
			default:
				$value = (is_null($value)) ? $this->getDefaultValue() : $value;
				$h_value = htmlspecialchars($value, ENT_QUOTES, "UTF-8");
				$body = '<input type="text" class="custom_field_input" style="width:100%"'
				       .' id="' . $h_formID . '"'
				       .' name="' . $h_formName . '"'
				       .' value="' . $h_value . '"'
				       .' />';
				break;
		}

		$return = $body . "\n";

		return $return;
	}
	
	function getDefaultValue() {
		return $this->config["defaultValue"];
	}
	function setDefaultValue($defaultValue) {
		$this->config["defaultValue"] = $defaultValue;
	}
	function getEmptyValue() {
		return $this->config["emptyValue"];
	}
	function setEmptyValue($emptyValue) {
		$this->config["emptyValue"] = $emptyValue;
	}
	function getIsRequired(){
		return (isset($this->config["isRequired"])) ? $this->config["isRequired"] : 0;
	}
	function setIsRequired($isRequired){
		$this->config["isRequired"] = $isRequired;
	}
}
?>