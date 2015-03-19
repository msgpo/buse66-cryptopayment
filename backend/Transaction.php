<?php
	include_once 'DBConnection.php';

	
      
	/**
	* 
	*/
	class Transaction extends DBConnection
	{
		public function addTransaction($student, $currency, $amount, $currentRate)
		{
			$currentTime = date('Y-m-d H:i:s');
			$walletlabel = 'AF-' . $student . '-' . date('Ymd-His');
			$this->query("INSERT INTO transactions (student_record_id, amount, currency_id, exchange_rate, wallet_label) VALUES ($student, $amount, $currency, $currentRate, '$walletlabel')");
			return $walletlabel;
		}

		public function getTransactions($student)
		{
			$this->get_results("SELECT tx.amount, tx.tx_date, c.name FROM transactions tx INNER JOIN cryptocurrencies c ON (tx.currency_id = c.id) WHERE tx.student_record_id = $student");
		}
	}
?>