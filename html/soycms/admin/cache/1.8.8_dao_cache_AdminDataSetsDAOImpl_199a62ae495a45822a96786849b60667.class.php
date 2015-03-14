<?php if(!class_exists('AdminDataSets')){ 
include_once("/vagrant/html/soycms/common/domain/admin/AdminDataSets.class.php"); 
} 
?><?php $updateDate = max(filemtime("/vagrant/html/soycms/common/domain/admin/AdminDataSetsDAO.class.php"),filemtime("/vagrant/html/soycms/common/domain/admin/AdminDataSets.class.php"));if($updateDate  < filemtime(__FILE__)){ ?><?php
class AdminDataSetsDAOImpl extends AdminDataSetsDAO{
var $_entity = "O:14:\"SOY2DAO_Entity\":5:{s:4:\"name\";s:13:\"AdminDataSets\";s:5:\"table\";s:22:\"soycms_admin_data_sets\";s:2:\"id\";b:0;s:7:\"columns\";a:3:{s:2:\"id\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:2:\"id\";s:5:\"alias\";N;s:4:\"prop\";s:2:\"id\";s:9:\"isPrimary\";b:1;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:9:\"classname\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:10:\"class_name\";s:5:\"alias\";N;s:4:\"prop\";s:9:\"className\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"object\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"object_data\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"object\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}}s:14:\"reverseColumns\";a:3:{s:2:\"id\";s:2:\"id\";s:10:\"class_name\";s:9:\"classname\";s:11:\"object_data\";s:6:\"object\";}}";
function insert(AdminDataSets $bean){
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
function getByClass($class){
$this->setMethod("getByClass");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "class_name = :class";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("class" => $class)); }
$this->buildBinds($query,array("class" => $class));
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
function clear($class){
$this->setMethod("clear");
$this->setQuery("delete from soycms_admin_data_sets where class_name = :class");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("class" => $class)); }
$this->buildBinds($query,array("class" => $class));
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
}}?><?php
 } 
?>