<?php if(!class_exists('SOYShop_Item')){ 
include_once("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_Item.class.php"); 
} 
?><?php $updateDate = max(filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_ItemDAO.class.php"),filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_Item.class.php"));if($updateDate  < filemtime(__FILE__)){ ?><?php
class SOYShop_ItemDAOImpl extends SOYShop_ItemDAO{
var $_entity = "O:14:\"SOY2DAO_Entity\":5:{s:4:\"name\";s:12:\"SOYShop_Item\";s:5:\"table\";s:12:\"soyshop_item\";s:2:\"id\";b:0;s:7:\"columns\";a:19:{s:2:\"id\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:2:\"id\";s:5:\"alias\";N;s:4:\"prop\";s:2:\"id\";s:9:\"isPrimary\";b:1;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:4:\"name\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:9:\"item_name\";s:5:\"alias\";N;s:4:\"prop\";s:4:\"name\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:5:\"alias\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:10:\"item_alias\";s:5:\"alias\";N;s:4:\"prop\";s:5:\"alias\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:4:\"code\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:9:\"item_code\";s:5:\"alias\";N;s:4:\"prop\";s:4:\"code\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:8:\"saleflag\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:14:\"item_sale_flag\";s:5:\"alias\";N;s:4:\"prop\";s:8:\"saleFlag\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:5:\"price\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:10:\"item_price\";s:5:\"alias\";N;s:4:\"prop\";s:5:\"price\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:9:\"saleprice\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:15:\"item_sale_price\";s:5:\"alias\";N;s:4:\"prop\";s:9:\"salePrice\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:12:\"sellingprice\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:18:\"item_selling_price\";s:5:\"alias\";N;s:4:\"prop\";s:12:\"sellingPrice\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:5:\"stock\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:10:\"item_stock\";s:5:\"alias\";N;s:4:\"prop\";s:5:\"stock\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"config\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"item_config\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"config\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:8:\"category\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:13:\"item_category\";s:5:\"alias\";N;s:4:\"prop\";s:8:\"category\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:4:\"type\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:9:\"item_type\";s:5:\"alias\";N;s:4:\"prop\";s:4:\"type\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:10:\"createdate\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"create_date\";s:5:\"alias\";N;s:4:\"prop\";s:10:\"createDate\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:10:\"updatedate\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"update_date\";s:5:\"alias\";N;s:4:\"prop\";s:10:\"updateDate\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:15:\"openperiodstart\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:17:\"open_period_start\";s:5:\"alias\";N;s:4:\"prop\";s:15:\"openPeriodStart\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:13:\"openperiodend\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:15:\"open_period_end\";s:5:\"alias\";N;s:4:\"prop\";s:13:\"openPeriodEnd\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:12:\"detailpageid\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:14:\"detail_page_id\";s:5:\"alias\";N;s:4:\"prop\";s:12:\"detailPageId\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"isopen\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:12:\"item_is_open\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"isOpen\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:10:\"isdisabled\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"is_disabled\";s:5:\"alias\";N;s:4:\"prop\";s:10:\"isDisabled\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}}s:14:\"reverseColumns\";a:19:{s:2:\"id\";s:2:\"id\";s:9:\"item_name\";s:4:\"name\";s:10:\"item_alias\";s:5:\"alias\";s:9:\"item_code\";s:4:\"code\";s:14:\"item_sale_flag\";s:8:\"saleflag\";s:10:\"item_price\";s:5:\"price\";s:15:\"item_sale_price\";s:9:\"saleprice\";s:18:\"item_selling_price\";s:12:\"sellingprice\";s:10:\"item_stock\";s:5:\"stock\";s:11:\"item_config\";s:6:\"config\";s:13:\"item_category\";s:8:\"category\";s:9:\"item_type\";s:4:\"type\";s:11:\"create_date\";s:10:\"createdate\";s:11:\"update_date\";s:10:\"updatedate\";s:17:\"open_period_start\";s:15:\"openperiodstart\";s:15:\"open_period_end\";s:13:\"openperiodend\";s:14:\"detail_page_id\";s:12:\"detailpageid\";s:12:\"item_is_open\";s:6:\"isopen\";s:11:\"is_disabled\";s:10:\"isdisabled\";}}";


function get(){
$this->setMethod("get");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "is_disabled != 1";
$query->order = "id desc";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array()); }
$this->buildBinds($query,array());
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$obj = $this->getObject($row);
$array[$obj->getId()] = $obj;
}
}
return $array;
}
function getByIsOpen($isOpen){
$this->setMethod("getByIsOpen");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "is_disabled != 1";
$query->order = "create_date desc";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("isOpen" => $isOpen)); }
$this->buildBinds($query,array("isOpen" => $isOpen));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function getByIsOpenOnlyParent($isOpen){
$this->setMethod("getByIsOpenOnlyParent");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "item_is_open = :isOpen AND item_type IN ('single','group','download') AND is_disabled != 1";
$query->order = "create_date desc";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("isOpen" => $isOpen)); }
$this->buildBinds($query,array("isOpen" => $isOpen));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function getDesc(){
$this->setMethod("getDesc");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->order = "id desc";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array()); }
$this->buildBinds($query,array());
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function getById($id){
$this->setMethod("getById");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("id" => $id)); }
$this->buildBinds($query,array("id" => $id));
$query = $this->getQuery();
$binds = $this->getBinds();
$oldLimit = $this->_limit;
$this->setLimit(1);
$oldOffset = $this->_offset;
$this->setOffset(0);
$result = $this->executeQuery($query,$binds);
$this->setLimit($oldLimit);
$this->setOffset($oldOffset);
if(count($result)<1)throw new SOY2DAOException("[SOY2DAO]Failed to return Object.");
$obj = $this->getObject($result[0]);
return $obj;
}
function getByCode($code){
$this->setMethod("getByCode");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("code" => $code)); }
$this->buildBinds($query,array("code" => $code));
$query = $this->getQuery();
$binds = $this->getBinds();
$oldLimit = $this->_limit;
$this->setLimit(1);
$oldOffset = $this->_offset;
$this->setOffset(0);
$result = $this->executeQuery($query,$binds);
$this->setLimit($oldLimit);
$this->setOffset($oldOffset);
if(count($result)<1)throw new SOY2DAOException("[SOY2DAO]Failed to return Object.");
$obj = $this->getObject($result[0]);
return $obj;
}
function getByName($name){
$this->setMethod("getByName");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("name" => $name)); }
$this->buildBinds($query,array("name" => $name));
$query = $this->getQuery();
$binds = $this->getBinds();
$oldLimit = $this->_limit;
$this->setLimit(1);
$oldOffset = $this->_offset;
$this->setOffset(0);
$result = $this->executeQuery($query,$binds);
$this->setLimit($oldLimit);
$this->setOffset($oldOffset);
if(count($result)<1)throw new SOY2DAOException("[SOY2DAO]Failed to return Object.");
$obj = $this->getObject($result[0]);
return $obj;
}
function getByAlias($alias){
$this->setMethod("getByAlias");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("alias" => $alias)); }
$this->buildBinds($query,array("alias" => $alias));
$query = $this->getQuery();
$binds = $this->getBinds();
$oldLimit = $this->_limit;
$this->setLimit(1);
$oldOffset = $this->_offset;
$this->setOffset(0);
$result = $this->executeQuery($query,$binds);
$this->setLimit($oldLimit);
$this->setOffset($oldOffset);
if(count($result)<1)throw new SOY2DAOException("[SOY2DAO]Failed to return Object.");
$obj = $this->getObject($result[0]);
return $obj;
}
function getByStock($stock){
$this->setMethod("getByStock");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "item_stock <= :stock";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("stock" => $stock)); }
$this->buildBinds($query,array("stock" => $stock));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function getByType($type){
$this->setMethod("getByType");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("type" => $type)); }
$this->buildBinds($query,array("type" => $type));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$obj = $this->getObject($row);
$array[$obj->getId()] = $obj;
}
}
return $array;
}
function getByTypeNoDisabled($type){
$this->setMethod("getByTypeNoDisabled");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "item_type = :type AND is_disabled = 0";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("type" => $type)); }
$this->buildBinds($query,array("type" => $type));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$obj = $this->getObject($row);
$array[$obj->getId()] = $obj;
}
}
return $array;
}
function checkAlias($alias){
$this->setMethod("checkAlias");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "item_alias = :alias";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("alias" => $alias)); }
$this->buildBinds($query,array("alias" => $alias));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function getByDetailPageId($detailPageId){
$this->setMethod("getByDetailPageId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->order = "update_date desc";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("detailPageId" => $detailPageId)); }
$this->buildBinds($query,array("detailPageId" => $detailPageId));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function getByDetailPageIdIsOpen($detailPageId){
$this->setMethod("getByDetailPageIdIsOpen");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "detail_page_id = :detailPageId AND item_is_open = 1 AND is_disabled != 1";
$query->order = "update_date desc";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("detailPageId" => $detailPageId)); }
$this->buildBinds($query,array("detailPageId" => $detailPageId));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function getByDetailPageIdIsPublished($detailPageId){
$this->setMethod("getByDetailPageIdIsPublished");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "detail_page_id = :detailPageId AND is_disabled != 1";
$query->order = "update_date desc";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("detailPageId" => $detailPageId)); }
$this->buildBinds($query,array("detailPageId" => $detailPageId));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function newItems(){
$this->setMethod("newItems");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "is_disabled != 1";
$query->order = "update_date desc";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array()); }
$this->buildBinds($query,array());
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function insert(SOYShop_Item $item){
$this->setMethod("insert");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("item" => $item)); }
$this->buildBinds($query,array("item" => $item));
$query = $this->getQuery();
$binds = $this->getBinds();
if(method_exists($this,"onInsert")){
list($query,$binds) = $this->onInsert($query,$binds);
}else{
list($query,$binds) = onInsert($query,$binds);
}
$result = $this->executeUpdateQuery($query,$binds);
return $this->lastInsertId();
}


function update(SOYShop_Item $item){
$this->setMethod("update");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("item" => $item)); }
$this->buildBinds($query,array("item" => $item));
$query = $this->getQuery();
$binds = $this->getBinds();
if(method_exists($this,"onUpdate")){
list($query,$binds) = $this->onUpdate($query,$binds);
}else{
list($query,$binds) = onUpdate($query,$binds);
}
$result = $this->executeUpdateQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function updateStock(SOYShop_Item $item){
$this->setMethod("updateStock");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("item" => $item)); }
$this->buildBinds($query,array("item" => $item));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeUpdateQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function updateIsOpen($id,$isOpen){
$this->setMethod("updateIsOpen");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:2:{i:0;s:2:"id";i:1;s:12:"item_is_open";}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("id" => $id,"isOpen" => $isOpen)); }
$this->buildBinds($query,array("id" => $id,"isOpen" => $isOpen));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeUpdateQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}
function getByCategories($categories){
$this->setMethod("getByCategories");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("categories" => $categories)); }
$this->buildBinds($query,array("categories" => $categories));
return parent::getByCategories($categories);
}

function getOpenItemByCategories($categories){
$this->setMethod("getOpenItemByCategories");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("categories" => $categories)); }
$this->buildBinds($query,array("categories" => $categories));
return parent::getOpenItemByCategories($categories);
}
function getOpenItemByMultiCategories($itemIds){
$this->setMethod("getOpenItemByMultiCategories");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("itemIds" => $itemIds)); }
$this->buildBinds($query,array("itemIds" => $itemIds));
return parent::getOpenItemByMultiCategories($itemIds);
}
function countOpenItemByCategories($categories){
$this->setMethod("countOpenItemByCategories");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:1:{i:0;s:23:"count(id) as item_count";}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("categories" => $categories)); }
$this->buildBinds($query,array("categories" => $categories));
return parent::countOpenItemByCategories($categories);
}



function updateSortValue($id,$name,$value){
$this->setMethod("updateSortValue");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:1:{i:0;s:2:"id";}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("id" => $id,"name" => $name,"value" => $value)); }
$this->buildBinds($query,array("id" => $id,"name" => $name,"value" => $value));
return parent::updateSortValue($id,$name,$value);
}
function delete($id){
$this->setMethod("delete");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("id" => $id)); }
$this->buildBinds($query,array("id" => $id));
$query = $this->getQuery();
$binds = $this->getBinds();
if(method_exists($this,"onDelete")){
list($query,$binds) = $this->onDelete($query,$binds);
}else{
list($query,$binds) = onDelete($query,$binds);
}
$result = $this->executeUpdateQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$array[] = $this->getObject($row);
}
}
return $array;
}


}?><?php
 } 
?>