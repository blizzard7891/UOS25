<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>판매내역</title>

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
   	<h1 class="page-header">판매내역</h1>
   </div>
   
   <div class="col-lg-12">
   	<div class="panel panel-blue">
   		<div class="panel-heading">
   			<strong>판매내역</strong>
   		</div>
   		<div class="panel-body">
   			<table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable" >
   				<thead>
   					<tr>
   						<th>판매번호</th>
   						<th>판매일자</th>
   						<th>판매수단</th>
   						<th>판매금액</th>
   						<th>환불여부</th>
   						<th>처리자</th>
   					</tr>
   				</thead>
   				<tbody>

          <?php
            // include_once("./db.php");
            // $query = "DELETE FROM SALE";
            // $s = oci_parse($conn,$query);
            // oci_execute($s);
            // oci_free_statement($s);
            // oci_close($conn);
// include_once("./db.php");
//             $query = "DELETE FROM SALE_LIST";
//             $s = oci_parse($conn,$query);
//             oci_execute($s);
//             oci_free_statement($s);
//             oci_close($conn);

            include_once("./db.php");

            function do_fetch($s)
            {

              $count=0;

              while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
              {
                echo "<tr id="."tr_".$count." onclick=more_info(".$count.");>";

                echo '<td>'; echo $row['SALE_NUM']; echo '</td>';
                echo '<td>'; echo $row['TO_CHAR(A.SALE_DATE,\'YYYY/MM/DD\')']; echo'</td>';
                if($row['PAY_METHOD']=='00') echo "<td>현금</td>";
                else echo "<td>카드</td>";
                echo '<td>'; echo $row['SALE_AMOUNT']; echo'원</td>';
                if($row['REFUND_FLAG']==0) echo "<td class='td-p0' style=\"text-align: center\"><button class='btn-danger' onclick=refund(".$count."); >환불처리</button></td>";
                else echo "<td class='td-l5'>환불됨</button></td>";
                echo '<td>'; echo $row['NAME']; echo'</td>';
                echo "</tr>";

                $count=$count+1;
              }
            }

            $query = "SELECT a.sale_num, TO_CHAR(a.SALE_DATE,'YYYY/MM/DD'), a.pay_method, a.sale_amount, a.refund_flag, b.name FROM SALE a, EMPLOYEE b WHERE a.EMPLOYEE_NUM = b.EMPLOYEE_NUM";
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

      function refund(row) {

          var tr = document.getElementById("tr_"+row);
          var salenum = tr.cells[0].innerHTML;
          var empname = tr.cells[1].innerHTML;
          var form = document.createElement("form");
          form.setAttribute("method", "post");
          form.setAttribute("action", "./refund_process.php");

          var hiddenField = document.createElement("input");
          hiddenField.setAttribute("type", "hidden");
          hiddenField.setAttribute("name", "salenum");
          hiddenField.setAttribute("value", salenum);
          form.appendChild(hiddenField);
          document.body.appendChild(form);

          if(confirm("정말 환불처리 하시겠습니까? \n")==true){
            alert(salenum +"상품은 환불처리되었습니다");
            form.submit();
          }
          else{
            return;
          }

          
              //location.replace('./discharge_process.php');
      }

    function more_info(row) {


         var win = window.open('about:blank', 'viewer', 'width=800,height=1000');


         var tr = document.getElementById("tr_"+row);
         var prodnum = tr.cells[0].innerHTML;
         var form = document.createElement("form");
         form.setAttribute("name", "myform")
         form.setAttribute("method", "post");
         form.setAttribute("action", "./sales_log_more.php");

         var hiddenField = document.createElement("input");
         hiddenField.setAttribute("type", "hidden");
         hiddenField.setAttribute("name", "prodnum");
         hiddenField.setAttribute("value", prodnum);
         form.appendChild(hiddenField);
         document.body.appendChild(form);

         var frm = document.myform;
         frm.action = './sales_log_more.php';


         frm.target = "viewer";
         frm.method = "post";
         frm.submit();

      }

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
