<?php

class Analytics_CommonPage extends HTMLTemplatePage{
	
	function buildCommon(){
		$this->buildTag();
		$this->buildTitle();
		$this->buildPeriod();
	}
	
	function buildTag(){
		$tags = array();
		$tags[] = "<link rel=\"stylesheet\" href=\"" . SOY2PageController::createRelativeLink("css/admin/style.css") . "\" charset=\"utf-8\">";
		$tags[] = "<link rel=\"stylesheet\" href=\"" . SOY2PageController::createRelativeLink("css/soy2/style.css") . "\" charset=\"utf-8\">";
		$tags[] = "<link rel=\"stylesheet\" href=\"" . SOY2PageController::createRelativeLink("css/jquery-ui/themes/base/jquery-ui.css") . "\" charset=\"utf-8\">";
		
		$tags[] = "<script type=\"text/javascript\" src=\"" . SOY2PageController::createRelativeLink("js/jquery.js") . "\" charset=\"utf-8\"></script>";
		$tags[] = "<script type=\"text/javascript\" src=\"" . SOY2PageController::createRelativeLink("js/jquery-ui.min.js") . "\" charset=\"utf-8\"></script>";
		$tags[] = "<script type=\"text/javascript\" src=\"" . SOY2PageController::createRelativeLink("js/chart/Chart.js") . "\" charset=\"utf-8\"></script>";
		
		$this->addLabel("tag", array(
			"html" => implode("\n", $tags)
		));
	}
	
	function buildTitle(){
		$this->addLabel("title", array(
			"text" => AnalyticsPluginUtil::getTitle()
		));
	}
	
	function buildPeriod(){
		$start = AnalyticsPluginUtil::convertTitmeStamp("start");
		$end = AnalyticsPluginUtil::convertTitmeStamp("end");
		
		$this->addLabel("period", array(
			"text" => date("Y年n月", $start) . "から" . date("Y年n月", $end) . "まで"
		));
	}
}
?>