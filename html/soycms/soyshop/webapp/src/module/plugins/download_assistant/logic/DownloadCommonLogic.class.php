<?php

class DownloadCommonLogic extends SOY2LogicBase{
	
	//登録可能な拡張子
	private $allowExtension = array(
								".zip"=>"application/zip",
								".epub"=>"application/epub+zip",
								".pdf"=>"application/pdf",
								".mp3"=>"audio/mpeg",
								".mp4"=>"application/mp4"
							);
	
	/**
	 * 登録するファイルの拡張子をチェック
	 * @param string
	 * @return boolean
	 */
	function checkFileType($file){
		
		$flag = false;
		foreach($this->allowExtension as $key => $value){
			if(preg_match('/' . $key . '$/', $file)){
				$flag = true;
				break;
			}
		}
		return $flag;
	}
	
	/**
	 * ダウンロードするファイルのcontent-typeを取得する
	 * @param string filename
	 * @return string extenstion
	 */
	function getContentType($fileName){
		$extension = strtolower(substr($fileName, strrpos($fileName, ".")));
		return (isset($this->allowExtension[$extension])) ? $this->allowExtension[$extension] : "application/octet-stream";
	}
	
	/**
	 * zipファイルのサイズを取得する
	 * 他のファイル形式でも可能
	 * @return int size
	 */
	function getFileSize($size){
		$sizes = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
		  $ext = $sizes[0];
    				
    	for ($i=1; (($i <count($sizes)) && ($size>= 1024)); $i++) {
    	   	$size = $size / 1024;
        	$ext = $sizes[$i];
    	}
    	return round($size, 2) . $ext;
	}
	
	/**
	 * ダウンロード可能な拡張子を表示する
	 */
	function allowExtension(){
		$text = array();
		foreach($this->allowExtension as $key => $value){
			$text[] = "<strong>" . $key . "</strong>";
		}
		return implode(",&nbsp;", $text);
	}
}
?>