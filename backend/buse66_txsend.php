<?php
	include_once 'buse66_connectvars.php';
	include_once 'buse66_blockio_keys.php';
	require_once 'block_io/lib/block_io.php';
	include_once 'Currency.php';
	include_once 'Transaction.php';

	$student = $_POST['studRecID'];
	$currency = $_POST['currency'];
	$amount = $_POST['amount'];
	$currentRate = $_POST['curRate'];
	$dbcCurrency = new Currency(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$dbcTx = new Transaction(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if ($currency == 'BTC') 
	{
		$apikey = BITCOIN_API_KEY;
	}
	if ($currency == 'DOGE') 
	{
		$apikey = DOGECOIN_API_KEY;
	}
	if ($currency == 'LTC') 
	{
		$apikey = LITECOIN_API_KEY;
	}
	$block_io = new BlockIo($apikey, BLOCK_IO_PIN, VERSION);

	// get Currency ID
	$curRow = $dbcCurrency->getCurrencyInfo($currency);
	$curID = $curRow['id'];
	$curURI = $curRow['uri'];

	$walletLabel = $dbcTx->addTransaction($student, $curID, $amount, $currentRate);
	$deposit_address = $block_io->get_new_address(array('label' => $walletLabel));

	$return = array();
	$address_decoded = $deposit_address;
	$return['deposit_address'] = $address_decoded;
	$return['uri'] = $curURI;
	$return['amount'] = $amount;
	$return['qrlabel'] = 'Assessment Fee Payment';
	echo json_encode($return);

?>