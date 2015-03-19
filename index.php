<!DOCTYPE html>
<html lang="en-au">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title id="pageTitle">Cryptocurrency Payment of College Fees</title>


		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/studview.css">
	</head>
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12" id="forex-bar">
					<input type="text" name="idsearch" id="idsearch" placeholder="Search ID number" />
					<button class="btn btn-xs btn-primary" id="searchbtn"><span class="glyphicon glyphicon-search"></span> Search</button>
					<select id="currency">
						<option value="BTC">Bitcoin</option>
						<option value="LTC">Litecoin</option>
						<option value="DOGE">Dogecoin</option>
					</select>
					<p class="pull-right" id="forex">Loading exchange rate...</p>
				</div>
			</div>
			<div class="row" id="studdata">
				<div class="xs-hidden col-sm-2 col-md-2 col-lg-2" id="studpic">
					<img class="img-responsive" alt="student" src="img/students/empty.png">
				</div>
				<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10" id="studinfo">
					<input type="hidden" id="studrecordsid" value="" />
					<input type="hidden" id="collegeid" value="" />
					<p>ID Number: <span id="studid"></span></p>
					<p>Name: <span id="studname"></span></p>
					<p>Course: <span id="course"></span></p>
					<p>College: <span id="college"></span></p>
					<p id="jsontest"></p>
				</div>
			</div>
			<div class="row" id="functions">
				<div class="col-md-4">
					<p id="collegeinfo"><!-- College: SCS --></p>
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Fee Type</th>
									<th>Price</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody id="fees">
								<tr>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-md-8">
					<h1>Past Transactions</h1>
					<p id="prevtx"></p>
				</div>
			</div>
		</div>

<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Start Transaction</h4>
      </div>
      <div class="modal-body">
      	<input type="hidden" id="confFeeType">

        <p>Are you sure you want to pay <span class="convertedAmount"></span>?</p>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-primary" id="send-confirmed">Yes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="sendLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sendLabel">Send Payment</h4>
      </div>
      <div class="modal-body">
        <p class="text-center">Please send <span class="convertedAmount"></span> to the following wallet address:</p>
        <p class="text-center" id="deposit-address"></p>
        <div class="text-center" id="address-qr"></div>
      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>

		<script src="js/jquery-1.11.1.js"></script>
		<script src="js/jquery-qrcode-master/jquery.qrcode.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script>
		var activeCurrency = 'BTC'; // Default: Bitcoin
		var rate = 0; // 1 crypto per X PHP.
		var convertedAmount;

		// Loads the exchange rate.
		function getExchangeRate() 
		{
			$.ajax({
				url: 'backend/buse66_forex.php',
				type: 'GET',
				dataType: 'json',
				success: function (response) {
					var i = 0;
					while(response[i].currency != activeCurrency)
					{
						i++;
					}
					if (response[i].currency == activeCurrency)
					{
						$('#forex').html('1 ' + activeCurrency + ' = PHP ' + response[i].rate);
						rate = response[i].rate;
					}
				},
				error: function () {
					alert('PATAY');
				}
			});
		}

		// Searches for a student's information, including past transactions and current college fees
		function search() 
		{
			//alert($('#idsearch').val());
			$.ajax({
				url: 'backend/buse66_studsearch.php',
				type: 'POST',
				data: {
					studid: $('#idsearch').val()
				},
				dataType: 'json',
				beforeSend: function () {
					$('#studid').html('SEARCHING...');
					$('#studname').html('SEARCHING...');
					$('#course').html('SEARCHING...');
					$('#college').html('SEARCHING...');
				},
				success: function (response) 
				{
					if (response != false)
					{
						$('#studrecordsid').val(response.student.stud_records_id);
						$('#collegeid').val(response.student.collegeid);
						$('#studid').html(response.student.studid);
						$('#studname').html(response.student.fname + ' ' + response.student.lname);
						$('#course').html(response.student.course_name);
						$('#college').html(response.student.college_name);
						var fees = response.fees;
						var feesTable;

						for (var i = 0; i < fees.length; i++) {
							feesTable += '<tr><td>' + fees[i].fee_type + '</td><td>' + fees[i].amount.replace("$", "PHP ") + '</td><td><button class="btn btn-success pay-now" data-student="'+ response.student.stud_records_id +'" data-amount="'+ fees[i].amount.replace("$", "") +'" data-feetype="'+ fees[i].fee_type +'">Pay Now</button></td></tr>';
						}

						$('tbody#fees').html(feesTable);
						$('#prevtx').html(JSON.stringify(response));
					}
					else
					{
						$('#studrecordsid').val('');
						$('#collegeid').val('');
						$('#studid').html('');
						$('#studname').html('');
						$('#course').html('');
						$('#college').html('');
						$('tbody#fees').html('');
						$('#jsontest').html('Student not found.');
					}
					
				},
				error: function () 
				{
					alert('PATAY');
				}
			});
		}

		// Changes the currency to be used and sets the exchange rate for the new selected currency.
		function changeCurrency() 
		{
			activeCurrency = $('#currency').val();
			$('#forex').html('Getting the exchange rate, please wait');
			getExchangeRate();
		}

		// Shows the confirmation modal
		function confirmModal() 
		{
			var phpAmount = parseFloat($(this).data("amount"));
			$('#confFeeType').val($(this).data("feetype"));
		//	var student = $(this).data("student");
			convertedAmount = phpAmount * (1/rate);
			$('.convertedAmount').html(convertedAmount.toFixed(8) + ' ' + activeCurrency);
			$('#deposit-address').html('');
			$('#address-qr').html('');
			$('#confirmModal').modal();
		}

		function sendConfirmation() 
		{
			var student = $('#studrecordsid').val();
			var currency = activeCurrency; // BTC, LTC, DOGE...
			var amount = convertedAmount; // 0.xxxxxxxx, etc.
			var curRate = 1/rate;
			$.ajax({
				url: 'backend/buse66_txsend.php',
				type: 'POST',
				data: {
					studRecID: student,
					currency: currency,
					amount: amount,
					curRate: curRate
				},
				dataType: 'json',
				success: function (response) {
					$('#confirmModal').modal('hide');
					$('#deposit-address').html(response.deposit_address.data.address);
					$('#address-qr').qrcode({
    					"text": response.uri + ':' + response.deposit_address.data.address + '?amount=' + response.amount
					});
					$('#payModal').modal();
				},
				error: function () {
					alert('ERROR 500');
				}
			});
		}

		function payModal() {
			alert($(this).data("amount"));
		}

		$(function () {
			getExchangeRate();
			$('#currency').change(changeCurrency);
			$('#searchbtn').click(search);
			$(document).on('click', '.pay-now', confirmModal);
			$('#send-confirmed').click(sendConfirmation);
		});
		</script>
	</body>
</html>