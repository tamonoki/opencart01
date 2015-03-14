<?php
SOY2::import("module.plugins.download_assistant.common.DownloadAssistantCommon");
class DownloadAssistantCustomField extends SOYShopItemCustomFieldBase{
	
	private $dao;

	function doPost(SOYShop_Item $item){
		
		$dir = SOYSHOP_SITE_DIRECTORY . "download/" . $item->getCode();
		if(!is_dir($dir)){
			mkdir($dir);
		}
		
		//削除
		if(isset($_POST["download_assistant_delete"])){
			$files = $_POST["download_assistant_delete"];
			foreach($files as $file){
				$deleteFile = $dir."/" . $file;
				unlink($deleteFile);
			}
		}
		
		$commonLogic = SOY2Logic::createInstance("module.plugins.download_assistant.logic.DownloadCommonLogic");
		
		//拡張子のチェック。許可してある拡張子はcheckExtension内に記載
		if(isset($_FILES["file"]) && strlen($_FILES["file"]["type"]) > 0 && $commonLogic->checkFileType($_FILES["file"]["name"]) === true){
			$fname = $_FILES["file"]["name"];
				
			//半角英数字かチェックする
			if (preg_match("/^[0-9A-Za-z%&+\-\^_`{|}~.]+$/", $fname)){
				$dest_name = $dir . "/" . $fname;
				
	
				//iconsディレクトリの中にすでにファイルがないかチェックする				
				if(!file_exists($dest_name)){
					//ファイルの移動が失敗していないかどうかをチェック
					if(@move_uploaded_file($_FILES["file"]["tmp_name"], $dest_name) === false){
						
					}
				}				
			}
		}
		
		$this->dao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		$array = $this->dao->getByItemId($item->getId());
		
		if(isset($_POST["download_assistant_time"])){
		
			//ダウンロード期限の値を設定する
			$time = mb_convert_kana($_POST["download_assistant_time"], "a");
			$time = (strlen($time) > 0 && is_numeric($time))? (int)$time : null;
			$key = "download_assistant_time";
				
			try{
				if(isset($array[$key])){
					$obj = $array[$key];
					$obj->setValue($time);
					$this->dao->update($obj);
				}else{
					$obj = new SOYShop_ItemAttribute();
					$obj->setItemId($item->getId());
					$obj->setFieldId($key);
					$obj->setValue($time);
	
					$this->dao->insert($obj);
				}
			}catch(Exception $e){
			}
			
			//ダウンロード回数を設定する
			$count = mb_convert_kana($_POST["download_assistant_count"], "a");
			$count = (strlen($count) > 0 && is_numeric($count)) ? (int)$count : null;
			$key = "download_assistant_count";
			
			try{
				if(isset($array[$key])){
					$obj = $array[$key];
					$obj->setValue($count);
					$this->dao->update($obj);
				}else{
					$obj = new SOYShop_ItemAttribute();
					$obj->setItemId($item->getId());
					$obj->setFieldId($key);
					$obj->setValue($count);
					$this->dao->insert($obj);
				}
			}catch(Exception $e){
				//
			}
		}
		
	}

	function getForm(SOYShop_Item $item){
		
		//商品タイプがダウンロードの時のみ表示
		if($item->getType() == SOYShop_Item::TYPE_DOWNLOAD){
			
			$commonLogic = SOY2Logic::createInstance("module.plugins.download_assistant.logic.DownloadCommonLogic");
			
			$dao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
			try{
				$array = $dao->getByItemId($item->getId());
			}catch(Exception $e){
				echo $e->getPDOExceptionMessage();
				$array = array();
			}
			
			if(isset($array["download_assistant_time"])){
				$time = $array["download_assistant_time"]->getValue();
				$count = $array["download_assistant_count"]->getValue();
			}else{
				$config = DownloadAssistantCommon::getConfig();
				$time = (isset($config["timeLimit"])) ? $config["timeLimit"] : null;
				$count = (isset($config["count"]))? $config["count"] : null;
			}
			
			$dir = SOYSHOP_SITE_DIRECTORY . "download/" . $item->getCode() . "/";
	
			$style = "style=\"text-align:right;ime-mode:inactive;\" size=\"4\"";
			
			$html = array();
			
			$html[] = "<h1>ダウンロード販売用設定</h1>";
			$html[] = "<dt><label for=\"download_field\">ダウンロード販売商品登録&nbsp;(半角英数字)</label><br />";
			$html[] = "<span style=\"font-size:0.9em;\">※登録可能なファイルの拡張子：</span>&nbsp;" . $commonLogic->allowExtension() . "</dt>";
			$html[] = "<dd>";
			$html[] = "<input type=\"file\" name=\"file\" id=\"file\" />";
			$html[] = "<p style=\"font-size:0.9em;padding:5px 0;\">※ファイルを直接サーバに配置することも可能です</p>";
			$html[] = "<p>ファイルの配置ディレクトリ&nbsp;:&nbsp;<strong>" . $dir."</strong></p>";
			$html[] = "<br />";
			
			//削除ボタン用のフラグ
			$deleteFlag = false;
			
			//ダウンロード用のファイルがあるか確認する
			$files = opendir($dir);
			while($file = readdir($files)){
				if($commonLogic->checkFileType($file) === true && preg_match("/^[0-9A-Za-z%&+\-\^_`{|}~.]+$/", $file)){
					if($deleteFlag == false){
						$html[] = "<h3>登録されているファイル</h3>";
					}
					$html[] = "<input type=\"checkbox\" name=\"download_assistant_delete[]\" value=\"" . $file . "\" id=\"download_assistant_" . $file."\" />";
					$html[] = "<label for=\"download_assistant_" . $file . "\">" . $file . "&nbsp;" . $commonLogic->getFileSize(filesize($dir . $file)) . "</label>";
					$html[] = "<br />";
					if($deleteFlag == false){
						$deleteFlag = true;
					}
				}
			}
			if($deleteFlag){
				$html[] = "<p style=\"font-size:0.9em;padding:5px 0;\">※チェックしたファイルは商品情報更新時に削除されます</p>";
			}
			
			$html[] = "</dd>";
			
			$html[] = "<dt><label for=\"download_field\">ダウンロード期間日数</label></dt>";
			$html[] = "<dd>";
			$html[] = "<input type=\"text\" name=\"download_assistant_time\" value=\"" . $time."\" " . $style." />&nbsp;日";
			$html[] = "<p>※値がない場合は無期限</p>";
			$html[] = "</dd>";
		
			$html[] = "<dt><label for=\"download_field\">ダウンロード回数</label></dt>";
			$html[] = "<dd>";
			$html[] = "<input type=\"text\" name=\"download_assistant_count\" value=\"" . $count."\" " . $style." />&nbsp;回";
			$html[] = "<p>※値がない場合は無制限</p>";
			$html[] = "</dd>";
		
			return implode("\n", $html);
		}
	}

	/**
	 * onOutput
	 */
	function onOutput($htmlObj, SOYShop_Item $item){
	}

	function onDelete($id){
		$attributeDAO = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		$attributeDAO->deleteByItemId($id);
	}
}

SOYShopPlugin::extension("soyshop.item.customfield","download_assinstant","DownloadAssistantCustomField");
?>