<?php if(!class_exists('Site')){ 
include_once("/vagrant/html/soycms/common/domain/admin/Site.class.php"); 
} 
?><?php $updateDate = max(filemtime("/vagrant/html/soycms/common/domain/admin/SiteDAO.class.php"),filemtime("/vagrant/html/soycms/common/domain/admin/Site.class.php"));if($updateDate  < filemtime(__FILE__)){ ?><?php
class SiteDAOImpl extends SiteDAO{
var $_entity = "O:14:\"SOY2DAO_Entity\":5:{s:4:\"name\";s:4:\"Site\";s:5:\"table\";s:4:\"Site\";s:2:\"id\";b:0;s:7:\"columns\";a:8:{s:2:\"id\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:2:\"id\";s:5:\"alias\";N;s:4:\"prop\";s:2:\"id\";s:9:\"isPrimary\";b:1;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"siteid\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:7:\"site_id\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"siteId\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:8:\"sitename\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:9:\"site_name\";s:5:\"alias\";N;s:4:\"prop\";s:8:\"siteName\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:8:\"sitetype\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:9:\"site_type\";s:5:\"alias\";N;s:4:\"prop\";s:8:\"siteType\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:3:\"url\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:3:\"url\";s:5:\"alias\";N;s:4:\"prop\";s:3:\"url\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:4:\"path\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:4:\"path\";s:5:\"alias\";N;s:4:\"prop\";s:4:\"path\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:14:\"datasourcename\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:16:\"data_source_name\";s:5:\"alias\";N;s:4:\"prop\";s:14:\"dataSourceName\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:12:\"isdomainroot\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:12:\"isDomainRoot\";s:5:\"alias\";N;s:4:\"prop\";s:12:\"isDomainRoot\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}}s:14:\"reverseColumns\";a:8:{s:2:\"id\";s:2:\"id\";s:7:\"site_id\";s:6:\"siteid\";s:9:\"site_name\";s:8:\"sitename\";s:9:\"site_type\";s:8:\"sitetype\";s:3:\"url\";s:3:\"url\";s:4:\"path\";s:4:\"path\";s:16:\"data_source_name\";s:14:\"datasourcename\";s:12:\"isdomainroot\";s:12:\"isdomainroot\";}}";
function insert(Site $bean){
$this->setMethod("insert");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("bean" => $bean)); }
$this->buildBinds($query,array("bean" => $bean));
$query = $this->getQuery();
$binds = $this->getBinds();
$result = $this->executeUpdateQuery($query,$binds);
return $this->lastInsertId();
}
function update(Site $bean){
$this->setMethod("update");
$query = $this->buildQuery($this->_method,unserialize('a:1:{i:0;s:7:"site_id";}'),unserialize('a:0:{}'),"");
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
function getBySiteId($siteId){
$this->setMethod("getBySiteId");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("siteId" => $siteId)); }
$this->buildBinds($query,array("siteId" => $siteId));
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
function getNameMap(){
$this->setMethod("getNameMap");
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
function resetDomainRootSite($isDomainRoot = 0){
$this->setMethod("resetDomainRootSite");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:1:{i:0;s:12:"isDomainRoot";}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("isDomainRoot" => $isDomainRoot)); }
$this->buildBinds($query,array("isDomainRoot" => $isDomainRoot));
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
function updateDomainRootSite($id,$isDomainRoot = 1){
$this->setMethod("updateDomainRootSite");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:1:{i:0;s:12:"isDomainRoot";}'),"");
$query->where = "id = :id";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("id" => $id,"isDomainRoot" => $isDomainRoot)); }
$this->buildBinds($query,array("id" => $id,"isDomainRoot" => $isDomainRoot));
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
function getDomainRootSite(){
$this->setMethod("getDomainRootSite");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
$query->where = "isDomainRoot = 1";
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array()); }
$this->buildBinds($query,array());
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
function getBySiteType($siteType){
$this->setMethod("getBySiteType");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("siteType" => $siteType)); }
$this->buildBinds($query,array("siteType" => $siteType));
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
}}?><?php
 } 
?>