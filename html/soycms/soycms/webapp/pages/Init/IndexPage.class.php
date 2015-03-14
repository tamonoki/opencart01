<?php

/**
 * 初めてサイトにログインしたときの初期ページ
 * 「ページ新規作成」と「ダミーデータを作成」の２者択一
 */

class IndexPage extends CMSWebPageBase{

	function IndexPage() {

		$initDetect = $this->run("Init.InitDetectAction");
		if($initDetect->success() && $initDetect->getAttribute("detect")){
			//処理を続ける
		}else{
			$this->jump("");
		}

		WebPage::WebPage();


	}


}
?>