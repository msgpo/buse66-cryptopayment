<?php
	function phpeso_to_btc($phpesos)
	{
		$ch = curl_init();

		if ($phpesos < 0) 
		{
			return "Invalid amount";
		}
		else if ($phpesos == 0)
		{
			return 0;
		}
		else
		{
			$url = 'https://api.coinbase.com/v1/currencies/exchange_rates';
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = json_decode(curl_exec($ch));

			return $phpesos * $result->php_to_btc;
		}
	}

	function btc_to_phpeso($phpesos)
	{
		$ch = curl_init();

		if ($phpesos < 0) 
		{
			return "Invalid amount";
		}
		else if ($phpesos == 0)
		{
			return 0;
		}
		else
		{
			$url = 'https://api.coinbase.com/v1/currencies/exchange_rates';
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = json_decode(curl_exec($ch));

			return $result->btc_to_php;
		}
	}

	function phpeso_to_doge($phpesos)
	{
		$ch = curl_init();

		if ($phpesos < 0) 
		{
			return "Invalid amount";
		} 
		else 
		{
			$btc_to_doge_url = 'http://pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=132';
			curl_setopt($ch,CURLOPT_URL, $btc_to_doge_url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$btdres = json_decode(curl_exec($ch));

			// $btdres returns DOGE to BTC rate
			$btcdogerate = 1/$btdres->return->markets->DOGE->lasttradeprice;

			return phpeso_to_btc($phpesos) * $btcdogerate;
		}
		
	}

	function phpeso_to_ltc($phpesos)
	{
		$ch = curl_init();

		if ($phpesos < 0) 
		{
			return "Invalid amount";
		} 
		else 
		{
			$btc_to_ltc_url = 'http://pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=3';
			curl_setopt($ch,CURLOPT_URL, $btc_to_ltc_url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$btdres = json_decode(curl_exec($ch));

			// $btdres returns LTC to BTC rate
			$btcltcrate = 1/$btdres->return->markets->LTC->lasttradeprice;

			return phpeso_to_btc($phpesos) * $btcltcrate;
		}
		
	}

	function ltc_to_phpeso($phpesos)
	{
		$ch = curl_init();

		if ($phpesos < 0) 
		{
			return "Invalid amount";
		}
		else if ($phpesos == 0)
		{
			return 0;
		}
		else
		{
			$btc_to_ltc_url = 'http://pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=3';
			curl_setopt($ch,CURLOPT_URL, $btc_to_ltc_url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$btdres = json_decode(curl_exec($ch));

			// $btdres returns LTC to BTC rate
			$btcltcrate = $btdres->return->markets->LTC->lasttradeprice;

			return btc_to_phpeso($phpesos) * $btcltcrate;
		}
	}
?>