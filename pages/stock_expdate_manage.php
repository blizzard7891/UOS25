<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>폐기관리</title>

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
    	<h1 class="page-header">폐기관리</h1>
    </div>
    
    <div class="col-lg-6">
    	<div class="panel panel-blue">
    		<div class="panel-heading">
    			금일 폐기품목
    		</div>
    		<div class="panel-body">
				<form action="./stock_expdate_manage_process.php" method="POST">
					<div class="pull-right mb-5">
					<button type="submit" class="btn btn-danger">금일 폐기상품 폐기</button>
					</div>
				</form>
    			<table width="100%" class="table table-striped table-bordered table-hover">
    			<thead>
    				<tr>
    					<th width="1%">입고일자</th>
    					<th width="1%">제품명</th>
    					<th width="1%">유통기한</th>
    					<th width="1%">재고수량</th>
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
							  if($item == '0')
								echo "<td>0</td>";
							  else
								echo "<td>".($item?htmlentities($item):'&nbsp;')."</td>";
                            }
                            echo "</tr>";
                          }
                        }

                        $query = "SELECT 
                        TO_CHAR(a.ENT_DATE,'yyyy/mm/dd'),
						b.PROD_NAME,
                        TO_CHAR(a.EXPDATE,'yyyy/mm/dd'),
						a.QTY
                        FROM EXP_DATE_MANAGEMENT a,PRODUCT b
						WHERE TO_CHAR(a.EXPDATE,'yyyymmdd')=TO_CHAR(sysdate,'yyyymmdd')
						AND a.PROD_NUM = b.PROD_NUM";
	
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
    
    <div class="col-lg-6">
    	<div class="panel panel-blue">
    		<div class="panel-heading">
    			유통기한 관리
    		</div>
    		<div class="panel-body">
    			<table width="100%" class="table table-bordered table-striped table-hover" id="myTable">
    				<thead>
    					<tr>
    						<th>입고일자</th>
							<th>제품명</th>
							<th>유통기한</th>
							<th>재고수량</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php
                        include_once("./db.php");

                         $query = "SELECT 
                        TO_CHAR(a.ENT_DATE,'yyyy/mm/dd'),
						b.PROD_NAME,
                        TO_CHAR(a.EXPDATE,'yyyy/mm/dd'),
						a.QTY
                        FROM EXP_DATE_MANAGEMENT a,PRODUCT b
						WHERE a.PROD_NUM = b.PROD_NUM";
	
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
			document.location.reload(1);
	   }
	 }  
    </script>
</body>
</html>
