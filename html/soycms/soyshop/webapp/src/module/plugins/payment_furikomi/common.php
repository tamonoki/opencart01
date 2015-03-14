<?php

class PaymentFurikomiCommon{

	/**
	 * 文言取得
	 */
	public static function getConfigText(){
		$array = SOYShop_DataSets::get("payment_furikomi.text", array(
			"account" => "○○銀行　△△支店\n普通　1234567\n口座名義　◇◇◇◇株式会社",
			"text" => "振込先情報\n--------------------------\n#ACCOUNT#\n--------------------------",
			"mail" => "※振込先は以下です。お間違えの無い様よろしくお願いします。\n" .
					"=================================\n" .
					"#ACCOUNT#\n" .
					"=================================\n"

		));
		return $array;
	}

}

?>
