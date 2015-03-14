<?php /* created 2015-03-14 01:20:09 */ ?>
<?php $soyshop_recommend_items = HTMLPage::getPage("soyshop_recommend_items"); ?>
				<section id="recommended_items">
					<h1>おすすめ商品</h1>
					<div class="item_list">
						<?php if(!isset($soyshop_recommend_items["recommend_item_list_visible"]) || $soyshop_recommend_items["recommend_item_list_visible"]){ ?><?php $recommend_item_list_counter = -1;foreach($soyshop_recommend_items["recommend_item_list"] as $key => $recommend_item_list){ $recommend_item_list_counter++; ?>
						<article>
							<?php if(!isset($recommend_item_list["item_link_visible"]) || $recommend_item_list["item_link_visible"]){ ?><?php if(strlen($recommend_item_list["item_link_attribute"]["href"])>0){ ?><a href="<?php echo $recommend_item_list["item_link_attribute"]["href"]; ?>"><?php } ?><?php if(!isset($recommend_item_list["item_small_image_visible"]) || $recommend_item_list["item_small_image_visible"]){ ?><img src="<?php echo $recommend_item_list["item_small_image_attribute"]["src"]; ?>" width="150" height="113" alt="苔オブジェ" /><?php } ?>

							<?php if(!isset($recommend_item_list["item_name_visible"]) || $recommend_item_list["item_name_visible"]){ ?><h1><?php echo $recommend_item_list["item_name"]; ?></h1><?php } ?>
<?php if(strlen($recommend_item_list["item_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>

							<p class="tag"><?php if(!isset($recommend_item_list["custom_icon_field_visible"]) || $recommend_item_list["custom_icon_field_visible"]){ ?><?php echo $recommend_item_list["custom_icon_field"]; ?><?php } ?>
</p>
							<p class="price">¥<?php if(!isset($recommend_item_list["item_price_visible"]) || $recommend_item_list["item_price_visible"]){ ?><?php echo $recommend_item_list["item_price"]; ?><?php } ?>
</p>
							<?php if(!isset($recommend_item_list["item_copy1_visible"]) || $recommend_item_list["item_copy1_visible"]){ ?><p class="text"><?php echo $recommend_item_list["item_copy1"]; ?></p><?php } ?>

							<?php if(!isset($recommend_item_list["this_is_new_visible"]) || $recommend_item_list["this_is_new_visible"]){ ?><p class="new"><img src="/shop/themes/common/images/new_icon.png" alt="NEW!"></p><?php } ?>

							<p class="more"><?php if(!isset($recommend_item_list["item_link_visible"]) || $recommend_item_list["item_link_visible"]){ ?><?php if(strlen($recommend_item_list["item_link_attribute"]["href"])>0){ ?><a href="<?php echo $recommend_item_list["item_link_attribute"]["href"]; ?>"><?php } ?>&gt;&gt;詳細を見る<?php if(strlen($recommend_item_list["item_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
						</article>
					<?php } ?><?php } ?>

					</div>
				</section>
				