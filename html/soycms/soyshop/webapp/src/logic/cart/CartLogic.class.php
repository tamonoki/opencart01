<?php
SOY2::import("domain.order.SOYShop_ItemOrder");
SOY2::import("domain.order.SOYShop_ItemModule");
SOY2::import("domain.user.SOYShop_User");
SOY2::import("domain.config.SOYShop_ShopConfig");

/**
 * カート全般
 *
 * セッションを使ってカートを保存
 */
class CartLogic extends SOY2LogicBase{

	/**
	 * カートを取得
	 */
	public static function getCart($cartId = null){

		if(!$cartId)$cartId = SOYSHOP_CURRENT_CART_ID;
		$userSession = SOY2ActionSession::getUserSession();
		$cart = $userSession->getAttribute("soyshop_" . SOYSHOP_ID . $cartId);
		if(is_string($cart) && strlen($cart)){
			$cart = soy2_unserialize($cart);
		}

		return (!is_null($cart)) ? $cart : new CartLogic($cartId);
	}

	/**
	 * カートを保存
	 */
	public static function saveCart(CartLogic $cart){
		$userSession = SOY2ActionSession::getUserSession();
		$userSession->setAttribute("soyshop_" . SOYSHOP_ID . $cart->getId(), soy2_serialize($cart));
	}
	function save(){
		CartLogic::saveCart($this);
	}

	/**
	 * カートを削除
	 */
	public static function clearCart($cartId = null){
		if(!$cartId)$cartId = soyshop_get_cart_id();
		$userSession = SOY2ActionSession::getUserSession();
		$userSession->setAttribute("soyshop_" . SOYSHOP_ID . $cartId, null);
	}
	function clear(){
		CartLogic::clearCart($this->getId());
	}

	/**
	 * construct
	 */
	function CartLogic($cartId = null){
		$this->id = $cartId;
	}

	protected $id;
	protected $items = array();	//商品情報
	protected $customerInformation;
	protected $order;
	protected $modules = array();
	protected $attributes = array();
	protected $orderAttributes = array();

	protected $errorMessage = array();
	protected $noticeMessage = array();

	/**
	 * カートに商品を追加
	 */
	function addItem($itemId, $count = 1){

		try{
			$itemDAO = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
			$item = $itemDAO->getById($itemId);

			//追加不可能
			if(false == $item->isOrderable()){
				throw new Exception("Can not orderable");
			}

			//個数は0以上の整数
			$count = max(0, (int)$count);

			//在庫以上は入らない
			//$count = min($item->getOpenStock(),$count);

			//商品オプションの値がポストされている場合
			if(isset($_POST["item_option"]) && is_array($_POST["item_option"]) && count($_POST["item_option"])){
				$cart = $this->getCart();

				//商品オプションの配列を比較する
				$result = null;

				//すでにカートの中に商品が入っていないかチェック
				$res = false;
				foreach($this->items as $key => $obj){
					if($itemId==$obj->getItemId()){
						$res = true;
						break;
					}
				}

				//商品があればオプションも同じかどうかを調べる
				if($res){
					$delegate = SOYShopPlugin::invoke("soyshop.item.option", array(
						"mode" => "compare",
						"cart" => $cart,
						"option" => $_POST["item_option"]
					));

					//すでにある商品と配列が一致したらtrueを返す
					$result = $delegate->getCartOrderId();
					if(isset($this->items[$result]) && $this->items[$result]->getItemId() == $itemId){
						//
					}else{
						$result = null;
					}
				}

				//配列が一致しなかった場合は新しい商品として追加
				if(is_null($result)){
					$this->items[] = $this->setItemOrder($item, $count);
					return true;
				}else{
					$this->updateItem($result, $count + $this->items[$result]->getItemCount());
					return false;
				}

			//商品オプションの値がポストされていない場合
			}else{
				$index = null;
				foreach($this->items as $key => $obj){
					if($obj->getItemId() == $item->getId()){
						$index = $key;
						break;
					}
				}

				if(isset($index)){
					$this->updateItem($index, $count + $this->items[$index]->getItemCount());
				}else{
					//1個以上ならカートに入れる
					if($count > 0){
						$this->items[] = $this->setItemOrder($item, $count);
					}
			   	}

			   	//商品オプションがないから必ずfalseを返す
			   	return false;
			}
		}catch(Exception $e){
			return false;
		}
	}

	function setItemOrder(SOYShop_Item $item, $count){
		SOYShopPlugin::load("soyshop.cart.set.itemorder");
		$delegate = SOYShopPlugin::invoke("soyshop.cart.set.itemorder", array(
			"item" => $item,
			"count" => $count
		));
		
		$obj = $delegate->getObject();
		if(!is_null($obj) && $obj instanceof SOYShop_ItemOrder){
			return $obj;
		}

		$obj = new SOYShop_ItemOrder();
		$obj->setItemId($item->getId());
		$obj->setItemCount($count);
		$obj->setItemPrice($item->getSellingPrice());
		$obj->setTotalPrice($item->getSellingPrice() * $count);
		$obj->setItemName($item->getName());

		return $obj;
	}

	/**
	 * カートから商品を削除
	 */
	function removeItem($index){
		if(isset($this->items[$index])){
			$this->items[$index] = null;
			unset($this->items[$index]);
		}
	}

	/**
	 * カートでアイテム数の個数を更新
	 */
	function updateItem($index, $count){
		if($count > 0){
			if(isset($this->items[$index])){
				$item = $this->items[$index];
				$item->setItemCount($count);
				$item->setTotalPrice($item->getItemPrice() * $count);
			}
		}else{
			$this->removeItem($index);
		}
	}

	/**
	 * 商品合計金額を取得
	 * @return number
	 */
	function getItemPrice(){
		$total = 0;
		foreach($this->items as $item){
			$total += $item->getTotalPrice();
		}

		return $total;
	}
	
	/**
	 * @return integer 商品数を取得
	 */
	function getItemCount(){
		return count($this->items);
	}
	
	/**
	 * @return integer 商品の個数の合計
	 */
	function getOrderItemCount(){
		$total = 0;
		foreach($this->items as $item){
			$total += $item->getItemCount();
		}
		
		return $total;
	}
	
	/**
	 * モジュール追加
	 * 同じタイプのモジュールは１つしか追加できない
	 */
	function addModule(SOYShop_ItemModule $module){
		$id = $module->getId();

		//同一タイプは削除する
		if(strlen($module->getType()) > 0){
			foreach($this->modules as $key => $value){
				if($value->getType() == $module->getType()){
					$this->removeModule($key);
				}
			}
		}

		$this->modules[$id] = $module;
	}

	/**
	 * モジュール削除
	 */
	function removeModule($moduleId){
		if(isset($this->modules[$moduleId])){
			$this->modules[$moduleId] = null;
			unset($this->modules[$moduleId]);
		}

		//子モジュールを削除
		foreach($this->modules as $id => $module){
			if(preg_match("/^$moduleId\..+/", $id)){
				unset($this->modules[$id]);
			}
		}

		//関連する設定値をクリア
		$this->clearOrderAttribute($moduleId);
	}

	/**
	 * モジュール取得
	 */
	function getModule($moduleId){
		return (isset($this->modules[$moduleId])) ? $this->modules[$moduleId] : null;
	}
	
	/**
	 * 消費税をセットする
	 * typeがtaxのプラグインで処理を行う
	 */
	function setConsumptionTax(){
		$config = SOYShop_ShopConfig::load();
		$pluginId = $config->getConsumptionTaxModule();
		
		if(!isset($pluginId)) return false;

   		$pluginDao = SOY2DAOFactory::create("plugin.SOYShop_PluginConfigDAO");
			
   		try{
   			$plugin = $pluginDao->getByPluginId($pluginId);
   		}catch(Exception $e){
   			return false;
   		}
			
   		if($plugin->getIsActive() == SOYShop_PluginConfig::PLUGIN_INACTIVE) return false;
   		
   		SOYShopPlugin::load("soyshop.tax.calculation", $plugin);
		SOYShopPlugin::invoke("soyshop.tax.calculation", array(
			"mode" => "post",
			"cart" => $this
		));
	}
	
	/**
	 * 消費税の内税をセットする。内税表示モード
	 */
	function setConsumptionTaxInclusivePricing(){
		$items = $this->getItems();
		if(count($items) === 0) return;
				
		$totalPrice = 0;
		
		foreach($items as $item){
			$totalPrice += $item->getTotalPrice();
		}
		
		if($totalPrice === 0) return;
		
		$config = SOYShop_ShopConfig::load();
		$taxRate = (int)$config->getConsumptionTaxInclusivePricingRate();	//内税率
		
		if($taxRate === 0) return;
		
		$module = new SOYShop_ItemModule();
		$module->setId("consumption_tax");
		$module->setName("内税");
		$module->setType(SOYShop_ItemModule::TYPE_TAX);	//typeを指定しておくといいことがある
		$module->setPrice(floor($totalPrice * $taxRate / 100));
		$module->setIsInclude(true);	//合計に合算されない
		$this->addModule($module);
	}
	
	//taxモジュールが登録されているか？をチェックする
	function checkTaxModule(){
		$modules = $this->getModules();
		
		if(count($modules) === 0) return false;
		
		$res = false;
		foreach($modules as $module){
			if($module->getType() == SOYShop_ItemModule::TYPE_TAX){
				$res = true;
				break;
			}
		}
		
		return $res;
	}

	/**
	 * 総合計金額を取得
	 * @return number
	 */
	function getTotalPrice(){
		$total = $this->getItemPrice();

		foreach($this->modules as $module){

			//明細に記載されるのみモジュールに追加
			if($module->isInclude()) continue;

			$total += $module->getPrice();
		}

		return $total;
	}

	/**
	 * カートに入れている商品が更新されていないかチェック
	 *
	 * @return boolean
	 */
	function checkUpdated(){

	}

	function getAttributes() {
		return $this->attributes;
	}
	function setAttributes($attributes) {
		$this->attributes = $attributes;
	}

	function setAttribute($id, $value){
		$this->attributes[$id] = $value;
		$this->save();
	}
	function getAttribute($id){
		return (isset($this->attributes[$id])) ? $this->attributes[$id] : null;
	}
	function clearAttribute($id){
		$this->attributes[$id] = null;
		unset($this->attributes[$id]);
	}

	function getOrderAttributes() {
		return $this->orderAttributes;
	}
	function setOrderAttributes($orderAttributes) {
		$this->orderAttributes = $orderAttributes;
	}

	function setOrderAttribute($id, $name, $value, $hidden = false){
		if(!is_array($this->orderAttributes)) $this->orderAttributes = array();
		$this->orderAttributes[$id] = array(
			"name" => $name,
			"value" => $value,
			"hidden" => $hidden
		);
		$this->save();
	}
	function getOrderAttribute($id){
		return (isset($this->orderAttributes[$id])) ? $this->orderAttributes[$id] : null;
	}
	function clearOrderAttribute($id){
		if(isset($this->orderAttributes[$id])){
			$this->orderAttributes[$id] = null;
			unset($this->orderAttributes[$id]);
		}

		//関連のattributeを削除
		foreach($this->orderAttributes as $key => $attr){
			if(strpos($key, $id.".") === 0){
				unset($this->orderAttributes[$key]);
			}
		}
	}

	function getId() {
		return $this->id;
	}
	function setId($id) {
		$this->id = $id;
	}
	function getItems() {
		return $this->items;
	}
	function setItems($items) {
		$this->items = $items;
	}
	function getOrder(){
		return $this->order;
	}
	function setOrder($order){
		$this->order = $order;
	}
	function getCustomerInformation() {
		if(is_null($this->customerInformation)){
			$this->customerInformation = new SOYShop_User();
	   	}
		return $this->customerInformation;
	}
	function setCustomerInformation($customerInformation) {
		$this->customerInformation = $customerInformation;
	}

	/**
	 * 商品送付先を取得
	 */
	function getAddress(){
		$key = $this->getAttribute("address_key");
		if(is_null($key)) $key = -1;
		return $this->customerInformation->getAddress($key);
	}

	function getClaimedAddress(SOYShop_User $user){
		//$user = $this->customerInformation;
		return array(
			"name" => $user->getName(),
			"reading" => $user->getReading(),
			"zipCode" => $user->getZipCode(),
			"area" => $user->getArea(),
			"address1" => $user->getAddress1(),
			"address2" => $user->getAddress2(),
			"telephoneNumber" => $user->getTelephoneNumber(),
			"office" => $user->getJobName(),
		);
	}

	/**
	 * 宛先が指定されているかどうか
	 * @return boolean
	 */
	function isUseCutomerAddress(){
		$key = $this->getAttribute("address_key");
		if(is_null($key)) $key = -1;
		return ($key >= 0);
	}


	function getModules() {
		return $this->modules;
	}
	function setModules($modules) {
		$this->modules = $modules;
	}
	
	/**
	 * @param all boolean 
	 * trueの場合はすべてのモジュールをクリアする
	 * falseの場合はisVisibleがtrueのもののみクリアする
	 */
	function clearModules($all = false){
		foreach($this->modules as $moduleId => $module){
			if($all === false && $module->getIsVisible() === false) continue;
			$this->removeModule($moduleId);
		}
	}

	/*
	 * 以下注文を実行したりなんだり。
	 */

	/**
	 * 注文実行
	 */
	function order(){
		//注文可能かチェック
		$this->checkOrderable();
		
		//ユーザーエージェント
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			$this->setOrderAttribute("order_check_carrier", "ユーザーエージェント", $_SERVER['HTTP_USER_AGENT'], true);
		}

		//顧客情報の登録
		$this->registerCustomerInformation();

		//注文情報の登録
		$this->orderItems();

		//記録
		$orderLogic = SOY2Logic::createInstance("logic.order.OrderLogic");

		$orderLogic->addHistory($this->getAttribute("order_id"), "注文を受け付けました");
	}

	/**
	 * 注文完了（メール送信あり）
	 * @return boolean
	 */
	function orderComplete(){
		return $this->_orderComplete(true);
	}

	/**
	 * 注文完了（メール送信なし）
	 * @return boolean
	 */
	function orderCompleteWithoutMail(){
		return $this->_orderComplete(false);
	}

	/**
	 * 注文完了
	 *
	 * ・フラグを仮登録->登録に変更
	 * ・メールを送信
	 *
	 * @param boolean $sendMail メール送信可否
	 * @return boolean
	 */
	function _orderComplete($sendMail = true){

		$orderLogic = SOY2Logic::createInstance("logic.order.OrderLogic");

		try{
			$orderDAO = SOY2DAOFactory::create("order.SOYShop_OrderDAO");
			$order = $orderDAO->getById($this->getAttribute("order_id"));

			//既に完了していた場合はtrueを返す
			if($order->getStatus() == SOYShop_Order::ORDER_STATUS_REGISTERED){
				return true;
			}else{
				$order->setStatus(SOYShop_Order::ORDER_STATUS_REGISTERED);
				$orderDAO->updateStatus($order);
			}
		}catch(Exception $e){
			$orderLogic->addHistory($this->getAttribute("order_id"), "注文を完了することができませんでした。メールは送信されません。");
			return false;
		}

		//準備
		$this->order = $order;

		//注文確定時に関するプラグインを実行する
		SOYShopPlugin::load("soyshop.order.complete");
		SOYShopPlugin::invoke("soyshop.order.complete", array(
			"order" => $this->order
		));


		//メールの送信
		if($sendMail){
			try{
				$this->sendMail();
			}catch(Exception $e){
				//メール送信に失敗した場合
				$orderLogic->addHistory($this->getAttribute("order_id"), "注文受付メールの送信に失敗しました。");
			}
		}else{
			$orderLogic->addHistory($this->getAttribute("order_id"), "注文受付メールの送信はスキップされました。");
		}

		return true;
	}

	/**
	 * 支払確認
	 *
	 * ・支払い状況を支払待ちから支払確認済みに変更
	 * ・メールを送信
	 *
	 * @param $orderId
	 * @return boolean
	 */
	function orderPaymentConfirm(){

		$orderLogic = SOY2Logic::createInstance("logic.order.OrderLogic");

		try{
			$orderDAO = SOY2DAOFactory::create("order.SOYShop_OrderDAO");
			$order = $orderDAO->getById($this->getAttribute("order_id"));

			//既に完了していた場合はtrueを返す
			if($order->getPaymentStatus() == SOYShop_Order::PAYMENT_STATUS_CONFIRMED){
				return true;
			}else{
				$order->setPaymentStatus(SOYShop_Order::PAYMENT_STATUS_CONFIRMED);

				//仮登録時に支払確認状態になった場合、注文状態を新規受付に変更する
				if($order->getStatus() == SOYShop_Order::ORDER_STATUS_INTERIM){
					$order->setStatus(SOYShop_Order::ORDER_STATUS_REGISTERED);
					$orderLogic->addHistory($this->getAttribute("order_id"), "支払を確認したので、注文状態を仮登録から新規受付に変更しました。");
				}

				$orderDAO->updateStatus($order);
				$orderLogic->addHistory($this->getAttribute("order_id"), "支払いを確認しました。");
			}
		}catch(Exception $e){
			$orderLogic->addHistory($this->getAttribute("order_id"), "支払いを確認できませんでした。メールは送信されません。");
			return false;
		}

		//準備
		$this->order = $order;

		//支払確認済みに関するプラグインを実行する
		SOYShopPlugin::load("soyshop.order.status.update");
		SOYShopPlugin::invoke("soyshop.order.status.update", array(
			"order" => $this->order,
			"mode" => "status"
		));

		try{
			//完了したらメールの送信
			$this->sendMail("payment");

			$orderLogic->setMailStatus($this->getAttribute("order_id"), "payment", time());
		}catch(Exception $e){
			//メール送信に失敗した場合
			$orderLogic->addHistory($this->getAttribute("order_id"), "支払い確認メールの送信に失敗しました。");
		}

		return true;
	}

	/**
	 * 注文可能かチェック
	 */
	function checkOrderable(){
		$itemDAO = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		$config = SOYShop_ShopConfig::load();
		$ignoreStock = $config->getIgnoreStock();

		//transaction start
		$itemDAO->begin();

		$items = $this->getItems();

		foreach($items as $itemOrder){
			$itemId = $itemOrder->getItemId();

			$item = $itemDAO->getById($itemId);

			//非公開
			if(false == $item->isPublished()){
				throw new SOYShop_EmptyStockException("");
			}

			//在庫無視モード
			if($ignoreStock)continue;

			//在庫0
			if($item->getOpenStock() < 1){
				throw new SOYShop_EmptyStockException("");
			}

			$openStock = $item->getOpenStock();
			$itemCount = $itemOrder->getItemCount();

			//子商品の在庫管理設定をオン(子商品購入時に親商品の在庫数で購入できるか判断する)
			$childItemStock = $config->getChildItemStock();
			if($childItemStock && is_numeric($item->getType())){
				//親商品の残り在庫数を取得
				$parent = $this->getParentOpenStock($item->getType());
				$openStock = $parent->getStock();

				//子商品の注文数の合計を取得
				$itemCount = $this->getChildItemOrders($parent->getId());
			}

			//在庫オーバー
			if($openStock < $itemCount){
				throw new SOYShop_OverStockException("");
			}
		}
	}
	
	function checkItemCountInCart(){
		$items = $this->getItems();
		
		//カートに商品が入っていない
		if(count($items) === 0){
			throw new SOYShop_EmptyCartException("");
		}
	}

	//親商品の在庫数
	function getParentOpenStock($itemId){
		$itemDAO = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		try{
			$parent = $itemDAO->getById($itemId);
		}catch(Exception $e){
			$parent = new SOYShop_Item();
		}

		return $parent;
	}

	//子商品の注文数の合計
	function getChildItemOrders($itemId){
		$cart = CartLogic::getCart();

		$itemCount = 0;

		$items = $cart->getItems();
		if(count($items) > 0){
			$dao = new SOY2DAO();
			$sql = "select id from soyshop_item where item_type = " . $itemId;
			try{
				$result = $dao->executeQuery($sql);
			}catch(Exception $e){
			}
			$ids = array();
			foreach($result as $value){
				$ids[] = $value["id"];
			}

			foreach($items as $item){
				if(in_array($item->getItemId(), $ids)){
					$itemCount = $itemCount + $item->getItemCount();
				}
			}
		}

		return $itemCount;
	}

	/**
	 * 顧客情報の登録
	 *
	 * ・最初の画面でユーザ名とパスワード入力→ID取得
	 * ・新規登録(パスワード入力)→ID取得
	 * ・新規登録(パスワード未入力、初回)→登録
	 * 		宛先情報などは登録しない
	 * ・新規登録(パスワード未入力、2回目)→ID取得
	 */
	function registerCustomerInformation(){
		$user = $this->getCustomerInformation();
		$userDAO = SOY2DAOFactory::create("user.SOYShop_UserDAO");

		//登録済みユーザーかどうか
		try{
			$tmpUser = $userDAO->getByMailAddress($user->getMailAddress());
		}catch(Exception $e){
			$tmpUser = null;
		}


		//本登録カラムに値を今の時間を入れる
		$user->setRealRegisterDate(time());
		$user->setUserType(SOYShop_User::USERTYPE_REGISTER);

		//二回目以降のユーザ
		if($tmpUser instanceof SOYShop_User){

			if( $this->getAttribute("logined") ){
				$id = $this->getAttribute("logined_userid");
				$newPassword = $this->getAttribute("new_password");

				$user->setId($id);

				if( strlen($newPassword) ){
					//もし新しいパスワードが入力されていたらパスワードを上書きする
					$user->setPassword($user->hashPassword($newPassword));
				}else{
					//それ以外はパスワードを残す
					$tmpUser = $userDAO->getById($id);
					$user->setPassword($tmpUser->getPassword());
				}
				
				//update
				$userDAO->update($user);
			
			//ログインしていないのでゲスト注文となる
			}else{
				$id = $tmpUser->getId();
				
				//既に登録されているメールアドレスは会員で、今回はゲスト注文
				if(strlen($tmpUser->getPassword()) > 0){
					//顧客情報を更新せず
				
				//ゲスト注文二回目	
				}else{
					$user->setId($id);
	
					$user = clone($user);
					$user->setAddressList($tmpUser->getAddressList());
	
					//旧ユーザのパスワードが空なら登録する
					if(strlen($tmpUser->getPassword()) < 1 && strlen($user->getPassword()) > 0){
						$user->setPassword($user->hashPassword($user->getPassword()));
					}else{
						$user->setPassword($tmpUser->getPassword());
					}
					
					//update
					$userDAO->update($user);
				}
			}	

		//初回ユーザ
		}else{
			//パスワード未設定の時は宛先情報を保持しない
			if(strlen($user->getPassword()) < 1){
				$user = clone($user);
				$user->setAddressList(serialize(null));
			}

			//insert: パスワードのハッシュ化はonInsertで行う
			$id = $userDAO->insert($user);
		}

		$this->customerInformation->setId($id);
	}

	/**
	 * 商品情報の登録
	 */
	function orderItems(){

		$itemDAO = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		$orderDAO = SOY2DAOFactory::create("order.SOYShop_OrderDAO");

		$order = new SOYShop_Order();
		$order->setOrderDate(time());
		$order->setPrice($this->getTotalPrice());
		$order->setUserId($this->customerInformation->getId());
		$order->setItems($this->getItems());
		$order->setModules($this->getModules());
		$order->setAttributes($this->getOrderAttributes());

		//注文のStatusは仮登録
		$order->setStatus(SOYShop_Order::ORDER_STATUS_INTERIM);

		//送信先
		$address = $this->getAddress();
		$order->setAddress(serialize($address));

		$claimedAddress = $this->getClaimedAddress($this->customerInformation);
		$order->setClaimedAddress($claimedAddress);

		$id = $orderDAO->insert($order);
		$order->setId($id);
		$this->setAttribute("order_id", $id);

		$itemOrderDAO = SOY2DAOFactory::create("order.SOYShop_ItemOrderDAO");
		$items = $this->getItems();

		foreach($items as $key => $item){
			$config = SOYShop_ShopConfig::load();

			//子商品の在庫管理設定をオン(子商品購入時に在庫を減らさない)
			$noChildItemStock = $config->getNoChildItemStock();
			if(!$noChildItemStock){
				$itemDAO->orderItem($item->getItemId(), $item->getItemCount());
			}

			//子商品の在庫管理設定をオン(子商品購入時に親商品の在庫も減らす)
			$childItemStock = $config->getChildItemStock();
			if($childItemStock){
				try{
					//SOYShop_Itemオブジェクトの値を入れた変数
					$itemObj = $itemDAO->getById($item->getItemId());
					if(is_numeric($itemObj->getType())){
						try{
							$parent = $itemDAO->getById($itemObj->getType());
							$itemDAO->orderItem($parent->getId(), $item->getItemCount());
						}catch(Exceptoin $e){
						}
					}
				}catch(Exception $e){
				}
			}

			$item->setOrderId($id);

			SOYShopPlugin::load("soyshop.item.option");

			//商品オプションがある場合は、attributeに値を挿入
			$delegate = SOYShopPlugin::invoke("soyshop.item.option", array(
				"mode" => "order",
				"index" => $key
			));
			$item->setAttributes($delegate->getAttributes());

			//加算オプションがある場合は、is_additionに値を挿入
			$delegate = SOYShopPlugin::invoke("soyshop.item.option", array(
				"mode" => "addition",
				"index" => $key
			));
			$item->setIsAddition($delegate->getAddition());

			$itemOrderDAO->insert($item);
		}

		$orderLogic = SOY2Logic::createInstance("logic.order.OrderLogic");
		$trackingNumber = $orderLogic->getTrackingNumber($order);
		$order->setTrackingNumber($trackingNumber);
		$orderDAO->update($order);


		//begin
		$orderDAO->commit();

		$this->order = $order;

	}

	/**
	 * メールの送信
	 */
	function sendMail($type="order"){

		$logic = SOY2Logic::createInstance("logic.mail.MailLogic");
		$builder = SOY2Logic::createInstance("logic.mail.MailBuilder");
		$user = $this->getCustomerInformation();
		$orderLogic = SOY2Logic::createInstance("logic.order.OrderLogic");

		/**
		 * ユーザー宛のメール
		 *
		 * ヘッダー（管理画面＞注文受付メール設定）
		 * 注文内容
		 * プラグイン（soyshop.order.mail.user）
		 * フッター（管理画面＞注文受付メール設定）
		 */
		//DBから設定を取得：ヘッダー、フッター
		$userMailConfig = $logic->getUserMailConfig($type);

		if(isset($userMailConfig["active"]) && $userMailConfig["active"]){
			//メール本文（注文内容）を取得
			$body = $builder->buildOrderMailBodyForUser($this->order, $user);

			SOYShopPlugin::load("soyshop.order.mail");

			//プラグインを実行してメール本文の取得
			$appned_body = SOYShopPlugin::invoke("soyshop.order.mail.user", array(
				"order" => $this->order,
				"mail" => $userMailConfig
			))->getBody();

			$mailBody =
				$userMailConfig["header"] ."\n".
				$body . "\n" .
				$appned_body .
				$userMailConfig["footer"];

			//置換文字列
			$title = $logic->convertMailContent($userMailConfig["title"], $user, $this->order);
			$mailBody = $logic->convertMailContent($mailBody, $user, $this->order);

			//宛名
			$userName = $this->getCustomerInformation()->getName();
			if(strlen($userName) > 0) $userName .= " 様";

			//送信
			$logic->sendMail($this->getCustomerInformation()->getMailAddress(), $title, $mailBody, $userName, $this->order);

			//メール送信フラグ
			$orderLogic->setMailStatus($this->getAttribute("order_id"), "order", time());

			//ログ
			$orderLogic->addHistory($this->getAttribute("order_id"), "注文者宛の注文受付メールを送信しました。");
		}else{
			//ログ
			$orderLogic->addHistory($this->getAttribute("order_id"), "設定により注文者宛の注文受付メールは送信されません。");
		}

		/**
		 * 管理者（メール設定の「送信元」）宛のメール
		 *
		 * ヘッダー（管理画面＞管理者メール設定）
		 * 注文内容
		 * 注文者情報
		 * プラグイン（soyshop.order.mail.admin）
		 * フッター（管理画面＞管理者メール設定）
		 */
		//DBから設定を取得：ヘッダー、フッター
		$adminMailConfig = $logic->getAdminMailConfig($type);

		if(isset($adminMailConfig["active"]) && $adminMailConfig["active"]){
			//ユーザ情報
			$body = $builder->buildOrderMailBodyForAdmin($this->order, $user);

			//プラグインを実行してメール本文の取得
			$appned_body = SOYShopPlugin::invoke("soyshop.order.mail.admin", array(
				"order" => $this->order,
				"mail" => $adminMailConfig
			))->getBody();

			$mailBody =
				$adminMailConfig["header"] . "\n" .
				$body . "\n" .
				$appned_body .
				$adminMailConfig["footer"];

			//置換文字列
			$title = $logic->convertMailContent($adminMailConfig["title"], $user, $this->order);
			$mailBody = $logic->convertMailContent($mailBody, $user, $this->order);

			//送信
			//@TODO 複数管理者へのメール送信
			$serverConfig = SOYShop_ServerConfig::load();
			$adminMailAddress = $serverConfig->getAdministratorMailAddress();
			$adminName = $serverConfig->getAdministratorName();
			$logic->sendMail($adminMailAddress, $title, $mailBody, $adminName, $this->order, true);

			//ログ
			$orderLogic->addHistory($this->getAttribute("order_id"), "管理者宛の注文受付メールを送信しました。");
		}else{
			//ログ
			$orderLogic->addHistory($this->getAttribute("order_id"), "設定により管理者宛の注文受付メールは送信されません。");
		}
	}

	/**
	 * エラーメッセージ
	 */
	function addErrorMessage($id, $str){
		$this->errorMessage[$id] = $str;
	}

	/**
	 * エラーメッセージのクリア
	 */
	function removeErrorMessage($id){
		if(isset($this->errorMessage[$id])){
			unset($this->errorMessage[$id]);
		}
	}

	/**
	 * 取得
	 */
	function getErrorMessage($id){
		return (isset($this->errorMessage[$id])) ? $this->errorMessage[$id] : null;
	}

	/**
	 * チェック
	 * @return boolean
	 */
	function hasError($id = null){
		if(isset($id) && strlen($id) > 0){
			return isset($this->errorMessage[$id]) && (strlen($this->errorMessage[$id]) > 0);
		}else{
			return (count($this->errorMessage) > 0);
		}
	}

	/**
	 * 全て
	 */
	function getErrorMessages(){
		return $this->errorMessage;
	}

	/**
	 *
	 */
	function clearErrorMessage(){
		$this->errorMessage = array();
	}
	
	/**
	 * 通知メッセージ
	 */
	function addNoticeMessage($id, $str){
		$this->noticeMessage[$id] = $str;
	}

	/**
	 * 通知のクリア
	 */
	function removeNoticeMessage($id){
		if(isset($this->noticeMessage[$id])){
			unset($this->noticeMessage[$id]);
		}
	}
	
	/**
	 * 取得
	 */
	function getNoticeMessage($id){
		return (isset($this->noticeMessage[$id])) ? $this->noticeMessage[$id] : null;
	}

	/**
	 * 全て
	 */
	function getNoticeMessages(){
		return $this->noticeMessage;
	}
	
	/**
	 *
	 */
	function clearNoticeMessage(){
		$this->noticeMessage = array();
	}
}

/* 在庫切れ */
class SOYShop_StockException extends Exception{}
class SOYShop_EmptyStockException extends SOYShop_StockException{}
class SOYShop_OverStockException extends SOYShop_StockException{}

class SOYShop_CartException extends Exception{}
class SOYShop_EmptyCartException extends SOYShop_CartException{}
?>