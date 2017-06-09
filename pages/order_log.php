<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>주문내역</title>

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
   	<h1 class="page-header">주문내역</h1>
   </div>
   
   <div class="col-lg-12">
   	<div class="panel panel-default">
   		<div class="panel-heading">
   			<strong>주문내역</strong>
   		</div>
   		<div class="panel-body">
   			<!-- <div class="row mt-5">
   				<div class="col-lg-12">
   					<form action="#" class="">
   						<div class="form-group row">
   							<div class="col-lg-3">
   								<input type="text" class="form-control" placeholder="주문번호 입력">
   							</div>
   							<div class="col-lg-1"></div>
   							<div class="col-lg-2">
   								<input type="date" class="form-control">
   							</div>
   							<span class="pull-left">--</span>
   							<div class="col-lg-2">
   								<input type="date" class="form-control">
   							</div>
   							<button type="submit" class="btn btn-primary">검색</button>
   						</div>
   					</form>
   				</div>
   			</div> -->
   			
   				<table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable">
   					<thead>
   						<tr>
							<th>주문번호</th>
							<th>주문액</th>
							<th>주문일자</th>
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

              $query = "SELECT ORDER_NUM,ORDER_AMT, TO_CHAR(ORDER_DATE,'YYYY/MM/DD') FROM ORDER_INFO";
              $s = oci_parse($conn,$query);

              oci_execute($s);
              do_fetch($s);
              oci_free_statement($s);


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
