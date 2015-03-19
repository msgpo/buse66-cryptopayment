<?php
	include_once 'phpeso_conversions.php';

	$exchange_rates = array();
	$php_btc = array('currency' => 'BTC', 'rate' => btc_to_phpeso(1));
	$php_ltc = array('currency' => 'LTC', 'rate' => ltc_to_phpeso(1));
	// Temporary
	$php_doge = array('currency' => 'DOGE', 'rate' => 1);

	$exchange_rates[] = $php_btc;
	$exchange_rates[] = $php_ltc;
	$exchange_rates[] = $php_doge;
	echo json_encode($exchange_rates);
?>