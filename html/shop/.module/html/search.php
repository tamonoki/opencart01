<?php /* this script is generated by soyshop. */
function soyshop_search($html,$htmlObj){

ob_start();

echo <<<HTML
<section id="search">
  <form method="GET" action="/shop/search.html">
  <h1>キーワードから探す</h1>
  <p>
    <label for="textfield"></label>
    <input type="hidden" name="type" value="name">
    <input type="text" name="q" id="textfield" class="w130">
    <input type="image" name="button" src="/shop/themes/common/images/btn_search.png" alt="検索">
  </p>
  </form>
</section>
HTML;

ob_end_flush();

}
?>