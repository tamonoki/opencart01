<?php /* created 2015-03-14 01:16:45 */ ?>
<?php $page = HTMLPage::getPage(); ?>
<html>

<?php if(!isset($page["head_visible"]) || $page["head_visible"]){ ?><head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="robots" content="noindex">
<?php echo $page["head"]["metas"]; ?><?php echo $page["head"]["links"]; ?><?php echo $page["head"]["scripts"]; ?>
<title><?php echo $page["head"]["title"]; ?></title>
</head><?php } ?>


<body>

<?php if(!isset($page["AuthForm_visible"]) || $page["AuthForm_visible"]){ ?><?php $AuthForm = $page["AuthForm"]; ?><form name="AuthForm" action="<?php echo $page["AuthForm_attribute"]["action"]; ?>" method="<?php echo $page["AuthForm_attribute"]["method"]; ?>" <?php if($page["AuthForm_attribute"]["disabled"]){ ?>disabled="<?php echo $page["AuthForm_attribute"]["disabled"]; ?>"<?php } ?>><input type="hidden" name="soy2_token" value="<?php echo soy2_get_token(); ?>" />
	<div id="login_wrapper_for_IE">
		<div id="login_wrapper">
			<?php if(!isset($page["biglogo_visible"]) || $page["biglogo_visible"]){ ?><img src="<?php echo $page["biglogo_attribute"]["src"]; ?>" alt="logo" /><?php } ?>

			
			<div id="login_main"> 
				<div class="user">
					<p>ログインID（管理者ID）</p>
					<?php if(!isset($page["username_visible"]) || $page["username_visible"]){ ?><input name="<?php echo $page["username_attribute"]["name"]; ?>" value="<?php echo $page["username_attribute"]["value"]; ?>" id="login-id" type="text" <?php if($page["username_attribute"]["disabled"]){ ?>disabled="<?php echo $page["username_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["username_attribute"]["readonly"]){ ?>readonly="<?php echo $page["username_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

				</div>
				<div class="password">
					<p>パスワード <?php if(!isset($page["reminder_visible"]) || $page["reminder_visible"]){ ?><span><?php echo $page["reminder"]; ?></span><?php } ?>
</p>
					<?php if(!isset($page["password_visible"]) || $page["password_visible"]){ ?><input name="<?php echo $page["password_attribute"]["name"]; ?>" value="<?php echo $page["password_attribute"]["value"]; ?>" type="password" <?php if($page["password_attribute"]["disabled"]){ ?>disabled="<?php echo $page["password_attribute"]["disabled"]; ?>"<?php } ?> <?php if($page["password_attribute"]["readonly"]){ ?>readonly="<?php echo $page["password_attribute"]["readonly"]; ?>"<?php } ?> /><?php } ?>

				</div>
				<?php if(!isset($page["message_visible"]) || $page["message_visible"]){ ?><div class="failed_message"><?php echo $page["message"]; ?></div><?php } ?>

							
				<input class="login" type="submit" name="login" value="ログイン" />
			</div>
		</div>
	</div>
</form><?php } ?>


<script type="text/JavaScript">
if (top != self) { top.location.href =self.location.href }

$(function(){
	$("#login-id").focus();
});

</script>
</body>

</html>
