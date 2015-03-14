<?php
/*
 */
class CommonRecommendItemInfo extends SOYShopInfoPageBase{

	function getPage($active = true){
		if($active){
			return '<a href="'.SOY2PageController::createLink("Config.Detail?plugin=common_recommend_item").'">テンプレートへの記述例</a>';
		}else{
			return "";
		}
	}

}
SOYShopPlugin::extension("soyshop.info","common_recommend_item","CommonRecommendItemInfo");
?>
