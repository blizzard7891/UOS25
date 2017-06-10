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

	<!-- Custom CSS -->
	<link href="../css/sb-admin-2.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="../css/content.css" rel="stylesheet" type="text/css">



</head>

<body class="whitebody">
	<div class="col-lg-12">
		<h1 class="page-header">주문하기</h1>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<strong>주문입력</strong>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<form action="order_product_process.php" method="POST">
							<div class="form-group">
								<label>제품명</label>
								<select class="form-control" name="pname">
									<option value="">제품선택</option>
									<?php
									include_once("db.php");
									$query = "SELECT PROD_NAME FROM PRODUCT";
									$stid = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));
									if(oci_execute($stid)==false) die("oci query error [$query] message : ".oci_error($stid));
									while ( ($res = oci_fetch_array($stid, OCI_ASSOC)) != false) {
										$tmp = $res['PROD_NAME'];
										echo "<option value=$tmp>{$res['PROD_NAME']}</option>";
									}
									oci_free_statement($stid);
									?> 
								</select>
							</div>
							<div class="form-group">
								<label>수량</label>
								<input class="form-control" name="quantity">
							</div>
							<div class="pull-right">
								<button type="submit" class="btn btn-primary mr-3" name="type" value="주문추가">추가하기</button>
								<button type="submit" class="btn btn-primary" name="type" value="주문삭제">주문서초기화</button>	
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-12">
		<div class="panel panel-blue">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover mb-0">
						<thead>
							<tr>
								<th>제품명</th>
								<th>수량</th>
								<th>도매가</th>
								<th>총액</th>
								<th>업체명</th>
							</tr>
						</thead>
						<tbody>
							<?php
							include_once("db.php");

							function do_fetch($s)
							{
								while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
								{
									echo "<tr>";
									foreach ($row as $item) 
									{
										echo "<td>".($item?htmlentities($item):'&nbsp;')."</td>";
									}
									echo "</tr>";
								}
							}

							$query = "SELECT ATT1,ATT2,ATT3,ATT4,ATT5 FROM TEMP2";
							$s = oci_parse($conn,$query);



							oci_execute($s);
							do_fetch($s);
							oci_free_statement($s);


							$query = "SELECT SUM(ATT2),SUM(ATT4) FROM TEMP2";
							$s = oci_parse($conn,$query);

							oci_execute($s);
							$row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
							$countsum=$row['SUM(ATT2)'];
							$pricesum=$row['SUM(ATT4)'];

							oci_close($conn);


							?>
						</tbody>
					</table>

					<br>
					<label class="mr-2">총 수량: </label>
					<label class="mr-2"><?php echo $countsum?> 개</label>
					
					<br>
					<label class="mr-2">총 금액: </label>
					<label class="mr-2"><?php echo $pricesum?> 원</label>
					

				</div>
				<div class="pull-right mt-3">
				<form method="POST" action="order_product_process.php">
					<button type="submit" class="btn btn-primary" name="type" value="주문">주문</button>
					<input type="hidden" name="countsum" value="<?php echo $countsum; ?>">
					<input type="hidden" name="pricesum" value="<?php echo $pricesum; ?>">
				</form>
				</div>
			</div>
		</div>
	</div>

	<!-- jQuery -->
	<script src="../js/jquery.js"></script>
	<!-- Bootstrap Core JavaScript -->
	<script src="../js/bootstrap.js"></script>
	<!-- Metis Menu Plugin JavaScript -->
	<script src="../js/metisMenu.js"></script>
	<!-- Custom Theme JavaScript -->
	<script src="../js/sb-admin-2.js"></script>
	<script src="../js/content.js"></script>
	<script>
		$(document).ready(function() {
			$('#myTable').DataTable({
				responsive: true
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
				document.location.reload(1);
			}
		}  
	</script>
</body>

</html>
