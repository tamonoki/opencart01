<?php /* created 2015-03-14 01:18:07 */ ?>
<?php $page = HTMLPage::getPage(); ?>
<html>

<?php if(!isset($page["page_4026cb5aa338a2498d1adb25de9618f7"])){ ?>
<?php $page["page_4026cb5aa338a2498d1adb25de9618f7"] = PagePlugin::loadWebPage("","/vagrant/html/soycms/admin/webapp/pages/Site/IndexPage.class.php","_common.HeaderPage",__FILE__); ?>
<?php } ?>
<?php echo $page["page_4026cb5aa338a2498d1adb25de9618f7"]; ?>

<body>

<div id="wrapper">
	<div id="upperMenu"><?php if(!isset($page["page_e8a0f124cf356d383d2a0086383827c2"])){ ?>
<?php $page["page_e8a0f124cf356d383d2a0086383827c2"] = PagePlugin::loadWebPage("","/vagrant/html/soycms/admin/webapp/pages/Site/IndexPage.class.php","UpperMenuPage",__FILE__); ?>
<?php } ?>
<?php echo $page["page_e8a0f124cf356d383d2a0086383827c2"]; ?></div>
	<div id="content">
		<h2>サイト管理</h2>

		<?php if(DisplayPlugin::toggle("only_default_user")){ ?><div class="functions">
			<p><?php if(!isset($page["create_link_visible"]) || $page["create_link_visible"]){ ?><?php if(strlen($page["create_link_attribute"]["href"])>0){ ?><a href="<?php echo $page["create_link_attribute"]["href"]; ?>"><?php } ?>サイトの作成	<?php if(strlen($page["create_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
		</div><?php } ?>
		
		<?php if(!isset($page["message_visible"]) || $page["message_visible"]){ ?><p class="message"><?php echo $page["message"]; ?></p><?php } ?>

		<?php if(!isset($page["error_visible"]) || $page["error_visible"]){ ?><p class="notify"><?php echo $page["error"]; ?></p><?php } ?>

		
		<div id="siteboxes">
			<h3>サイト一覧</h3>
			
			<?php $counter = 0; ?>
			<?php if(!isset($page["list_visible"]) || $page["list_visible"]){ ?><?php $list_counter = -1;foreach($page["list"] as $key => $list){ $list_counter++; ?>
			<div class="sitebox_big">
				<div class="sitebox_top">
					<?php if(!isset($list["site_name_visible"]) || $list["site_name_visible"]){ ?><h4 class="sitename"><?php echo $list["site_name"]; ?></h4><?php } ?>

					<p class="login_button"><?php if(!isset($list["login_link_visible"]) || $list["login_link_visible"]){ ?><?php if(strlen($list["login_link_attribute"]["href"])>0){ ?><a id="<?php echo $list["login_link_attribute"]["id"]; ?>" href="<?php echo $list["login_link_attribute"]["href"]; ?>"><?php } ?>ログイン<?php if(strlen($list["login_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
				</div>
				<div style="clear:both"><!----></div>
				<p class="uri"><?php if(!isset($list["domain_root_site_url_visible"]) || $list["domain_root_site_url_visible"]){ ?><?php if(strlen($list["domain_root_site_url_attribute"]["href"])>0){ ?><a href="<?php echo $list["domain_root_site_url_attribute"]["href"]; ?>"><?php } ?><?php echo $list["domain_root_site_url"]; ?><?php if(strlen($list["domain_root_site_url_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
				<p class="uri"><?php if(!isset($list["site_link_visible"]) || $list["site_link_visible"]){ ?><?php if(strlen($list["site_link_attribute"]["href"])>0){ ?><a href="<?php echo $list["site_link_attribute"]["href"]; ?>"><?php } ?><?php echo $list["site_link"]; ?><?php if(strlen($list["site_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
				<?php if(DisplayPlugin::toggle("only_default_user")){ ?><div class="sitebox_functions">
					<p><?php if(!isset($list["site_detail_link_visible"]) || $list["site_detail_link_visible"]){ ?><?php if(strlen($list["site_detail_link_attribute"]["href"])>0){ ?><a href="<?php echo $list["site_detail_link_attribute"]["href"]; ?>"><?php } ?>詳細<?php if(strlen($list["site_detail_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
					<p><?php if(!isset($list["auth_link_visible"]) || $list["auth_link_visible"]){ ?><?php if(strlen($list["auth_link_attribute"]["href"])>0){ ?><a href="<?php echo $list["auth_link_attribute"]["href"]; ?>"><?php } ?>管理者設定<?php if(strlen($list["auth_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
					<p><?php if(!isset($list["root_site_link_visible"]) || $list["root_site_link_visible"]){ ?><?php if(strlen($list["root_site_link_attribute"]["href"])>0){ ?><a onclick="<?php echo $list["root_site_link_attribute"]["onclick"]; ?>" id="<?php echo $list["root_site_link_attribute"]["id"]; ?>" href="<?php echo $list["root_site_link_attribute"]["href"]; ?>"><?php } ?><?php echo $list["root_site_link"]; ?><?php if(strlen($list["root_site_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
					<p><?php if(!isset($list["remove_link_visible"]) || $list["remove_link_visible"]){ ?><?php if(strlen($list["remove_link_attribute"]["href"])>0){ ?><a onclick="<?php echo $list["remove_link_attribute"]["onclick"]; ?>" href="<?php echo $list["remove_link_attribute"]["href"]; ?>"><?php } ?>削除<?php if(strlen($list["remove_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
				</div><?php } ?>
			</div>
			<?php $counter++; ?>
			<?php if(($counter % 2) == 0) echo "<br style=\"clear:both;\">"; ?>
			<?php } ?><?php } ?>

			
			<?php if(!isset($page["no_site_visible"]) || $page["no_site_visible"]){ ?><div style="margin-left:10px;">
				<p>現在サイトは作成されていません。<?php if(DisplayPlugin::toggle("only_default_user")){ ?>→<a href="/soycms/admin/index.php/Site/Create">サイトの作成</a><?php } ?></p>
			</div><?php } ?>

			
			<div style="clear:both"><!----></div>
		</div>
		
	</div>
	<div><?php if(!isset($page["page_e28f752b97886c42543ca14da109ea0d"])){ ?>
<?php $page["page_e28f752b97886c42543ca14da109ea0d"] = PagePlugin::loadWebPage("","/vagrant/html/soycms/admin/webapp/pages/Site/IndexPage.class.php","_common.FooterPage",__FILE__); ?>
<?php } ?>
<?php echo $page["page_e28f752b97886c42543ca14da109ea0d"]; ?></div>
</div>
</html>