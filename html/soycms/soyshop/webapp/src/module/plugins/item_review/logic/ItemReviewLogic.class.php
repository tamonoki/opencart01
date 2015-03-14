<?php
SOY2::import("module.plugins.item_review.common.ItemReviewCommon");
SOY2::imports("module.plugins.item_review.domain.*");
class ItemReviewLogic extends SOY2LogicBase{

	private $page;
	private $userDao;
	private $reviewDao;

	/**
	 * 投稿されたレビューを登録する
	 * @param array
	 */
	function registerReview($array){
		
		if(!$this->reviewDao){
			$this->reviewDao = SOY2DAOFactory::create("SOYShop_ItemReviewDAO");
		}
		
		$review = SOY2::cast("SOYShop_ItemReview", (object)$array);
		
		$config = ItemReviewCommon::getConfig();
		
		//値を挿入する
		$review->setItemId($this->getItemId());
		$review->setUserId($this->getUserId());
		
		$isApproved = (!is_null($config["publish"])) ? 1 : 0;
		$review->setIsApproved($isApproved);
		$review->setCreateDate(time());
		$review->setUpdateDate(time());
		
		try{
			$this->reviewDao->insert($review);
		}catch(Exception $e){
			return false;
		}
		
		return true;
	}

	function getReviews(){
		$itemId = $this->getItemId();
		if(!$this->reviewDao){
			$this->reviewDao = SOY2DAOFactory::create("SOYShop_ItemReviewDAO");
		}
		
		try{
			$reviews = $this->reviewDao->getIsApprovedByItemId($itemId);
		}catch(Exception $e){
			$reviews = array();
		}
		return $reviews;
	}

	function getUser(){
		
		if(!$this->userDao){
			$this->userDao = SOY2DAOFactory::create("user.SOYShop_UserDAO");
		}
		
		try{
			$user = $this->userDao->getById($this->getUserId());
		}catch(Exception $e){
			$user = new SOYShop_User();
		}
		
		return $user;
	}
	
	function isLoggedin(){
		$attributes = $this->getAttributes();
		return (isset($attributes["userId"]));
	}
	
	function getUserId(){
		$attributes = $this->getAttributes();
		return (isset($attributes["userId"])) ? $attributes["userId"] : null;
	}
	
	function getItemId(){
		$item = $this->page->getItem();
		return $item->getId();
	}
	
	function getAttributes(){
		$mypage = MyPageLogic::getMyPage();
		return $mypage->getAttributes();
	}
    
    function getPage(){
    	return $this->page;
    }
    
    function setPage($page){
    	if(!$this->page){
    		$this->page = $page;
    	}
    }
}
?>