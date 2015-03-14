<?php /* created 2015-03-14 01:18:07 */ ?>
<?php $page_83efa047f63aadb3df78e31426d50db7 = HTMLPage::getPage("page_83efa047f63aadb3df78e31426d50db7"); ?>
<?php if(!isset($page_83efa047f63aadb3df78e31426d50db7["biglogo_visible"]) || $page_83efa047f63aadb3df78e31426d50db7["biglogo_visible"]){ ?><img src="<?php echo $page_83efa047f63aadb3df78e31426d50db7["biglogo_attribute"]["src"]; ?>" alt="logo" /><?php } ?>

<div id="administrator">
	<p>管理者 <?php if(!isset($page_83efa047f63aadb3df78e31426d50db7["adminname_visible"]) || $page_83efa047f63aadb3df78e31426d50db7["adminname_visible"]){ ?><span title="<?php echo $page_83efa047f63aadb3df78e31426d50db7["adminname_attribute"]["title"]; ?>" id="adminname"><?php echo $page_83efa047f63aadb3df78e31426d50db7["adminname"]; ?></span><?php } ?>
</p>
	<?php if(!isset($page_83efa047f63aadb3df78e31426d50db7["update_link_visible"]) || $page_83efa047f63aadb3df78e31426d50db7["update_link_visible"]){ ?><?php if(strlen($page_83efa047f63aadb3df78e31426d50db7["update_link_attribute"]["href"])>0){ ?><a href="<?php echo $page_83efa047f63aadb3df78e31426d50db7["update_link_attribute"]["href"]; ?>"><?php } ?>アカウント設定<?php if(strlen($page_83efa047f63aadb3df78e31426d50db7["update_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>

	<a onclick="return confirm(&#039;ログアウトしますか？&#039;);" href="/soycms/admin/index.php/Login/Logout">ログアウト</a>
</div>
<div id="tabs">
	<a href="/soycms/admin/"><?php if(!isset($page_83efa047f63aadb3df78e31426d50db7["top_visible"]) || $page_83efa047f63aadb3df78e31426d50db7["top_visible"]){ ?><div class="<?php echo $page_83efa047f63aadb3df78e31426d50db7["top_attribute"]["class"]; ?>">トップ</div><?php } ?>
</a>

	<?php if(DisplayPlugin::toggle("for_default_user")){ ?>
	<a href="/soycms/admin/index.php/Site"><?php if(!isset($page_83efa047f63aadb3df78e31426d50db7["site_visible"]) || $page_83efa047f63aadb3df78e31426d50db7["site_visible"]){ ?><div class="<?php echo $page_83efa047f63aadb3df78e31426d50db7["site_attribute"]["class"]; ?>">サイト一覧</div><?php } ?>
</a>
	<a href="/soycms/admin/index.php/Administrator"><?php if(!isset($page_83efa047f63aadb3df78e31426d50db7["administrator_visible"]) || $page_83efa047f63aadb3df78e31426d50db7["administrator_visible"]){ ?><div class="<?php echo $page_83efa047f63aadb3df78e31426d50db7["administrator_attribute"]["class"]; ?>">管理者一覧</div><?php } ?>
</a>
	<?php if(!isset($page_83efa047f63aadb3df78e31426d50db7["is_application_installed_visible"]) || $page_83efa047f63aadb3df78e31426d50db7["is_application_installed_visible"]){ ?>
	<a href="/soycms/admin/index.php/Application"><?php if(!isset($page_83efa047f63aadb3df78e31426d50db7["application_visible"]) || $page_83efa047f63aadb3df78e31426d50db7["application_visible"]){ ?><div class="<?php echo $page_83efa047f63aadb3df78e31426d50db7["application_attribute"]["class"]; ?>">アプリケーション</div><?php } ?>
</a>
	<?php } ?>

	<?php } ?>

	<?php if(DisplayPlugin::toggle("for_not_default_user")){ ?>
	<a href="/soycms/admin/index.php/Administrator"><?php if(!isset($page_83efa047f63aadb3df78e31426d50db7["update_link_visible"]) || $page_83efa047f63aadb3df78e31426d50db7["update_link_visible"]){ ?><?php if(strlen($page_83efa047f63aadb3df78e31426d50db7["update_link_attribute"]["href"])>0){ ?><div class="tab_inactive" href="<?php echo $page_83efa047f63aadb3df78e31426d50db7["update_link_attribute"]["href"]; ?>"><?php } ?>アカウント設定<?php if(strlen($page_83efa047f63aadb3df78e31426d50db7["update_link_attribute"]["href"])>0){ ?></div><?php } ?><?php } ?>
</a>
	<?php } ?>

</div>
<div style="clear:both;"></div>

<script type="text/javascript">
$(".div.tab_inactive").each(function(){
	$(this).mouseover(function(e){
		$(this).attr("class","tab_inactive_hover");
		$(this).attr("className","tab_inactive_hover");
	});
	
	$(this).mouseout(function(e){
		$(this).attr("class","tab_inactive");
		$(this).attr("className","tab_inactive");
	});
	
	$(this).mousedown(function(e){
		$(this).attr("class","tab_inactive_active");
		$(this).attr("className","tab_inactive_active");
	});
});
</script>