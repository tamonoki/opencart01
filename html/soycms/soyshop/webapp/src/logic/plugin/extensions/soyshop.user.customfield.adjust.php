<?php
/**
 * ユーザカスタムフィールドの設定した項目を、SOYShop_Userと絡ませる時の拡張ポイント
 */
class SOYShopUserCustomfieldAdjust implements SOY2PluginAction{

	private $mypage;
	private $user;
	
	/**
	 * Userの値を調節。
	 * @param MyPageLogic || CartLogic $mypage
	 * @param SOYShop_User $user
	 */
	function adjust(MyPageLogic $mypage, SOYShop_User $user){
/*
以下の4箇所で呼び出しを想定。
- カートの初回購入時。
- マイページの登録。
- マイページの編集。
- 管理画面
*/		
	}
		
	function getMypage() {
		return $this->mypage;
	}
	function setMypage($mypage) {
		$this->mypage = $mypage;
	}

	public function getUser() {
		return $this->user;
	}
	public function setUser($user) {
		$this->user = $user;
	}
	
	/**
	 * @param $obj
	 * @param $value
	 */
	function convertArray($obj, $value){
		$other = (isset($_POST["user_customfield"]["custom_radio_other_text"])) ? $_POST["user_customfield"]["custom_radio_other_text"] : null;
		$values = array();
		$values["value"] = $value;
		$config = $obj->getConfig();
		$values["other"] = (isset($config["attributeOtherText"]) && $value==$config["attributeOtherText"]) ? $other : null;

		return $values;
	}

	/**
	 * @param string $fieldId
	 * @return string MyPage/Cart の attributeのKey
	 */
	public static function getAttributeKey($fieldId){
		return "user_customfield_" . SOYSHOP_ID . "_" . $fieldId . ".value";
	}

	/**
	 * @param string $fieldId
	 * @param boolean $isRadio
	 * @return string MyPage/Cart の attributeのKey
	 */
	public static function getFormId($fieldId, $isRadio=false){
		
		if($isRadio){
			$id = "user_customfield_radio_". htmlspecialchars($fieldId, ENT_QUOTES, "UTF-8");
		}else{
			$id = "user_customfield_". htmlspecialchars($fieldId, ENT_QUOTES, "UTF-8");
		}
		
		return $id;
	}


}
class SOYShopUserCustomfieldAdjustDelegateAction implements SOY2PluginDelegateAction{

	private $mode = "adjust";
	private $mypage;
	private $user;
	private $param;//$_POST["user_customfield"][key]
	
	function run($extetensionId, $moduleId, SOY2PluginAction $action){

		$action->setMypage($this->getMypage());

		switch($this->mode){
			case "adjust";
				$user = $action->adjust($this->getMypage(), $this->getUser());
				$this->setUser($user);
				break;
		}
		
	}
	
	function getMode() {
		return $this->mode;
	}
	function setMode($mode) {
		$this->mode = $mode;
	}
	function getMypage() {
		return $this->mypage;
	}
	function setMypage($mypage) {
		$this->mypage = $mypage;
	}
	public function getUser() {
		return $this->user;
	}
	public function setUser($user) {
		$this->user = $user;
	}
	function getParam() {
		return $this->param;
	}
	function setParam($param) {
		$this->param = $param;
	}

}
SOYShopPlugin::registerExtension("soyshop.user.customfield.adjust","SOYShopUserCustomfieldAdjustDelegateAction");
?>