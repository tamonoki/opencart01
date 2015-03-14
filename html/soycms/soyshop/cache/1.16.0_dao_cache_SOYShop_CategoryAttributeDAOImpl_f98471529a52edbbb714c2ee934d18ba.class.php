<?php if(!class_exists('SOYShop_CategoryAttribute')){ 
include_once("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_CategoryAttribute.class.php"); 
} 
?><?php $updateDate = max(filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_CategoryAttributeDAO.class.php"),filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_CategoryAttribute.class.php"));if($updateDate  < filemtime(__FILE__)){ ?><?php
class SOYShop_CategoryAttributeDAOImpl extends SOYShop_CategoryAttributeDAO{
var $_entity = "O:14:\"SOY2DAO_Entity\":5:{s:4:\"name\";s:25:\"SOYShop_CategoryAttribute\";s:5:\"table\";s:26:\"soyshop_category_attribute\";s:2:\"id\";b:0;s:7:\"columns\";a:4:{s:10:\"categoryid\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"category_id\";s:5:\"alias\";N;s:4:\"prop\";s:10:\"categoryId\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:7:\"fieldid\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:17:\"category_field_id\";s:5:\"alias\";N;s:4:\"prop\";s:7:\"fieldId\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:5:\"value\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:14:\"category_value\";s:5:\"alias\";N;s:4:\"prop\";s:5:\"value\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"value2\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:15:\"category_value2\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"value2\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}}s:14:\"reverseColumns\";a:4:{s:11:\"category_id\";s:10:\"categoryid\";s:17:\"category_field_id\";s:7:\"fieldid\";s:14:\"category_value\";s:5:\"value\";s:15:\"category_value2\";s:6:\"value2\";}}";
function insert(SOYShop_CategoryAttribute $bean){
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
function update(SOYShop_CategoryAttribute $bean){
$this->setMethod("update");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "category_id = :categoryId AND category_field_id = :fieldId";
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
function getByCategoryId($categoryId){
$this->setMethod("getByCategoryId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("categoryId" => $categoryId)); }
$this->buildBinds($query,array("categoryId" => $categoryId));
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
function get($categoryId,$fieldId){
$this->setMethod("get");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "category_id = :categoryId AND category_field_id = :fieldId";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("categoryId" => $categoryId,"fieldId" => $fieldId)); }
$this->buildBinds($query,array("categoryId" => $categoryId,"fieldId" => $fieldId));
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
function deleteByCategoryId($categoryId){
$this->setMethod("deleteByCategoryId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("categoryId" => $categoryId)); }
$this->buildBinds($query,array("categoryId" => $categoryId));
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
function delete($categoryId,$fieldId){
$this->setMethod("delete");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "category_id = :categoryId AND category_field_id = :fieldId";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("categoryId" => $categoryId,"fieldId" => $fieldId)); }
$this->buildBinds($query,array("categoryId" => $categoryId,"fieldId" => $fieldId));
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