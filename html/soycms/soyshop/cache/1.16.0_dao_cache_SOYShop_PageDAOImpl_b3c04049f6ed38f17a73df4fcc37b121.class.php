<?php if(!class_exists('SOYShop_Page')){ 
include_once("/vagrant/html/soycms/soyshop/webapp/src/domain/site/SOYShop_Page.class.php"); 
} 
?><?php $updateDate = max(filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/site/SOYShop_PageDAO.class.php"),filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/site/SOYShop_Page.class.php"));if($updateDate  < filemtime(__FILE__)){ ?><?php
class SOYShop_PageDAOImpl extends SOYShop_PageDAO{
var $_entity = "O:14:\"SOY2DAO_Entity\":5:{s:4:\"name\";s:12:\"SOYShop_Page\";s:5:\"table\";s:12:\"soyshop_page\";s:2:\"id\";b:0;s:7:\"columns\";a:8:{s:2:\"id\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:2:\"id\";s:5:\"alias\";N;s:4:\"prop\";s:2:\"id\";s:9:\"isPrimary\";b:1;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:4:\"name\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:4:\"name\";s:5:\"alias\";N;s:4:\"prop\";s:4:\"name\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:3:\"uri\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:3:\"uri\";s:5:\"alias\";N;s:4:\"prop\";s:3:\"uri\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:4:\"type\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:4:\"type\";s:5:\"alias\";N;s:4:\"prop\";s:4:\"type\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:8:\"template\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:8:\"template\";s:5:\"alias\";N;s:4:\"prop\";s:8:\"template\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"config\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:6:\"config\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"config\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:10:\"createdate\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"create_date\";s:5:\"alias\";N;s:4:\"prop\";s:10:\"createDate\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:10:\"updatedate\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"update_date\";s:5:\"alias\";N;s:4:\"prop\";s:10:\"updateDate\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}}s:14:\"reverseColumns\";a:8:{s:2:\"id\";s:2:\"id\";s:4:\"name\";s:4:\"name\";s:3:\"uri\";s:3:\"uri\";s:4:\"type\";s:4:\"type\";s:8:\"template\";s:8:\"template\";s:6:\"config\";s:6:\"config\";s:11:\"create_date\";s:10:\"createdate\";s:11:\"update_date\";s:10:\"updatedate\";}}";
function insert(SOYShop_Page $bean){
$this->setMethod("insert");
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
function getByUri($uri){
$this->setMethod("getByUri");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("uri" => $uri)); }
$this->buildBinds($query,array("uri" => $uri));
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
function newPages(){
$this->setMethod("newPages");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
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
function update(SOYShop_Page $bean){
$this->setMethod("update");
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
function updateConfig(SOYShop_Page $bean){
$this->setMethod("updateConfig");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:2:{i:0;s:2:"id";i:1;s:6:"config";}'),"");
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
function delete($id){
$this->setMethod("delete");
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



function checkUri($uri){
$this->setMethod("checkUri");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "uri = :uri";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("uri" => $uri)); }
$this->buildBinds($query,array("uri" => $uri));
return parent::checkUri($uri);
}}?><?php
 } 
?>