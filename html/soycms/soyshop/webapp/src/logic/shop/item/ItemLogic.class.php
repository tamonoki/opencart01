<?php

class ItemLogic extends SOY2LogicBase{

	private $errors = array();

    function validate(SOYShop_Item $obj){

		$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		$errors = array();

		if(strlen($obj->getName()) < 1){
			$errors["name"] = MessageManager::get("ERROR_REQUIRE");
		}

		if(strlen($obj->getCode()) < 1){
			$errors["code"] = MessageManager::get("ERROR_REQUIRE");
		}else{
			//重複チェック
			try{
				$tmp = $dao->getByCode($obj->getCode());
				if($tmp->getId() !== $obj->getId()){
					$errors["code"] = MessageManager::get("ERROR_DUPLICATED");
				}
			}catch(Exception $e){
				//ok
			}
		}

		if(strlen($obj->getAlias()) > 0){

			//重複チェック
			$tmp = $dao->checkAlias($obj->getAlias());

			if(count($tmp) > 0){
				if(count($tmp) > 1){
					$errors["alias"] = MessageManager::get("ERROR_DUPLICATED");
				}else if($tmp[0]->getId() !== $obj->getId()){
					$errors["alias"] = MessageManager::get("ERROR_DUPLICATED");
				}
			}
		}

		$this->setErrors($errors);

		return (empty($errors));
    }

    function update(SOYShop_Item $obj, $alias = null){
		$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");

		//設定しない限りaliasはそのまま
		$obj->setAlias($alias);

		try{
			$dao->update($obj);
		}catch(Exception $e){
			var_dump($e);
		}
		
    }

    function setAttribute($id, $key, $value){
    	$dao = $this->getItemAttributeDAO();
    	$dao->delete($id,$key);

    	$obj = new SOYShop_ItemAttribute();
		$obj->setItemId($id);
		$obj->setFieldId($key);
		$obj->setValue($value);

		$dao->insert($obj);
    }

    function delete($ids){
    	if(!is_array($ids)) $ids = array($ids);

    	$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");			

    	$dao->begin();

    	foreach($ids as $id){
    		//商品がおすすめ商品登録してある場合は、おすすめ商品設定を解除
    		$recommend = SOYShop_DataSets::get("item.recommend_items", array());
			if(array_search($id, $recommend) !==false){
				$index = array_search($id, $recommend);
				unset($recommend[$index]);
				SOYShop_DataSets::put("item.recommend_items", $recommend);
			}
			
			try{
				$item = $dao->getById($id);
			}catch(Exception $e){
				continue;
			}

			//削除用のデータを作成
			$itemCode = $item->getCode();
			for($i=0; $i<=100; $i++){
    			try{
    				$checkItemCode = $dao->getByCode($itemCode . "_delete_" . $i);
    			}catch(Exception $e){
    				$deleteItemCode = $itemCode . "_delete_" . $i;
    				break;
    			}
    		}
    		
    		$itemAlias = $item->getAlias();
			for($j=0; $j<=100; $j++){
    			try{
    				$checkItemAlias = $dao->getByAlias($itemAlias . "_delete_" . $i);
    			}catch(Exception $e){
    				$deleteItemAlias = $itemAlias . "_delete_" . $i;
    				break;
    			}
    		}

			$itemName = $item->getName();
			$item->setName($itemName . "(削除)");
			$item->setCode($deleteItemCode);
			$item->setAlias($deleteItemAlias);
			$item->setIsDisabled(SOYShop_Item::IS_DISABLED);
			
			try{
				$dao->update($item);
			}catch(Exception $e){
				continue;
			}
    	}
    	$dao->commit();
    }

    function create(SOYShop_Item $obj){
		$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");

		$siteUrl = soyshop_get_site_url();
		$obj->setAttribute("image_small", $siteUrl . "themes/sample/sample1_thumb.jpg");
		$obj->setAttribute("image_large", $siteUrl . "themes/sample/sample1.jpg");


		return $dao->insert($obj);
    }

    function getErrors() {
    	return $this->errors;
    }
    function setErrors($errors) {
    	$this->errors = $errors;
    }

	/**
	 * 公開状態を変更する
	 */
    function changeOpen($itemIds, $status){
    	if(!is_array($itemIds)) $itemIds = array($itemIds);
    	$status = (int)(boolean)$status;	//0 or 1

    	$dao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
    	$dao->begin();

    	foreach($itemIds as $id){
			$dao->updateIsOpen($id, (int)$status);
    	}

    	$dao->commit();
    }

    function getItemAttributeDAO(){
    	static $dao;
    	if(!$dao){
    		$dao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
    	}
    	return $dao;
    }
    
    //マルチカテゴリモード
    function updateCategories($categories, $itemId){
    	$dao = SOY2DAOFactory::create("shop.SOYShop_CategoriesDAO");
    	try{
    		$dao->deleteByItemId($itemId);
    	}catch(Exception $e){
    		//
    	}
    	
    	foreach($categories as $categoryId){
    		$obj = new SOYShop_Categories();
    		$obj->setItemId($itemId);
    		$obj->setCategoryId($categoryId);
    		try{
    			$dao->insert($obj);
    		}catch(Exception $e){
    			//
    		}
    	}
    }
}
?>