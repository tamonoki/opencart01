<?php /* created 2015-03-14 01:16:40 */ ?>
<?php $page = HTMLPage::getPage(); ?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>リダイレクト中 - SOY CMS</title>
	<link rel="stylesheet" href="/soycms/admin/css/login/style.css" />
	<meta http-equiv="refresh" content="5;URL=<?php if(!isset($page["redirect_link_visible"]) || $page["redirect_link_visible"]){ ?><?php echo $page["redirect_link"]; ?><?php } ?>
">
</head>

<body>

	<div id="login_wrapper_for_IE">
		<div id="login_wrapper">
			<?php if(!isset($page["biglogo_visible"]) || $page["biglogo_visible"]){ ?><img alt="logo" src="<?php echo $page["biglogo_attribute"]["src"]; ?>" /><?php } ?>

			<p>
				ユーザーID <?php if(!isset($page["user_id_visible"]) || $page["user_id_visible"]){ ?><strong><?php echo $page["user_id"]; ?></strong><?php } ?>
 で初期管理者を作成しました。<br />
				次の画面でログインしてください。<br />
				自動でログイン画面に移らない場合は次のリンクをクリックしてください。→<a href="/soycms/admin/index.php">ログイン</a>
			</p>
		</div>
	</div>

</body>

</html>