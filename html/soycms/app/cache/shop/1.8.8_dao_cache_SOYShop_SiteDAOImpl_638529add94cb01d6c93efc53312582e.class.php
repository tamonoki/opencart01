<?php if(!class_exists('SOYShop_Site')){ 
include_once("/vagrant/html/soycms/app/webapp/shop/src/domain/SOYShop_Site.class.php"); 
} 
?><?php $updateDate = max(filemtime("/vagrant/html/soycms/app/webapp/shop/src/domain/SOYShop_SiteDAO.class.php"),filemtime("/vagrant/html/soycms/app/webapp/shop/src/domain/SOYShop_Site.class.php"));if($updateDate  < filemtime(__FILE__)){ ?><?php
class SOYShop_SiteDAOImpl extends SOYShop_SiteDAO{
var $_entity = "O:14:\"SOY2DAO_Entity\":5:{s:4:\"name\";s:12:\"SOYShop_Site\";s:5:\"table\";s:12:\"soyshop_site\";s:2:\"id\";b:0;s:7:\"columns\";a:8:{s:2:\"id\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:2:\"id\";s:5:\"alias\";N;s:4:\"prop\";s:2:\"id\";s:9:\"isPrimary\";b:1;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:6:\"siteid\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:7:\"site_id\";s:5:\"alias\";N;s:4:\"prop\";s:6:\"siteId\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:4:\"name\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:9:\"site_name\";s:5:\"alias\";N;s:4:\"prop\";s:4:\"name\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:3:\"url\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:3:\"url\";s:5:\"alias\";N;s:4:\"prop\";s:3:\"url\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:4:\"path\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:4:\"path\";s:5:\"alias\";N;s:4:\"prop\";s:4:\"path\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:3:\"dsn\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:16:\"data_source_name\";s:5:\"alias\";N;s:4:\"prop\";s:3:\"dsn\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:10:\"createdate\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"create_date\";s:5:\"alias\";N;s:4:\"prop\";s:10:\"createDate\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}s:10:\"updatedate\";O:20:\"SOY2DAO_EntityColumn\":7:{s:2:\"id\";N;s:4:\"name\";s:11:\"update_date\";s:5:\"alias\";N;s:4:\"prop\";s:10:\"updateDate\";s:9:\"isPrimary\";N;s:8:\"readOnly\";b:0;s:8:\"sequence\";N;}}s:14:\"reverseColumns\";a:8:{s:2:\"id\";s:2:\"id\";s:7:\"site_id\";s:6:\"siteid\";s:9:\"site_name\";s:4:\"name\";s:3:\"url\";s:3:\"url\";s:4:\"path\";s:4:\"path\";s:16:\"data_source_name\";s:3:\"dsn\";s:11:\"create_date\";s:10:\"createdate\";s:11:\"update_date\";s:10:\"updatedate\";}}";
function insert(SOYShop_Site $obj){
$this->setMethod("insert");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("obj" => $obj)); }
$this->buildBinds($query,array("obj" => $obj));
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
function update(SOYShop_Site $obj){
$this->setMethod("update");
$query = $this->buildQuery($this->_method,unserialize('a:0:{}'),unserialize('a:0:{}'),"");
if($query instanceof SOY2DAO_Query){ $query->parseExpression(array("obj" => $obj)); }
$this->buildBinds($query,array("obj" => $obj));
$query = $this->getQuery();
$binds = $this->getBinds();
if(method_exists($this,"onUpdate")){
list($query,$binds) = $this->onUpdate($query,$binds);
}else{
list($query,$binds) = onUpdate($query,$binds);
}
$result = $this->executeUpdateQuery($query,$binds);
return $this->lastInsertId();
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

}?><?php
 } 
?>