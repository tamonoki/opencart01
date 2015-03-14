<?php
/**
 * @class Site.Pages.SubMenu.FreePage
 * @date 2009-11-19T22:37:03+09:00
 * @author SOY2HTMLFactory
 */
class FreeMenuPage extends HTMLPage{

	var $id;

	function FreeMenuPage($arg = array()){
		$this->id = $arg[0];
		HTMLPage::HTMLPage();


		$this->createAdd("free_page_site_link","HTMLLink", array(
			"link" => soyshop_get_page_url($arg[1]->getUri())
		));

		$this->createAdd("free_page_detail_link","HTMLLink", array(
			"link" => SOY2PageController::createLink("Site.Pages.Extra.Free." . $this->id)
		));

		$dao = SOY2DAOFactory::create("site.SOYShop_PageDAO");
		$page = $dao->getById($this->id);

		$obj = $page->getPageObject();

		$this->createAdd("title","HTMLLabel", array(
			"text" => $obj->getTitle()
		));

		$this->createAdd("update_date","HTMLLabel", array(
			"text" => $obj->getUpdateDateText()
		));
	}
}


?>