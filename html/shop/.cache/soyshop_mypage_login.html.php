<?php /* created 2015-03-14 01:20:09 */ ?>
<?php $soyshop_mypage_login = HTMLPage::getPage("soyshop_mypage_login"); ?>
			<?php if(!isset($soyshop_mypage_login["not_loggedin_visible"]) || $soyshop_mypage_login["not_loggedin_visible"]){ ?>
			<p>こんにちは、ゲスト様（<?php if(!isset($soyshop_mypage_login["login_link_visible"]) || $soyshop_mypage_login["login_link_visible"]){ ?><?php if(strlen($soyshop_mypage_login["login_link_attribute"]["href"])>0){ ?><a href="<?php echo $soyshop_mypage_login["login_link_attribute"]["href"]; ?>"><?php } ?>ログイン<?php if(strlen($soyshop_mypage_login["login_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
）</p>
			<?php } ?>

			<?php if(!isset($soyshop_mypage_login["is_loggedin_visible"]) || $soyshop_mypage_login["is_loggedin_visible"]){ ?>
			<p>こんにちは、<?php if(!isset($soyshop_mypage_login["user_name_visible"]) || $soyshop_mypage_login["user_name_visible"]){ ?><?php echo $soyshop_mypage_login["user_name"]; ?><?php } ?>
様（<?php if(!isset($soyshop_mypage_login["logout_link_visible"]) || $soyshop_mypage_login["logout_link_visible"]){ ?><?php if(strlen($soyshop_mypage_login["logout_link_attribute"]["href"])>0){ ?><a href="<?php echo $soyshop_mypage_login["logout_link_attribute"]["href"]; ?>"><?php } ?>ログアウト<?php if(strlen($soyshop_mypage_login["logout_link_attribute"]["href"])>0){ ?></a><?php } ?><?php } ?>
）</p>
			<?php } ?>

			