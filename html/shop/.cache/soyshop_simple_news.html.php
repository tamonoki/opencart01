<?php /* created 2015-03-14 01:20:09 */ ?>
<?php $soyshop_simple_news = HTMLPage::getPage("soyshop_simple_news"); ?>
				<section id="news">
				<h1>新着情報</h1>
					<ul>
						<?php if(!isset($soyshop_simple_news["news_list_visible"]) || $soyshop_simple_news["news_list_visible"]){ ?><?php $news_list_counter = -1;foreach($soyshop_simple_news["news_list"] as $key => $news_list){ $news_list_counter++; ?>
						<li><?php if(!isset($news_list["title_visible"]) || $news_list["title_visible"]){ ?><?php echo $news_list["title"]; ?><?php } ?>

						<time pubdate>（<?php if(!isset($news_list["create_date_visible"]) || $news_list["create_date_visible"]){ ?><?php echo $news_list["create_date"]; ?><?php } ?>
）</time></li>
						<?php } ?><?php } ?>

					</ul>
				</section>
				