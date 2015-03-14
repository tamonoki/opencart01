<?php if(!class_exists('SOYShop_ItemAttribute')){ 
include_once("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_ItemAttribute.class.php"); 
} 
?><?php $updateDate = max(filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_ItemAttributeDAO.class.php"),filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_ItemAttribute.class.php"));if($updateDate  < filemtime(__FILE__)){ ?><?php
class SOYShop_ItemAttributeDAOImpl extends SOYShop_ItemAttributeDAO{
var $_entity = "O:14:\"SOY2DAO_Entity\":5:{s:4:\"name\";s:21:\"SOYShop_ItemAttribute\";s:5:\"table\";s:22:\"soyshop_item_attribute\";s:2:\"id\";b:0;s:7:\"columns\";a:4:{s:6:\"itemid\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:7:\"item_id\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"itemId\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:7:\"fieldid\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:13:\"item_field_id\";s:5:\"alias\";N;s:4:\"prop\";s:7:\"fieldId\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:5:\"value\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:10:\"item_value\";s:5:\"alias\";N;s:4:\"prop\";s:5:\"value\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:11:\"extravalues\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:17:\"item_extra_values\";s:5:\"alias\";N;s:4:\"prop\";s:11:\"extraValues\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}}s:14:\"reverseColumns\";a:4:{s:7:\"item_id\";s:6:\"itemid\";s:13:\"item_field_id\";s:7:\"fieldid\";s:10:\"item_value\";s:5:\"value\";s:17:\"item_extra_values\";s:11:\"extravalues\";}}";
function insert(SOYShop_ItemAttribute $bean){
$this->setMethod("insert");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("bean" => $bean)); }
$this->buildBinds($query,array("bean" => $bean));
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
function update(SOYShop_ItemAttribute $bean){
$this->setMethod("update");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "item_id = :itemId AND item_field_id = :fieldId";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("bean" => $bean)); }
$this->buildBinds($query,array("bean" => $bean));
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
function getByItemId($itemId){
$this->setMethod("getByItemId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("itemId" => $itemId)); }
$this->buildBinds($query,array("itemId" => $itemId));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeQuery($query,$binds);
$array = array();
if(is_array($result)){
foreach($result as $row){
$obj = $this->getObject($row);
$array[$obj->getFieldId()] = $obj;
}
}
return $array;
}
function get($itemId,$fieldId){
$this->setMethod("get");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "item_id = :itemId AND item_field_id = :fieldId";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("itemId" => $itemId,"fieldId" => $fieldId)); }
$this->buildBinds($query,array("itemId" => $itemId,"fieldId" => $fieldId));
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
function deleteByItemId($itemId){
$this->setMethod("deleteByItemId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("itemId" => $itemId)); }
$this->buildBinds($query,array("itemId" => $itemId));
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
function delete($itemId,$fieldId){
$this->setMethod("delete");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "item_id = :itemId AND item_field_id = :fieldId";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("itemId" => $itemId,"fieldId" => $fieldId)); }
$this->buildBinds($query,array("itemId" => $itemId,"fieldId" => $fieldId));
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
function deleteByFieldId($fieldId){
$this->setMethod("deleteByFieldId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("fieldId" => $fieldId)); }
$this->buildBinds($query,array("fieldId" => $fieldId));
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
}}?><?php
 } 
?>