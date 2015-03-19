<?php
	include_once 'buse66_connectvars.php';
	include_once 'Student.php';
	include_once 'Fees.php';
	include_once 'Transaction.php';

	$studid = $_POST['studid'];
	$dbcStudent = new Student(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$dbcFees = new Fees(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$dbcTx = new Transaction(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$result = array();
	$student = $dbcStudent->search($studid);
	$collegeID = $student['college_id'];
	$stud_rec = $student['stud_records_id'];

	$fees = $dbcFees->getFees($collegeID);
	$txs = $dbcTx->getTransactions($stud_rec);

	$result['student'] = $student;
	$result['fees'] = $fees;
	$result['transactions'] = $txs;
	echo json_encode($result);
?>