<?php

class OrderLogic extends SOY2LogicBase{

	/**
	 * 商品情報を詳細つきで取得
	 * @return SOYShop_Order
	 */
    function getById($id){
		try{
			//Orderを取得
			$order = SOY2DAOFactory::create("order.SOYShop_OrderDAO")->getById($id);
		}catch(Exception $e){
			return new SOYShop_Order();
		}

    	//ItemOrderを取得（してないし…）
    	$items = array();
    	$order->setItems($items);

    	return $order;
    }
	
	/**
	 * 商品情報を詳細つきで取得
	 * @param integer $id
	 * @return SOYShop_Order
	 */
	function getFullOrderById($id){
		$order = $this->getById($id);
    	$items = $this->getItemsByOrderId($id);
    	$order->setItems($items);

    	return $order;
	}
	
    function getTotalPrice($orderId) {
    	try{
    		return SOY2DAOFactory::create("order.SOYShop_ItemOrderDAO")->getTotalPriceByOrderId($orderId);
    	}catch(Exception $e){
    		return null;
    	}
    }

    function getItemsByOrderId($orderId) {
    	try{
			return SOY2DAOFactory::create("order.SOYShop_ItemOrderDAO")->getByOrderId($orderId);
    	}catch(Exception $e){
    		return array();
    	}
    }
	
	/**
	 * @param integer $itemId
	 * @return integer 商品の個数
	 */
    function getOrderCountByItemId($itemId){
    	try{
			return SOY2DAOFactory::create("order.SOYShop_ItemOrderDAO")->countByItemId($itemId);
    	}catch(Exception $e){
    		return 0;
    	}
    }


	/**
	 * @param integer $orderId
	 * @return integer 商品の個数の合計
	 */
    function getTotalOrderItemCountByItemId($orderId){
    	try{
			return SOY2DAOFactory::create("order.SOYShop_ItemOrderDAO")->getTotalItemCountByOrderId($orderId);
    	}catch(Exception $e){
    		return 0;
    	}
    }
    
	/**
	 * @param integer $orderId
	 * @return integer 商品の個数
	 */
    function getItemCountById($orderId){
    	$items = $this->getItemsByOrderId($orderId);
    	return count($items);
    }

    /**
     * 変更履歴を取得する
     */
    function getOrderHistories($id){
    	try{
    		$dao = SOY2DAOFactory::create("order.SOYShop_OrderStateHistoryDAO");
    		$dao->setOrder("id asc");
    		return $dao->getByOrderId($id);
    	}catch(Exception $e){
    		return array();
    	}
    }

    /**
     * メールのステータスを設定する
     */
    function setMailStatus($id, $type, $value){
    	$orderDAO = SOY2DAOFactory::create("order.SOYShop_OrderDAO");

    	$order = $orderDAO->getById($id);
    	$order->setMailStatusByType($type, $value);

    	$orderDAO->updateMailStatus($order);
    }

    /**
     * ヒストリーに追加
     */
    function addHistory($id, $content, $more = null){
    	$historyDAO = SOY2DAOFactory::create("order.SOYShop_OrderStateHistoryDAO");
    	$history = new SOYShop_OrderStateHistory();

    	$history->setOrderId($id);
    	$history->setContent($content);
    	$history->setMore($more);

    	if(class_exists("UserInfoUtil")){
    		$history->setAuthor(UserInfoUtil::getUserName());
    	}

    	$historyDAO->insert($history);
    }

    /**
     * 問い合わせ番号を生成
     *
     */
    function getTrackingNumber(SOYShop_Order $order){

    	$orderDAO = SOY2DAOFactory::create("order.SOYShop_OrderDAO");

    	for($i = 0;;$i++){
	    	$seed = $order->getId() . $order->getOrderDate() . $i;
   	 		$hash = base_convert(md5($seed), 16, 10);
    		if($order->getId() < 100000){
    			$trackingnum = substr($hash, 2, 4) . "-" . substr($hash, 6, 4);
    		}else{
    			$trackingnum = substr($hash, 2, 4) . "-" . substr($hash, 6, 4) . "-" . substr($hash, 10, 4);
    		}

   			$trackingnum = $order->getUserId() . "-" . $trackingnum;

    		try{
	    		$tmp = $orderDAO->getByTrackingNumber($trackingnum);
    		}catch(Exception $e){
				break;
    		}
    	}

	    return $trackingnum;
    }
    
    /**
	 * 注文状態を変更する
	 */
    function changeOrderStatus($orderIds,$status){
    	if(!is_array($orderIds)) $orderIds = array($orderIds);
    	$status = (int)$status;

    	$dao = SOY2DAOFactory::create("order.SOYShop_OrderDAO");
    	$dao->begin();

    	foreach($orderIds as $id){
    		try{
    			$order = $dao->getById($id);
    		}catch(Exception $e){
    			continue;
    		}
    		
    		$order->setStatus($status);
    		$historyContent = "注文状態を<strong>「" . $order->getOrderStatusText() ."」</strong>に変更しました。";
    		try{
    			$dao->update($order);
    		}catch(Exception $e){
    			continue;
    		}
    		self::addHistory($id, $historyContent);
    	}

    	$dao->commit();
    }
    
    /**
	 * 支払状態を変更する
	 */
    function changePaymentStatus($orderIds,$status){
    	if(!is_array($orderIds)) $orderIds = array($orderIds);
    	$status = (int)$status;

    	$dao = SOY2DAOFactory::create("order.SOYShop_OrderDAO");
    	$dao->begin();

    	foreach($orderIds as $id){
    		try{
    			$order = $dao->getById($id);
    		}catch(Exception $e){
    			continue;
    		}
    		
    		$order->setPaymentStatus($status);
    		$historyContent = "支払い状態を<strong>「" . $order->getPaymentStatusText() ."」</strong>に変更しました。";
    		try{
    			$dao->update($order);
    		}catch(Exception $e){
    			continue;
    		}
    		self::addHistory($id,$historyContent);
    	}

    	$dao->commit();
    }
}
?>