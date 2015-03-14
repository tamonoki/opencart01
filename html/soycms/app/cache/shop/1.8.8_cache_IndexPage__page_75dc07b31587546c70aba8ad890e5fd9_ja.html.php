<?php /* created 2015-03-14 01:19:12 */ ?>
<?php $page = HTMLPage::getPage(); ?>
<style type="text/css">
.section{
	background-color:#EEEEEE;
	border:solid 1px #BBBBBB;
	margin:30px;
	padding:20px;
}

.section p.sub{
	color:#888;
	margin:0;
}

.mt20{
	margin-top:20px;
}
.ml30{
	margin-left:30px;
}
.mb0{
	margin-bottom:0;
}

input[type=text]{
	width: 300px;
}
</style>

<div style="padding:20px;">

<h1>SOY Shopの作成</h1>

<p>SOY ShopはSOY CMSのサイトを使って簡単にECサイトを運営するためのアプリケーションです。
</p>

<p>SOY Shopを使うには、SOY Shop専用のサイトを作成する必要があります。
</p>

<h2>SOY Shopサイトの作成</h2>

<?php if(DisplayErrorPlugin::check("error_soycms_site_dir")){ ?><?php if(DisplayPlugin::toggle("error_soycms_site_dir")){ ?><p class="error">
	サイト作成ディレクトリの書き込み権限がありません。<br>
	<?php if(!isset($page["run_user_visible"]) || $page["run_user_visible"]){ ?><?php echo $page["run_user"]; ?><?php } ?>
 が <?php if(!isset($page["soycms_site_dir_visible"]) || $page["soycms_site_dir_visible"]){ ?><?php echo $page["soycms_site_dir"]; ?><?php } ?>
 に書き込めるようにしてください。
</p><?php } ?><?php } ?>
<?php if(DisplayErrorPlugin::check("error_soyshop_shop_conf_dir")){ ?><?php if(DisplayPlugin::toggle("error_soyshop_shop_conf_dir")){ ?><p class="error">
	設定ファイル保存ディレクトリの書き込み権限がありません。<br>
	<?php if(!isset($page["run_user_visible"]) || $page["run_user_visible"]){ ?><?php echo $page["run_user"]; ?><?php } ?>
 が <?php if(!isset($page["soyshop_conf_dir_visible"]) || $page["soyshop_conf_dir_visible"]){ ?><?php echo $page["soyshop_conf_dir"]; ?><?php } ?>
 に書き込めるようにしてください。
</p><?php } ?><?php } ?>

<p class="sub ml30 mt20">SOY Shopを使用するサイトを作成します。</p>

<?php if(!isset($page["init_form_visible"]) || $page["init_form_visible"]){ ?><?php $init_form = $page["init_form"]; ?><form action="<?php echo $page["init_form_attribute"]["action"]; ?>" method="<?php echo $page["init_form_attribute"]["method"]; ?>" <?php if($page["init_form_attribute"]["disabled"]){ ?>disabled="<?php echo $page["init_form_attribute"]["disabled"]; ?>"<?php } ?>><input type="hidden" name="soy2_token" value="<?php echo soy2_get_token(); ?>" />

<?php if(DisplayErrorPlugin::check("site_id_empty")){ ?><?php if(DisplayPlugin::toggle("site_id_empty")){ ?><p class="error">
	サイトIDを入力してください。
</p><?php } ?><?php } ?>

<?php if(DisplayErrorPlugin::check("site_id_unique")){ ?><?php if(DisplayPlugin::toggle("site_id_unique")){ ?><p class="error">
	サイトIDが既に使用されています。
</p><?php } ?><?php } ?>

<?php if(DisplayErrorPlugin::check("site_id_format")){ ?><?php if(DisplayPlugin::toggle("site_id_format")){ ?><p class="error">
	サイトIDに使用可能なのは半角英数字、-(ハイフン)、_(アンダーライン)のみです。
</p><?php } ?><?php } ?>

<?php if(DisplayErrorPlugin::check("site_id_format_init")){ ?><?php if(DisplayPlugin::toggle("site_id_format_init")){ ?><p class="error">
	サイトIDには半角英字からはじめてください。
</p><?php } ?><?php } ?>

<?php if(DisplayErrorPlugin::check("site_name_empty")){ ?><?php if(DisplayPlugin::toggle("site_name_empty")){ ?><p class="error">
	サイト名を入力してください。
</p><?php } ?><?php } ?>

<?php if(DisplayErrorPlugin::check("init")){ ?><?php if(DisplayPlugin::toggle("init")){ ?><p class="error">
	初期化に失敗しました 
</p><?php } ?><?php } ?>

<?php if(DisplayErrorPlugin::check("mysql_host_empty")){ ?><?php if(DisplayPlugin::toggle("mysql_host_empty")){ ?><p class="error">
	MySQL ホスト名を入力してください。
</p><?php } ?><?php } ?>

<?php if(DisplayErrorPlugin::check("mysql_db_empty")){ ?><?php if(DisplayPlugin::toggle("mysql_db_empty")){ ?><p class="error">
	MySQL データベース名を入力してください。
</p><?php } ?><?php } ?>

<?php if(DisplayErrorPlugin::check("db_connect")){ ?><?php if(DisplayPlugin::toggle("db_connect")){ ?><p class="error">
	データベースとの接続に失敗しました。データベースの接続情報を確認してください。
</p><?php } ?><?php } ?>

<?php if(DisplayErrorPlugin::check("db_exist_table")){ ?><?php if(DisplayPlugin::toggle("db_exist_table")){ ?><p class="error">
	接続先のデータベースにすでにSOY Shopが存在する可能性があります。DBを確認してください。
</p><?php } ?><?php } ?>

<p class="sub ml30 mb0">
<?php if(!isset($page["dbtype_sqlite_visible"]) || $page["dbtype_sqlite_visible"]){ ?><input name="<?php echo $page["dbtype_sqlite_attribute"]["name"]; ?>" value="<?php echo $page["dbtype_sqlite_attribute"]["value"]; ?>" type="radio" id="<?php echo $page["dbtype_sqlite_attribute"]["id"]; ?>" <?php if($page["dbtype_sqlite_attribute"]["disabled"]){ ?>disabled="<?php echo $page["dbtype_sqlite_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["dbtype_sqlite_attribute"]["readonly"]){ ?>readonly="<?php echo $page["dbtype_sqlite_attribute"]["readonly"]; ?>"<?php } ?> <?php if($page["dbtype_sqlite_attribute"]["checked"]){ ?>checked="<?php echo $page["dbtype_sqlite_attribute"]["checked"]; ?>"<?php } ?> /><?php if(strlen($page["dbtype_sqlite"])>0){ ?><label for="<?php echo $page["dbtype_sqlite_attribute"]["id"]; ?>"><?php echo $page["dbtype_sqlite"]; ?></label><?php } ?><?php } ?>

<label for="dbtype_sqlite">SQLite版</label>
<?php if(!isset($page["dbtype_mysql_visible"]) || $page["dbtype_mysql_visible"]){ ?><input name="<?php echo $page["dbtype_mysql_attribute"]["name"]; ?>" value="<?php echo $page["dbtype_mysql_attribute"]["value"]; ?>" type="radio" id="<?php echo $page["dbtype_mysql_attribute"]["id"]; ?>" <?php if($page["dbtype_mysql_attribute"]["disabled"]){ ?>disabled="<?php echo $page["dbtype_mysql_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["dbtype_mysql_attribute"]["readonly"]){ ?>readonly="<?php echo $page["dbtype_mysql_attribute"]["readonly"]; ?>"<?php } ?> <?php if($page["dbtype_mysql_attribute"]["checked"]){ ?>checked="<?php echo $page["dbtype_mysql_attribute"]["checked"]; ?>"<?php } ?> /><?php if(strlen($page["dbtype_mysql"])>0){ ?><label for="<?php echo $page["dbtype_mysql_attribute"]["id"]; ?>"><?php echo $page["dbtype_mysql"]; ?></label><?php } ?><?php } ?>

<label for="dbtype_mysql">MySQL版</label>
</p>

<div class="section">
	<p class="sub">サイトID</p>
	<?php if(!isset($page["site_id_visible"]) || $page["site_id_visible"]){ ?><input name="<?php echo $page["site_id_attribute"]["name"]; ?>" value="<?php echo $page["site_id_attribute"]["value"]; ?>" type="text" <?php if($page["site_id_attribute"]["disabled"]){ ?>disabled="<?php echo $page["site_id_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["site_id_attribute"]["readonly"]){ ?>readonly="<?php echo $page["site_id_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

	
	<p class="sub">サイト名</p>
	<?php if(!isset($page["site_name_visible"]) || $page["site_name_visible"]){ ?><input name="<?php echo $page["site_name_attribute"]["name"]; ?>" value="<?php echo $page["site_name_attribute"]["value"]; ?>" type="text" <?php if($page["site_name_attribute"]["disabled"]){ ?>disabled="<?php echo $page["site_name_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["site_name_attribute"]["readonly"]){ ?>readonly="<?php echo $page["site_name_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

</div>

<div id="dbtype_mysql_config" class="section" style="margin-top:5px;">
	<p>データベースはあらかじめ作成しておいてください。文字コードはutf8を指定してください。</p>
	<p class="sub">MySQLホスト名</p>
	<?php if(!isset($page["mysql_host_visible"]) || $page["mysql_host_visible"]){ ?><input name="<?php echo $page["mysql_host_attribute"]["name"]; ?>" value="<?php echo $page["mysql_host_attribute"]["value"]; ?>" type="text" <?php if($page["mysql_host_attribute"]["disabled"]){ ?>disabled="<?php echo $page["mysql_host_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["mysql_host_attribute"]["readonly"]){ ?>readonly="<?php echo $page["mysql_host_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

	
	<p class="sub">MySQLポート番号（空欄可）</p>
	<?php if(!isset($page["mysql_port_visible"]) || $page["mysql_port_visible"]){ ?><input name="<?php echo $page["mysql_port_attribute"]["name"]; ?>" value="<?php echo $page["mysql_port_attribute"]["value"]; ?>" type="text" <?php if($page["mysql_port_attribute"]["disabled"]){ ?>disabled="<?php echo $page["mysql_port_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["mysql_port_attribute"]["readonly"]){ ?>readonly="<?php echo $page["mysql_port_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

	
	<p class="sub">MySQLデータベース名</p>
	<?php if(!isset($page["mysql_db_visible"]) || $page["mysql_db_visible"]){ ?><input name="<?php echo $page["mysql_db_attribute"]["name"]; ?>" value="<?php echo $page["mysql_db_attribute"]["value"]; ?>" type="text" <?php if($page["mysql_db_attribute"]["disabled"]){ ?>disabled="<?php echo $page["mysql_db_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["mysql_db_attribute"]["readonly"]){ ?>readonly="<?php echo $page["mysql_db_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

	
	<p class="sub">MySQLユーザ名</p>
	<?php if(!isset($page["mysql_user_visible"]) || $page["mysql_user_visible"]){ ?><input name="<?php echo $page["mysql_user_attribute"]["name"]; ?>" value="<?php echo $page["mysql_user_attribute"]["value"]; ?>" type="text" autocomplete="off" <?php if($page["mysql_user_attribute"]["disabled"]){ ?>disabled="<?php echo $page["mysql_user_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["mysql_user_attribute"]["readonly"]){ ?>readonly="<?php echo $page["mysql_user_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

	
	<p class="sub">MySQLパスワード</p>
	<?php if(!isset($page["mysql_pass_visible"]) || $page["mysql_pass_visible"]){ ?><input name="<?php echo $page["mysql_pass_attribute"]["name"]; ?>" value="<?php echo $page["mysql_pass_attribute"]["value"]; ?>" type="password" autocomplete="off" <?php if($page["mysql_pass_attribute"]["disabled"]){ ?>disabled="<?php echo $page["mysql_pass_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["mysql_pass_attribute"]["readonly"]){ ?>readonly="<?php echo $page["mysql_pass_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

</div>

<div style="text-align:center;">
	<?php if(!isset($page["create_button_visible"]) || $page["create_button_visible"]){ ?><input type="submit" value="作成" <?php if($page["create_button_attribute"]["disabled"]){ ?>disabled="<?php echo $page["create_button_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["create_button_attribute"]["readonly"]){ ?>readonly="<?php echo $page["create_button_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

</div>

</form><?php } ?>



</div>

<script type="text/javascript">
$("#dbtype_mysql").click(function(){
	$("#dbtype_mysql_config").show();
});

$("#dbtype_sqlite").click(function(){
	$("#dbtype_mysql_config").hide();
});

if($("#dbtype_sqlite").prop("checked")){
	$("#dbtype_mysql_config").hide();
};
</script>