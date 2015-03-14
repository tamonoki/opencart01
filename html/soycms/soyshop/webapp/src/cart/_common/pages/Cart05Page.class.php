<?php
/**
 * @class Cart05Page
 * @date 2009-07-16T17:44:33+09:00
 * @author SOY2HTMLFactory
 */
class Cart05Page extends MainCartPageBase{

	function doPost(){

		$cart = CartLogic::getCart();
		$paymentModule = $cart->getAttribute("payment_module");

		$moduleDAO = SOY2DAOFactory::create("plugin.SOYShop_PluginConfigDAO");
		$paymentModule = $moduleDAO->getByPluginId($paymentModule);

		SOYShopPlugin::load("soyshop.payment", $paymentModule);

		 SOYShopPlugin::invoke("soyshop.payment.option", array(
			"cart" => $cart,
			"mode" => "post"
		));

		soyshop_redirect_cart();
		exit;
	}

	function Cart05Page(){
		WebPage::WebPage();

		//completeはCompletaPage.class.phpに移動
		$cart = CartLogic::getCart();
		$paymentModule = $cart->getAttribute("payment_module");

		$moduleDAO = SOY2DAOFactory::create("plugin.SOYShop_PluginConfigDAO");
		$paymentModule = $moduleDAO->getByPluginId($paymentModule);

		SOYShopPlugin::load("soyshop.payment", $paymentModule);

		$this->addLabel("option_page", array(
			"html" => SOYShopPlugin::display("soyshop.payment.option", array(
				"cart" => $cart
			))
		));
		
		SOYShopPlugin::load("soyshop.cart");
		$delegate = SOYShopPlugin::invoke("soyshop.cart", array(
			"mode" => "page05",
			"cart" => $cart
		));

		$html = $delegate->getHtml();
		
		$this->addModel("has_cart_plugin", array(
			"visible" => (count($html) > 0)
		));
		
		$this->createAdd("cart_plugin_list", "_common.CartPluginListComponent", array(
			"list" => $html
		));
	}
}
?>