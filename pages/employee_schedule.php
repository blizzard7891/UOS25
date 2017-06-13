<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>근무부</title>

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

	<div class="col-lg-12">
		<h1 class="page-header">근무부</h1>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<strong>근무시간 입력</strong>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<form action="./employee_schedule_process.php" method="POST">
							<div class="form-group">
								<label>직원명</label>
								<select name="empname" class="form-control" required>
									<?php
									include_once( "./db.php" );

									function do_fetch( $s ) {
										while ( $row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC ) ) {
											echo "<option value='" . $row[ 'EMPLOYEE_NUM' ] . "'>";
											echo htmlentities( $row[ 'NAME' ] . "__" . $row[ 'RANK' ] );
											echo "</option>";
										}
									}

									$query = "SELECT 
                                    EMPLOYEE_NUM,
                                    NAME,
                                    RANK
                                    FROM EMPLOYEE WHERE discharge_date IS NULL";

									$s = oci_parse( $conn, $query );
									oci_execute( $s );
									do_fetch( $s );

									oci_close( $conn );
									?>
								</select>
							</div>
							<div class="form-group">
								<label>근무 시간</label>
								<input name="workinghour" type="number" class="form-control" placeholder="0" min="1" max="24" required>
							</div>
							<div class="form-group">
								<label>근무 일자</label>
								<input type="date" id="workingdate" name="workingdate" class="form-control" required>
							</div>
							<div class="pull-right">
								<button class="btn btn-primary mt-3">근무시간 입력</button>
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
				<strong>전월 근무시간</strong>
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-bordered table-hover mb-0" style="text-align: center">
					<thead>
						<tr>
							<th width="2%" style="text-align: center">직원명</th>
							<th width="2%" style="text-align: center">직급</th>
							<th width="1.7%" style="text-align: center">총 근무시간</th>
							<th width="2%" style="text-align: center">시급</th>
							<th width="2%" style="text-align: center">월급</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php
							include_once( "./db.php" );

							function do_fetch2( $s ) {
								$conn2 = oci_connect( 'juneui', 'helloworld', 'juneui.cwpxsqzgvzt5.ap-northeast-2.rds.amazonaws.com:1521/orcl', 'UTF8' );
								$count =0;

								while ( $row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC ) ) {
									$query2 = "SELECT hourly_wage FROM HOURLY_WAGE_INFO WHERE rank = :rank";
									$s2 = oci_parse( $conn2, $query2 );
									oci_bind_by_name( $s2, ':rank', $row[ 'RANK' ] );
									oci_execute( $s2 );
									$row2 = oci_fetch_array( $s2, OCI_RETURN_NULLS + OCI_ASSOC );

									echo "<tr onclick='loadTd(this)' id='tr_" . $count . "'>";
									foreach ( $row as $item ) {
											echo "<td>" . ( $item ? htmlentities( $item ) : '' ) . "</td>";
									}
									echo "<td style='text-align: right'>" . $row2[ 'HOURLY_WAGE' ] . "원</td>";
									echo "<td style='text-align: right'>" . $row[ 'SUM(B.WORKING_HOUR)' ] * $row2[ 'HOURLY_WAGE' ] . "원</td>";
									echo "</tr>";
									
									$count++;
									oci_free_statement( $s2 );
								}
								oci_close( $conn2 );
							}

							$query = "SELECT a.name,a.rank,SUM(b.working_hour) FROM EMPLOYEE a, WORKING_MANAGEMENT b WHERE a.employee_num = b.employee_num AND TO_CHAR(b.working_date,'mm')=TO_CHAR(SYSDATE,'mm') GROUP BY a.name,a.rank";
							$s = oci_parse( $conn, $query );
							oci_execute( $s );
							do_fetch2( $s );

							oci_close( $conn );
							?>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="col-lg-6 " style="clear: left">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<strong>근무내역</strong>
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-bordered table-hover" id="myTable" style="text-align: center">
					<thead>
						<tr>
							<th width="1%">직원이름</th>
							<th width="1%">직급</th>
							<th width="1%">근무 일자</th>
							<th width="1%">근무 시간</th>
						</tr>
					</thead>
					<tbody>
						<?php
						include_once( "./db.php" );

						function do_fetch1( $s ) {
							$count = 0;
							while ( $row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC ) ) {
								$i = 0;
								echo "<tr onclick='loadTd(this)' id='tr_" . $count . "'>";
								foreach ( $row as $item ) {
									if ( $i == 6 && $item != '' )
										echo "<td class='td-p0'><button class='btn btn-danger no-border' onclick='returnbattery(" . $count . ")'>반납</button></td>";
									else
										echo "<td>" . ( $item ? htmlentities( $item ) : '' ) . "</td>";

									$i++;
								}
								echo "</tr>";
								$count++;
							}
						}

						$query = "SELECT a.name,a.rank,TO_CHAR(b.working_date,'yyyy/mm/dd'),b.working_hour  FROM EMPLOYEE a, WORKING_MANAGEMENT b WHERE a.employee_num = b.employee_num";
						$s = oci_parse( $conn, $query );
						oci_execute( $s );
						do_fetch1( $s );

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
	<script>
		$( document ).ready( function () {
			$( '#myTable' ).DataTable( {
				responsive: true
			} );
		} );

		Date.prototype.toDateInputValue = ( function () {
			var local = new Date( this );
			local.setMinutes( this.getMinutes() - this.getTimezoneOffset() );
			return local.toJSON().slice( 0, 10 );
		} );

		$( document ).ready( function () {
			$( '#workingdate' ).val( new Date().toDateInputValue() );
		} );


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