<?php /* created 2015-03-14 01:16:53 */ ?>
<?php $page = HTMLPage::getPage(); ?>
<html>

<?php if(!isset($page["page_4026cb5aa338a2498d1adb25de9618f7"])){ ?>
<?php $page["page_4026cb5aa338a2498d1adb25de9618f7"] = PagePlugin::loadWebPage("","/vagrant/html/soycms/admin/webapp/pages/IndexPage.class.php","_common.HeaderPage",__FILE__); ?>
<?php } ?>
<?php echo $page["page_4026cb5aa338a2498d1adb25de9618f7"]; ?>

<body>

<div id="wrapper">
	<div id="upperMenu"><?php if(!isset($page["page_e8a0f124cf356d383d2a0086383827c2"])){ ?>
<?php $page["page_e8a0f124cf356d383d2a0086383827c2"] = PagePlugin::loadWebPage("","/vagrant/html/soycms/admin/webapp/pages/IndexPage.class.php","UpperMenuPage",__FILE__); ?>
<?php } ?>
<?php echo $page["page_e8a0f124cf356d383d2a0086383827c2"]; ?></div>
	<div id="content">
		
		<h2>ようこそSOY CMSへ</h2>
				
		<div></div>
		<?php if(DisplayPlugin::toggle("only_default_user")){ ?><div class="functions">
			<p><?php if(!isset($page["create_link_visible"]) || $page["create_link_visible"]){ ?><?php if(strlen($page["create_link_attribute"]["href"])>0){ ?><a href="<?php echo $page["create_link_attribute"]["href"]; ?>"><?php } ?>サイトの作成<?php if(strlen($page["create_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
			<p><?php if(!isset($page["addAdministrator_visible"]) || $page["addAdministrator_visible"]){ ?><?php if(strlen($page["addAdministrator_attribute"]["href"])>0){ ?><a href="<?php echo $page["addAdministrator_attribute"]["href"]; ?>"><?php } ?>管理者の追加<?php if(strlen($page["addAdministrator_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
			<?php if(!isset($page["file_form_visible"]) || $page["file_form_visible"]){ ?><?php $file_form = $page["file_form"]; ?><form id="file_form" method="<?php echo $page["file_form_attribute"]["method"]; ?>" style="display:inline;" action="<?php echo $page["file_form_attribute"]["action"]; ?>" <?php if($page["file_form_attribute"]["disabled"]){ ?>disabled="<?php echo $page["file_form_attribute"]["disabled"]; ?>"<?php } ?>><input type="hidden" name="soy2_token" value="<?php echo soy2_get_token(); ?>" />
				<input type="hidden" name="file_db_update" value="1" />
				<p><a href="javascript:void(0);" onclick="$('#file_form').submit();">ファイルDB更新</a><img src="/soycms/admin/image/icon/help.gif" class="help_icon" onMouseOver="this.style.cursor=&#039;pointer&#039;" onMouseOut="this.style.cursor=&#039;auto&#039;" onclick="common_show_message_popup(this,&#039;ファイルマネージャーを使わずFTPなどで直接ファイルの&lt;br/&gt;アップロードなどを行った場合は「ファイルDBの更新」を行ってください。&#039;)" /></p>
			</form><?php } ?>

			<?php if(!isset($page["cache_form_visible"]) || $page["cache_form_visible"]){ ?><?php $cache_form = $page["cache_form"]; ?><form id="cache_form" method="<?php echo $page["cache_form_attribute"]["method"]; ?>" style="display:inline;" action="<?php echo $page["cache_form_attribute"]["action"]; ?>" <?php if($page["cache_form_attribute"]["disabled"]){ ?>disabled="<?php echo $page["cache_form_attribute"]["disabled"]; ?>"<?php } ?>><input type="hidden" name="soy2_token" value="<?php echo soy2_get_token(); ?>" />
				<input type="hidden" name="cache_clear" value="1" />
				<p><a href="javascript:void(0);" onclick="$('#cache_form').submit();">キャッシュのクリア</a><img src="/soycms/admin/image/icon/help.gif" class="help_icon" onMouseOver="this.style.cursor=&#039;pointer&#039;" onMouseOut="this.style.cursor=&#039;auto&#039;" onclick="common_show_message_popup(this,&#039;テンプレートのキャッシュをクリアします。&lt;br/&gt;通常は行う必要はありませんが、バージョンアップ後には行うことをお勧めします。&#039;)" /></p>
			</form><?php } ?>

			<p><a href="/soycms/admin/index.php/Server">サーバー情報</a></p>
			
		</div><?php } ?>
		
		<?php if(!isset($page["file_db_massage_visible"]) || $page["file_db_massage_visible"]){ ?><p class="message">ファイルDBを更新しました</p><?php } ?>

		<?php if(!isset($page["cache_clear_massage_visible"]) || $page["cache_clear_massage_visible"]){ ?><p class="message">キャッシュを削除しました</p><?php } ?>

		
		<div id="siteboxes">
			<h3>サイト一覧</h3>
			
			<?php $counter = 0; ?>
			<?php if(!isset($page["list_visible"]) || $page["list_visible"]){ ?><?php $list_counter = -1;foreach($page["list"] as $key => $list){ $list_counter++; ?>
			<div class="sitebox">
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
			</div>
			<?php $counter++; ?>
			<?php if(($counter % 2) == 0) echo "<br style=\"clear:both;\">"; ?>
			<?php } ?><?php } ?>

			
			<?php if(!isset($page["no_site_visible"]) || $page["no_site_visible"]){ ?><div style="margin:10px;">
				<p>現在サイトは作成されていません。<?php if(DisplayPlugin::toggle("only_default_user")){ ?>→<a href="/soycms/admin/index.php/Site/Create">サイトの作成</a><?php } ?></p>
			</div><?php } ?>

			
			<div style="clear:both"><!----></div>
		</div>
		
		<?php if(!isset($page["application_list_wrapper_visible"]) || $page["application_list_wrapper_visible"]){ ?>
		<div class="siteboxes">
			<h3>アプリケーション</h3>
			<?php if(!isset($page["application_list_visible"]) || $page["application_list_visible"]){ ?><?php $application_list_counter = -1;foreach($page["application_list"] as $key => $application_list){ $application_list_counter++; ?><div class="sitebox">
				<div class="sitebox_top">
					<?php if(!isset($application_list["name_visible"]) || $application_list["name_visible"]){ ?><h4 class="sitename"><?php echo $application_list["name"]; ?></h4><?php } ?>

					<p class="login_button"><?php if(!isset($application_list["login_link_visible"]) || $application_list["login_link_visible"]){ ?><?php if(strlen($application_list["login_link_attribute"]["href"])>0){ ?><a href="<?php echo $application_list["login_link_attribute"]["href"]; ?>"><?php } ?>ログイン<?php if(strlen($application_list["login_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</p>
				</div>
				<div style="clear:both"><!----></div>
				<?php if(!isset($application_list["description_visible"]) || $application_list["description_visible"]){ ?><p class="description"><?php echo $application_list["description"]; ?></p><?php } ?>

				<?php if(!isset($application_list["version_visible"]) || $application_list["version_visible"]){ ?><p class="version"><?php echo $application_list["version"]; ?></p><?php } ?>

			</div><?php } ?><?php } ?>

			
			<div style="clear:both"><!----></div>
		</div>
		<?php } ?>

		
		<div id="soycms_info">
			<h3>SOY CMSについて</h3>
			
			<div style="margin-left:10px;margin-top:2px;">
				<p>Version: <?php echo SOYCMS_VERSION; ?>
				(Build Date: <?php echo date("Y-m-d H:i:s T",strtotime(SOYCMS_BUILD)); ?>)</p>
				<p>DB Type: <?php echo SOYCMS_DB_TYPE; ?></p>
				<?php if(!isset($page["allow_php_visible"]) || $page["allow_php_visible"]){ ?>
				<p>Allow PHP Script: true</p>
				<?php } ?>


				<p><a href="http://www.soycms.net/">SOY CMS公式サイト</a></p>
			</div>
		</div>
		
		
		<div style="clear:both"><!----></div>
		
	</div>
	<div><?php if(!isset($page["page_e28f752b97886c42543ca14da109ea0d"])){ ?>
<?php $page["page_e28f752b97886c42543ca14da109ea0d"] = PagePlugin::loadWebPage("","/vagrant/html/soycms/admin/webapp/pages/IndexPage.class.php","_common.FooterPage",__FILE__); ?>
<?php } ?>
<?php echo $page["page_e28f752b97886c42543ca14da109ea0d"]; ?></div>
</div>
</body>
</html>