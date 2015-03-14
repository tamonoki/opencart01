<?php /* created 2015-03-14 01:20:10 */ ?>
<?php $calendar_display = HTMLPage::getPage("calendar_display"); ?>
				<section id="calender" class="info">
					<h1>定休日のご案内</h1>
					<?php if(!isset($calendar_display["current_calendar_visible"]) || $calendar_display["current_calendar_visible"]){ ?><?php echo $calendar_display["current_calendar"]; ?><?php } ?>

					<?php if(!isset($calendar_display["next_calendar_visible"]) || $calendar_display["next_calendar_visible"]){ ?><?php echo $calendar_display["next_calendar"]; ?><?php } ?>

					<p>色付の日は定休日です。<br>
					定休日にいただきましたご注文・お問い合わせは、休み明けにお返事いたします。</p>
				</section>
				