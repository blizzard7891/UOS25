<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>택배서비스</title>

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
		<h1 class="page-header">택배서비스</h1>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<strong>택배결제 정보</strong>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<form action="./service_parcel_process.php" method="POST">
							<div class="form-group">
								<label>송장번호</label>
								<input type="text" name="invoice" class="form-control" placeholder="0000000000000" pattern="[0-9]{13}" maxlength="13" required>
							</div>
							<div class="form-group">
								<label>무게(g)</label>
								<input id="winput" type="number" name="weight" class="form-control" min="1" required>
							</div>
							<div class="form-group">
    							<label class="mr-2">결제수단: </label>
    							<label class="radio-inline">
    								<input type="radio" name="payMethod" value="00" checked>현금
    							</label>
    							<label class="radio-inline">
    								<input type="radio" name="payMethod" value="01">카드
    							</label>
    						</div>
							<div class="pull-right">
								<strong class="mr-2">금액 : <span id="amount">0 원</span> </strong>
								<button type="submit" class="btn btn-primary mr-3">결제</button>
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
    			<strong>택배접수 내역</strong>
    		</div>
    		<div class="panel-body">
				<table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable">
					<thead>
						<tr>
							<th>송장번호</th>
							<th>접수일자</th>
							<th>무게(g)</th>
							<th>금액</th>
						</tr>
					</thead>
					<tbody>
						<?php
						include_once("./db.php");

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

						$query = "SELECT invoice_num,TO_CHAR(accept_date,'yyyy/mm/dd'),weight,shipping_price FROM PARCEL";
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
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../js/sb-admin-2.js"></script>
    <script src="../js/content.js"></script>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true
        });
    });
		
	$("#winput").change(function(){
		var weight = $("#winput").val();
		
		if(weight >= 10000)
			document.getElementById("amount").innerHTML ="9500 원";
		else if(weight >= 5000)
			document.getElementById("amount").innerHTML ="8000 원";
		else if(weight > 0)
			document.getElementById("amount").innerHTML ="6500 원";
		else
			document.getElementById("amount").innerHTML ="0 원";
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
