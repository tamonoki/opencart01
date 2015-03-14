<?php

class PointBaseLogic extends SOY2LogicBase{
	
	private $point;
	private $config;
	private $pointDao;
	private $pointHistoryDao;
	private $userDao;
	private $itemAttributeDao;
	private $percentage;
	
	const PLUGIN_ID = "common_point_base";
		
	function PointBaseLogic(){
		SOY2::imports("module.plugins.common_point_base.domain.*");
		SOY2::imports("module.plugins.common_point_base.util.*");
		if(!$this->config) $this->config = PointBaseUtil::getConfig();
		if(!$this->pointDao) $this->pointDao = SOY2DAOFactory::create("SOYShop_PointDAO");
		if(!$this->pointHistoryDao) $this->pointHistoryDao = SOY2DAOFactory::create("SOYShop_PointHistoryDAO");
		if(!$this->itemAttributeDao) $this->itemAttributeDao = SOY2DAOFactory::create("shop.SOYShop_ItemAttributeDAO");
		
		$this->config = PointBaseUtil::getConfig();
		$this->percentage = (int)$this->config["percentage"];
	}
	
	/**
	 * 自由にポイントを投稿する場合
	 * @param int point 追加したいポイント, string history, int userId
	 */
	function insert($point, $history, $userId){
		
		$config = $this->config;
		$dao = $this->pointDao;
		
		try{
			$obj = $dao->getByUserId($userId);
		}catch(Exception $e){
			$obj = new SOYShop_Point();
		}
		
		$res = true;
		
		//すでに指定したユーザにポイントがあった場合
		$id = $obj->getUserId();
		if(isset($id)){
			$oldPoint = $obj->getPoint();
			$obj->setPoint($oldPoint + $point);
			$obj->setTimeLimit($this->getTimeLimit($config["limit"]));
			$obj->setUpdateDate(time());
				
			try{
				$dao->deleteByUserId($userId);
				$dao->insert($obj);
			}catch(Exception $e){
				$res = false;
			}
			
		//初回のポイント加算の場合
		}else{
			$obj->setUserId($userId);
			$obj->setPoint($point);
			$obj->setTimeLimit($this->getTimeLimit($config["limit"]));
			$obj->setCreateDate(time());
			$obj->setUpdateDate(time());
				
			try{
				$dao->insert($obj);
			}catch(Exception $e){
				$res = false;
			}
		}
		
		//次に履歴に挿入する
		$dao = $this->pointHistoryDao;
		
		$content = ($res) ? $history : "ポイント追加を失敗しました";
		
		$obj = new SOYShop_PointHistory();
		
		$obj->setUserId($userId);
		$obj->setPoint($point);
		$obj->setContent($content);
		$obj->setCreateDate(time());
		
		try{
			$dao->insert($obj);
		}catch(Exception $e){
			return false;
		}
		
		//一応boolean値を返しておく
		return true;
	}
	
	/**
	 * @param object SOYShop_Order, int point
	 */
	function insertPoint(SOYShop_Order $order, $point){
		
		$this->point = $point;
				
		$config = $this->config;
		
		$userId = $order->getUserId();
		
		//ポイント加算処理のフラグ
		$flag = true;
		
		if(isset($config["customer"])){
			$user = self::getUser($userId);
			$pass = $user->getPassword();
			if(!isset($pass)) $flag = false;
		}
		
		if($flag){
			
			$dao = $this->pointDao;
			
			$obj = self::getPointByUserId($userId);
			
			$res = true;
		
			//初回の購入
			if(is_null($obj->getUserId())){
				$obj->setUserId($userId);
				$obj->setPoint($point);
				$obj->setTimeLimit($this->getTimeLimit($config["limit"]));
				$obj->setCreateDate(time());
				$obj->setUpdateDate(time());
				
				try{
					$dao->insert($obj);
				}catch(Exception $e){
					$res = false;
				}
					
			//二度目以降の購入
			}else{
				$oldPoint = $obj->getPoint();
				//有効期限切れだった場合は、ポイントを0にリセットしてから加算する
				if(!is_null($obj->getTimeLimit()) && $obj->getTimeLimit() < time()){
					$oldPoint = 0;
					self::insertHistory($order->getId(), $userId, $res, false, true);
				}
				$obj->setPoint($oldPoint + $point);
				$obj->setTimeLimit($this->getTimeLimit($config["limit"]));
				$obj->setUpdateDate(time());
				
				try{
					$dao->deleteByUserId($userId);
					$dao->insert($obj);
				}catch(Exception $e){
					$res = false;
				}
			}
			self::insertHistory($order->getId(), $userId, $res);
		}
	}
	
	/**
	 * @param int point, int userId
	 */
	function updatePoint($point, $userId){
		
		$this->point = $point;
		
		$obj = self::getPointByUserId($userId);		
		$config = $this->config;
		
		$res = false;
		
		//念の為、オブジェクトの中に値があるかチェックする
		if(is_numeric($obj->getUserId())){
			
			$obj->setPoint($point);
			$obj->setUpdateDate(time());
			
			try{
				$this->pointDao->deleteByUserId($userId);
				$this->pointDao->insert($obj);
				$res = true;
			}catch(Exception $e){
			}
			
		//userIdがない場合は新規にポイント加算
		}else{
			$obj->setUserId($userId);
			$obj->setPoint($point);
			$obj->setTimeLimit($this->getTimeLimit($config["limit"]));
			$obj->setCreateDate(time());
			$obj->setUpdateDate(time());
			
			try{
				$this->pointDao->insert($obj);
				$res = true;
			}catch(Exception $e){
			}
		}
		
		if($res){
			self::insertHistory(null, $userId, true, true);
		}
	}
	
	function insertHistory($orderId, $userId, $result, $update=false, $timeLimit=false){		
		
		$config = $this->config;
				
		//要リファクタリング
		if($update){
			$content = $this->point . SOYShop_PointHistory::POINT_UPDATE;
		}else{
			$content = ($result) ? $this->point . SOYShop_PointHistory::POINT_INCREASE : SOYShop_PointHistory::POINT_FAILED;
		}
		
		//timeLimitフラグがある場合は、強制的に$contentを書き換える
		if($timeLimit){
			$content = "有効期限切れのため、ポイントをリセット";
		}
		
		$obj = new SOYShop_PointHistory();
		
		$obj->setUserId($userId);
		$obj->setOrderId($orderId);
		$obj->setPoint($this->point);
		$obj->setContent($content);
		$obj->setCreateDate(time());
		
		try{
			$this->pointHistoryDao->insert($obj);
		}catch(Exception $e){
			//
		}
	}
	
	/**
	 * @param int paymentPoint, int userId
	 */
	function paymentPoint($paymentPoint, $userId){
				
		$obj = self::getPointByUserId($userId);
				
		$res = false;
		
		//念の為、オブジェクトの中に値があるかチェックする
		if(is_numeric($obj->getUserId())){
			
			//有効期限チェック
			if($obj->getTimeLimit() > time() || is_null($obj->getTimeLimit())){
				//手持ちのポイント
				$hasPoint = $obj->getPoint();
				$newPoint = $hasPoint - $paymentPoint;
				
				$obj->setPoint($newPoint);
				$obj->setUpdateDate(time());
				
				try{
					$this->pointDao->deleteByUserId($userId);
					$this->pointDao->insert($obj);
					$res = true;
				}catch(Exception $e){
				}
			}	
		}
		
		return $res;
	}
	
	/**
	 * @param object SOYShop_Order, int paymentPoint
	 */
	function insertPaymentPointHistory(SOYShop_Order $order, $paymentPoint){
				
		$res = self::paymentPoint($paymentPoint, $order->getUserId());
		
		if($res){
						
			$content = $paymentPoint . SOYShop_PointHistory::POINT_PAYMENT;
			
			$obj = new SOYShop_PointHistory();
			$obj->setUserId($order->getUserId());
			$obj->setOrderId($order->getId());
			$obj->setPoint(-1 * $paymentPoint);	//使用した際はマイナスの値でログを取る
			$obj->setContent($content);
			$obj->setCreateDate(time());
			
			try{
				$this->pointHistoryDao->insert($obj);
			}catch(Exception $e){
				//
			}
		}
	}
	
	/**
	 * ポイント支払後に付与するポイントを取得
	 * @param CartLogic $cart, SOYShop_Order $order
	 * @return Integer $totalPoint
	 */
	function getTotalPointAfterPaymentPoint(CartLogic $cart, SOYShop_Order $order){
		$totalPoint = 0;
		$itemOrders = $cart->getItems();
		
		foreach($itemOrders as $itemOrder){
			$totalPoint += self::getPointPercentage($itemOrder->getItemId(), $itemOrder->getTotalPrice());
		}

		$paymentPoint = $cart->getAttribute("point_payment");
		if(isset($paymentPoint)){
			
			//ポイントを使用する
			self::insertPaymentPointHistory($order, $paymentPoint);
			
			//ポイントの再計算
			if($this->config["recalculation"] == 1){
				$itemTotalPrice = (int)$cart->getItemPrice();
				$itemTotalPrice = $itemTotalPrice - (int)$paymentPoint;
				if($itemTotalPrice < 0){
					$itemTotalPrice = 0;
				}
				
				//pointによる支払があった場合は、ここで商品のトータルから引いておく
				$totalPoint = $totalPoint - ceil($paymentPoint * (int)$this->config["percentage"] / 100);
				
				if($totalPoint < 0) $totalPoint = 0;
			}
		}

		return $totalPoint;
	}
	
	//商品ごとに設定したポイント付与の割合
	function getPointPercentage($itemId, $totalPrice){
		try{
			$obj = $this->itemAttributeDao->get($itemId, self::PLUGIN_ID);
		}catch(Exception $e){
			$obj = new SOYShop_ItemAttribute();
		}
		
		$percentage = (!is_null($obj->getValue())) ? (int)$obj->getValue() : $this->percentage;
		
		return floor($totalPrice * $percentage / 100);
	}
	
	function getUser($userId){
		
		if(!$this->userDao){
			$this->userDao = SOY2DAOFactory::create("user.SOYShop_UserDAO");
		}
				
		try{
			$user = $this->userDao->getById($userId);
		}catch(Exception $e){
			$user = new SOYShop_User();
		}
		
		return $user;
	}
	
	function getPointByUserId($userId){
				
		try{
			$point = $this->pointDao->getByUserId($userId);
		}catch(Exception $e){
			$point = new SOYShop_Point();
		}
		return $point;
	}
	
	function getTimeLimit($timeLimit){
		return (isset($timeLimit)) ? time() + $timeLimit * 60 * 60 * 24 : null;
	}
}
?>