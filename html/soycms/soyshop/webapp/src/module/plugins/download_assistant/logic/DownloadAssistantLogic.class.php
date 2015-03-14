<?php
SOY2::imports("module.plugins.download_assistant.logic.*");
SOY2::imports("module.plugins.download_assistant.domain.*");
class DownloadAssistantLogic extends SOY2LogicBase{
	
	private $dao;
	private $itemDao;
	
	/**
	 * order_idとitem_idからfileリストを取得する
	 * @param int orderId, int itemId
	 * @return object SOYShop_Download
	 */
	function getDownloadFiles($orderId, $itemId){
		if(!$this->dao){
			$this->dao = SOY2DAOFactory::create("SOYShop_DownloadDAO");
		}
		
		try{
			$files = $this->dao->getByOrderIdAndItemId($orderId, $itemId);
		}catch(Exception $e){
			$files = array();
		}
		
		return $files;
	}
	
	/**
	 * 表示用のダウンロードパスを生成する
	 * @param object SOYShop_Download
	 * @return string url
	 */
	function getDownloadFilePath(SOYShop_Download $file){
		return	$this->getMypagePath() . $file->getToken();
	}
	
	function getMypagePath(){
		return SOYSHOP_SITE_URL.soyshop_get_mypage_uri() . "?soyshop_download=download_assistant&token=";
	}
	
	function getItemIds($orderId){
		if(!$this->dao){
			$this->dao = SOY2DAOFactory::create("SOYShop_DownloadDAO");
		}
		
		try{
			$ids = $this->dao->getItemIdByOrderId($orderId);
		}catch(Exception $e){
			$ids = array();
		}
		
		return $ids;
	}
	
	/**
	 * ファイルが配置されているディレクトリのパスを生成する
	 * @param object SOYShop_Download
	 * @return string path
	 */
	function getFileDirectoryPath($file){
		if(!$this->itemDao){
			$this->itemDao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		}
		
		try{
			$item = $this->itemDao->getById($file->getItemId());
		}catch(Exception $e){
			return null;
		}
		
		return SOYSHOP_SITE_DIRECTORY . "download/" . $item->getCode() . "/" . $file->getFileName();
	}
	
	/**
	 * ファイルをダウンロードする
	 * @param object SOYShop_Download
	 */
	function downloadFile(SOYShop_Download $file){
		if(!$this->dao){
			$this->dao = SOY2DAOFactory::create("SOYShop_DownloadDAO");
		}
		
		$filePath = $this->getFileDirectoryPath($file);
		if($filePath){
			$rest = $file->getCount();
			
			//ダウンロード回数の再度チェック
			if(is_null($rest) || $rest > 0){
				
				//残りダウンロード回数から1引いて、DBに再度インサート
				$rest = (!is_null($rest)) ? $rest - 1 : null;
				$file->setCount($rest);
				
				//zipファイルをダウンロードする
				try{
					$this->outputFile($file, $filePath);
					$this->dao->update($file);
				}catch(Exception $e){
					//
				}
			}
		}
		
		return false;
	}
	
	/**
	 * ファイルをダウンロードする
	 * @param object SOYShop_Download file, string filepath
	 */
	function outputFile(SOYShop_Download $file, $filePath){
		//ダウンロード前にファイル名のチェック
		if(preg_match("/^[0-9A-Za-z%&+\-\^_`{|}~.]+$/", $file->getFileName())){
			$commonLogic = new DownloadCommonLogic();
			$contentType = $commonLogic->getContentType($file->getFileName());
			
			header("Cache-Control: public");
			header("Pragma: public");
			header("Content-Type: " . $contentType . ";");
	    	header("Content-Disposition: attachment; filename=" . basename($file->getFileName()));
	    	header("Content-Length: " . filesize($filePath));
	
			flush();
			while(ob_get_level()){
				ob_end_clean();
			}
	
			$handle = fopen($filePath, 'rb');
			while ( $handle !== false && !feof($handle) && ! connection_aborted() ){
				echo fread($handle, 4096);
				flush();
			}
			fclose($handle);
		}
	}
	
	//itemIdからitemを取得する
	function getItem($itemId){
		if(!$this->itemDao){
			$this->itemDao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		}
		
		try{
			$item = $this->itemDao->getById($itemId);
		}catch(Exception $e){
			$item = new SOYShop_Item();
		}
			
		return $item;
	}
	
	/**
	 * tokenからfileを取得する
	 * @param string token
	 * @return object SOYShop_Download
	 */
	function getFileByToken($token){
		if(!$this->dao){
			$this->dao = SOY2DAOFactory::create("SOYShop_DownloadDAO");
		}
		
		try{
			$file = $this->dao->getByToken($token);
		}catch(Exception $e){
			$file = null;
		}
		
		return $file;
	}
}
?>