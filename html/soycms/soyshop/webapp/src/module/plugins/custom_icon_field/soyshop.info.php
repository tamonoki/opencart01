<?php
/*
 */
class CUstomIconFieldInfo extends SOYShopInfoPageBase{

	function getPage($active = false){
		if($active){
			return '<a href="'.SOY2PageController::createLink("Config.Detail?plugin=custom_icon_field").'">カスタムアイコンフィールドの設定</a>';
		}else{
			return "";
		}
	}

}
SOYShopPlugin::extension("soyshop.info","custom_icon_field","CustomIconFieldInfo");
?>