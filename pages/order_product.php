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
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>주문입력</strong>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<form action="order_product.php" method="POST" id="form">
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
								<button type="submit" class="btn btn-primary mr-3">추가하기</button>
								<button type="reset" class="btn btn-primary">입력리셋</button>	
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
    
    <div class="col-lg-12">
    	<div class="panel panel-default">
    		<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover mb-0">
						<thead>
							<tr>
								<th>제품명</th>
								<th>수량</th>
								<th>단가</th>
								<th>총액</th>
								<th>업체명</th>
							</tr>
						</thead>
						<tbody id="my-tbody">
						<?
						$row = 1;
						if(isset($_POST['pname'])&&isset($_POST['quantity']))
						{
						$query1 = "SELECT SUPPLIER_NAME FROM SUPPLIER WHERE SUPPLIER_NUM =(SELECT SUPPLIER_NUM FROM PRODUCT WHERE PROD_NAME = :pname)";
						$query2 = "SELECT PROD_PRICE FROM PRODUCT WHERE PROD_NAME = :pname";
						$compiled1 = oci_parse($conn, $query1) or die('oci parse error: '.oci_error($conn));
						$compiled2 = oci_parse($conn, $query2) or die('oci parse error: '.oci_error($conn));
						oci_bind_by_name($compiled1, ':pname', $_POST['pname']);
						oci_bind_by_name($compiled2, ':pname', $_POST['pname']);
						if(oci_execute($compiled1)==false) die("oci query error [$query] message : ".oci_error($stid));
						if(oci_execute($compiled2)==false) die("oci query error [$query] message : ".oci_error($stid));
						while ( ($res1 = oci_fetch_assoc($compiled1)) && ($res2 = oci_fetch_assoc($compiled2))!= false) 
						{
						$sname = $res1['SUPPLIER_NAME'];
						$price = $res2['PROD_PRICE'];
						$total = $_POST['quantity'] * $price;

						echo "<tr id=\"$row\">";
						echo "<td id=\"td_".$row."_1\">{$_POST['pname']}</td>";
						echo "<td id=\"td_".$row."_2\">{$_POST['quantity']}</td>";
						echo "<td id=\"td_".$row."_3\">$price</td>";
						echo "<td id=\"td_".$row."_4\">$total</td>";
						echo "<td id=\"td_".$row."_5\">$sname</td>";
						//echo "<td width=\"100\" height=\"50\"><button id=\"delete\" class=\"btn btn-primary\">삭제</button></td>";
						echo "</tr>";

						$row = $row + 1;

						}
						oci_free_statement($compiled1);
						oci_free_statement($compiled2);	
						}
						?>
						</tbody>
					</table>
				</div>
   				<div class="pull-right mt-3">
   					<button type="submit" class="btn btn-primary">주문</button>
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
