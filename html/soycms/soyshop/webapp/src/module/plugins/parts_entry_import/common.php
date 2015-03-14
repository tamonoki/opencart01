<?php

class EntryImportCommon{
	
	private $soyshopRooDir;
	private $soyshopPageDir;
	private $soyshopDaoDir;
	private $soyshopEntityDir;
	private $soyshopDsn;
	private $soyshopUser;
	private $soyshopPass;
	
	/**
	 * 呼び出すサイトとブログの設定。
	 * @return array(["siteId"],["blogId"],["count"])
	 */
	public static function getSiteConfig(){
		//DBがSOYShopにいる時に使う
		
		try{
			$siteConfig = SOYShop_DataSets::get("parts.entry.import");
		}catch(Exception $e){
			$siteConfig = array();
		}
		
		return $siteConfig;
	}

	/**
	 * コンストラクタ
	 */
	public function EntryImportCommon(){
		
	}
	
	/**
	 * SOYShopのSOY2::rootDir() など設定を保持<br>
	 * 
	 */
	public function prepareSOYShopConfig(){
		$this->soyshopRooDir = SOY2::RootDir();
		$this->soyshopPageDir = SOY2HTMLConfig::PageDir();
		$this->soyshopDaoDir = SOY2DAOConfig::DaoDir();
		$this->soyshopEntityDir = SOY2DAOConfig::EntityDir();
		$this->soyshopDsn = SOY2DAOConfig::Dsn();
		$this->soyshopUser = SOY2DAOConfig::User();
		$this->soyshopPass = SOY2DAOConfig::Pass();
		
		
		//後で戻す必要ある
	}
	
	/**
	 * RootDir, DaoConfig, EntityConfig をSOY CMSに変更する
	 */
	public function changeSOYCMSDir(){
		
		$rooDir = str_replace("/soyshop/webapp/src/", "/common/", $this->getSoyshopRooDir());
		$pageDir = str_replace("/soyshop/","/soycms/" , $this->getSoyshopPageDir());
		$daoDir = str_replace("/soyshop/webapp/src/", "/common/", $this->getSoyshopDaoDir());
		$entityDir = str_replace("/soyshop/webapp/src/", "/common/", $this->getSoyshopDaoDir());

		SOY2::RootDir($rooDir);
		SOY2HTMLConfig::PageDir($pageDir);
		SOY2DAOConfig::DaoDir($daoDir);
		SOY2DAOConfig::EntityDir($entityDir);
		
		SOY2::import("util.SOYShopUtil");
	}
	
	/**
	 * SOYShopのSOY2::rootDir() などを元に戻す
	 */
	public function setSOYShopConfig(){
		SOY2::RootDir($this->soyshopRooDir);
		SOY2HTMLConfig::PageDir($this->soyshopPageDir);
		SOY2DAOConfig::DaoDir($this->soyshopDaoDir);
		SOY2DAOConfig::EntityDir($this->soyshopEntityDir);
		SOY2DAOConfig::Dsn($this->soyshopDsn);
		SOY2DAOConfig::user($this->soyshopUser);
		SOY2DAOConfig::pass($this->soyshopPass);
	}
	
	
	/**
	 * SOY CMS管理用DB
	 * @return array(dsn, user, pass)
	 */
	public function getCmsDSN(){
		//DBファイルの在処の有無でMySQL
		$dbFilePath = str_replace("/soyshop/", "/common/config/db/mysql.php", SOYSHOP_ROOT);
		$isMySQL = file_exists($dbFilePath);

		$dsn = null;
		$user = null;
		$pass = null;
		
		//MySQL版
		if($isMySQL){
			include_once($dbFilePath);
			$dsn = (defined("ADMIN_DB_DSN"))? ADMIN_DB_DSN : null;
			$user = (defined("ADMIN_DB_USER"))? ADMIN_DB_USER : null;
			$pass = (defined("ADMIN_DB_PASS"))? ADMIN_DB_PASS : null;

		//SQLite版
		}else{
			$dsn = str_replace("/soyshop/", "/common/db/cms.db", SOYSHOP_ROOT);
			$dsn = "sqlite:" . $dsn;
		}
		
		return array($dsn, $user, $pass);
	}
	
	/**
	 * SOY CMS管理用DBへ切り替え
	 */
	public function changeCmsDSN(){
		list($cmsDsn, $cmsUser, $cmsPass) = $this->getCmsDSN();

		SOY2DAOConfig::Dsn($cmsDsn);
		SOY2DAOConfig::user($cmsUser);
		SOY2DAOConfig::pass($cmsPass);
		
	}

	/**
	 * SOY CMSサイトの取得
	 * @param array $siteConfig
	 * @return Site
	 */
	public function getSite($siteConfig){
		//MySQLはSOY CMS管理用DBに切り替わっているのが前提
		
		//DBファイルの在処の有無でMySQL
		$dbFilePath = str_replace("/soyshop/", "/common/config/db/mysql.php", SOYSHOP_ROOT);
		$isMySQL = file_exists($dbFilePath);

		$sites = $this->getSiteList($siteConfig);
		$target = new Site();
		
		if(isset($siteConfig["siteId"])){
			foreach($sites as $site){
				if($site->getSiteId() == $siteConfig["siteId"]){
					$target = $site;
					break;
				}
			}
		}
		
		return $target;
	}
	
	/**
	 * SOY CMSサイトDBへ切り替え
	 * @paramarray $siteConfig
	 */
	public function changeSiteDSN($siteConfig){
		//MySQLはSOY CMS管理用DBに入れ替わっているのが前提

		$site = $this->getSite($siteConfig);
		$dsn = $site->getDataSourceName();
		
		list($cmsDsn, $cmsUser, $cmsPass) = $this->getCmsDSN();
		SOY2DAOConfig::user($cmsUser);
		SOY2DAOConfig::pass($cmsPass);
		SOY2DAOConfig::Dsn($dsn);

	}
	
	/**
	 * SOY CMSサイト 配列取得
	 * @param array $siteConfig
	 * @return array(Site)
	 */
	public function getSiteList($siteConfig){
		//DNSはSOY CMS管理用DBに切り替わっている前提
		
		//SOY CMSサイト
		$adminDao = SOY2DAOFactory::create("admin.SiteDAO");
		try{
			$sites = $adminDao->getBySiteType(Site::TYPE_SOY_CMS);
		}catch(Exception $e){
			$sites = array();
		}

		return $sites;
	}
	
	/**
	 * ブログの取得
	 * @param array $siteConfig
	 * @return array(BlogPage)
	 */
	public function getBlogList(){
		//DNSはSOY CMSサイトに切り替わっている前提
		SOY2::import("util.CMSUtil");
		$blogDao = SOY2DAOFactory::create("cms.BlogPageDAO");
		try{
			$blogs = $blogDao->get();
		}catch(Exception $e){
			$blogs = array();
		}
		
		return $blogs;
	}
	
	/**
	 * ブログ記事 配列取得
	 * @param array $siteConfig
	 * @return array(Entry)
	 */
	public function getBlogEntiryList($siteConfig){
		if(!isset($siteConfig["blogId"])) return array();
		
		//DNSはSOY CMSサイトに切り替わっている前提
		SOY2::import("util.CMSUtil");
		
		//ブログの取得
		$blogDao = SOY2DAOFactory::create("cms.BlogPageDAO");
		try{
			$blog = $blogDao->getById($siteConfig["blogId"]);
		}catch(Exception $e){
			$blog = new BlogPage();
		}
		
		//特定ラベルの記事を取得します
		$sql = new SOY2DAO_Query();

		$binds = array(
			":label_id" => $blog->getBlogLabelId(),
			":now" => time()
		);
		
		$sql->prefix = "select";
		$sql->table = "Entry INNER JOIN EntryLabel ON (Entry.id = EntryLabel.entry_id)";
		$sql->distinct = true;
		$sql->order = "cdate desc";
		$sql->sql = "id,id,alias,title,content,more,cdate,display_order ";
		$sql->where = "label_id = :label_id ";
		$sql->where .= "AND Entry.isPublished = 1 ";
		$sql->where .= "AND (openPeriodEnd >= :now AND openPeriodStart < :now) ";

		$dao = SOY2DAOFactory::create("cms.EntryDAO");
		$dao->setLimit($siteConfig["count"]);
		try{
			$res = $dao->executeQuery($sql, $binds);
		}catch(Exception $e){
			$res = array();
			exit;
		}
		
		$entries = array();
		foreach($res as $key => $row){
			
			if(!isset($row)) continue;
			
			$obj = $dao->getObject($row);//SOY2::cast("Entry",(object)$row);
			$entries[] = $obj;
			
		}
		
		return $entries;
	}
	
	/**
	 * ブログURLの取得
	 * @param arrary $siteConfig
	 * @return string $path
	 */
	function getBlogPath($siteConfig){
		
		if(!isset($siteConfig["blogId"])) return "";
		
		//SOY CMS管理用DBへ切り替え
		//サイトの取得
		$this->changeCmsDSN();
		$site = $this->getSite($siteConfig);
		
		//
		$this->changeSiteDSN($siteConfig);
		
		
		//ブログの取得
		$blogDao = SOY2DAOFactory::create("cms.BlogPageDAO");
		try{
			$blog = $blogDao->getById($siteConfig["blogId"]);
		}catch(Exception $e){
			$blog = new BlogPage();
		}
		
		
		//ブログ記事のURL
		$blogUri = $blog->getUri();
		if(!strlen($blogUri) > 0){
			$entryPage = $site->getUrl(). $blog->getEntryPageUri() . "/";
		}else{
			$entryPage = $site->getUrl(). $blogUri. "/" . $blog->getEntryPageUri() . "/";
		}
		
		return $entryPage;
	}

	/* setter getter */

	public function getSoyshopRooDir() {
		return $this->soyshopRooDir;
	}
	public function setSoyshopRooDir($soyshopRooDir) {
		$this->soyshopRooDir = $soyshopRooDir;
	}

	public function getSoyshopPageDir() {
		return $this->soyshopPageDir;
	}
	public function setSoyshopPageDir($soyshopPageDir) {
		$this->soyshopPageDir = $soyshopPageDir;
	}

	public function getSoyshopDaoDir() {
		return $this->soyshopDaoDir;
	}
	public function setSoyshopDaoDir($soyshopDaoDir) {
		$this->soyshopDaoDir = $soyshopDaoDir;
	}

	public function getSoyshopEntityDir() {
		return $this->soyshopEntityDir;
	}
	public function setSoyshopEntityDir($soyshopEntityDir) {
		$this->soyshopEntityDir = $soyshopEntityDir;
	}

	public function getSoyshopDsn() {
		return $this->soyshopDsn;
	}
	public function setSoyshopDsn($soyshopDsn) {
		$this->soyshopDsn = $soyshopDsn;
	}

	public function getSoyshopUser() {
		return $this->soyshopUser;
	}
	public function setSoyshopUser($soyshopUser) {
		$this->soyshopUser = $soyshopUser;
	}

	public function getSoyshopPass() {
		return $this->soyshopPass;
	}
	public function setSoyshopPass($soyshopPass) {
		$this->soyshopPass = $soyshopPass;
	}
}



?>