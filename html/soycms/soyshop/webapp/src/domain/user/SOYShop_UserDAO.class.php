<?php
/**
 * @entity user.SOYShop_User
 */
abstract class SOYShop_UserDAO extends SOY2DAO{

	/**
	 * @final
	 */
    function insert(SOYShop_User $bean){
    	$password = $bean->getPassword();
    	if(strlen($password) > 0){
    		$bean->setPassword($bean->hashPassword($password));
    	}

    	return $this->insertImpl($bean);
    }

    /**
	 * @return id
	 * @trigger onInsert
	 */
    abstract function insertImpl(SOYShop_User $bean);

	/**
	 * @return id
	 * @trigger onUpdate
	 */
    abstract function update(SOYShop_User $bean);

    abstract function get();

    /**
     * @return object
     */
    abstract function getById($id);
    
    /**
     * @return object
     */
    abstract function getByName($name);

    abstract function delete(SOYShop_User $bean);

	/**
	 * @return object
	 */
	abstract function getByMailAddress($mailAddress);

    /**
     * 互換性のために残しますが、使用禁止
     * @return object
     * @query mail_address = :email
     */
    abstract function getIdByEmail($email);
    
    /**
     * @return id
     */
    abstract function getIdByMailAddress($mailAddress);
    
    /**
     * @return list
     * @query is_disabled = 0
     */
    abstract function getByNotDisabled();
    
    /**
	 * @return object
	 */
	abstract function getByAccountId($accountId);
	
	/**
	 * @return object
	 * @query account_id = :accountId AND id != :id
	 */
	abstract function getByAccountIdAndNotId($accountId, $id);
		
	/**
	 * @return object
	 */
	abstract function getByProfileId($profileId);

    /**
     * @return object
     * @query mail_address = :email
     * @query user_type = 10
     */
    abstract function getTmpUserByEmail($email);

    /**
     * @return object
     * @query mail_address = :email
     * @query user_type = 1
     */
    abstract function getRegisterUserByEmail($email);
    
    /**
     * @return list
	 * @query register_date > :startDate AND register_date <= :endDate
	 * @order id ASC
     */
    abstract function getByBetweenRegisterDate($startDate, $endDate = 2147483647);
    
    /**
     * @return list
	 * @query update_date > :startDate AND update_date <= :endDate
	 * @order id ASC
     */
    abstract function getByBetweenUpdateDate($startDate, $endDate = 2147483647);

	/**
	 * @return column_count_user
	 * @columns count(id) as count_user
	 * @query is_disabled != 1
	 */
	abstract function countUser();

	/**
	 * @final
	 */
	function onInsert($query, $binds){
		if((int)$binds[":area"] === 0){
			$binds[":area"] = null;
		}
		
		if(strlen($binds[":accountId"]) === 0){
			$binds[":accountId"] = null;
		}
		
		if(strlen($binds[":profileId"]) === 0){
			$binds[":profileId"] = null;
		}
		
    	$binds[":registerDate"] = time();
    	$binds[":updateDate"] = time();
    	return array($query, $binds);
	}

	/**
	 * @final
	 */
	function onUpdate($query, $binds){
		if((int)$binds[":area"] === 0){
			$binds[":area"] = null;
		}
		
		if(strlen($binds[":accountId"]) === 0){
			$binds[":accountId"] = null;
		}
		
		if(strlen($binds[":profileId"]) === 0){
			$binds[":profileId"] = null;
		}
		
    	$binds[":updateDate"] = time();
    	return array($query, $binds);
	}
}
?>
