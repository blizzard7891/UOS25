<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>반품내역</title>

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
  <h1 class="page-header">반품내역</h1>
</div>

<div class="col-lg-12">
  <div class="panel panel-blue">
   <div class="panel-heading">
    <strong>반품내역</strong>
  </div>
  <div class="panel-body">
    <table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable">
     <thead>
      <tr>
       <th>반품번호</th>
       <th>제품명</th>
       <th>반품수량</th>
       <th>반품금액</th>
       <th>반품일</th>
     </tr>
   </thead>
   <tbody>
    <?php
    include_once("db.php");

    function do_fetch($s)
    {
      $count = 0;
      while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
      {

        echo "<tr id="."tr_".$count.">";
        foreach ($row as $item) 
        {
          echo "<td>".($item?htmlentities($item):'&nbsp;')."</td>";
        }
        echo "</tr>";
        $count = $count + 1;
      }
    }

    $query = "SELECT a.RETURN_NUM, c.PROD_NAME, b.QTY, b.RETURN_AMOUNT,TO_CHAR(a.RETURN_DATE,'YYYY/MM/DD') FROM RETURN a, RETURN_LIST b, PRODUCT c WHERE a.RETURN_NUM = b.RETURN_NUM AND c.PROD_NUM = b.PROD_NUM";
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
