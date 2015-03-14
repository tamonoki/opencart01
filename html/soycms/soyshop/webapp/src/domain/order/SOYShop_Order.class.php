<?php
SOY2::import("domain.user.SOYShop_User");

/**
 * @table soyshop_order
 */
class SOYShop_Order {

	//注文ステータス
	const ORDER_STATUS_INTERIM = 1;		//仮登録
	const ORDER_STATUS_REGISTERED = 2; //新規受付
	const ORDER_STATUS_RECEIVED = 3; //受付完了
	const ORDER_STATUS_SENDED = 4; //発送済み
	const ORDER_STATUS_CANCELED = 5; //キャンセル

	//支払ステータス
	const PAYMENT_STATUS_WAIT = 1; //支払待ち
	const PAYMENT_STATUS_CONFIRMED = 2;	//支払確認済み
	const PAYMENT_STATUS_ERROR = 3;	//入金エラー
	const PAYMENT_STATUS_DIRECT = 4; //直接支払
	
	//メール送信のタイプ
	const SENDMAIL_TYPE_ORDER = "order";		//注文受付メール
	const SENDMAIL_TYPE_CONFIRM = "confirm"; 	//注文確定メール
	const SENDMAIL_TYPE_PAYMENT = "payment";	//支払確認メール
	const SENDMAIL_TYPE_DELIVERY = "delivery";	//配送確認メール
	const SENDMAIL_TYPE_OTHER = "other";		//その他のメール

	/**
	 * @id
	 */
    private $id;

    /**
     * @column order_date
     */
    private $orderDate;

    /**
     * @column tracking_number
     */
   	private $trackingNumber;

    /**
     * @column price
     */
    private $price = 0;

    /**
     * @column order_status
     */
    private $status = SOYShop_Order::ORDER_STATUS_REGISTERED;

    /**
     * @column payment_status
     */
    private $paymentStatus = SOYShop_Order::PAYMENT_STATUS_WAIT;

    /**
     * @column user_id
     */
    private $userId;

    /**
     * 送付先
     */
    private $address;

    /**
     * 請求先
     * @column claimed_address
     */
    private $claimedAddress;

    /**
     * メール送信状況
     * @column mail_status
     */
    private $mailStatus;

    /**
     * @no_persistent
     */
    private $items = array();

	/**
	 * 注文に適用されたモジュール SOYShop_ItemModule
	 */
    private $modules = array();

	/**
	 * 注文に関する属性値
	 * array(
	 *     キー => array("name" => 名称, "value" => 値),
	 *     memo => array("name" => "備考", "value" => "注文時の備考"),
	 * )
	 */
    private $attributes = array();

    /**
     * 注文の状態のテキストを取得
     */
    function getOrderStatusText(){
    	$texts = $this->getOrderStatusList(true);
    	$status = $this->getStatus() + 0;
    	if(isset($texts[$status])) {
	    	return $texts[$status];
    	} else {
    		return false;
    	}
    }

    /**
     * 支払い状況のテキストを取得
     */
    function getPaymentStatusText(){
    	$texts = $this->getPaymentStatusList();
    	$status = $this->getPaymentStatus() + 0;
    	if($texts[$status]){
	    	return $texts[$status];
    	} else {
    		return false;
    	}
    }

    /**
     * テーブル名を取得
     */
    public static function getTableName(){
    	return "soyshop_order";
    }

    /**
     * 注文ステータスの配列を取得
     */
    public static function getOrderStatusList($all = false){
    	$list = array(
    			//注文ステータス
				SOYShop_Order::ORDER_STATUS_REGISTERED => "新規受付",
				SOYShop_Order::ORDER_STATUS_RECEIVED => "受付完了",
				SOYShop_Order::ORDER_STATUS_SENDED => "発送済み",
				SOYShop_Order::ORDER_STATUS_CANCELED => "キャンセル"
		);
    	if($all){
    		$list[SOYShop_Order::ORDER_STATUS_INTERIM] = "仮登録";//仮登録は管理画面からは見えない
    	}
    	return $list;
    }

    /**
     * 支払ステータスのリストを取得
     */
    public static function getPaymentStatusList(){
		return array(
				//支払ステータス
				SOYShop_Order::PAYMENT_STATUS_WAIT => "支払待ち",
				SOYShop_Order::PAYMENT_STATUS_CONFIRMED => "支払確認済み",
				SOYShop_Order::PAYMENT_STATUS_ERROR => "入金エラー",
				SOYShop_Order::PAYMENT_STATUS_DIRECT => "直接支払"
    	);
    }

    function getId() {
    	return $this->id;
    }
    function setId($id) {
    	$this->id = $id;
    }
    function getOrderDate() {
    	return $this->orderDate;
    }
    function setOrderDate($orderDate) {
    	$this->orderDate = $orderDate;
    }
    function getPrice() {
    	return $this->price;
    }
    function setPrice($price) {
    	$this->price = $price;
    }
    function getStatus() {
    	return $this->status;
    }
    function setStatus($status) {
    	$this->status = $status;
    }
    function getPaymentStatus() {
    	return $this->paymentStatus;
    }
    function setPaymentStatus($paymentStatus) {
    	$this->paymentStatus = $paymentStatus;
    }
    function getUserId() {
    	return $this->userId;
    }
    function setUserId($userId) {
    	$this->userId = $userId;
    }
    function getItems() {
    	return $this->items;
    }
    function setItems($items) {
    	$this->items = $items;
    }
    function getModules() {
    	return $this->modules;
    }
    function setModules($modules) {
    	if(is_array($modules)) $modules = soy2_serialize($modules);
    	$this->modules = $modules;
    }
    function getModuleList(){
    	$res = soy2_unserialize($this->modules);
    	return (is_array($res)) ? $res : array();
    }
    function getAddress() {
    	return $this->address;
    }
    function setAddress($address) {
    	if(is_array($address)) $address = soy2_serialize($address);
    	$this->address = $address;
    }
    function getClaimedAddress(){
    	return $this->claimedAddress;
    }
    function setClaimedAddress($claimedAddress){
    	if(is_array($claimedAddress)) $claimedAddress = soy2_serialize($claimedAddress);
    	$this->claimedAddress = $claimedAddress;
    }
    function getAddressArray(){
    	return soy2_unserialize($this->address);
    }
    function getClaimedAddressArray(){
    	return soy2_unserialize($this->claimedAddress);
    }
    function getAttributes() {
    	return $this->attributes;
    }
    function setAttributes($attributes) {
    	if(is_array($attributes)) $attributes = soy2_serialize($attributes);
    	$this->attributes = $attributes;
    }
    function getAttributeList(){
    	$res = soy2_unserialize($this->attributes);
    	return (is_array($res)) ? $res : array();
    }
    function getAttribute($key) {
    	$attributes = $this->getAttributeList();
    	if(array_key_exists($key, $attributes)){
	    	return $attributes[$key];
    	}else{
    		return null;
    	}
    }
    function setAttribute($key,$value){
    	$attributes = $this->getAttributeList();
    	$attributes[$key] = $value;
    	$this->setAttributes($attributes);
    }
    function setMailStatus($status){
    	if(is_array($status)) $status = serialize($status);
    	$this->mailStatus = $status;
    }
    function getMailStatus(){
    	return $this->mailStatus;
    }
    function getMailStatusList(){
    	if(empty($this->mailStatus)) return array();
    	$status = @soy2_unserialize($this->mailStatus);
    	return (is_array($status)) ? $status : array();
    }
    function getMailStatusByType($type){
    	$status = soy2_unserialize($this->mailStatus);
    	return (isset($status[$type])) ? $status[$type] : null;
    }
    function setMailStatusByType($type, $value){
    	$array = $this->getMailStatusList();
    	$array[$type] = $value;
    	$this->setMailStatus($array);
    }
    function getTrackingNumber() {
    	return $this->trackingNumber;
    }
    function setTrackingNumber($trackingNumber) {
    	$this->trackingNumber = $trackingNumber;
    }

    /* util */
    /**
     * マイページで表示するかどうか
     * @return boolean
     */
    function isOrderDisplay(){

		/*
		 * 仮登録以外は見せる
		 */
		switch( $this->getStatus() ){
			case self::ORDER_STATUS_REGISTERED :
			case self::ORDER_STATUS_RECEIVED :
			case self::ORDER_STATUS_SENDED :
			case self::ORDER_STATUS_CANCELED :
				$order = true;
				break;
			case self::ORDER_STATUS_INTERIM :
			default:
				$order = false;
		}

		/*
		 * 入金エラーも見せる
		 */
		switch( $this->getPaymentStatus() ){
			case self::PAYMENT_STATUS_WAIT :
			case self::PAYMENT_STATUS_CONFIRMED :
			case self::PAYMENT_STATUS_DIRECT :
			case self::PAYMENT_STATUS_ERROR :
				$payment = true;
				break;
			default:
				$payment = false;
		}

		return ( $order && $payment ) ;
    }
    
    public static function getMailTypes(){
    	return array(
    		self::SENDMAIL_TYPE_ORDER,
    		self::SENDMAIL_TYPE_CONFIRM,
    		self::SENDMAIL_TYPE_PAYMENT,
    		self::SENDMAIL_TYPE_DELIVERY,
    		self::SENDMAIL_TYPE_OTHER
    	);
    }
}
?>