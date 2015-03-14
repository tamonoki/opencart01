<?php if(!class_exists('SOYShop_Category')){ 
include_once("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_Category.class.php"); 
} 
?><?php $updateDate = max(filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_CategoryDAO.class.php"),filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/shop/SOYShop_Category.class.php"));if($updateDate  < filemtime(__FILE__)){ ?><?php
class SOYShop_CategoryDAOImpl extends SOYShop_CategoryDAO{
var $_entity = "O:14:\"SOY2DAO_Entity\":5:{s:4:\"name\";s:16:\"SOYShop_Category\";s:5:\"table\";s:16:\"soyshop_category\";s:2:\"id\";b:0;s:7:\"columns\";a:7:{s:2:\"id\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:2:\"id\";s:5:\"alias\";N;s:4:\"prop\";s:2:\"id\";s:9:\"isPrimary\";b:1;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:4:\"name\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:13:\"category_name\";s:5:\"alias\";N;s:4:\"prop\";s:4:\"name\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:5:\"alias\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:14:\"category_alias\";s:5:\"alias\";N;s:4:\"prop\";s:5:\"alias\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:5:\"order\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:14:\"category_order\";s:5:\"alias\";N;s:4:\"prop\";s:5:\"order\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"parent\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:15:\"category_parent\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"parent\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"config\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:15:\"category_config\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"config\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"isopen\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:16:\"category_is_open\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"isOpen\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}}s:14:\"reverseColumns\";a:7:{s:2:\"id\";s:2:\"id\";s:13:\"category_name\";s:4:\"name\";s:14:\"category_alias\";s:5:\"alias\";s:14:\"category_order\";s:5:\"order\";s:15:\"category_parent\";s:6:\"parent\";s:15:\"category_config\";s:6:\"config\";s:16:\"category_is_open\";s:6:\"isopen\";}}";
function insertImpl(SOYShop_Category $bean){
$this->setMethod("insertImpl");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("bean" => $bean)); }
$this->buildBinds($query,array("bean" => $bean));
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
function get(){
$this->setMethod("get");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->order = "category_order,id";
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
$query->order = "category_order,id";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("isOpen" => $isOpen)); }
$this->buildBinds($query,array("isOpen" => $isOpen));
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
function getByName($name){
$this->setMethod("getByName");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("name" => $name)); }
$this->buildBinds($query,array("name" => $name));
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
function updateImpl(SOYShop_Category $bean){
$this->setMethod("updateImpl");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("bean" => $bean)); }
$this->buildBinds($query,array("bean" => $bean));
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




function deleteImpl($id){
$this->setMethod("deleteImpl");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("id" => $id)); }
$this->buildBinds($query,array("id" => $id));
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
function deleteById($id){
$this->setMethod("deleteById");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("id" => $id)); }
$this->buildBinds($query,array("id" => $id));
return parent::deleteById($id);
}
function updateParent($id,$parent){
$this->setMethod("updateParent");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:1:{i:0;s:15:"category_parent";}'),"");
$query->where = "category_parent = :id";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("id" => $id,"parent" => $parent)); }
$this->buildBinds($query,array("id" => $id,"parent" => $parent));
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



function isAlias($alias){
$this->setMethod("isAlias");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("alias" => $alias)); }
$this->buildBinds($query,array("alias" => $alias));
return parent::isAlias($alias);
}
function getAncestry($current,$myself = "1",$ksort = "1"){
$this->setMethod("getAncestry");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("current" => $current,"myself" => $myself,"ksort" => $ksort)); }
$this->buildBinds($query,array("current" => $current,"myself" => $myself,"ksort" => $ksort));
return parent::getAncestry($current,$myself,$ksort);
}
function getRootCategories(){
$this->setMethod("getRootCategories");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array()); }
$this->buildBinds($query,array());
return parent::getRootCategories();
}}?><?php
 } 
?>