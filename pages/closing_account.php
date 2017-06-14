<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>UOS25</title>

	<!-- Bootstrap Core CSS -->
	<link href="../css/bootstrap.css" rel="stylesheet">

	<!-- MetisMenu CSS -->
	<link href="../css/metisMenu.css" rel="stylesheet">

	<!-- DataTables CSS -->
	<link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

	<!-- DataTables Responsive CSS -->
	<link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="../css/sb-admin-2.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="../css/content.css" rel="stylesheet" type="text/css">
</head>

<body class="whitebody">
	<?PHP
	include_once( "./db.php" );

	function do_fetch1( $s ) {
		$row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC );
		echo "<td style='text-align: right'>" . ( $row[ 'SUM' ] ? htmlentities( $row[ 'SUM' ] ) : '0' ) . "</td>";
	}

	function do_fetch2( $s ) {
		$row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC );
		echo "<td style='text-align: right'>" . ( $row[ 'SUM' ] ? htmlentities( $row[ 'SUM' ] ) : '0' ) . "</td>";
		echo "<td>" . date( 'Y-m-d' ) . "</td>";
	}
	?>

	<div class="col-lg-12">
		<h1 class="page-header">결산관리</h1>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<strong>매출입력</strong>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<form onsubmit="return false;" method="POST">
							<div id="form1" class="form-group">
								<label>구분</label>
								<div>
									<label class="radio-inline">
   									<input type="radio" name="flag" id="cash" value="00" checked>현금
   								</label>
								
									<label class="radio-inline">
   									<input type="radio" name="flag" id="card" value="01">카드
   								</label>
								
								</div>
							</div>
							<div class="form-group">
								<label>수익</label>
								<input type="number" id="profit" name="profit" class="form-control">
							</div>
							<div class="form-group">
								<label>지출(손실)</label>
								<input type="number" id="spending" name="spending" class="form-control">
							</div>
							<div class="form-group">
								<label>결산일</label>
								<input type="date" id="closingdate" name="closingdate" class="form-control">
							</div>
							<div class="pull-right">
								<button onClick="writeTd(this.form)" class="btn btn-primary mr-3">내역추가</button>
								<button type="reset" class="btn btn-primary">입력리셋</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<strong>일 매출</strong>
			</div>
			<div class="panel-body">
				<form action="./closing_account_process.php" method="POST">
					<table id="tb" class="table table-striped table-bordered table-hover" style="text-align: center">
						<thead>
							<tr>
								<th width="25%" style="text-align: center">구분</th>
								<th width="25%" style="text-align: center">수익</th>
								<th width="25%" style="text-align: center">지출</th>
								<th width="25%" style="text-align: center">결산일</th>
							</tr>
						</thead>
						<tbody>
							<tr onclick='loadTd(this)' id="cashTr">
								<td>현금</td>
								<?php
								$query = "SELECT SUM(sale_amount) AS sum FROM SALE WHERE pay_method='00'";
								$s = oci_parse( $conn, $query );
								oci_execute( $s );
								do_fetch1( $s );
								oci_free_statement( $s );

								$query = "SELECT SUM(a.sale_amount) AS sum FROM SALE a WHERE pay_method='00' and sale_num in(SELECT sale_num FROM REFUND b WHERE TO_CHAR(b.refund_date,'yyyymmdd') = TO_CHAR(sysdate,'yyyymmdd') )";
								$s = oci_parse( $conn, $query );
								oci_execute( $s );
								do_fetch2( $s );
								oci_free_statement( $s );
								?>
							</tr>
							<tr onclick='loadTd(this)' id="cardTr">
								<td>카드</td>
								<?php
								$query = "SELECT SUM(sale_amount) AS sum FROM SALE WHERE pay_method='01'";
								$s = oci_parse( $conn, $query );
								oci_execute( $s );
								do_fetch1( $s );
								oci_free_statement( $s );

								$query = "SELECT SUM(a.sale_amount) AS sum FROM SALE a WHERE pay_method='01' and sale_num in(SELECT sale_num FROM REFUND b WHERE TO_CHAR(b.refund_date,'yyyymmdd') = TO_CHAR(sysdate,'yyyymmdd') )";
								$s = oci_parse( $conn, $query );
								oci_execute( $s );
								do_fetch2( $s );
								oci_free_statement( $s );
								?>
							</tr>
						</tbody>
					</table>
					<input name="td1_1" id="td1_1" type="hidden">
					<input name="td1_2" id="td1_2" type="hidden">
					<input name="td1_3" id="td1_3" type="hidden">
					<input name="td2_1" id="td2_1" type="hidden">
					<input name="td2_2" id="td2_2" type="hidden">
					<input name="td2_3" id="td2_3" type="hidden">
					<div class="pull-right mt-3">
						<label class="mr-3">매출 : <span id="amount">0원</span></label>
						<button type="submit" class="btn btn-primary">결산</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="col-lg-12">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<strong>결산내역</strong>
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-bordered table-hover" id="myTable">
					<thead>
						<tr>
							<th width="1%">결산번호</th>
							<th width="1%">결산일</th>
							<th width="1%">구분</th>
							<th width="1%">수익</th>
							<th width="1%">지출</th>
							<th width="1%">매출액</th>
							<th width="1%">결산자</th>
						</tr>
					</thead>
					<tbody>
						<?php

						function do_fetch( $s ) {
							while ( $row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC ) ) {
								$i = 0;
								echo "<tr>";
								foreach ( $row as $item ) {
									$i++;

									if ( $item == $row[ 'CLACNT_GROUP' ] && trim( $item ) === "00" )
										echo "<td>현금</td>";
									elseif ( $item == $row[ 'CLACNT_GROUP' ] && trim( $item ) === "01" )
										echo "<td>카드</td>";
									elseif ( $item == '0' )
										echo "<td>0</td>";
									else
										echo "<td>" . ( $item ? htmlentities( $item ) : '&nbsp;' ) . "</td>";
								}
								echo "</tr>";
								$i = 0;
							}
						}

						$query = "SELECT 
					a.CLACNT_NUM,
					TO_CHAR(a.CLACNT_DATE,'YYYY/MM/DD'),
					a.CLACNT_GROUP,
					a.PROFIT,
					a.EXPENSE,
					a.CLACNT_AMOUNT,
					b.NAME
					FROM CLOSING_ACCOUNT a, EMPLOYEE b WHERE a.employee_num = b.employee_num";

						$s = oci_parse( $conn, $query );
						oci_execute( $s );
						do_fetch( $s );

						oci_close( $conn );
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- jQuery -->
	<script src="../js/jquery.js"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="../js/bootstrap.js"></script>
	<!-- Metis Menu Plugin JavaScript -->
	<script src="../js/metisMenu.js"></script>
	<!-- DataTables JavaScript -->
	<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
	<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
	<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
	<!-- Custom Theme JavaScript -->
	<script src="../js/sb-admin-2.js"></script>
	<script src="../js/content.js"></script>
	<script src="../js/jquery.json-2.4.min.js"></script>
	<script>
		$( document ).ready( function () {
			$( '#myTable' ).DataTable( {
				responsive: true,
				"aaSorting": [
					[ 0, 'desc' ]
				]
			} );
		} );

		//	Date.prototype.toDateInputValue = (function() {
		//		var local = new Date(this);
		//		local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
		//		return local.toJSON().slice(0,10);
		//	});
		//		
		//	$(document).ready( function() {
		//		$('#closingdate').val(new Date().toDateInputValue());
		//		saveData();
		//	});

		//document.getElementById('closingdate').value = new Date().toDateInputValue();

		function loadTd( heTr ) {
			if ( $( heTr ).find( "td" ).eq( 0 ).text() == '현금' )
				document.getElementById( "cash" ).checked = true;
			else if ( $( heTr ).find( "td" ).eq( 0 ).text() == '카드' )
				document.getElementById( "card" ).checked = true;

			document.getElementById( "profit" ).value = $( heTr ).find( "td" ).eq( 1 ).text();
			document.getElementById( "spending" ).value = $( heTr ).find( "td" ).eq( 2 ).text();
			document.getElementById( "closingdate" ).value = $( heTr ).find( "td" ).eq( 3 ).text();
		}

		function writeTd( form ) {
			if ( form.cash.checked )
				var tmpTr = "#cashTr";
			else
				var tmpTr = "#cardTr";

			var profit = document.getElementById( "profit" ).value;
			var closingDate = document.getElementById( "closingdate" ).value;
			var spending = document.getElementById( "spending" ).value;

			if ( profit != '' )
				$( tmpTr ).find( "td" ).eq( 1 ).text( profit );
			else
				$( tmpTr ).find( "td" ).eq( 1 ).text( 0 );
			if ( spending != '' )
				$( tmpTr ).find( "td" ).eq( 2 ).text( spending );
			else
				$( tmpTr ).find( "td" ).eq( 2 ).text( 0 );
			$( tmpTr ).find( "td" ).eq( 3 ).text( closingDate );

			form.reset();
			document.getElementById( 'closingdate' ).value = closingDate;

			var cashProfit = parseInt( $( "#cashTr" ).find( "td" ).eq( 1 ).text() );
			var cashSpending = parseInt( $( "#cashTr" ).find( "td" ).eq( 2 ).text() );
			var cardProfit = parseInt( $( "#cardTr" ).find( "td" ).eq( 1 ).text() );
			var cardSpending = parseInt( $( "#cardTr" ).find( "td" ).eq( 2 ).text() );

			var result = cashProfit + cardProfit - cashSpending - cardSpending;

			document.getElementById( "amount" ).innerHTML = result + "원";
			saveData();
		}
		
		function calc(){
			var cashProfit = parseInt( $( "#cashTr" ).find( "td" ).eq( 1 ).text() );
			var cashSpending = parseInt( $( "#cashTr" ).find( "td" ).eq( 2 ).text() );
			var cardProfit = parseInt( $( "#cardTr" ).find( "td" ).eq( 1 ).text() );
			var cardSpending = parseInt( $( "#cardTr" ).find( "td" ).eq( 2 ).text() );

			var result = cashProfit + cardProfit - cashSpending - cardSpending;

			document.getElementById( "amount" ).innerHTML = result + "원";
			saveData();
		}
		calc();

		function saveData() {
			$( '#td1_1' ).val( $( '#cashTr' ).find( "td" ).eq( 1 ).text() );
			$( '#td1_2' ).val( $( '#cashTr' ).find( "td" ).eq( 2 ).text() );
			$( '#td1_3' ).val( $( '#cashTr' ).find( "td" ).eq( 3 ).text() );
			$( '#td2_1' ).val( $( '#cardTr' ).find( "td" ).eq( 1 ).text() );
			$( '#td2_2' ).val( $( '#cardTr' ).find( "td" ).eq( 2 ).text() );
			$( '#td2_3' ).val( $( '#cardTr' ).find( "td" ).eq( 3 ).text() );
		}

		document.onkeydown = trapRefresh;

		function trapRefresh() {
			if ( event.keyCode == 116 ) {
				event.keyCode = 0;
				event.cancelBubble = true;
				event.returnValue = false;
				document.location.reload( 1 );
			}
		}
	</script>
</body>

</html>