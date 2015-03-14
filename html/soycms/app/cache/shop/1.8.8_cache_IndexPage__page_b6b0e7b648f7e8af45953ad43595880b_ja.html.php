<?php /* created 2015-03-14 01:19:07 */ ?>
<?php $page = HTMLPage::getPage(); ?>
<div style="padding:20px;">

<h1>ようこそSOY Shopへ！</h1>

<?php if(DisplayPlugin::toggle("for_super_user")){ ?>
<p>SOY ShopはSOY CMSのサイトを使って簡単にECサイトを運営するためのアプリケーションです。
</p>

<p>SOY Shopを使うには、SOY Shop専用のサイトを作成する必要があります。</p>
<?php } ?>

<h2>SOY Shopサイトの一覧</h2>

<?php if(!isset($page["no_soyshop_visible"]) || $page["no_soyshop_visible"]){ ?>
<p style="margin-top:20px;margin-left:30px;">サイトがありません。 → <a href="/soycms/app/index.php/shop/Create">サイトの新規作成</a></p>
<?php } ?>


<?php if(!isset($page["display_soyshop_list_visible"]) || $page["display_soyshop_list_visible"]){ ?>

<div class="table_container">
	<table class="nowrap list">
		<thead>
			<tr>
				<th class="short">サイトID</th>
				<th>サイト名</th>
				<th class="short">データベース</th>
				<th>URL</th>
				<th class="short">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php if(!isset($page["soyshop_list_visible"]) || $page["soyshop_list_visible"]){ ?><?php $soyshop_list_counter = -1;foreach($page["soyshop_list"] as $key => $soyshop_list){ $soyshop_list_counter++; ?>
			<tr>
				<td><?php if(!isset($soyshop_list["soyshop_site_id_visible"]) || $soyshop_list["soyshop_site_id_visible"]){ ?><?php echo $soyshop_list["soyshop_site_id"]; ?><?php } ?>
</td>
				<td><?php if(!isset($soyshop_list["soyshop_site_name_visible"]) || $soyshop_list["soyshop_site_name_visible"]){ ?><?php echo $soyshop_list["soyshop_site_name"]; ?><?php } ?>
</td>
				<td><?php if(!isset($soyshop_list["soyshop_site_db_visible"]) || $soyshop_list["soyshop_site_db_visible"]){ ?><?php echo $soyshop_list["soyshop_site_db"]; ?><?php } ?>
</td>
				<td><?php if(!isset($soyshop_list["soyshop_site_url_visible"]) || $soyshop_list["soyshop_site_url_visible"]){ ?><?php if(strlen($soyshop_list["soyshop_site_url_attribute"]["href"])>0){ ?><a href="<?php echo $soyshop_list["soyshop_site_url_attribute"]["href"]; ?>" target="<?php echo $soyshop_list["soyshop_site_url_attribute"]["target"]; ?>"><?php } ?><?php echo $soyshop_list["soyshop_site_url"]; ?><?php if(strlen($soyshop_list["soyshop_site_url_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</td>
				<td><?php if(!isset($soyshop_list["soyshop_site_login_visible"]) || $soyshop_list["soyshop_site_login_visible"]){ ?><?php if(strlen($soyshop_list["soyshop_site_login_attribute"]["href"])>0){ ?><a href="<?php echo $soyshop_list["soyshop_site_login_attribute"]["href"]; ?>"><?php } ?><?php echo $soyshop_list["soyshop_site_login"]; ?><?php if(strlen($soyshop_list["soyshop_site_login_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
</td>
			</tr>
			<?php } ?><?php } ?>

		</tbody>
	</table>
</div>
<?php } ?>


</div>