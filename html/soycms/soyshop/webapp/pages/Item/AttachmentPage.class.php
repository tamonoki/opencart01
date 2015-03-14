<?php
SOY2HTMLFactory::importWebPage("Item.DetailPage");

/**
 * @class Item.AttachmentPage
 * @date 2009-12-21T00:09:51+09:00
 * @author SOY2HTMLFactory
 */
class AttachmentPage extends DetailPage{

	function doPost(){

		if(isset($_POST["target_image"]) && soy2_check_token()){
			$url = $_POST["target_image"];
			$filepath = str_replace(soyshop_get_site_url(), SOYSHOP_SITE_DIRECTORY, $url);

			if(file_exists($filepath)){
				unlink($filepath);
			}

			SOY2PageController::jump("Item.Attachment.". $this->id . "?deleted");
		}

		if(isset($_POST["thumbnail"]) && soy2_check_token()){
			$url = $_POST["thumbnail"]["target_image"];
			$filepath = str_replace(soyshop_get_site_url(), SOYSHOP_SITE_DIRECTORY, $url);
			$width = $_POST["thumbnail"]["width"];
			$height = $_POST["thumbnail"]["height"];
			
			$savepath = dirname($filepath)."/thumb-".basename($filepath);
			
			if(file_exists($filepath)){
				copy($filepath, $savepath);
				//soy2_resize_image($filepath, $savepath,$width, $height);
			}

			SOY2PageController::jump("Item.Attachment." . $this->id . "?updated");
		}

		if(isset($_FILES["upload"])){
			$url = $this->uploadImage();

			if($url){
				SOY2PageController::jump("Item.Attachment." . $this->id . "?updated");
			}else{
				SOY2PageController::jump("Item.Attachment." . $this->id . "?failed");
			}
		}
	}

	var $id;

	function AttachmentPage($args){
		$this->id = (isset($args[0])) ? (int)$args[0] : null;

		DetailPage::DetailPage($args);

		$this->addLink("item_detail_link", array(
			"link" => SOY2PageController::createLink("Item.Detail.". $this->id)
		));

		$this->addForm("remove_form");
		
		$this->addForm("thumbnail_form");
		
	}

	/**
     * 添付ファイル取得(新しい順にする)
     */
    function getAttachments(SOYShop_Item $item){
    	$res = $item->getAttachments();

    	usort($res, array($this, "sortUrlByFilemtime"));

    	return $res;
    }

    function sortUrlByFilemtime($file1, $file2){
    	$file1 = str_replace(soyshop_get_site_url(), SOYSHOP_SITE_DIRECTORY, $file1);
    	$file2 = str_replace(soyshop_get_site_url(), SOYSHOP_SITE_DIRECTORY, $file2);

    	return (filemtime($file1) <= filemtime($file2));
    }
}
?>