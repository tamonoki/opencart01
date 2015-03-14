<?php
SOY2::import("domain.shop.SOYShop_Item");
class SearchItemLogic extends SOY2LogicBase{

	private $query;
	private $limit;
	private $offset;
	private $order;
	private $group;
	private $having;
	private $where = array();
	private $binds = array();



	private $sorts = array(

		"category" =>  "item_category",
		"category_desc" =>  "item_category desc",

		"name" =>  "item_name",
		"name_desc" =>  "item_name desc",

		"code" =>  "item_code",
		"code_desc" =>  "item_code desc",

		"price" =>  "item_price",
		"price_desc" =>  "item_price desc",

		"stock" =>  "item_stock",
		"stock_desc" =>  "item_stock desc",

	);

	const TABLE_NAME = "soyshop_item";

	function getQuery(){
		if(is_null($this->query)){
			SOY2DAOConfig::setOption("limit_query", true);
			$this->query = SOY2DAOFactory::create("shop.SOYShop_ItemDAO");
		}

		return $this->query;
	}

	function setLimit($value){
		$this->limit = $value;
	}
	function setOffset($value){
		$this->offset = $value;
	}

	function setOrder($order){
		if(isset($this->sorts[$order])){
			$order = $this->sorts[$order];
			$order = str_replace("_desc", " desc", $order);
		}else{
			$order = "update_date desc";
		}
		$this->order = "order by " . $order;

	}

	function getSorts(){
		return $this->sorts;
	}

	function setSearchCondition($search){
		$where = array();
		$binds = array();
		foreach($search as $key => $value){

			switch($key){
				case "name":
					$where[] = "item_name LIKE :item_name";
					$binds[":item_name"] = "%" . $value . "%";
					break;
				case "code":
					$where[] = "item_code LIKE :item_code";
					$binds[":item_code"] = "%" . $value . "%";
					break;
				case "categories":
					$values = explode(" ", $value);
					$mappings = SOYShop_DataSets::get("category.mapping", array());

					$ids = array();
					foreach($values as $value){
						if(!isset($mappings[$value])) continue;
						$ids = array_merge($ids, $mappings[$value]);
					}
					$ids = array_unique($ids);
					if(count($ids) > 0){
						$where[] = "item_category in (" . implode(",", $ids) . ")";
					}
					break;
				case "attributes":
					$attributes = $value;
					foreach($attributes as $key => $value){

					}
					break;
			}
		}

		//公開条件
		$openConditions = array();
		if(isset($search["is_open"])){
			$openConditions[] = "item_is_open = 1 ";
		}
		if(isset($search["is_close"])){
			$openConditions[] = "item_is_open = 0 ";
		}
		if(isset($search["is_sale"])){
			$openConditions[] = "item_sale_flag = 1";
		}
		if(count($openConditions) > 0){
			$where[] = "(" . implode(" OR ", $openConditions) .")";
		}
				
		
		$this->where = $where;
		$this->binds = $binds;
	}

	protected function getCountSQL(){
		$countSql = "select count(*) as count from " . self::TABLE_NAME . " ";
		if(count($this->where) > 0){
			$countSql .= " where ".implode(" and ", $this->where);
		}else{
			$countSql .= " where item_type in (" . $this->getItemType() . ") ";
		}
		
		//削除フラグ
		$countSql .= "and is_disabled != 1 ";
		return $countSql;
	}

	protected function getItemsSQL(){
		$sql = "select * from " . self::TABLE_NAME . " ";
		if(count($this->where) > 0){
			$sql .= " where ".implode(" and ", $this->where);
		//一覧ページの時
		}else{
			$sql .= " where item_type in (" . $this->getItemType() . ") ";
		}
		
		//削除フラグ
		$sql .= "and is_disabled != 1 ";
		if(strlen($this->order)) $sql .= " " . $this->order;
		return $sql;
	}
	
	function getItemType(){
		$array = SOYShop_Item::getItemTypes();
		$obj = array();
		foreach($array as $value){
			$obj[] = "'" . $value."'";
		}
		return implode(",", $obj);
	}

	//合計件数取得
	function getTotalCount(){
		$countSql = $this->getCountSQL();
		try{
			$countResult = $this->getQuery()->executeQuery($countSql, $this->binds);
		}catch(Exception $e){
			return 0;
		}
		return $countResult[0]["count"];
	}

	//ユーザー取得
	function getItems(){
		$this->getQuery()->setLimit($this->limit);
		$this->getQuery()->setOffset($this->offset);
		$sql = $this->getItemsSQL();

		try{
			$result = $this->getQuery()->executeQuery($sql, $this->binds);
		}catch(Exception $e){
			$result = array();
		}

		$users = array();
		foreach($result as $raw){
			$users[] = $this->getQuery()->getObject($raw);
		}

		return $users;
	}

}
?>