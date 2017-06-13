<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>품목분류</title>

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
    <h1 class="page-header">품목분류</h1>
   </div>
   
   <div class="col-lg-6">
    <div class="panel panel-blue">
      <div class="panel-heading">
        <strong>품목분류 입력</strong>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-12">
            <form action="product_class_process.php" method="POST">
              <div class="form-group">
                <label>분류명</label>
                <input type="text" class="form-control" name="tname" required>
              </div>
              <div class="pull-right">
                <button type="submit" class="btn btn-primary">분류입력</button>
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
      <strong>품목분류 목록</strong>
    </div>
    <div class="panel-body">
      <table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable">
        <thead>
          <tr>
            <th>순서번호</th>
            <th>분류명</th>
            <th>항목개수</th>
          </tr>
        </thead>
        <tbody>
        <?php
            include_once("db.php");
            
            $query1 = "SELECT * FROM PRODUCT_CLASS";
            $stid1 = oci_parse($conn, $query1) or die('oci parse error: '.oci_error($conn));
            if(oci_execute($stid1) == false) die("oci query error [$query] message : ".oci_error($stid1));
            while (($res1 = oci_fetch_array($stid1, OCI_ASSOC)) != false) {
                $total_qty = 0;
                $num = $res1['PROD_CLASS_NUM'];
                $name = $res1['PROD_CLASS_NAME'];
                
                $query2 = "SELECT COUNT(*) FROM PRODUCT WHERE PROD_CLASS_NUM = :num";
                $stid2 = oci_parse($conn, $query2) or die('oci parse error: '.oci_error($conn));          
                oci_bind_by_name($stid2, ':num', $num);
                if(oci_execute($stid2) == false) die("oci query error [$query] message : ".oci_error($stid2));
                $res2 = oci_fetch_array($stid2, OCI_ASSOC);
                $class_count = $res2['COUNT(*)'];    
                echo "<tr>";
                echo "<td>$num</td>";
                echo "<td>$name</td>";
                echo "<td>$class_count</td>";
                echo "</tr>";
            }
            oci_free_statement($stid1);
            oci_free_statement($stid2);
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
