<?php
SOY2::imports("module.plugins.download_assistant.domain.*");
class DownloadStatusLogic extends SOY2LogicBase{

	private $dao;
	private $customFieldDao;
	private $logic;
	private $config;

    function receivedStatus($orderId){
    	$files = $this->getDownloadFile($orderId);
    	
    	foreach($files as $file){
    		$this->updateDownloadFile($file);
    	}
    }
    
    function cancelStatus($orderId){
    	$files = $this->getDownloadFile($orderId);
    	
    	foreach($files as $file){
    		$this->cancelDownloadFile($file);
    	}
    }
    
    function getDownloadFile($orderId){
    	if(!$this->dao){
    		$this->dao = SOY2DAOFactory::create("SOYShop_DownloadDAO");
    	}
    	
    	try{
    		$files = $this->dao->getByOrderId($orderId);
    	}catch(Exception $e){
    		$files = array();
    	}
    	
    	return $files;
    }
    
    function updateDownloadFile(SOYShop_Download $file){
   		$this->config = $this->getDownloadFieldConfig($file->getItemId());
 	   	$config = $this->config;
 		
 		if(!$this->dao){
    		$this->dao = SOY2DAOFactory::create("SOYShop_DownloadDAO");
    	}
    	
    	$file->setReceivedDate(time());
    	$file->setTimeLimit($this->getLimitDate());
    	
    	try{
    		$this->dao->update($file);
    	}catch(Exception $e){
    	}
    }
    
    function cancelDownloadFile(SOYShop_Download $file){
   		$this->config = $this->getDownloadFieldConfig($file->getItemId());
 	   	$config = $this->config;
 		
 		if(!$this->dao){
    		$this->dao = SOY2DAOFactory::create("SOYShop_DownloadDAO");
    	}
 		
 		$file->setReceivedDate(null);
 		
 		try{
    		$this->dao->update($file);
    	}catch(Exception $e){
    	}
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
	
	function getChangeDate($time){
		$year = date("Y", $time);
		$month = date("m", $time);
		$day = date("d", $time) + 1;
		return mktime(0, 0, 0, $month, $day, $year);
	}
}
?>