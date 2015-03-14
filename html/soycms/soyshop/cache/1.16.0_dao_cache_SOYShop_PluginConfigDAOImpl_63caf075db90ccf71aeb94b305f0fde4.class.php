<?php if(!class_exists('SOYShop_PluginConfig')){ 
include_once("/vagrant/html/soycms/soyshop/webapp/src/domain/plugin/SOYShop_PluginConfig.class.php"); 
} 
?><?php $updateDate = max(filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/plugin/SOYShop_PluginConfigDAO.class.php"),filemtime("/vagrant/html/soycms/soyshop/webapp/src/domain/plugin/SOYShop_PluginConfig.class.php"));if($updateDate  < filemtime(__FILE__)){ ?><?php
class SOYShop_PluginConfigDAOImpl extends SOYShop_PluginConfigDAO{
var $_entity = "O:14:\"SOY2DAO_Entity\":5:{s:4:\"name\";s:20:\"SOYShop_PluginConfig\";s:5:\"table\";s:15:\"soyshop_plugins\";s:2:\"id\";b:0;s:7:\"columns\";a:5:{s:2:\"id\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:2:\"id\";s:5:\"alias\";N;s:4:\"prop\";s:2:\"id\";s:9:\"isPrimary\";b:1;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:8:\"pluginid\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:9:\"plugin_id\";s:5:\"alias\";N;s:4:\"prop\";s:8:\"pluginId\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:4:\"type\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"plugin_type\";s:5:\"alias\";N;s:4:\"prop\";s:4:\"type\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"config\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:6:\"config\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"config\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:8:\"isactive\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:9:\"is_active\";s:5:\"alias\";N;s:4:\"prop\";s:8:\"isActive\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}}s:14:\"reverseColumns\";a:5:{s:2:\"id\";s:2:\"id\";s:9:\"plugin_id\";s:8:\"pluginid\";s:11:\"plugin_type\";s:4:\"type\";s:6:\"config\";s:6:\"config\";s:9:\"is_active\";s:8:\"isactive\";}}";

function isActiveImpl($id){
$this->setMethod("isActiveImpl");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:1:{i:0;s:9:"is_active";}'),"");
$query->where = "id = :id;";
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
if(count($result)<1)throw new SOY2DAOException("[SOY2DAO]Failed to return column.");
$row = $result[0];
return $row["is_active"];
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
$array[] = $this->getObject($row);
}
}
return $array;
}
function getActiveModules(){
$this->setMethod("getActiveModules");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "is_active = 1";
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
function getByPluginId($pluginId){
$this->setMethod("getByPluginId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("pluginId" => $pluginId)); }
$this->buildBinds($query,array("pluginId" => $pluginId));
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
function insert(SOYShop_PluginConfig $soyshopmodule){
$this->setMethod("insert");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("soyshopmodule" => $soyshopmodule)); }
$this->buildBinds($query,array("soyshopmodule" => $soyshopmodule));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeUpdateQuery($query,$binds);
return $this->lastInsertId();
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
$array[] = $this->getObject($row);
}
}
return $array;
}
function update(SOYShop_PluginConfig $soyshopmodule){
$this->setMethod("update");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("soyshopmodule" => $soyshopmodule)); }
$this->buildBinds($query,array("soyshopmodule" => $soyshopmodule));
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