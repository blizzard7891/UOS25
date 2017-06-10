<?php
echo "<script type=\"text/javascript\">
window.opener.location.reload();
</script>";
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>주문상세내역</title>

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
  <h1 class="page-header">주문상세내역</h1>
</div>

<div class="col-lg-12">
  <div class="panel panel-default">
   <div class="panel-heading">
    <strong>주문상세내역</strong>
  </div>
  <div class="panel-body">

   <table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable">
    <thead>
     <tr>
       <th>주문번호</th>
       <th>제품명</th>
       <th>주문수량</th>
       <th>주문금액</th>
       <th>입고여부</th>
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
       $flag = $row['ENT_FLAG'];
       echo "<tr id="."tr_".$count.">";
       echo "<td>{$row['ORDER_NUM']}</td>";
       echo "<td>{$row['PROD_NAME']}</td>";
       echo "<td>{$row['ORDER_QTY']}</td>";
       echo "<td>{$row['ORDER_AMT']}</td>";

       if(is_null($flag)){
        $id = $count."입고전";
        echo "<td>";
        echo "<input type=\"hidden\" value=\"입고전\" id=$id>";
        echo "<button onclick=enter(".$count.");>입고처리</button><button onclick=order_return($count);>반품요청</button></td>";
      }
      else {
        $id = $count."입고후";
        echo "<td>";
        echo "<input type=\"hidden\" value=\"입고후\" id=$id>";
        echo "입고&nbsp;&nbsp;&nbsp;&nbsp;<button onclick=order_return($count);>반품요청</button></td>";
      }

      echo "</tr>";

      $count = $count + 1;
    }
  }

  $onum = $_POST['onum'];
  $query = "SELECT a.ORDER_NUM, b.PROD_NAME, a.ORDER_QTY, a.ORDER_AMT, a.ENT_FLAG FROM ORDER_LIST a, PRODUCT b WHERE a.PROD_NUM = b.PROD_NUM AND a.ORDER_NUM = :onum";
  $s = oci_parse($conn,$query);
  oci_bind_by_name($s, ':onum', $onum);
  oci_execute($s);
  do_fetch($s);
  oci_free_statement($s);

  $query2 = "SELECT SUM(a.ORDER_QTY), SUM(a.ORDER_AMT) FROM ORDER_LIST a, PRODUCT b WHERE a.PROD_NUM = b.PROD_NUM AND a.ORDER_NUM = :onum";

  $s = oci_parse($conn,$query2);
  oci_bind_by_name($s, ':onum', $onum);
  oci_execute($s);
  $res = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
  oci_free_statement($s);

  $countsum = $res['SUM(A.ORDER_QTY)'];
  $pricesum = $res['SUM(A.ORDER_AMT)'];


  oci_close($conn);

  ?>
</tbody>
</table>
<br>
<label class="mr-2">총 주문수량: </label>
<label class="mr-2"><?php echo $countsum?> 개</label>

<br>
<label class="mr-2">총 주문금액: </label>
<label class="mr-2"><?php echo $pricesum?> 원</label>
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

  function enter(row){

   var tr = document.getElementById("tr_"+row);
   var onum = tr.cells[0].innerHTML;
   var pname = tr.cells[1].innerHTML;
   var enter_quantity = tr.cells[2].innerHTML;
   var date = getTimeStamp();

   var form = document.createElement("form");
   form.setAttribute("method", "post");
   form.setAttribute("action", "./order_enter_process.php");

   var hiddenField1 = document.createElement("input");
   hiddenField1.setAttribute("type", "hidden");
   hiddenField1.setAttribute("name", "pname");
   hiddenField1.setAttribute("value", pname);
   form.appendChild(hiddenField1);

   var hiddenField2 = document.createElement("input");
   hiddenField2.setAttribute("type", "hidden");
   hiddenField2.setAttribute("name", "enterqty");
   hiddenField2.setAttribute("value", enter_quantity);
   form.appendChild(hiddenField2);

   var hiddenField3 = document.createElement("input");
   hiddenField3.setAttribute("type", "hidden");
   hiddenField3.setAttribute("name", "onum");
   hiddenField3.setAttribute("value", onum);
   form.appendChild(hiddenField3);

   document.body.appendChild(form);

   alert(pname+" 입고되었습니다 "+date);
   form.submit();
 }

 function order_return(row){

  var td1 = document.getElementById(row+"입고후");
  var td2 = document.getElementById(row+"입고전");
  var date = getTimeStamp();
  //flag는 입고후 또는 입고전
  if (!td1) {
    var flag = td2.value;}
    else { 
      var flag = td1.value;}

      var tr = document.getElementById("tr_"+row);
      var pname = tr.cells[1].innerHTML;
      var return_quantity = tr.cells[2].innerHTML;
      var return_amount = tr.cells[3].innerHTML;

      var form = document.createElement("form");
      form.setAttribute("method", "post");
      form.setAttribute("action", "./order_return_process.php");

      var hiddenField1 = document.createElement("input");
      hiddenField1.setAttribute("type", "hidden");
      hiddenField1.setAttribute("name", "pname");
      hiddenField1.setAttribute("value", pname);
      form.appendChild(hiddenField1);

      var hiddenField2 = document.createElement("input");
      hiddenField2.setAttribute("type", "hidden");
      hiddenField2.setAttribute("name", "returnqty");
      hiddenField2.setAttribute("value", return_quantity);
      form.appendChild(hiddenField2);

      var hiddenField3 = document.createElement("input");
      hiddenField3.setAttribute("type", "hidden");
      hiddenField3.setAttribute("name", "returnamt");
      hiddenField3.setAttribute("value", return_amount);
      form.appendChild(hiddenField3);

      var hiddenField4 = document.createElement("input");
      hiddenField4.setAttribute("type", "hidden");
      hiddenField4.setAttribute("name", "flag");
      hiddenField4.setAttribute("value", flag);
      form.appendChild(hiddenField4);

      document.body.appendChild(form);
      alert(pname+" 반품 요청되었습니다 "+date);
      form.submit();

}

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

function getTimeStamp() {
  var d = new Date();

  var s =
  leadingZeros(d.getFullYear(), 4) + '-' +
  leadingZeros(d.getMonth() + 1, 2) + '-' +
  leadingZeros(d.getDate(), 2) + ' ' +

  leadingZeros(d.getHours(), 2) + ':' +
  leadingZeros(d.getMinutes(), 2) + ':' +
  leadingZeros(d.getSeconds(), 2);

  return s;
}



function leadingZeros(n, digits) {
  var zero = '';
  n = n.toString();

  if (n.length < digits) {
    for (i = 0; i < digits - n.length; i++)
      zero += '0';
  }
  return zero + n;
}



</script>
</body>
</html>
