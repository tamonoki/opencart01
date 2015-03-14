<?php
SOY2::imports("module.plugins.discount_free_coupon.config.*");
class SOYShopDiscountFreeCouponConfig extends SOYShopConfigPageBase{

	/**
	 * @return string
	 */
	function getConfigPage(){		
		$form = SOY2HTMLFactory::createInstance("DiscountFreeCouponConfigFormPage");
		$form->setConfigObj($this);
		$form->execute();
		return $form->getObject();
	}

	/**
	 * @return string
	 * 拡張設定に表示されたモジュールのタイトルを表示する
	 */
	function getConfigPageTitle(){
		return "クーポン自由設定プラグイン";
	}

}
SOYShopPlugin::extension("soyshop.config", "discount_free_coupon", "SOYShopDiscountFreeCouponConfig");
?>