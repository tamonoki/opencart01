<?php /* created 2015-03-14 01:20:09 */ ?>
<?php $soyshop_cart = HTMLPage::getPage("soyshop_cart"); ?>
			<section id="head_cart">
				<p class="head_btn"><?php if(!isset($soyshop_cart["cart_link_visible"]) || $soyshop_cart["cart_link_visible"]){ ?><?php if(strlen($soyshop_cart["cart_link_attribute"]["href"])>0){ ?><a href="<?php echo $soyshop_cart["cart_link_attribute"]["href"]; ?>"><?php } ?><img src="/shop/themes/common/images/btn_cart_off.png" alt="カートを見る"><?php if(strlen($soyshop_cart["cart_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
				<h1>現在のカートの中身</h1>
				<?php if(!isset($soyshop_cart["full_cart_visible"]) || $soyshop_cart["full_cart_visible"]){ ?>
				<p id="sum">商品点数：<?php if(!isset($soyshop_cart["item_total_visible"]) || $soyshop_cart["item_total_visible"]){ ?><?php echo $soyshop_cart["item_total"]; ?><?php } ?>
 点｜小計：¥ <?php if(!isset($soyshop_cart["cart_total_visible"]) || $soyshop_cart["cart_total_visible"]){ ?><?php echo $soyshop_cart["cart_total"]; ?><?php } ?>
</p>
				<?php } ?>

				<?php if(!isset($soyshop_cart["empty_cart_visible"]) || $soyshop_cart["empty_cart_visible"]){ ?>
				<p id="sum">商品点数：0 点｜小計：¥ 0</p>
				<?php } ?>

			</section>
			