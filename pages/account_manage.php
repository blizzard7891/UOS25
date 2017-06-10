<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>자금관리</title>

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

	<div class="col-lg-12">.
		<h1 class="page-header">자금관리</h1>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<strong>수입 / 지출 입력</strong>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<form action="./account_manage_process.php" method="POST">
							<div class="form-group">
								<label>구분</label>
								<div>
									<label class="radio-inline">
										<input type="radio" name="flag" value="inAccount" checked>수입
									</label>
									<label class="radio-inline">
										<input type="radio" name="flag" value="outAccount"> 지출
									</label>
								</div>
							</div>
							<div class="form-group">
								<label>현금/카드</label>
								<div>
									<label class="radio-inline">
										<input type="radio" name="paymethod" value="cash" checked>현금
									</label>
									<label class="radio-inline">
										<input type="radio" name="paymethod" value="card"> 카드
									</label>
								</div>
							</div>
							<div class="form-group">
								<label>금액</label>
								<input type="number" name="amount" class="form-control" min="0" max="999999999999" required>
							</div>
							<div class="form-group">
								<label>내역 설명</label>
								<textarea class="form-control" name="description" rows="2" maxlength="50" required></textarea>
							</div>
							<div class="pull-right">
								<button type="submit" class="btn btn-primary mr-3">내역입력</button>
								<button type="reset" class="btn btn-primary">입력리셋</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-12">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<strong>현금 자금내역</strong>
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable">
					<thead>
						<th width="10%">순번</th>
						<th width="10%">일자</th>
						<th width="30%">설명</th>
						<th width="10%">구분</th>
						<th width="10%">현금/카드</th>
						<th width="10%">금액</th>
						<th width="10%">잔여액</th>
					</thead>
					<tbody>

						<?php
						include_once("./db.php");

						function do_fetch($s)
						{
							while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
							{
								$i =0;
								echo "<tr>";
								foreach ($row as $item) 
								{
									$i++;
									
									if($item == $row['FLAG'] && trim($item)==="00" && $i==4)
										echo "<td>수입</td>";
									elseif($item == $row['FLAG'] && trim($item)==="01" && $i==4)
										echo "<td>지출</td>";
									elseif($item == $row['PAY_METHOD'] && trim($item)==="00" && $i==5)
										echo "<td>현금</td>";
									elseif($item == $row['PAY_METHOD'] && trim($item)==="01" && $i==5)
										echo "<td>카드</td>";
									elseif($item == $row['REMAIN_AMOUNT'] && $item ==0)
										echo "<td>0</td>";
									else
										echo "<td>".($item?htmlentities($item):'&nbsp;')."</td>";
								}
								echo "</tr>";
								$i =0;
							}
						}

						$query = "SELECT seq_num,TO_CHAR(write_date,'YYYY/MM/DD'),description,write_group,pay_method,amount,remain_amount FROM MONEY_MANAGEMENT WHERE pay_method ='00'";
						$s = oci_parse($conn,$query);
						oci_execute($s);
						do_fetch($s);

						oci_close($conn);
						?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<div class="col-lg-12">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<strong>카드 자금내역</strong>
			</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable1">
					<thead>
						<th width="10%">순번</th>
						<th width="10%">일자</th>
						<th width="30%">설명</th>
						<th width="10%">구분</th>
						<th width="10%">현금/카드</th>
						<th width="10%">금액</th>
						<th width="10%">잔여액</th>
					</thead>
					<tbody>

						<?php
						include_once("./db.php");

						$query = "SELECT seq_num,TO_CHAR(write_date,'YYYY/MM/DD'),description,write_group,pay_method,amount,remain_amount FROM MONEY_MANAGEMENT WHERE pay_method ='01'";
						$s = oci_parse($conn,$query);
						oci_execute($s);
						do_fetch($s);

						oci_close($conn);
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
    <script src="../vendor/datatables/js/jquery.dataTables.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
	<!-- Custom Theme JavaScript -->
	<script src="../js/sb-admin-2.js"></script>
	<script src="../js/content.js"></script>
	<script>
		$(document).ready(function() {
			$('#myTable').DataTable({
				responsive: true,
				"aaSorting":[[0,'desc']]
			});
		});
		
		$(document).ready(function() {
			$('#myTable1').DataTable({
				responsive: true,
				"aaSorting":[[0,'desc']]
			});
		});
		
		document.onkeydown = trapRefresh;
		function trapRefresh()
		{
			if (event.keyCode == 116)
			{
				event.keyCode = 0; 
				event.cancelBubble = true; 
				event.returnValue = false;
				document.location.reload(true);
			}
		}  
	</script>
</body>
</html>
