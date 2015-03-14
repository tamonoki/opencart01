<?php
/**
 * @entity SOYShop_Coupon
 */
abstract class SOYShop_CouponDAO extends SOY2DAO{
	
	/**
	 * @index id
	 * @order id desc
	 */
    abstract function get();
    
    /**
     * @return list
     */
    abstract function getByIsDelete($isDelete);
    
    /**
     * @return list
     * @query time_limit_end >= :now AND is_delete = 0
     */
    abstract function getByTimeLimitEndAndNoDelete($now);

	/**
	 * @return object
	 */
   	abstract function getById($id);
   	
	/**
	 * @return object
	 */
	abstract function getByCouponCode($couponCode);
	
	/**
	 * @return object
	 * @query coupon_code = :couponCode AND is_delete = 0
	 */
	abstract function getByCouponCodeAndNoDelete($couponCode);
   	
   	/**
	 * @return id
	 * @trigger onInsert
	 */
   	abstract function insert(SOYShop_Coupon $bean);
   	
   	/**
   	 * @return id
   	 * @trigger onUpdate
   	 */
	abstract function update(SOYShop_Coupon $bean);
	
	/**
	 * @final
	 */
	function onInsert($query, $binds){
		
		$binds[":createDate"] = time();
		$binds[":updateDate"] = time();
		
		return array($query, $binds);
	}
	
	/**
	 * @final
	 */
	function onUpdate($query, $binds){
		
		$binds[":updateDate"] = time();
		
		return array($query, $binds);
	}
}
?>