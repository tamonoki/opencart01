<?php

class DiscountFreeCouponConfigFormPage extends WebPage{

	private $isError = false;
	private $errors = array();
	private $config;
	private $dao;

	function DiscountFreeCouponConfigFormPage() {
		SOY2::imports("module.plugins.discount_free_coupon.domain.*");
		SOY2::imports("module.plugins.discount_free_coupon.util.*");
		SOY2::imports("module.plugins.discount_free_coupon.config.*");
		$this->dao = SOY2DAOFactory::create("SOYShop_CouponDAO");
	}

	function doPost(){
		
		if(soy2_check_token()){

			if(isset($_POST["csv"])){
				
				$logic = SOY2Logic::createInstance("module.plugins.discount_free_coupon.logic.DiscountFreeCouponCsvLogic");				
				
				if(isset($_POST["csv"]["coupon"])){
					$labels = $logic->getLabels();
					$lines = $logic->getLines();
					$charset = (isset($_POST["coupon"]["charset"])) ? $_POST["coupon"]["charset"] : "Shift-JIS";
					$fileName = "discount_free_coupon_";
				}else{
					$labels = $logic->getLogLabels();
					$lines = $logic->getLogLines();
					$charset = (isset($_POST["log"]["charset"])) ? $_POST["log"]["charset"] : "Shift-JIS";
					$fileName = "discount_free_coupon_log_";
				}
												
				if(count($lines) == 0) return;
				
				set_time_limit(0);
				
				header("Cache-Control: public");
				header("Pragma: public");
	    		header("Content-Disposition: attachment; filename=" . $fileName.date("YmdHis", time()) . ".csv");
				header("Content-Type: text/csv; charset=" . htmlspecialchars($charset).";");
					
				ob_start();
				echo implode(",", $labels);
				echo "\n";
				echo implode("\n", $lines);
				$csv = ob_get_contents();
				ob_end_clean();
				
				echo mb_convert_encoding($csv, $charset, "UTF-8");
				exit;
			}

			if(isset($_POST["Register"])){
				$register = $_POST["Register"];
				
				if($this->checkValidate($register, true)){
					
					$dao = $this->dao;
					
					$register = DiscountFreeCouponUtil::convertObject($register);
					
					$coupon = SOY2::cast("SOYShop_Coupon", (object)$register);
					
					$coupon->setIsDelete(SOYShop_Coupon::NOT_DELETED);
					try{
						$dao->insert($coupon);
						$this->config->redirect("issued");
					}catch(Exception $e){
						//
					}
				}	
			}	
			$this->isError = true;
		}
		
		//各設定内容を変更する
		if(isset($_POST["edit_save"])){
			$edit = $_POST["Edit"];
			
			//idを取得できなかった場合は処理を終了
			if(isset($edit["id"])){
				$id = $edit["id"];
				
				if($this->checkValidate($edit)){
				
					$edit = DiscountFreeCouponUtil::convertObject($edit);
					$dao = $this->dao;
					
					try{
						$coupon = $dao->getById($id);
					}catch(Exception $e){
						$this->config->redirect("error");
					}
					
					$coupon = SOY2::cast($coupon, (object)$edit);
					
					try{
						$dao->update($coupon);
						$this->config->redirect("updated");
					}catch(Exception $e){
						$this->config->redirect("error");
					}
				}
			}
		}
		
		//削除フラグ
		if(isset($_POST["remove"])){
			$edit = $_POST["Edit"];
			
			//idを取得できなかった場合は処理を終了
			if(isset($edit["id"])){
				$id = $edit["id"];
				
				$dao = $this->dao;
				
				try{
					$coupon = $dao->getById($id);
				}catch(Exception $e){
					$this->config->redirect("error");
				}
				
				//削除フラグをアクティブにする
				$coupon->setIsDelete(SOYShop_Coupon::DELETED);
				
				try{
					$dao->update($coupon);
					$this->config->redirect("deleted");
				}catch(Exception $e){
					$this->config->redirect("error");
				}
				
				$this->config->redirect("deleted");
			}
		}
		
		//設定変更
		if(isset($_POST["Config"])){
			$config = $_POST["Config"];
			
			$config["min"] = DiscountFreeCouponUtil::convertNumber($config["min"], 0);
			$config["max"] = DiscountFreeCouponUtil::convertNumber($config["max"], null);
			
			try{
				DiscountFreeCouponUtil::saveConfig($config);
				$this->config->redirect("updated");
			}catch(Exception $e){
				$this->config->redirect("error");
			}
		}
	}

	function execute(){

		WebPage::WebPage();
		
		$this->addLink("register_link", array(
			"link" => SOY2PageController::createLink("Config.Detail?plugin=discount_free_coupon#register")
		));

		$this->buildList();

		$this->addForm("form", array(
			"action" => SOY2PageController::createLink("Config.Detail?plugin=discount_free_coupon"),
			"method" => "post"
		));

		$this->buildRegisterForm();
	
		$this->addModel("issued", array(
			"visible" => (isset($_GET["issued"]))
		));
		$this->addModel("updated", array(
			"visible" => (isset($_GET["updated"]))
		));
		$this->addModel("error", array(
			"visible" => ($this->isError || isset($_GET["error"]))
		));
		$this->addModel("deleted", array(
			"visible" => (isset($_GET["deleted"]))
		));
		
		$this->buildConfigForm();
		$this->buildError();
	}
	
	function buildList(){
		
		$dao = SOY2DAOFactory::create("SOYShop_CouponDAO");
		try{
			$coupons = $dao->getByTimeLimitEndAndNoDelete(time());
		}catch(Exception $e){
			$coupons = array();
		}
		
		$this->addModel("is_coupon_list", array(
			"visible" => (count($coupons) > 0)
		));
		
		/** CSVフォーム **/
		
		$this->addForm("csv_form");
		
		$this->addInput("time_limit_start", array(
			"name"  => "Csv[timeLimitStart]",
			"value" => (isset($_POST["Register"]["timeLimitStart"])) ? $_POST["Register"]["timeLimitStart"] : ""
		));
		
		$this->addInput("time_limit_end", array(
			"name"  => "Register[timeLimitEnd]",
			"value" => (isset($_POST["Register"]["timeLimitEnd"])) ? $_POST["Register"]["timeLimitEnd"] : ""
		));
		
		/** 登録済みクーポン一覧 **/
		
		$this->addForm("edit_form");
		
		$this->createAdd("coupon_list", "CouponListComponent", array(
			"list" => $coupons,
			"dao" => SOY2DAOFactory::create("SOYShop_CouponHistoryDAO")
		));
	}

	function buildRegisterForm(){
		
		$this->addInput("name", array(
			"name"  => "Register[name]",
			"value" => (isset($_POST["Register"]["name"])) ? $_POST["Register"]["name"] : ""
		));
		$this->addInput("coupon_code", array(
			"name"  => "Register[couponCode]",
			"value" => (isset($_POST["Register"]["couponCode"])) ? $_POST["Register"]["couponCode"] : ""
		));
		$this->addCheckBox("coupon_type_price", array(
			"name" => "Register[couponType]",
			"value" => SOYShop_Coupon::TYPE_PRICE,
			"selected" => ((isset($_POST["Register"]["couponType"]) && $_POST["Register"]["couponType"] != SOYShop_Coupon::TYPE_PERCENT) || !isset($_POST["Register"]["couponType"])),
			"label" => "値引き額"
		));
		
		$this->addCheckBox("coupon_type_percent", array(
			"name" => "Register[couponType]",
			"value" => SOYShop_Coupon::TYPE_PERCENT,
			"selected" => (isset($_POST["Register"]["couponType"]) && $_POST["Register"]["couponType"] == SOYShop_Coupon::TYPE_PERCENT),
			"label" => "値引き率"
		));
		
		$this->addInput("discount", array(
			"name"  => "Register[discount]",
			"value" => (isset($_POST["Register"]["discount"]) && strlen($_POST["Register"]["discount"]) > 0) ? (int)$_POST["Register"]["discount"] : 0
		));
		$this->addInput("discout_percent", array(
			"name" => "Register[discountPercent]",
			"value" => (isset($_POST["Register"]["discountPercent"]) && strlen($_POST["Register"]["discountPercent"]) > 0) ? (int)$_POST["Register"]["discountPercent"] : 0
		));
		$this->addInput("count", array(
			"name"  => "Register[count]",
			"value" => (isset($_POST["Register"]["count"])) ? $_POST["Register"]["count"] : ""
		));
		$this->addInput("memo", array(
			"name"  => "Register[memo]",
			"value" => (isset($_POST["Register"]["memo"])) ? $_POST["Register"]["memo"] : ""
		));
		$this->addInput("price_limit_min", array(
			"name" => "Register[priceLimitMin]",
			"value" => (isset($_POST["Register"]["priceLimitMin"])) ? $_POST["Register"]["priceLimitMin"] : ""
		));
		
		$this->addInput("price_limit_max", array(
			"name" => "Register[priceLimitMax]",
			"value" => (isset($_POST["Register"]["priceLimitMax"])) ? $_POST["Register"]["priceLimitMax"] : ""
		));
		
		$this->addInput("time_limit_start", array(
			"name"  => "Register[timeLimitStart]",
			"value" => (isset($_POST["Register"]["timeLimitStart"])) ? $_POST["Register"]["timeLimitStart"] : ""
		));
		$this->addInput("time_limit_end", array(
			"name"  => "Register[timeLimitEnd]",
			"value" => (isset($_POST["Register"]["timeLimitEnd"])) ? $_POST["Register"]["timeLimitEnd"] : ""
		));
	}
	
	function buildConfigForm(){
		
		$config = DiscountFreeCouponUtil::getConfig();
		
		$this->addForm("config_form");
		
		$this->addInput("config_enable_amount_min", array(
			"name" => "Config[min]",
			"value" => (isset($config["min"])) ? $config["min"] : 0
		));
		
		$this->addInput("config_enable_amount_max", array(
			"name" => "Config[max]",
			"value" => (isset($config["max"])) ? $config["max"] : ""
		));
	}
	
	function buildError(){
		
		$this->addModel("name_error", array(
			"visible" => (isset($this->errors["name"]))
		));
		
		$this->addModel("coupon_length_error", array(
			"visible" => (isset($this->errors["coupon_length"]))
		));
		
		$this->addModel("coupon_reg_error", array(
			"visible" => (isset($this->errors["coupon_reg"]))
		));
		
		$this->addModel("count_error", array(
			"visible" => (isset($this->errors["count"]))
		));
		
		$this->addModel("discount_error", array(
			"visible" => (isset($this->errors["discount"]))
		));
		
		$this->addModel("discount_percent_error", array(
			"visible" => (isset($this->errors["discountPercent"]))
		));
		
		$this->addModel("price_limit_error", array(
			"visible" => (isset($this->errors["price_limit"]))
		));
		
		$this->addModel("price_limit_compare_error", array(
			"visible" => (isset($this->errors["price_limit_compare"]))
		));
		
		$this->addModel("time_limit_error", array(
			"visible" => (isset($this->errors["time_limit"]))
		));
		
		$this->addModel("time_limit_compare_error", array(
			"visible" => (isset($this->errors["time_limit_compare"]))
		));
	}
	
	//isCodeCheckは更新の際、クーポンコードの入力がないため、クーポンコードのチェックを省くためのフラグ
	function checkValidate($values, $isCodeCheck=false){
		
		//クーポン名が入力されていない場合
		if(strlen($values["name"]) == 0){
			$this->errors["name"] = true;
		}
		
		//更新の場合はクーポンコード周りのチェックを行わない
		if($isCodeCheck){
			//クーポンコードが4文字から16文字以内でない場合
			if(strlen($values["couponCode"]) < 4 || strlen($values["couponCode"]) > 16){
				$this->errors["coupon_length"] = true;
			}
			
			//クーポンコードが半角英数字以外の文字で入力されていない場合
			if(!preg_match("/^[a-zA-Z0-9]+$/", $values["couponCode"])){
				$this->errors["coupon_reg"] = true;
			}
		}
		
		//回数に数字以外の文字列が入力されていた時
		if(isset($values["count"]) && strlen($values["count"]) > 0){
			$count = DiscountFreeCouponUtil::convertNumber($values["count"]);
			if(!is_numeric($count)){
				$this->errors["count"] = true;
			}
		}
		
		//値引き額に数字以外の値が入力されていた場合
		if((int)$values["discount"] !== 0){
			if(!is_numeric($values["discount"])){
				$this->errors["discount"] = true;
			}
		}
		
		//値引き率に数字以外の値が入力されていた場合
		if((int)$values["discountPercent"] !== 0){
			if(!is_numeric($values["discountPercent"])){
				$this->errors["discountPercent"] = true;
			}
		}
				
		$min = 0;
		$max = 0;
				
		//利用可能金額に数字以外の文字列が入力された場合
		if(isset($values["priceLimitMin"]) && strlen($values["priceLimitMin"]) > 0){
			$min = DiscountFreeCouponUtil::convertNumber($values["priceLimitMin"], null);
			if(!preg_match("/^[0-9]+$/", $min)){
				$this->errors["price_limit"] = true;
			}
		}
		
		if(isset($values["priceLimitMax"]) && strlen($values["priceLimitMax"]) > 0){
			$max = DiscountFreeCouponUtil::convertNumber($values["priceLimitMax"], null);
			if(!preg_match("/^[0-9]+$/", $max)){
				$this->errors["price_limit"] = true;
			}
		}
		
		if($min > 0 && $max > 0){
			if($min > $max){
				$this->errors["price_limit_compare"] = true;
			}
		}
		
		
		$start = null;
		$end = null;
		
		//有効期限に数字以外の文字列が入力された場合
		if(isset($values["timeLimitStart"]) && strlen($values["timeLimitStart"]) > 0){
			$start = DiscountFreeCouponUtil::removeHyphen($values["timeLimitStart"]);
			if(!preg_match("/^[0-9]+$/", $start)){
				$this->errors["time_limit"] = true;
			}
		}
			
		if(isset($values["timeLimitEnd"]) && strlen($values["timeLimitEnd"]) > 0){
			$end = DiscountFreeCouponUtil::removeHyphen($values["timeLimitEnd"]);
			if(!preg_match("/^[0-9]+$/", $end)){
				$this->errors["time_limit"] = true;
			}
		}
		
		if(isset($start) && isset($end)){
		//開始日が終了日よりも後の場合
			if($start >= $end){
				$this->errors["time_limit_compare"] = true;
			}
		}
		
		return (count($this->errors) == 0);
	}

	function setConfigObj($obj) {
		$this->config = $obj;
	}
}
?>