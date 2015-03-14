<?php /* created 2015-03-14 01:15:54 */ ?>
<?php $page = HTMLPage::getPage(); ?>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>初回設定 - SOY CMS</title>
	<link rel="stylesheet" href="/soycms/admin/css/init/style.css" />
</head>

<body>

<?php if(!isset($page["initform_visible"]) || $page["initform_visible"]){ ?><?php $initform = $page["initform"]; ?><form action="<?php echo $page["initform_attribute"]["action"]; ?>" method="<?php echo $page["initform_attribute"]["method"]; ?>" <?php if($page["initform_attribute"]["disabled"]){ ?>disabled="<?php echo $page["initform_attribute"]["disabled"]; ?>"<?php } ?>><input type="hidden" name="soy2_token" value="<?php echo soy2_get_token(); ?>" />
	<div id="init_wrapper_for_IE">
		<div id="init_wrapper">
			<?php if(!isset($page["biglogo_visible"]) || $page["biglogo_visible"]){ ?><img src="<?php echo $page["biglogo_attribute"]["src"]; ?>" alt="logo" /><?php } ?>

			<h1>初期管理者を作成します</h1>
			<?php if(!isset($page["message_db_visible"]) || $page["message_db_visible"]){ ?><div class="failed_message"><?php echo $page["message_db"]; ?></div><?php } ?>

			<div id="init_main"> 
				<div class="item">
					<p>初期管理者ID（ログインID, 4～30文字）</p>
					<?php if(!isset($page["userId_visible"]) || $page["userId_visible"]){ ?><input name="<?php echo $page["userId_attribute"]["name"]; ?>" <?php if($page["userId_attribute"]["value"]){ ?>value="<?php echo $page["userId_attribute"]["value"]; ?>"<?php } ?> autocomplete="<?php echo $page["userId_attribute"]["autocomplete"]; ?>" type="text" <?php if($page["userId_attribute"]["disabled"]){ ?>disabled="<?php echo $page["userId_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["userId_attribute"]["readonly"]){ ?>readonly="<?php echo $page["userId_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

					<?php if(!isset($page["message_userId_visible"]) || $page["message_userId_visible"]){ ?><div class="failed_message"><?php echo $page["message_userId"]; ?></div><?php } ?>

				</div>
				<div class="item">
					<p>パスワード（6～30文字）</p>
					<?php if(!isset($page["password_visible"]) || $page["password_visible"]){ ?><input type="<?php echo $page["password_attribute"]["type"]; ?>" name="<?php echo $page["password_attribute"]["name"]; ?>" <?php if($page["password_attribute"]["value"]){ ?>value="<?php echo $page["password_attribute"]["value"]; ?>"<?php } ?> autocomplete="<?php echo $page["password_attribute"]["autocomplete"]; ?>" <?php if($page["password_attribute"]["disabled"]){ ?>disabled="<?php echo $page["password_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["password_attribute"]["readonly"]){ ?>readonly="<?php echo $page["password_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

					<?php if(!isset($page["message_password_visible"]) || $page["message_password_visible"]){ ?><div class="failed_message"><?php echo $page["message_password"]; ?></div><?php } ?>

				</div>
				<div class="item">
					<p>パスワード（確認）</p>
					<?php if(!isset($page["password_confirm_visible"]) || $page["password_confirm_visible"]){ ?><input type="<?php echo $page["password_confirm_attribute"]["type"]; ?>" name="<?php echo $page["password_confirm_attribute"]["name"]; ?>" <?php if($page["password_confirm_attribute"]["value"]){ ?>value="<?php echo $page["password_confirm_attribute"]["value"]; ?>"<?php } ?> autocomplete="<?php echo $page["password_confirm_attribute"]["autocomplete"]; ?>" <?php if($page["password_confirm_attribute"]["disabled"]){ ?>disabled="<?php echo $page["password_confirm_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["password_confirm_attribute"]["readonly"]){ ?>readonly="<?php echo $page["password_confirm_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

					<?php if(!isset($page["message_password_confirm_visible"]) || $page["message_password_confirm_visible"]){ ?><div class="failed_message"><?php echo $page["message_password_confirm"]; ?></div><?php } ?>

				</div>
				<?php if(!isset($page["submit_button_visible"]) || $page["submit_button_visible"]){ ?><input type="<?php echo $page["submit_button_attribute"]["type"]; ?>" name="<?php echo $page["submit_button_attribute"]["name"]; ?>" value="<?php echo $page["submit_button_attribute"]["value"]; ?>" class="submit_button" <?php if($page["submit_button_attribute"]["disabled"]){ ?>disabled="<?php echo $page["submit_button_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["submit_button_attribute"]["readonly"]){ ?>readonly="<?php echo $page["submit_button_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

			</div>
		</div>
	</div>
</form><?php } ?>


</body>

</html>