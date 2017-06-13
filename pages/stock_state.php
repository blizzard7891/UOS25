<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>재고</title>

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
        <h1 class="page-header">재고관리</h1>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-blue">
            <div class="panel-heading">
                <strong>입고 입력</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="./stock_state_process.php" method="POST">
                            <div class="form-group">
                            	<label>품목명</label>
                            	<select name="enterproduct" class="form-control" required>
                            		<?php
									include_once("./db.php");

									function do_fetch1($s)
									{
									  while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
									  {
										echo "<option value='".$row['PROD_NUM']."'>";
										echo htmlentities($row['PROD_NAME']);
										echo "</option>";
									  }
									}

									$query = "SELECT 
									PROD_NUM,
									PROD_NAME
									FROM PRODUCT WHERE prod_name not in('배터리','택배_6500','택배_8000','택배_9500')";

									$s = oci_parse($conn,$query);
									oci_execute($s);
									do_fetch1($s);

									oci_close($conn);
									?>
                            	</select>
                            </div>
                            <div class="form-group">
                            	<label>수량</label>
                            	<input name="enterqty" type="number" class="form-control" placeholder="0" min="1" required>
                            </div>
                            <div class="pull-right">
                            	<button type="submit" name="type" value="0" class="btn btn-primary">입고입력</button>
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
    			<strong>출고 입력</strong>
    		</div>
    		<div class="panel-body">
    			<div class="row">
    				<div class="col-lg-12">
    					<form action="./stock_state_process.php" method="POST">
                            <div class="form-group">
                            	<label>품목번호</label>
                            	<select name="releaseproduct" class="form-control" required>
                            		<?php
									include_once("db.php");

									$query = "SELECT PROD_NUM,PROD_NAME FROM PRODUCT WHERE std_expdate is null or WHERE prod_name not in('배터리','택배_6500','택배_8000','택배_9500')";
									$s = oci_parse($conn,$query);
									oci_execute($s);
									do_fetch1($s);

									oci_close($conn);
									?>
                            	</select>
                            </div>
                            <div class="form-group">
                            	<label>수량</label>
                            	<input name="releaseqty" type="number" class="form-control" placeholder="0" min="1" required>
                            </div>
                            <div class="pull-right">
                            	<button type="submit" name="type" value="1" class="btn btn-primary">출고입력</button>
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
    			<strong>재고 상황</strong>
    		</div>
    		<div class="panel-body">
    			<table width="100%" class="table table-striped table-bordered table-hover" id="myTable">
                   <thead>
                       <tr>
                           <th width="1%">순서번호</th>
                           <th width="2%">제품번호</th>
                           <th width="2%">제품명</th>
                           <th width="2%">표준유통기한(일)</th>
                           <th width="1%">재고수량</th>
                       </tr>
                   </thead>
                   <tbody>
                       <?php
                        include_once("./db.php");

                        function do_fetch($s)
                        {
                            $i = 0;
                          while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
                          {
                            $i++;
                            echo "<tr>";
                            echo "<td>".$i."</td>";
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
                        PROD_NUM,
                        PROD_NAME,
                        STD_EXPDATE,
                        STOCK_QTY
                        FROM PRODUCT WHERE prod_name not in('배터리','택배_6500','택배_8000','택배_9500')";

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
