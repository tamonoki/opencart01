<?php
/**
 * ユーザカスタムフィールドの設定した項目の、SOYShop_User新規作成・更新時のチェック
 */
class SOYShopUserCustomfieldCheck implements SOY2PluginAction{

	private $mypage;//MyPageLogic || CartLogic
	private $user;
	private $page;//MainCartPageBase || MainMyPagePageBase
	
	function checkUser(MyPageLogic $mypage, SOYShop_User $user){
		
	}
	
	function appendErrors(MyPageLogic $mypage, $page){
		
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
	

	public function getPage() {
		return $this->page;
	}
	public function setPage($page) {
		$this->page = $page;
	}
}
class SOYShopUserCustomfieldCheckDelegateAction implements SOY2PluginDelegateAction{

	private $mode = "check";
	private $mypage;//MyPageLogic || CartLogic
	private $user;
	private $error = false;
	private $page;//MainCartPageBase || MainMyPagePageBase

	function run($extetensionId, $moduleId, SOY2PluginAction $action){

		$action->setMypage($this->getMypage());

		switch($this->mode){
			case "check";//チェック
				$this->error = $action->checkUser($this->mypage, $this->user); 
				break;
			case "appendErrors"://メッセージ表示/非表示
				$action->appendErrors($this->mypage, $this->page);
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
	public function getError() {
		return $this->error;
	}
	public function setError($error) {
		$this->error = $error;
	}

	public function getPage() {
		return $this->page;
	}
	public function setPage($page) {
		$this->page = $page;
	}
}
SOYShopPlugin::registerExtension("soyshop.user.customfield.check","SOYShopUserCustomfieldCheckDelegateAction");
?>