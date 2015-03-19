<?php
	include_once 'DBConnection.php';

	class Fees extends DBConnection
	{
		/**
		 *
		 */
		public function getFees($collegeID)
		{
			return $this->get_results("SELECT amount, fee_type FROM fees WHERE college_id = $collegeID");
		}
	}

?>