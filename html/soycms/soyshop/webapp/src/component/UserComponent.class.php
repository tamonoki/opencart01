<?php

/**
 * カート・マイページ ユーザ項目 共通コンポーネント
 */
class UserComponent {
	/* 動作ページ */
	const MODE_CART_REGISTER = 	"cart_register";	//カート 登録
	const MODE_CART_EDIT = 		"cart_edit";		//カート 編集
	const MODE_MYPAGE_REGISTER = "mypage_register";	//マイページ 登録
	const MODE_MYPAGE_EDIT = 	"mypage_edit";		//マイページ 編集
	const MODE_ADMIN = 			"admin";			//管理画面
	
	/* ユーザカスタムフィールド タイプ */
	const MODE_CUSTOM_FORM = "form";
	const MODE_CUSTOM_CONFIRM = "confirm";
	
	private $config;
	
	/**
	 * コンストラクタ
	 */
	public function UserComponent($args=null){
		$this->config = SOYShop_ShopConfig::load();
	}
	
	/**
	 * @param any $page 各部のページクラス
	 * @param SOYShop_User $user
	 * @param any $app CartLogic、もしくはMyPageLogic
	 * @param string $mode ユーザカスタムフィールドのモード指定
	 * @param boolean $isAdmin SOY Shop管理画面かどうか
	 */
	public function buildForm($page, SOYShop_User $user, $app ,$mode = self::MODE_CUSTOM_FORM){
		
		$page->addForm("form", array(
			"enctype" => "multipart/form-data"
		));

		/* SOYShop_User */

		$displayFormConfig = $this->config->getCustomerDisplayFormConfig();
		$requiredConfig = $this->config->getCustomerInformationConfig();
		
		//フォームの表示に関して
		foreach($displayFormConfig as $key => $boolean){
			$page->addModel($key . "_show", array(
				"visible" => ($boolean)
			));
		}
		
		//必須項目に関して
		foreach($requiredConfig as $key => $boolean){
			$page->addLabel($key . "_required", array(
				"text" => ($boolean) ? "（必須）" : "",
				"attr:class" => ($boolean) ? "require" : ""
			));
		}
		
		//メールアドレス
		$page->addInput("mail_address", array(
    		"name" => "Customer[mailAddress]",
    		"value" => $user->getMailAddress(),
    	));

		
		//パスワード
    	$page->addInput("password", array(
    		"name" => "Customer[password]",
    		"value" => $user->getPassword(),
    	));
		
		//氏名
    	$page->addInput("name", array(
    		"name" => "Customer[name]",
    		"value" => $user->getName(),
    	));
		
		//フリガナ
    	$page->addInput("reading", array(
    		"name" => "Customer[reading]",
    		"value" => $user->getReading(),
    	));

    	//ニックネーム
    	$page->addInput("nickname", array(
    		"name" => "Customer[nickname]",
    		"value" => $user->getNickname(),
    	));
    	
		//性別 男
    	$page->addCheckBox("gender_male", array(
    		"type" => "radio",
			"name" => "Customer[gender]",
			"value" => 0,
			"elementId" => "radio_sex_male",
			"selected" => ($user->getGender() === 0 || $user->getGender() === "0")
    	));
		
		//性別 女
    	$page->addCheckBox("gender_female", array(
    		"type" => "radio",
    		"name" => "Customer[gender]",
			"value" => 1,
			"elementId" => "radio_sex_female",
			"selected" => ($user->getGender() === 1 || $user->getGender() === "1")
    	));
    	
    	$gender = $user->getGender();
    	$page->addLabel("gender_text", array(
			"text" => ($gender == SOYShop_User::USER_SEX_MALE) ? MessageManager::get("SEX_MALE") :
			        ( ($gender == SOYShop_User::USER_SEX_FEMALE) ? MessageManager::get("SEX_FEMALE") : "" )
		));

		//生年月日 年
    	$page->addInput("birth_year", array(
    		"name" => "Customer[birthday][]",
    		"value" => $user->getBirthdayYear(),
    	));
		
		//生年月日 月
    	$page->addInput("birth_month", array(
    		"name" => "Customer[birthday][]",
    		"value" => $user->getBirthdayMonth(),
    	));
		
		//生年月日 日
    	$page->addInput("birth_day", array(
    		"name" => "Customer[birthday][]",
    		"value" => $user->getBirthdayDay(),
    	));
    	
    	$page->addLabel("birthday_text", array(
    		"text" => $user->getBirthdayText()
    	));
		
		//郵便番号
    	$page->addInput("zip_code", array(
    		"name" => "Customer[zipCode]",
    		"value" => $user->getZipCode()
    	));
		
		//都道府県
    	$page->addSelect("area", array(
    		"name" => "Customer[area]",
    		"options" => SOYShop_Area::getAreas(),
    		"value" => $user->getArea()
    	));
    	
		$page->addLabel("area_text", array(
			"text" => SOYShop_Area::getAreaText($user->getArea())
		));
		
		//住所入力1
    	$page->addInput("address1", array(
    		"name" => "Customer[address1]",
    		"value" => $user->getAddress1(),
    	));
		
		//住所入力2
    	$page->addInput("address2", array(
    		"name" => "Customer[address2]",
    		"value" => $user->getAddress2(),
    	));
		
		//電話番号
    	$page->addInput("telephone_number", array(
    		"name" => "Customer[telephoneNumber]",
    		"value" => $user->getTelephoneNumber(),
    	));
		
		//FAX番号
    	$page->addInput("fax_number", array(
    		"name" => "Customer[faxNumber]",
    		"value" => $user->getFaxNumber(),
    	));
		
		//携帯電話番号
    	$page->addInput("cellphone_number", array(
    		"name" => "Customer[cellphoneNumber]",
    		"value" => $user->getCellphoneNumber(),
    	));

    	//URL
    	$page->addInput("url", array(
    		"name" => "Customer[url]",
    		"value" => $user->getUrl(),
    	));
    	
    	//メールマガジン(ユーザカスタムフィールド内で登録の処理を行う)
    	$page->addModel("active_soymail_connector", array(
    		"visible" => (class_exists("SOYShopPluginUtil") && (SOYShopPluginUtil::checkIsActive("soymail_connector")))
    	));	
    	$page->addInput("mail_magazine_hidden", array(
    		"name" => "Customer[notSend]",
    		"value" => SOYShop_User::USER_NOT_SEND
    	));
    	$page->addCheckBox("mail_magazine", array(
    		"name" => "Customer[notSend]",
    		"value" => SOYShop_User::USER_SEND,
    		"selected" => $this->getIsCheckMailMagazine($user),
    		"label" => "メールマガジンを希望する"
    	));
    	$page->addLabel("mail_magazine_select", array(
    		"text" => ($user->getNotSend() == SOYShop_User::USER_SEND) ? "希望する" : "希望しない"
    	));
		
		//勤務先名称・職種
    	$page->addInput("job_name", array(
    		"name" => "Customer[jobName]",
    		"value" => $user->getJobName(),
    	));
		
		
		/* ユーザカスタムフィールド */		
    	SOYShopPlugin::load("soyshop.user.customfield");
		$delegate = SOYShopPlugin::invoke("soyshop.user.customfield", array(
			"mode" => $mode,
			"app" => $app,
			"userId" => $user->getId()
		));
		
		switch($mode){
			
			case self::MODE_CUSTOM_FORM;//入力画面
			default:
				$form = array();
				if(is_array($delegate->getList())){
					foreach($delegate->getList() as $list){
						if(is_array($list)){
							foreach($list as $key => $array){
								$form[$key] = $array;
							}
						}
					}
				}

				break;
			
			case self::MODE_CUSTOM_CONFIRM;//確認画面
				$form = array();
				if(is_array($delegate->getConfirm())){
					foreach($delegate->getConfirm() as $list){
						if(is_array($list)){
							foreach($list as $key => $array){
								$form[$key] = $array;
							}
						}
					}
				}
				
				break;
		}

		//ユーザカスタムフィールド 有効かつ設定がある場合 表示
		$page->addModel("has_user_customfield", array(
			"visible" => (count($form) > 0),
		));
		
		//ユーザカスタムフィールド
		$page->createAdd("user_customfield_list", "SOYShop_UserCustomfieldList", array(
			"list" => $form
		));
		
    	//各項目をcreateAdd
		$delegate = SOYShopPlugin::invoke("soyshop.user.customfield", array(
			"mode" => "build_named_form",
			"app" => $app,
			"pageObj" => $page,
			"userId" => $user->getId()
		));
		
		
	}
	
	/**
	 * エラーチェック
	 * @param SOYShop_User $user
	 * @param any $app CartLogic、もしくはMyPageLogic
	 * @param string $mode 処理の区別
	 * @return boolean エラーがなければtrue
	 */
	public function checkError(SOYShop_User $user, $app, $mode=UserComponent::MODE_CART_REGISTER){
		$res = true;
		
		/* 各エラーメッセージは/soyshop/webapp/src/message/以下にあります */
				
		/* メールアドレス */
		if($this->checkFormConfig("mailAddress")){
			if(tstrlen($user->getMailAddress()) < 1){
				//メールアドレスを入力していない場合
				$app->addErrorMessage("mail_address", MessageManager::get("MAIL_ADDRESS_EMPTY"));
				$res = false;
			}else if(!soyshop_valid_email($user->getMailAddress())){
				//メールアドレスの書式に誤りがある場合
				$app->addErrorMessage("mail_address", MessageManager::get("MAIL_ADDRESS_FALSE"));
				$res = false;
			}
		}
		
		/* 名前 */
		if($this->checkFormConfig("name")){
			if(tstrlen($user->getName()) < 1){
				//名前を入力していない場合
				$app->addErrorMessage("name", MessageManager::get("USER_NAME_EMPTY"));
				$res = false;
			}
		}
		
		/* フリガナ */
		if($this->checkFormConfig("reading")){
			$reading = str_replace(array(" ", "　"), "", $user->getReading());
			if(tstrlen($reading) < 1){
				//フリガナを入力していない場合
				$app->addErrorMessage("reading", MessageManager::get("USER_READING_EMPTY"));
				$res = false;
			}
	
			if(strlen(mb_ereg_replace("([-_a-zA-Z0-9ァ-ー０-９])", "", $reading)) !== 0){
				//フリガナの書式に誤りがある場合
				$app->addErrorMessage("reading", MessageManager::get("USER_READING_FALSE"));
				$res = false;
			}
		}
		
		/* ニックネーム(マイページのみ) */
		if($this->checkFormConfig("nickname")){
			if(tstrlen($user->getNickname()) < 1){
				//名前を入力していない場合
				$app->addErrorMessage("nickname", MessageManager::get("USER_NICKNAME_EMPTY"));
				$res = false;
			}
		}
		
		/* 郵便番号 */
		if($this->checkFormConfig("zipCode")){
			if(tstrlen($user->getZipCode()) < 1){
				//郵便番号を入力していない場合
				$app->addErrorMessage("zip_code", MessageManager::get("ZIP_CODE_EMPTY"));
				$res = false;
			}
		}
		
		/* 住所 */
		if($this->checkFormConfig("address")){
			if(tstrlen($user->getArea()) < 1 || tstrlen($user->getAddress1()) < 1){
				//住所を入力していない場合
				$app->addErrorMessage("address", MessageManager::get("ADDRESS_EMPTY"));
				$res = false;
			}
		}
		
		/* 電話番号 */
		if($this->checkFormConfig("telephoneNumber")){
			if(tstrlen($user->getTelephoneNumber()) < 1){
				//電話番号を入力していない場合
				$app->addErrorMessage("tel_number", MessageManager::get("TELEPHONE_NUMBER_EMPTY"));
				$res = false;
			}
		}
		
		/* 職種 */
		if($this->checkFormConfig("jobName")){
			if(tstrlen($user->getJobName()) < 1){
				$app->addErrorMessage("job_name", MessageManager::get("JOB_NAME_EMPTY"));
				$res = false;
			}
		}
		
		/* 性別 */
		if($this->checkFormConfig("gender")){
			if(tstrlen($user->getGender()) < 1){
				$app->addErrorMessage("gender", MessageManager::get("GENDER_EMPTY"));
				$res = false;
			}
		}
		
		/* 誕生日 */
		if($this->checkFormConfig("birthday")){
			if(is_null($user->getBirthday())){
				$app->addErrorMessage("birthday", MessageManager::get("BIRTHDAY_EMPTY"));
				$res = false;
			}
		}
		
		/* FAX番号 */
		if($this->checkFormConfig("faxNumber")){
			if(tstrlen($user->getFaxNumber()) < 1){
				//FAX番号を入力していない場合
				$app->addErrorMessage("fax_number", MessageManager::get("FAX_NUMBER_EMPTY"));
				$res = false;
			}
		}
		
		/* 携帯番号 */
		if($this->checkFormConfig("cellphoneNumber")){
			if(tstrlen($user->getCellphoneNumber()) < 1){
				//携帯番号を入力していない場合
				$app->addErrorMessage("cellphone_number", MessageManager::get("CELLPHONE_NUMBER_EMPTY"));
				$res = false;
			}
		}
		
		/* URL(マイページのみ) */
		if($this->checkFormConfig("url")){
			if(tstrlen($user->getUrl()) < 1){
				//携帯番号を入力していない場合
				$app->addErrorMessage("url", MessageManager::get("URL_EMPTY"));
				$res = false;
			}
		}
		
		/* 備考 */
		if($this->checkFormConfig("memo")){
			
			//カートの場合
			if(preg_match("/^cart/", $mode)){
				if(isset($_POST["Attributes"]["memo"]) && tstrlen($_POST["Attributes"]["memo"]) < 1){
					//備考を入力していない場合
					$app->addErrorMessage("memo", MessageManager::get("MEMO_EMPTY"));
					$res = false;
				}
				
			//マイページの場合
			}else if(preg_match("/^mypage/", $mode)){
				if(isset($_POST["Customer"]["memo"]) && tstrlen($_POST["Customer"]["memo"]) < 1){
					//備考を入力していない場合
					$app->addErrorMessage("memo", MessageManager::get("MEMO_EMPTY"));
					$res = false;
				}
			}else{
				//
			}
				
		}

		/* パスワード */
		
		switch($mode){
			/* カート 登録 */
			case self::MODE_CART_REGISTER;
			default:	

				if( $app->getAttribute("logined") ){
					//ログイン時：パスワード変更
					if(isset($_POST["new_password"]) && is_array($_POST["new_password"]) &&
						(strlen($_POST["new_password"]["old"]) > 0 || strlen($_POST["new_password"]["new"]) > 0)
					){
						$old = (isset($_POST["new_password"]["old"])) ? $_POST["new_password"]["old"] : "";
						$new = (isset($_POST["new_password"]["new"])) ? $_POST["new_password"]["new"] : "";
		
						try{
							$userDAO = SOY2DAOFactory::create("user.SOYShop_UserDAO");
							$oldUser = $userDAO->getById($app->getAttribute("logined_userid"));
		
							if( $user->checkPassword($oldUser) ){
								if( strlen($new) < 8 ){
									//新しいパスワード設定で文字数が足りない場合
									$app->addErrorMessage("password_error", MessageManager::get("NEW_PASSWORD_COUNT_NOT_ENOUGH"));
									$res = false;
								}else{
									$app->setAttribute("new_password", $new);
								}
							}else{
								//新しいパスワード設定で古いパスワードに誤りがある場合
								$app->addErrorMessage("password_error", MessageManager::get("OLD_PASSWORD_DIFFERENT"));
								$res = false;
							}
						}catch(Exception $e){
							//DB error?
						}
					}
				}else{
					//未ログイン時
					if( tstrlen($user->getPassword()) ){
						if(tstrlen($user->getPassword()) < 8){
							//パスワード設定で文字数が足りない場合
							$app->addErrorMessage("password_error", MessageManager::get("PASSWORD_COUNT_NOT_ENOUGH"));
							$res = false;
						}
					}
				}
				
				break;
			
			/* カート 編集 */
			case self::MODE_CART_EDIT;
				
				break;
			
			/* マイページ 登録 */
			case self::MODE_MYPAGE_REGISTER;

				if(tstrlen($user->getPassword()) < 1){
					//パスワードが入力されていない場合
					$app->addErrorMessage("password", MessageManager::get("PASSWORD_EMPTY"));
					$res = false;
				}elseif(tstrlen($user->getPassword()) < 8){
					//パスワード設定で文字数が足りない場合
					$app->addErrorMessage("password", MessageManager::get("PASSWORD_COUNT_NOT_ENOUGH"));
					$res = false;
				}elseif(!preg_match("/^[a-zA-Z0-9]+$/", $user->getPassword())){
					//パスワードの書式に誤りがある場合
		    		$app->addErrorMessage("password", MessageManager::get("PASSWORD_FALSE"));
		    	}
				
				break;
			
			/* マイページ 編集 */
			case self::MODE_MYPAGE_EDIT;
				//パスワード変更なし
				break;
		}		
		
		/* メールアドレスの重複チェック */
		switch($mode){
			/* カート 登録 */
			case self::MODE_CART_REGISTER;
				
				break;
			
			/* カート 編集 */
			case self::MODE_CART_EDIT;
				
				break;
			
			/* マイページ 登録 */
			case self::MODE_MYPAGE_REGISTER;
				//メールアドレスの重複チェック
				$userDao = SOY2DAOFactory::create("user.SOYShop_UserDAO");
				try{
					$oldUser = $userDao->getByMailAddress($user->getMailAddress());
					$tmpUser = SOYShop_DataSets::get("config.mypage.tmp_user_register", 1);
					
					//仮登録ユーザだった場合は上書き
					if($tmpUser){
						//仮登録処理を行う
						if($oldUser->getUserType() != SOYShop_User::USERTYPE_TMP){
							$app->addErrorMessage("mail_address", MessageManager::get("MAIL_ADDRESS_REGISTERED_ALREADY"));
							$res = false;
						}
						
					}else{
						//仮登録処理を行わない
						$app->addErrorMessage("mail_address", MessageManager::get("MAIL_ADDRESS_REGISTERED_ALREADY"));
						$res = false;
					}
					
				}catch(Exception $e){
					
				}
				
				break;
			
			/* マイページ 編集 */
			case self::MODE_MYPAGE_EDIT;
				//登録されているメールアドレス
				$oldAddress = $app->getUser()->getMailAddress();
		
				//今回入力したメールアドレス
				$newAddress = $user->getMailAddress();
		
				//すでに登録されているアドレスと入力したアドレスが異なる場合は重複チェックを開始する
				if($oldAddress != $newAddress){
					$userDao = SOY2DAOFactory::create("user.SOYShop_UserDAO");
					try{
						$duplication = $userDao->getByMailAddress($newAddress);
						$app->addErrorMessage("mail_address", MessageManager::get("MAIL_ADDRESS_REGISTERED_ALREADY"));
						$res = false;
					}catch(Exception $e){
						//問題なし
					}
				}
				
				break;
		}
		
		/* ログインID(マイページのみ) */
		if($this->checkFormConfig("accountId")){
			if(tstrlen($user->getAccountId()) < 1){
				//ログインIDを入力していない場合
				$app->addErrorMessage("account_id", MessageManager::get("ACCOUNT_ID_EMPTY"));
				$res = false;
			}else if(!preg_match("/^[a-zA-Z0-9]+$/", $user->getAccountId())){
				//入力に誤りがある場合
				$app->addErrorMessage("account_id", MessageManager::get("ACCOUNT_ID_FALSE"));
				$res = false;
			}else{
				//重複チェック
				$userDao = SOY2DAOFactory::create("user.SOYShop_UserDAO");
				try{
					$user = $userDao->getByAccountId($user->getAccountId());
					$app->addErrorMessage("account_id", MessageManager::get("ACCOUNT_ID_REGISTERED_ALREADY"));
					$res = false;
				}catch(Exception $e){
					//問題なし
				}
			}
		}
		
		/* ユーザカスタムフィールド */
		//各項目をチェック
		SOYShopPlugin::load("soyshop.user.customfield");
		$delegate = SOYShopPlugin::invoke("soyshop.user.customfield", array(
			"mode" => "checkError",
			"app" => $app,
			"param" => (isset($_POST["user_customfield"])) ? $_POST["user_customfield"] : array(),
			"user" => $user
		));
		
		if($delegate->hasError()){
			$app->addErrorMessage("customfield", MessageManager::get("CUSTOMFIELD_ERROR"));
			$res = false;
		}else{
			$app->removeErrorMessage("customfield");
		}
		
		//極力上の拡張ポイントを使用してほしい
		SOYShopPlugin::load("soyshop.user.customfield.check");
		$delegate = SOYShopPlugin::invoke("soyshop.user.customfield.check", array(
			"mode" => "check",
			"mypage" => $app,
			"user" => $user
		));
		
		if($delegate->getError()){
			$res = false;
		}

		return $res;
	}
	
	/**
	 * エラーメッセージ
	 * @param any $page
	 * @param any $app CartLogic、もしくはMyPageLogic
	 */
	public function appendErrors($page, $app){
		
		//メールアドレス
		$page->createAdd("mail_address_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("mail_address")
		));
		
		//ログインID
		$page->createAdd("account_id_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("account_id")
		));
		
		//名前
		$page->createAdd("name_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("name")
		));
		
		//フリガナ
		$page->createAdd("reading_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("reading")
		));
		
		//ニックネーム
		$page->createAdd("nickname_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("nickname")
		));
		
		//郵便番号
		$page->createAdd("zip_code_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("zip_code")
		));
		
		//都道府県
		$page->createAdd("pref_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("pref")
		));
		
		//住所
		$page->createAdd("address_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("address")
		));
		
		//電話番号
		$page->createAdd("tel_number_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("tel_number")
		));
		
		//FAX番号
		$page->createAdd("fax_number_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("fax_number")
		));
		
		//携帯番号
		$page->createAdd("cellphone_number_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("cellphone_number")
		));
		
		//職種
		$page->createAdd("job_name_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("job_name")
		));
		
		//性別
		$page->createAdd("gender_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("gender")
		));
		
		//誕生日
		$page->createAdd("birthday_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("birthday")
		));
		
		//URL
		$page->createAdd("url_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("url")
		));
		
		//備考
		$page->createAdd("memo_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("memo")
		));
		
		/* password */		
		$passwordError = null;

		$cartError = $app->getErrorMessage("password_error");//カート登録
		if(!is_null($cartError))$passwordError = $cartError;

		$mypageError = $app->getErrorMessage("password");//マイページ 登録
		if(!is_null($mypageError))$passwordError = $mypageError;

		//パスワード
		$page->createAdd("password_error", "ErrorMessageLabel", array(
			"text" => $passwordError
		));
		
		//パスワード 間違え
		$page->createAdd("password_invalid", "ErrorMessageLabel", array(
			"text" => $passwordError
		));
		
		
		/* 送り先 */

		//送り先
		$page->createAdd("send_address_error", "ErrorMessageLabel", array(
			"text" => $app->getErrorMessage("send_address")
		));
		
		//送り先 表示
		$page->createAdd("has_send_address_error","HTMLModel", array(
			"visible" => (strlen($app->getErrorMessage("send_address")) > 0)
		));
		
		
		
		/* ユーザカスタムフィールド */
		SOYShopPlugin::load("soyshop.user.customfield.check");
    	//各項目のエラーメッセージ 表示/非表示
		$delegate = SOYShopPlugin::invoke("soyshop.user.customfield.check",array(
			"mode" => "appendErrors",
			"mypage" => $app,
			"page" => $page,
		));
		
	}
	
	private $display;
	private $required;
	
	function checkFormConfig($key){
		
		if(!$this->display){
			$this->display = $this->config->getCustomerDisplayFormConfig();
			$this->required = $this->config->getCustomerInformationConfig();	
		}
		$display = $this->display;
		$required = $this->required;
		
		if((isset($display[$key]) && $display[$key] && isset($required[$key]) && $required[$key])){
			
			//この３つは後に細かくエラーチェックを行う
			if($key == "address" || $key == "memo" || $key == "gender") return true;

			//それ以外の項目はここでフォームが表示されているか調べる
			return (isset($_POST["Customer"][$key]));
		}
		
		return false;		
	}
	
	/**
	 * POST時の値調整
	 * @param array $user $_POST["customer"]想定
	 * @return array 調整後の$_POST["customer"]
	 */
	function adjustUser($user){
		/* 名前関連 */
		
		//氏名
		if(isset($user["name"])){
			$user["name"] = soyshop_trim($user["name"]);
		}
		
		//フリガナ
		if(isset($user["reading"])){
			$user["reading"] = soyshop_trim($user["reading"]);
		}
		
		return $user;
	}
	
	/**
	 * @param object SOYShop_User
	 * @return boolean
	 */
	function getIsCheckMailMagazine(SOYShop_User $user){		
		return $isCheck = ($user->getNotSend() == SOYShop_User::USER_SEND);
	}
}
?>