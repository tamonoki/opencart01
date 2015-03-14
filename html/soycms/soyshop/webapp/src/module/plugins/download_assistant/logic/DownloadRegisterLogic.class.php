<?php
SOY2::imports("module.plugins.download_assistant.logic.*");
SOY2::imports("module.plugins.download_assistant.domain.*");
class DownloadRegisterLogic extends SOY2LogicBase{
	
	private $status;
	private $config;
	private $dao;
	private $customFieldDao;
	
	/**
	 * ダウンロードの購入登録前にすでに登録されていないか？をチェック
	 * @param int orderId
	 * @return boolean
	 */
	function checkRegister($orderId){
		if(!$this->dao){
			$this->dao = SOY2DAOFactory::create("SOYShop_DownloadDAO");
		}
		
		try{
			$files = $this->dao->getByOrderId($orderId);
		}catch(Exception $e){
			$files = array();
		}
		
		//$filesで配列が0で有れば処理を続ける
		if(count($files) == 0){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * ダウンロードの購入を登録
	 * @return int orderId, object SOYShop_Item, int userId, string status
	 */
	function register($orderId, SOYShop_Item $item, $userId, $status){

		//アイテムIDを取得
		$itemId = $item->getId();
		$itemCode = $item->getCode();
		
		$this->status = $status;
		$this->config = $this->getDownloadFieldConfig($item->getId());

		$files = $this->getZipFile($itemCode);
		
		if(!$this->dao){
			$this->dao = SOY2DAOFactory::create("SOYShop_DownloadDAO");
		}
		
		//ファイルの数だけ登録を開始する
		foreach($files as $file){
			//登録前にファイル名のチェック
			if(preg_match("/^[0-9A-Za-z%&+\-\^_`{|}~.]+$/", $file)){
				//登録する値を言えるための配列
				$array = $this->getDownloadArray($orderId, $itemId, $userId, $file);
				$download = SOY2::cast("SOYShop_Download",(object)$array);
				try{
					$this->dao->insert($download);
				}catch(Exception $e){
				}
			}
		}
	}
	
	/**
	 * @return array
	 */
	function getZipFile($code){
		
		$array = array();
		
		$commonLogic = new DownloadCommonLogic();
		
		$dir = SOYSHOP_SITE_DIRECTORY . "download/" . $code;
		$files = opendir($dir);
		while($file = readdir($files)){
			if($commonLogic->checkFileType($file) === true){
				$array[] = $file;
			}
		}
		return $array;
	}
	
	/**
	 * @param int orderId, int itemId, int userId, string file
	 * @return array
	 */
	function getDownloadArray($orderId, $itemId, $userId, $file){
		return array(
				"orderId" => $orderId,
				"itemId" => $itemId,
				"userId" => $userId,
				"fileName" => $file,
				"token" => md5(time().$userId.rand(0,65535)),
				"orderDate" => time(),
				"receivedDate" => $this->getReceivedDate(),
				"timeLimit" => $this->getLimitDate(),
				"count" => $this->getCount()
		);
	}
	
	function getReceivedDate(){
		return ($this->status == SOYShop_Order::PAYMENT_STATUS_CONFIRMED) ?time() : null;
	}
	
	function getLimitDate(){
		$config = $this->config;
		$timeLimit = $config["timeLimit"];
		if(!is_null($timeLimit)){
			$time = $timeLimit * 60 * 60 * 24;
			$timeLimit = time() + $time;
			return $this->getChangeDate($timeLimit);
		}else{
			return null;
		}
	}
	
	function getChangeDate($time){
		$year = date("Y", $time);
		$month = date("m", $time);
		$day = date("d", $time) + 1;
		return mktime(0, 0, 0, $month, $day, $year);
	}
	
	function getCount(){
		$config = $this->config;
		return $config["count"];
	}
	
	function getDownloadFieldConfig($itemId){
		if(!$this->customFieldDao){
			$this->customFieldDao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		}

		try{
			$array = $this->customFieldDao->getByItemId($itemId);
		}catch(Exception $e){
			echo $e->getPDOExceptionMessage();
		}
		
		return array("timeLimit" => $array["download_assistant_time"]->getValue(), "count" => $array["download_assistant_count"]->getValue());
	}
}
?>