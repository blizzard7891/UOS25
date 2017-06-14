<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>환불내역</title>

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
   	<h1 class="page-header">환불내역</h1>
   </div>
   
   <div class="col-lg-12">
   	<div class="panel panel-blue">
   		<div class="panel-heading">
   			<strong>환불내역</strong>
   		</div>
   		<div class="panel-body">
   			<table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable">
   				<thead>
   					<tr>
   						<th>환불번호</th>
   						<th>환불일자</th>
   						<th>환불금액</th>
   						<th>판매번호</th>
   						<th>처리자</th>
   					</tr>
   				</thead>
   				<tbody>
   					<?php

            // include_once("./db.php");
            // $query = "INSERT INTO REFUND(refund_num, refund_date, sale_num, employee_num  )VALUES ('1', '11-JUN-11','2','2017001')";
            // $s = oci_parse($conn,$query);
            // oci_execute($s);
            // oci_free_statement($s);
            // oci_close($conn);
//             include_once("./db.php");
//             $query = "DELETE FROM REFUND";
//             $s = oci_parse($conn,$query);
//             oci_execute($s);
//             oci_free_statement($s);
//             oci_close($conn);

            include_once("./db.php");

            function do_fetch($s)
            {
                while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
                {

                $sale_num=$row['SALE_NUM'];
                $conn = oci_connect('juneui', 'helloworld', 'juneui.cwpxsqzgvzt5.ap-northeast-2.rds.amazonaws.com:1521/orcl', 'UTF8');
                $query = "SELECT sale_amount FROM SALE WHERE sale_num='$sale_num' ";
                $s1 = oci_parse($conn,$query);
                oci_execute($s1);
                $row1 = oci_fetch_array($s1,OCI_RETURN_NULLS + OCI_ASSOC);
                oci_free_statement($s1);

                echo "<tr>";
                echo '<td>'; echo $row['REFUND_NUM']; echo '</td>';
                echo '<td>'; echo $row['TO_CHAR(A.REFUND_DATE,\'YYYY/MM/DD\')']; echo '</td>';
                echo '<td>'; echo $row1['SALE_AMOUNT']; echo'원</td>';
                echo '<td>'; echo $row['SALE_NUM']; echo'</td>';
                echo '<td>'; echo $row['NAME']; echo'</td>';
                echo "</tr>";
              
                }
            }

            $query = "SELECT a.refund_num, TO_CHAR(a.refund_date,'YYYY/MM/DD'), a.sale_num, b.name FROM refund a, employee b WHERE a.employee_num = b.employee_num";
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
