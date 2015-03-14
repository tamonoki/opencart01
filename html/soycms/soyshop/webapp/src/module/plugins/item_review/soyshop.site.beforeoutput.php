<?php
SOY2::import("module.plugins.item_review.common.ItemReviewCommon");
class ItemReviewBeforeOutput extends SOYShopSiteBeforeOutputAction{

	private $reviewLogic;
	private $config;
	private $user;
	private $userDao;
	private $itemDao;

	function doPost($page){
		
		$obj = $page->getPageObject();
		
		//詳細ページ以外では読み込まない
		if(get_class($obj) != "SOYShop_Page" || $obj->getType() != SOYShop_Page::TYPE_DETAIL){
			return;
		}

		if(soy2_check_token()){
			
			$this->prepare();
			
			$review = $_POST["Review"];
			
			$this->reviewLogic->setPage($page);
			$res = $this->reviewLogic->registerReview($review);
			if($res){
				//レビューを投稿できた時に何か行う
			}
		}
	}

	function beforeOutput($page){
		
		$pageObj = $page->getPageObject();
		
		//カートページとマイページでは読み込まない
		if(get_class($pageObj) != "SOYShop_Page" || $pageObj->getType() != SOYShop_Page::TYPE_DETAIL){
			return;
		}
		
		$this->prepare();
				
		$obj = $pageObj->getObject();		
		$current = $obj->getCurrentItem();
		
		$page->addLabel("review_item_name", array(
			"soy2prefix" =>SOYSHOP_SITE_PREFIX,
			"text" => $current->getName()
		));
		
		$this->reviewLogic->setPage($page);
		
		$isLoginnedin = $this->reviewLogic->isLoggedin();
		
		//ログイン時に表示する箇所
		$page->addModel("is_logged_in", array(
			"visible" => ($isLoginnedin == true),
			"soy2prefix" => SOYSHOP_SITE_PREFIX
		));
		
		//ログアウト時に表示する箇所
		$page->addModel("no_logged_in", array(
			"visible" => ($isLoginnedin == false),
			"soy2prefix" => SOYSHOP_SITE_PREFIX
		));
		
		$reviews = $this->reviewLogic->getReviews();
		
		$page->addModel("has_reviews", array(
			"visible" => (count($reviews) > 0),
			"soy2prefix" => "block"
		));
		
		//レビューが投稿されていない場合
		$page->addModel("no_reviews", array(
			"visible" => (count($reviews) == 0),
			"soy2prefix" => "block"
		));	
		
		$page->createAdd("review_list", "ReviewsListComponent", array(
			"soy2prefix" => "block",
			"list" => $reviews,
			"item" => $current,
			"mypage" => MyPageLogic::getMyPage(),
			"config" => $this->config
		));
		
		$this->user = $this->reviewLogic->getUser();
		
		$this->buildForm($page);
	}
	
	/**
	 * レビューフォーム
	 * 投稿されたレビュー一覧
	 * ログインモード
	 */
	function buildForm($page){
		
		$user = $this->user;
				
		$page->addForm("review_form", array(
			"soy2prefix" => "block",
		));
		
		if($this->config["login"] == 1){
			$nickname = (!is_null($user->getNickname())) ? $user->getNickname() : $user->getName();
		}else{
			$nickname = "";
		}
		
		$page->addInput("nickname", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"name" => "Review[nickname]",
			"value" => $nickname
		));
		
		$page->addInput("title", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"name" => "Review[title]",
			"value" => ""
		));
		
		$page->addTextArea("content", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"name" => "Review[content]",
			"value" => ""
		));
		
		$page->addSelect("evaluation", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"name" => "Review[evaluation]",
			"options" => range(5,1)
		));
	}
	
	function getItemName($itemId){
		
		try{
			$item = $this->itemDao->getById($itemId);
		}catch(Exception $e){
			$item = new SOYShop_Item();
		}
		
		return $item->getName();
	}
	
	protected function prepare(){
		$this->reviewLogic = SOY2Logic::createInstance("module.plugins.item_review.logic.ItemReviewLogic");
		$this->config = ItemReviewCommon::getConfig();
		$this->itemDao = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
	}
}
SOYShopPlugin::extension("soyshop.site.beforeoutput", "item_review", "ItemReviewBeforeOutput");

class ReviewsListComponent extends HTMLList{
	
	private $config;
	private $item;
	private $mypage;
	
	protected function populateItem($entity){	
		
		$nickname = (strlen($entity->getNickname()) > 0) ? $entity->getNickname() : $this->config["nickname"];
		
		//プロフィール閲覧が許可されている場合はリンクを出力する
		$profileLink = $this->mypage->getProfileUserLink($entity->getUserId());
		if(isset($profileLink) && (strlen($profileLink) > 0)){
			$nickname = "<a href=\"" . $profileLink."\">" . $nickname . "</a>";
		}
		
		$this->addLabel("nickname", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"html" => $nickname
		));
		
		$this->addLabel("evaluation", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"html" => $entity->getEvaluationString()
		));
		
		$this->addLabel("title", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"text" => (strlen($entity->getTitle()) > 0) ? $entity->getTitle() : "無題"
		));
		
		$this->addLabel("content", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"html" => nl2br(htmlspecialchars($entity->getContent(), ENT_QUOTES))
		));
		
		$this->addLabel("update_date", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"text" => date("Y年m月d日", $entity->getUpdateDate())
		));
		
		$this->addLabel("item_name", array(
			"soy2prefix" => SOYSHOP_SITE_PREFIX,
			"text" => $this->item->getName()
		));
	}
	
	function setConfig($config){
		return $this->config;
	}
	
	function setItem($item){
		$this->item = $item;
	}
	
	function setMypage($mypage){
		$this->mypage = $mypage;
	}
}
?>