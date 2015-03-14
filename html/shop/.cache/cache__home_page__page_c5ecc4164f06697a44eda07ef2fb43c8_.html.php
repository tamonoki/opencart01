<?php /* created 2015-03-14 01:20:09 */ ?>
<?php $page = HTMLPage::getPage(); ?>
<!DOCTYPE html> 
<html lang="ja"> 
<head>
<?php echo $page["page_head_insert"]; ?> 
<meta charset="UTF-8" />
<title><?php echo htmlspecialchars($page["page_head_title"],ENT_QUOTES); ?></title>
<?php if(isset($page["page_head_meta"]["author"])){  $old = "";  $array = $page["page_head_meta"]["author"];  if($array["content"] == false){ $content = $array["insert"] . $old . $array["append"]; }else{ $content = $array["content"]; } }else{ $content =  ""; } echo '<meta name="author" content="'.htmlspecialchars($content,ENT_QUOTES).'" />' . "\n"; ?> 
<?php if(isset($page["page_head_meta"]["copyright"])){  $old = "Copyright (C) ";  $array = $page["page_head_meta"]["copyright"];  if($array["content"] == false){ $content = $array["insert"] . $old . $array["append"]; }else{ $content = $array["content"]; } }else{ $content =  "Copyright (C) "; } echo '<meta name="copyright" content="'.htmlspecialchars($content,ENT_QUOTES).'" />' . "\n"; ?> 
<?php if(isset($page["page_head_meta"]["keywords"])){  $old = "";  $array = $page["page_head_meta"]["keywords"];  if($array["content"] == false){ $content = $array["insert"] . $old . $array["append"]; }else{ $content = $array["content"]; } }else{ $content =  ""; } echo '<meta name="keywords" content="'.htmlspecialchars($content,ENT_QUOTES).'" />' . "\n"; ?> 
<?php if(isset($page["page_head_meta"]["description"])){  $old = "";  $array = $page["page_head_meta"]["description"];  if($array["content"] == false){ $content = $array["insert"] . $old . $array["append"]; }else{ $content = $array["content"]; } }else{ $content =  ""; } echo '<meta name="description" content="'.htmlspecialchars($content,ENT_QUOTES).'" />' . "\n"; ?> 
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]--> 
<link href="/shop/themes/common/css/common.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/shop/themes/common/js/rollover.js"></script>
<script type="text/javascript" src="/shop/themes/common/js/jquery.min.js"></script>
<script type="text/javascript" src="/shop/themes/common/js/jqueryAutoHeight.js"></script><!--ボックスの高さを合わせる-->
<script type="text/javascript" src="/shop/themes/common/js/selectivizr.js"></script><!--IEで一部CSS3を対応-->
<script type="text/javascript" src="/shop/themes/common/js/DD_belatedPNG_0.0.8a.js"></script><!--IE6の透過png対応-->
<script type="text/javascript">
jQuery(function($){
		$("#recommended_items .item_list article").autoHeight();
		$("#new_items .item_list article").autoHeight();
});
</script>
<!--[if IE 6]>
<script src="/shop/themes/common/js/DD_belatedPNG.js"></script>
<script>
	DD_belatedPNG.fix('img,section h1');
</script>
<![endif]-->
<?php echo $page["page_head_append"]; ?>
</head>

<body id="top">
<div id="container">
	<header>
		<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/html/header.php")){include_once("/vagrant/html/shop/.module/html/header.php");}else{@SOY2::import("module.site.html.header",".php");} ?>
		<hgroup>
			<h1><a href="/shop/">インテリアショップLBD</a></h1>
			<h2>国産からインポートまで、店長絶賛のインテリア小物、家具、雑貨まで</h2>
		</hgroup>
		<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_header")){echo call_user_func("soyshop_header",$tmp_html,$this);}else{ echo "function not found : soyshop_header";} ?>
		<aside>
			<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/common/parts/cart.php")){include_once("/vagrant/html/shop/.module/common/parts/cart.php");}else{@SOY2::import("module.site.common.parts.cart",".php");} ?>
			<section id="head_cart">
				<p class="head_btn"><a cms:id="cart_link"><img src="/shop/themes/common/images/btn_cart_off.png" alt="カートを見る"></a></p>
				<h1>現在のカートの中身</h1>
				<!-- cms:id="full_cart" -->
				<p id="sum">商品点数：<!-- cms:id="item_total" -->0<!-- /cms:id="item_total" --> 点｜小計：¥ <!-- cms:id="cart_total" -->0<!-- /cms:id="cart_total" --></p>
				<!-- /cms:id="full_cart" -->
				<!-- cms:id="empty_cart" -->
				<p id="sum">商品点数：0 点｜小計：¥ 0</p>
				<!-- /cms:id="empty_cart" -->
			</section>
			<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_parts_cart")){echo call_user_func("soyshop_parts_cart",$tmp_html,$this);}else{ echo "function not found : soyshop_parts_cart";} ?>
			<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/common/parts/mypage_login.php")){include_once("/vagrant/html/shop/.module/common/parts/mypage_login.php");}else{@SOY2::import("module.site.common.parts.mypage_login",".php");} ?>
			<!-- cms:id="not_loggedin" -->
			<section id="login">
				<p>新規会員登録は<a cms:id="register_link">こちら</a></p>
				<p><a cms:id="login_link"><img src="/shop/themes/common/images/btn_login_off.png" alt="会員ログイン"></a></p>
			</section>
			<!-- /cms:id="not_loggedin" -->
			<!-- cms:id="is_loggedin" -->
			<section id="login">
				<p><a cms:id="logout_link">ログアウト</a></p>
				<p><a cms:id="order_link"><img src="/shop/themes/common/images/btn_mypage_off.png" alt="マイページ"></a></p>
			</section>
			<!-- /cms:id="is_loggedin" -->
			<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_parts_mypage_login")){echo call_user_func("soyshop_parts_mypage_login",$tmp_html,$this);}else{ echo "function not found : soyshop_parts_mypage_login";} ?>
		</aside>
		<nav>
			<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/html/navi.php")){include_once("/vagrant/html/shop/.module/html/navi.php");}else{@SOY2::import("module.site.html.navi",".php");} ?>
			<ul>
				<li><a href="#">ご利用ガイド</a></li>
				<li><a href="#">よくあるご質問</a></li>
				<li><a href="#">お問い合わせ</a></li>
			</ul>
			<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_navi")){echo call_user_func("soyshop_navi",$tmp_html,$this);}else{ echo "function not found : soyshop_navi";} ?>
			<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/common/parts/mypage_login2.php")){include_once("/vagrant/html/shop/.module/common/parts/mypage_login2.php");}else{@SOY2::import("module.site.common.parts.mypage_login2",".php");} ?>
			<!-- cms:id="not_loggedin" -->
			<p>こんにちは、ゲスト様（<a cms:id="login_link">ログイン</a>）</p>
			<!-- /cms:id="not_loggedin" -->
			<!-- cms:id="is_loggedin" -->
			<p>こんにちは、<!-- cms:id="user_name" -->とみた<!-- /cms:id="user_name" -->様（<a cms:id="logout_link">ログアウト</a>）</p>
			<!-- /cms:id="is_loggedin" -->
			<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_parts_mypage_login2")){echo call_user_func("soyshop_parts_mypage_login2",$tmp_html,$this);}else{ echo "function not found : soyshop_parts_mypage_login2";} ?>
		</nav>
	</header>

	<div id="wrapper" class="clearfix">
		<div id="main">
			<article>
				<section id="keyvisual">
					<p><img src="/shop/themes/photos/keyvisual.jpg" width="480" height="292" alt="苔オブジェ"></p>
				</section>

				<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/common/simple_news.php")){include_once("/vagrant/html/shop/.module/common/simple_news.php");}else{@SOY2::import("module.site.common.simple_news",".php");} ?>
				<section id="news">
				<h1>新着情報</h1>
					<ul>
						<!-- block:id="news_list" -->
						<li><!-- cms:id="title" -->新着テキスト新着テキスト新着テキスト新着テキスト新着テキスト新着<!-- /cms:id="title" -->
						<time pubdate>（<!-- cms:id="create_date" -->2011.01.15<!-- /cms:id="create_date" -->）</time></li>
						<!-- /block:id="news_list" -->
					</ul>
				</section>
				<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_simple_news")){echo call_user_func("soyshop_simple_news",$tmp_html,$this);}else{ echo "function not found : soyshop_simple_news";} ?>

				<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/common/recommend_items.php")){include_once("/vagrant/html/shop/.module/common/recommend_items.php");}else{@SOY2::import("module.site.common.recommend_items",".php");} ?>
				<section id="recommended_items">
					<h1>おすすめ商品</h1>
					<div class="item_list">
						<!-- block:id="recommend_item_list" -->
						<article>
							<a href="detail1.html" cms:id="item_link"><img cms:id="item_small_image" src="/shop/themes/photos/list_sample1.jpg" width="150" height="113" alt="苔オブジェ">
							<h1 cms:id="item_name">苔オブジェ</h1></a>
							<p class="tag"><!-- cms:id="custom_icon_field" /--></p>
							<p class="price">¥<!-- cms:id="item_price" -->2,750<!-- /cms:id="item_price" --></p>
							<p class="text" cms:id="item_copy1">ロゴをかたどったオブジェ、本物の苔を植えています。</p>
							<p class="new" cms:id="this_is_new"><img src="/shop/themes/common/images/new_icon.png" alt="NEW!"></p>
							<p class="more"><a href="" cms:id="item_link*">&gt;&gt;詳細を見る</a></p>
						</article>
					<!-- /block:id="recommend_item_list" -->
					</div>
				</section>
				<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_recommend_items")){echo call_user_func("soyshop_recommend_items",$tmp_html,$this);}else{ echo "function not found : soyshop_recommend_items";} ?>
				
			</article>
		<!-- /#main --></div>
		
		<div id="sub1">
			<aside>
				<section id="category">
					<h1>カテゴリから探す</h1>
					<ul>
						<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/common/category_navigation.php")){include_once("/vagrant/html/shop/.module/common/category_navigation.php");}else{@SOY2::import("module.site.common.category_navigation",".php");} ?>
						<li><a href="#" cms:id="category_link"><!-- cms:id="category_name" -->ダミーカテゴリ<!-- /cms:id="category_name" --></a>
						<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_category_navigation")){echo call_user_func("soyshop_category_navigation",$tmp_html,$this);}else{ echo "function not found : soyshop_category_navigation";} ?>
					</ul>
				</section>
				
				<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/html/search.php")){include_once("/vagrant/html/shop/.module/html/search.php");}else{@SOY2::import("module.site.html.search",".php");} ?>
				<section id="search">
					<h1>商品名から探す</h1>
					<p>
						<label for="textfield"></label>
						<input type="hidden" name="type" value="name">
						<input type="text" name="q" id="textfield" class="w130">
					<input type="image" name="button" src="/shop/themes/common/images/btn_search.png" alt="検索"></p>
				</section>
				<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_search")){echo call_user_func("soyshop_search",$tmp_html,$this);}else{ echo "function not found : soyshop_search";} ?>
				
				<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/common/calendar_display.php")){include_once("/vagrant/html/shop/.module/common/calendar_display.php");}else{@SOY2::import("module.site.common.calendar_display",".php");} ?>
				<section id="calender" class="info">
					<h1>定休日のご案内</h1>
					<!-- cms:id="current_calendar" -->
					<table cellspacing="0" cellpadding="0">
						<caption>2011年1月</caption>
						<tr>
							<th class="sun" scope="col">日</th>
							<th scope="col">月</th>
							<th scope="col">火</th>
							<th scope="col">水</th>
							<th scope="col">木</th>
							<th scope="col">金</th>
							<th class="sat" scope="col">土</th>
						</tr>
						<tr>
							<td class="sun">&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td class="sat close">1</td>
						</tr>
						<tr>
							<td class="sun close">2</td>
							<td class="close">3</td>
							<td>4</td>
							<td class="close">5</td>
							<td>6</td>
							<td>7</td>
							<td class="sat">8</td>
						</tr>
						<tr>
							<td class="sun close">9</td>
							<td class="close">10</td>
							<td>11</td>
							<td class="close">12</td>
							<td>13</td>
							<td>14</td>
							<td class="sat">15</td>
						</tr>
						<tr>
							<td class="sun close">16</td>
							<td>17</td>
							<td>18</td>
							<td class="close">19</td>
							<td>20</td>
							<td>21</td>
							<td class="sat">22</td>
						</tr>
						<tr>
							<td class="sun close">23</td>
							<td>24</td>
							<td>25</td>
							<td class="close">26</td>
							<td>27</td>
							<td>28</td>
							<td class="sat">29</td>
						</tr>
						<tr>
							<td class="sun close">30</td>
							<td>31</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td class="sat">&nbsp;</td>
						</tr>
					</table>
					<!-- /cms:id="current_calendar" -->
					<!-- cms:id="next_calendar" -->
					<table cellspacing="0" cellpadding="0">
						<caption>2011年2月</caption>
						<tr>
							<th class="sun" scope="col">日</th>
							<th scope="col">月</th>
							<th scope="col">火</th>
							<th scope="col">水</th>
							<th scope="col">木</th>
							<th scope="col">金</th>
							<th class="sat" scope="col">土</th>
						</tr>
						<tr>
							<td class="sun">&nbsp;</td>
							<td>&nbsp;</td>
							<td>1</td>
							<td class="close">2</td>
							<td>3</td>
							<td>4</td>
							<td class="sat">5</td>
						</tr>
						<tr>
							<td class="sun close">6</td>
							<td>7</td>
							<td>8</td>
							<td class="close">9</td>
							<td>10</td>
							<td class="close">11</td>
							<td class="sat">12</td>
						</tr>
						<tr>
							<td class="sun close">13</td>
							<td>14</td>
							<td>15</td>
							<td class="close">16</td>
							<td>17</td>
							<td>18</td>
							<td class="sat">19</td>
						</tr>
						<tr>
							<td class="sun close">20</td>
							<td>21</td>
							<td>22</td>
							<td class="close">23</td>
							<td>24</td>
							<td>25</td>
							<td class="sat">26</td>
						</tr>
						<tr>
							<td class="sun close">27</td>
							<td>28</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td class="sat">&nbsp;</td>
						</tr>
					</table>
					<!-- /cms:id="next_calendar" -->
					<p>色付の日は定休日です。<br>
					定休日にいただきましたご注文・お問い合わせは、休み明けにお返事いたします。</p>
				</section>
				<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_calendar_display")){echo call_user_func("soyshop_calendar_display",$tmp_html,$this);}else{ echo "function not found : soyshop_calendar_display";} ?>
				
			</aside>
		<!-- /#sub1 --></div>
	<!-- /#wrapper --></div>
		
	<div id="sub2">
		<aside>
			<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/common/new_items.php")){include_once("/vagrant/html/shop/.module/common/new_items.php");}else{@SOY2::import("module.site.common.new_items",".php");} ?>
			<section id="new">
				<h1>新着商品</h1>
				<ul>
					<!-- block:id="new_item_list" -->
					<li>
						<a href="detail2.html" cms:id="item_link"><img cms:id="item_small_image" src="/shop/themes/photos/ranking_sample1.jpg" alt=""></a>
						<p><a href="detail2.html" cms:id="item_link*"><!-- cms:id="item_name" -->ナノブロック<!-- /cms:id="item_name" --></a></p>
					</li>
				<!-- /block:id="new_item_list" -->
				</ul>
			</section>
			<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_new_items")){echo call_user_func("soyshop_new_items",$tmp_html,$this);}else{ echo "function not found : soyshop_new_items";} ?>

			<!-- cms:ignore -->
			<section id="community" class="info">
				<h1>コミュニティ</h1>
				<ul>
					<li class="twitter">
						<p class="btn"><a href="#"><img src="/shop/themes/common/images/btn_twitter_follow.png" alt="フォローする"></a></p>
						<a href="#">@interior_LBD（Twitter）</a>
					</li>
					<li class="facebook">
						<a href="#">インテリアショップLBDのファンページ（Facebook）</a>
						<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fexample.com%2Fpage%2Fto%2Flike&amp;layout=standard&amp;show_faces=true&amp;width=200&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:80px;" allowTransparency="true"></iframe>
					</li>
					<li class="hatena">
						<a href="#">インテリアショップLBD（はてなブックマーク）</a>
					</li>
				</ul>
			</section>
			<!-- /cms:ignore -->
			
			<section id="pr" class="info">
				<h1>PR</h1>
				<ul>
					<li><a href="http://www.houren.so/" target="_blank"><img src="/shop/themes/photos/bnr_sample_houren_so.png" alt="現場で、写真で、報告、連絡、相談"></a></li>
					<li><a href="http://www.soyshop.net/" target="_blank"><img src="/shop/themes/photos/bnr_sample_soyshop.png" alt="本気の人のためのEC構築システム。オープンソースで公開中"></a></li>
					<li><a href="#"><img src="/shop/themes/photos/bnr_sample.jpg" alt=""></a></li>
				</ul>
			</section>
	
		</aside>
	<!-- /#sub2 --></div>
<!-- /#container --></div>

<footer>
	<aside>
		<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/html/shop_info.php")){include_once("/vagrant/html/shop/.module/html/shop_info.php");}else{@SOY2::import("module.site.html.shop_info",".php");} ?>
		<div class="footer_column">
			<section>
				<h1>ショップ情報</h1>
				<h2>インテリアショップLBD</h2>
				<p>TEL：075-123-456<br>
					FAX：075-123-457<br>
					営業時間：10:00?18:00<br>
				定休日：水・日・祝日</p>
				<p>定休日にいただいたご注文やご質問へのお返事は、翌営業日<br>
					以降のお返事となりますのでご了承ください。<br>
					ご注文は24時間受け付けております。</p>
			</section>
		</div>
		<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_shop_info")){echo call_user_func("soyshop_shop_info",$tmp_html,$this);}else{ echo "function not found : soyshop_shop_info";} ?>
		<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/html/payment.php")){include_once("/vagrant/html/shop/.module/html/payment.php");}else{@SOY2::import("module.site.html.payment",".php");} ?>
		<div class="footer_column">
			<section>
				<h1>お支払いについて</h1>
				<dl>
					<dt>
						<h2>代金引換</h2>
					</dt>
					<dd>説明テキスト説明テキスト説明テキスト説明テキスト説明テキスト説明テキスト</dd>
					<dt>
						<h2>銀行振込</h2>
					</dt>
					<dd>説明テキスト説明テキスト説明テキスト説明テキスト説明テキスト説明テキスト</dd>
					<dt>
						<h2>クレジットカード</h2>
					</dt>
					<dd>説明テキスト説明テキスト説明テキスト説明テキスト説明テキスト説明テキスト</dd>
					<dt>
						<h2>コンビニ決済</h2>
					</dt>
					<dd>説明テキスト説明テキスト説明テキスト説明テキスト説明テキスト説明テキスト</dd>
				</dl>
			</section>
		</div>
		<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_payment")){echo call_user_func("soyshop_payment",$tmp_html,$this);}else{ echo "function not found : soyshop_payment";} ?>
		<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/html/method.php")){include_once("/vagrant/html/shop/.module/html/method.php");}else{@SOY2::import("module.site.html.method",".php");} ?>
		<div class="footer_column">
			<section>
				<h1>配送料金について</h1>
				<p>お買い物の合計金額が○○○円以上で送料無料となります。<br>
					それ以下のお買い物の場合の配送料金は<a href="#">こちら</a>をご覧ください。</p>
			</section>
			
			<section>
				<h1>商品の到着について</h1>
				<p>ご注文いただいてから5営業日以内に発送いたします。</p>
			</section>
		</div>
		<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_method")){echo call_user_func("soyshop_method",$tmp_html,$this);}else{ echo "function not found : soyshop_method";} ?>
	</aside>
	
	<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/html/footer.php")){include_once("/vagrant/html/shop/.module/html/footer.php");}else{@SOY2::import("module.site.html.footer",".php");} ?>
	<div id="footer_navi">
		<ul>
			<li><a href="#">ご利用ガイド</a></li>
			<li><a href="#">よくあるご質問</a></li>
			<li><a href="#">特定商取引法に基づく表記</a></li>
			<li><a href="#">プライバシーポリシー</a></li>
		</ul>
	</div>
	<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_footer")){echo call_user_func("soyshop_footer",$tmp_html,$this);}else{ echo "function not found : soyshop_footer";} ?>
	
	<?php ob_start(); if(file_exists("/vagrant/html/shop/.module/html/copyright.php")){include_once("/vagrant/html/shop/.module/html/copyright.php");}else{@SOY2::import("module.site.html.copyright",".php");} ?>
	<p><small>Copyright &copy; interior LBD, All Rights Reserved.</small></p>
	<?php $tmp_html=ob_get_contents();ob_end_clean(); if(function_exists("soyshop_copyright")){echo call_user_func("soyshop_copyright",$tmp_html,$this);}else{ echo "function not found : soyshop_copyright";} ?>
</footer>
</body>
</html>