<?php
SOY2::imports("module.plugins.common_consumption_tax.domain.*");
SOY2::imports("module.plugins.common_consumption_tax.config.*");
class CommonConsumptionTaxConfigFormPage extends WebPage{

	private $scheduleDao;

	function CommonConsumptionTaxConfigFormPage() {
		$this->scheduleDao = SOY2DAOFactory::create("SOYShop_ConsumptionTaxScheduleDAO");
		SOY2DAOFactory::importEntity("SOYShop_DataSets");
	}
	
	function doPost(){
		if(soy2_check_token()){
			
			if(isset($_POST["Register"])){
				$register = $_POST["Register"];
				
				$register["taxRate"] = soyshop_convert_number($register["taxRate"], 0);
				$register["startDate"] = $this->convertDate($register["startDate"]);
				
				if($register["taxRate"] > 0 && strlen($register["startDate"]) > 0){
					$schedule = SOY2::cast("SOYShop_ConsumptionTaxSchedule", $register);
					try{
						$this->scheduleDao->insert($schedule);
						SOY2PageController::jump("Config.Detail?plugin=common_consumption_tax&updated");
					}catch(Exception $e){
						//
					}
				}
			}
			
			//サーバで設定されている時間の確認
			if(isset($_POST["confirm"])){
				SOY2PageController::jump("Config.Detail?plugin=common_consumption_tax&time");
			}
		}
		
		SOY2PageController::jump("Config.Detail?plugin=common_consumption_tax&failed");
	}
	
	function execute(){
		
		//スケジュールオブジェクトの削除
		if(isset($_GET["id"]) && is_numeric($_GET["id"]) && isset($_GET["soy2_token"])){
			$this->remove();
		}
		
		WebPage::WebPage();
				
		$this->addModel("updated", array(
			"visible" => (isset($_GET["updated"]))
		));
		
		$this->addModel("failed", array(
			"visible" => (isset($_GET["failed"]))
		));
		
		$this->addModel("success", array(
			"visible" => (isset($_GET["success"]))
		));
		
		$this->addModel("delete_failed", array(
			"visible" => (isset($_GET["delete_failed"]))
		));
		
		//サーバに設定されている時刻の確認
		$this->addForm("time_confirm_form");
		
		$this->addLabel("confirm_time", array(
			"text" => (isset($_GET["time"])) ? date("Y-m-d H:i:s") : "",
			"style" => "font-size:1.4em;"
		));
		
		$schedules = $this->getSchedules();
		$this->addModel("is_schedule_list", array(
			"visible" => (count($schedules) > 0)
		));
		
		$this->addForm("form");
		
		$this->createAdd("schedule_list", "ScheduleListComponent", array(
			"list" => $schedules
		));
		
		$this->addForm("register_form");
		
		$this->addInput("tax_rate", array(
			"name" => "Register[taxRate]",
			"value" => ""
		));
		
		$this->addInput("start_date", array(
			"name" => "Register[startDate]",
			"value" => "",
			"readonly" => true
		));
	}
	
	//スケジュールオブジェクトの削除
	function remove(){
		
		if(soy2_check_token()){
			try{
				$this->scheduleDao->deleteById($_GET["id"]);
				SOY2PageController::jump("Config.Detail?plugin=common_consumption_tax&success");
			}catch(Exception $e){
				//
			}
		}
		
		SOY2PageController::jump("Config.Detail?plugin=common_consumption_tax&delete_failed");
	}
	
	function getSchedules(){
		try{
			$schedules = $this->scheduleDao->get();
		}catch(Exception $e){
			$schedules = array();
		}
		return $schedules;
	}
	
	function convertDate($date){
		$array = explode("-", $date);
		return mktime(0, 0, 0, $array[1], $array[2], $array[0]);
	}

	function setConfigObj($obj) {
		$this->config = $obj;
	}
}
?>