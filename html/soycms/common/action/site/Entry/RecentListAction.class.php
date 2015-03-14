<?php

class RecentListAction extends SOY2Action{

	var $limit = 3;

	function setLimit($limit){
		$this->limit = $limit;
	}

    function execute() {

    	$logic = SOY2Logic::createInstance("logic.site.Entry.EntryLogic");
		$labelLogic = SOY2Logic::createInstance("logic.site.Label.LabelLogic");

    	//最新エントリーを3件取得
    	$logic->setLimit(3);
    	$array = $logic->getRecentEntries();

		//記事管理者の場合
		if(count($array) && class_exists("UserInfoUtil") && !UserInfoUtil::hasSiteAdminRole()){
			$prohibitedLabelIds = $labelLogic->getProhibitedLabelIds();
		}

    	$entries = array();
    	foreach($array as $entry){
			$labeledEntry = SOY2::cast("LabeledEntry",$entry);

			$labels = $logic->getLabelIdsByEntryId($entry->getId());

			if(count($labels) && isset($prohibitedLabelIds) && count($prohibitedLabelIds) && count(array_intersect($labels, $prohibitedLabelIds))){
				//記事管理者の場合
				//非表示のラベルの付いた記事は飛ばす
			}else{
				$labeledEntry->setLabels($labels);
				$entries[] = $labeledEntry;
			}
    	}

    	$this->setAttribute("list",$entries);

    	//ラベルを取得
    	$labels = $labelLogic->getWithAccessControl();

    	$this->setAttribute("labels",$labels);

    	return SOY2Action::SUCCESS;
    }

}
?>