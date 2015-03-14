<?php
SOY2::imports("module.plugins.item_review.domain.*");
class ReviewLogic extends SOY2LogicBase{

	private $errors = array();

    function update(SOYShop_ItemReview $obj){
		$dao = SOY2DAOFactory::create("SOYShop_ItemReviewDAO");
		$dao->update($obj);
    }

    function delete($ids){
    	if(!is_array($ids))$ids = array($ids);

    	$dao = SOY2DAOFactory::create("SOYShop_ItemReviewDAO");

    	$dao->begin();
    	foreach($ids as $id){
    		$dao->delete($id);
    	}
    	$dao->commit();
    }

    function create(SOYShop_ItemReview $obj){
		$dao = SOY2DAOFactory::create("SOYShop_ItemReviewDAO");

		$siteUrl = soyshop_get_site_url();

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
    function changeOpen($reviewIds, $status){
    	if(!is_array($reviewIds)) $reviewIds = array($reviewIds);
    	$status = (int)(boolean)$status;	//0 or 1

    	$dao = SOY2DAOFactory::create("SOYShop_ItemReviewDAO");
    	$dao->begin();

    	foreach($reviewIds as $id){
			$dao->updateIsApproved($id, (int)$status);
    	}
    	$dao->commit();
    }
}
?>