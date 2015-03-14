<?php 
class IndexPage extends MainMyPagePageBase{

	public $component;
	public $backward;
	
	function doPost(){
		
		//保存
		if(soy2_check_token()){
		
			if(isset($_POST["confirm"]) || isset($_POST["confirm_x"])){
		
				$mypage = MyPageLogic::getMyPage();
				$userDAO = SOY2DAOFactory::create("user.SOYShop_UserDAO");
				$user = new SOYShop_User();
				
				//名前関連のデータの文字列変換
				$customer = $_POST["Customer"];
				$customer = $this->component->adjustUser($customer);
				
				//POSTデータ
				$postUser = (object)$customer;
				$user = SOY2::cast("SOYShop_User", $postUser);
				
				//ユーザカスタムフィールドの値をセッションに入れる
				if(isset($_POST["user_customfield"])){
					SOYShopPlugin::load("soyshop.user.customfield");
					SOYShopPlugin::invoke("soyshop.user.customfield", array(
						"mode" => "post",
						"app" => $mypage,
						"param" => $_POST["user_customfield"]
					));
				}
				
				//ユーザカスタムフィールド 値 調整	
				SOYShopPlugin::load("soyshop.user.customfield.adjust");
				$delegate = SOYShopPlugin::invoke("soyshop.user.customfield.adjust", array(
					"mode" => "adjust",
					"mypage" => $mypage,
					"user" => $user
				));
				$user = $delegate->getUser();
				$mypage->setUserInfo($user);
				
				if( $this->checkError($mypage) ){
					$this->jump("register/confirm");
				}else{
					$this->jump("register");
				}
			}
		}
		
		//郵便番号での住所検索
		if(isset($_POST["user_zip_search"]) || isset($_POST["user_zip_search_x"])){
			$logic = SOY2Logic::createInstance("logic.cart.AddressSearchLogic");
			$mypage = MyPageLogic::getMyPage();

			$postUser = (object)$_POST["Customer"];
			$user = SOY2::cast("SOYShop_User",$postUser);			

			$code = soyshop_cart_address_validate($user->getZipcode());
			$res = $logic->search($code);
			$user->setArea(SOYShop_Area::getAreaByText($res["prefecture"]));
			$user->setAddress1($res["address1"]);
			$user->setAddress2($res["address2"]);
			$anchor = "zipcode1";	

			$mypage->setUserInfo($user);
			$mypage->save();
			
			$this->jump("register#" . $anchor);
		}
	}
	
	function IndexPage(){

		$mypage = MyPageLogic::getMyPage();
		
		//すでにログインしていたら飛ばす
		if($mypage->getIsLoggedin()){
			$this->jumpToTop();
		}

		$user = $mypage->getUserInfo();
		if(is_null($user)) $user = new SOYShop_User();

		$this->backward = new BackwardUserComponent();
		$this->component = new UserComponent();

		WebPage::WebPage();


		//顧客情報フォーム
		$this->buildForm($user, $mypage);
		
		//エラー周り
		DisplayPlugin::toggle("has_error", $mypage->hasError());
		$this->appendErrors($mypage);
	}
	
	/**
	 * @param SOYShop_User $user
	 * @param MyPageLogic $mypage
	 * @param string $mode ユーザカスタムフィールドのモード指定
	 */
	function buildForm(SOYShop_User $user, MyPageLogic $mypage, $mode=UserComponent::MODE_CUSTOM_FORM){
		//共通コンポーネントに移し替え  soyshop/component/UserComponent.class.php buildFrom()
		//後方互換性確保は soyshop/component/backward/BackwardUserComponent 

		//以前のフォーム 後方互換
		$this->backward->backwardMyPageRegister($this, $user);

		//共通フォーム
		$this->component->buildForm($this, $user, $mypage, $mode);
	}
	
	/**
	 * エラー周りを設定
	 */
	function appendErrors(MyPageLogic $mypage){
		//共通エラーメッセージ
		$this->component->appendErrors($this, $mypage);
	}

	/**
	 * エラーチェック 問題がなければtrueを返す
	 * @return boolean
	 */
	function checkError(MyPageLogic $mypage){
		$user = $mypage->getUserInfo();
		$mypage->clearErrorMessage();
		$res = true;
		
		//共通エラーチェック
		$res = $this->component->checkError($user, $mypage, UserComponent::MODE_MYPAGE_REGISTER);
		
		$mypage->save();
		return $res;
	}
}
?>