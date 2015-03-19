<?php
	include_once 'DBConnection.php';

	/**
	* 
	*/
	class Currency extends DBConnection
	{
		public function getCurrencyInfo($unitcode)
		{
			return $this->get_row("SELECT * FROM cryptocurrencies WHERE unit = '$unitcode'");
		}
	}
?>