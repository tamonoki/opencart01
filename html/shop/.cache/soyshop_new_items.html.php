<?php /* created 2015-03-14 01:20:10 */ ?>
<?php $soyshop_new_items = HTMLPage::getPage("soyshop_new_items"); ?>
			<section id="new">
				<h1>新着商品</h1>
				<ul>
					<?php if(!isset($soyshop_new_items["new_item_list_visible"]) || $soyshop_new_items["new_item_list_visible"]){ ?><?php $new_item_list_counter = -1;foreach($soyshop_new_items["new_item_list"] as $key => $new_item_list){ $new_item_list_counter++; ?>
					<li>
						<?php if(!isset($new_item_list["item_link_visible"]) || $new_item_list["item_link_visible"]){ ?><?php if(strlen($new_item_list["item_link_attribute"]["href"])>0){ ?><a href="<?php echo $new_item_list["item_link_attribute"]["href"]; ?>"><?php } ?><?php if(!isset($new_item_list["item_small_image_visible"]) || $new_item_list["item_small_image_visible"]){ ?><img src="<?php echo $new_item_list["item_small_image_attribute"]["src"]; ?>" alt="" /><?php } ?>
<?php if(strlen($new_item_list["item_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>

						<p><?php if(!isset($new_item_list["item_link_visible"]) || $new_item_list["item_link_visible"]){ ?><?php if(strlen($new_item_list["item_link_attribute"]["href"])>0){ ?><a href="<?php echo $new_item_list["item_link_attribute"]["href"]; ?>"><?php } ?><?php if(!isset($new_item_list["item_name_visible"]) || $new_item_list["item_name_visible"]){ ?><?php echo $new_item_list["item_name"]; ?><?php } ?>
<?php if(strlen($new_item_list["item_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
					</li>
				<?php } ?><?php } ?>

				</ul>
			</section>
			