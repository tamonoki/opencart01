<?php
function soyshop_recommend_items($html, $htmlObj){

	$obj = $htmlObj->create("soyshop_recommend_items", "HTMLTemplatePage", array(
		"arguments" => array("soyshop_recommend_items", $html)
	));

	$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
	$array = SOYShop_DataSets::get("item.recommend_items", array());
	$items = array();
	foreach($array as $value){
		try{
			$item = $dao->getById($value);
			if($item->isPublished()){
				$items[$item->getId()] = $item;
			}
		}catch(Exception $e){

		}
	}

	$obj->createAdd("recommend_item_list", "SOYShop_ItemListComponent", array(
		"list" => $items,
		"soy2prefix" => SOYSHOP_SITE_PREFIX,//cms:idは互換性維持のため残しておく
	));
	$obj->createAdd("recommend_item_list", "SOYShop_ItemListComponent", array(
		"list" => $items,
		"soy2prefix" => "block",
	));

	//商品があるときだけ表示
	if(count($items) > 0){
		$obj->display();
	}else{
		ob_start();
		$obj->display();
		ob_end_clean();
	}
}
?>