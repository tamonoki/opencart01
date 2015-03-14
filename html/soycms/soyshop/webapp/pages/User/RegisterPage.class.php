<?php

class RegisterPage extends WebPage{

	private $errorType;
	private $user;

	function doPost(){
		if(!soy2_check_token()){
			SOY2PageController::jump("User.Register");
		}


		$dao = SOY2DAOFactory::create("user.SOYShop_UserDAO");
		$customer = (object)$_POST["Customer"];
		$user = SOY2::cast("SOYShop_User",$customer);
		
		$notSend = (isset($_POST["Customer"]["notSend"]) && $_POST["Customer"]["notSend"] > 0) ? 1 : 0;
		$user->setNotSend($notSend);
		$this->user = $user;
		
		//管理画面からの登録の場合は本登録にする
		$user->setUserType(SOYShop_User::USERTYPE_REGISTER);

		/*
		 * 書式チェック
		 */
		if(!$user->isValidEmail()){
			$this->errorType = "wrong_email";
			return;
		}

		/*
		 * すでに利用されていれば不可
		 */
		try{
			$dao->getByMailAddress($user->getMailAddress());
			$this->errorType = "used_email";
			return;
		}catch(Exception $e){
			//OK
		}

		/*
		 * 登録
		 */
		try{
			$dao = SOY2DAOFactory::create("user.SOYShop_UserDAO");
			$userId = $dao->insert($user);
		}catch(Exception $e){
			$this->errorType = "failed";
		}
		
		//ユーザカスタムフィールドの値をセッションに入れる
		SOYShopPlugin::load("soyshop.user.customfield");
		SOYShopPlugin::invoke("soyshop.user.customfield", array(
			"mode" => "register",
			"userId" => $userId
		));
		
		if(!$this->errorType){
			SOY2PageController::jump("User?registered");	
		}
	}

    function RegisterPage() {
    	/* 共通コンポーネント */
    	SOY2::import("base.site.classes.SOYShop_UserCustomfieldList");
    	SOY2::import("component.UserComponent");
    	SOY2::import("component.backward.BackwardUserComponent");
    	SOY2::import("logic.cart.CartLogic");
    	SOY2::import("logic.mypage.MyPageLogic");
    	
    	$this->backward = new BackwardUserComponent();
		$this->component = new UserComponent();
    	
    	WebPage::WebPage();
    	
    	$dao = SOY2DAOFactory::create("user.SOYShop_UserDAO");
    	
    	if(!$this->user){
	    	$this->user = new SOYShop_User();
    	}
	    	
    	$this->buildForm($this->user);
    	$this->buildJobForm($this->user);		//法人

    	DisplayPlugin::toggle("wrong_email",($this->errorType == "wrong_email"));
    	DisplayPlugin::toggle("used_email",($this->errorType == "used_email"));
    }

	function getCSS(){
		return array("./css/admin/user_detail.css");
	}

   function buildForm($user){
   		//以前のフォーム 後方互換
		$this->backward->backwardAdminBuildForm($this, $user);

		//共通フォーム
		$this->component->buildForm($this, $user, null, UserComponent::MODE_CUSTOM_FORM);

    	$this->addForm("register_form");
    }
    
    	
	/**
	 * 法人関連フォーム
	 * @param SOYShop_User $user
	 */
	function buildJobForm(SOYShop_User $user){
		/* 勤務先 */
		
		//法人名(勤務先など)
    	$this->addInput("office", array(
    		"name" => "Customer[jobName]",
    		"value" => $user->getJobName(),
    		"size" => 60,
    	));

		//法人所在地郵便番号
    	$this->addInput("office_zip_code", array(
    		"name" => "Customer[jobZipCode]",
    		"value" => $user->getJobZipCode(),
    		"size" => 60,
    	));

		//法人所在地 都道府県
    	$this->addSelect("office_area", array(
    		"name" => "Customer[jobArea]",
    		"options" => SOYShop_Area::getAreas(),
    		"selected" => $user->getJobArea()
    	));

		//法人所在地 入力1
    	$this->addInput("office_address1", array(
    		"name" => "Customer[jobAddress1]",
    		"value" => $user->getJobAddress1(),
    		"size" => 40
    	));

		//法人所在地 入力2
    	$this->addInput("office_address2", array(
    		"name" => "Customer[jobAddress2]",
    		"value" => $user->getJobAddress2(),
    		"size" => 100
    	));

		//法人電話番号
    	$this->addInput("office_tel_number", array(
    		"name" => "Customer[jobTelephoneNumber]",
    		"value" => $user->getJobTelephoneNumber(),
    		"size" => 30
    	));

		//法人FAX番号
    	$this->addInput("office_fax_number", array(
    		"name" => "Customer[jobFaxNumber]",
    		"value" => $user->getJobFaxNumber(),
    		"size" => 30
    	));
	}
}
?>