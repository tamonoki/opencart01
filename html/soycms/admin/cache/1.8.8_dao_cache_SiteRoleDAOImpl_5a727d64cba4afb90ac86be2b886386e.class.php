<?php if(!class_exists('SiteRole')){ 
include_once("/vagrant/html/soycms/common/domain/admin/SiteRole.class.php"); 
} 
?><?php $updateDate = max(filemtime("/vagrant/html/soycms/common/domain/admin/SiteRoleDAO.class.php"),filemtime("/vagrant/html/soycms/common/domain/admin/SiteRole.class.php"));if($updateDate  < filemtime(__FILE__)){ ?><?php
class SiteRoleDAOImpl extends SiteRoleDAO{
var $_entity = "O:14:\"SOY2DAO_Entity\":5:{s:4:\"name\";s:8:\"SiteRole\";s:5:\"table\";s:8:\"SiteRole\";s:2:\"id\";b:0;s:7:\"columns\";a:4:{s:2:\"id\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:2:\"id\";s:5:\"alias\";N;s:4:\"prop\";s:2:\"id\";s:9:\"isPrimary\";b:1;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"userid\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:7:\"user_id\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"userId\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"siteid\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:7:\"site_id\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"siteId\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:11:\"islimituser\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:8:\"is_limit\";s:5:\"alias\";N;s:4:\"prop\";s:11:\"isLimitUser\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}}s:14:\"reverseColumns\";a:4:{s:2:\"id\";s:2:\"id\";s:7:\"user_id\";s:6:\"userid\";s:7:\"site_id\";s:6:\"siteid\";s:8:\"is_limit\";s:11:\"islimituser\";}}";
function insert(SiteRole $bean){
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
function update(SiteRole $bean){
$this->setMethod("update");
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
function get(){
$this->setMethod("get");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->order = "user_id,site_id";
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
function getSiteRole($siteId,$userId){
$this->setMethod("getSiteRole");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "site_id = :siteId and user_id = :userId";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("siteId" => $siteId,"userId" => $userId)); }
$this->buildBinds($query,array("siteId" => $siteId,"userId" => $userId));
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
function getByUserId($userId){
$this->setMethod("getByUserId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("userId" => $userId)); }
$this->buildBinds($query,array("userId" => $userId));
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
function getBySiteId($siteId){
$this->setMethod("getBySiteId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("siteId" => $siteId)); }
$this->buildBinds($query,array("siteId" => $siteId));
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
function deleteByUserId($userId){
$this->setMethod("deleteByUserId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("userId" => $userId)); }
$this->buildBinds($query,array("userId" => $userId));
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
function deleteBySiteId($siteId){
$this->setMethod("deleteBySiteId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("siteId" => $siteId)); }
$this->buildBinds($query,array("siteId" => $siteId));
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
function deleteSiteRole($userId,$siteId){
$this->setMethod("deleteSiteRole");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "site_id = :siteId and user_id = :userId";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("userId" => $userId,"siteId" => $siteId)); }
$this->buildBinds($query,array("userId" => $userId,"siteId" => $siteId));
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