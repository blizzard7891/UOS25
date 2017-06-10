<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>직원관리</title>

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
  <h1 class="page-header">직원관리</h1>
</div>

<div class="col-lg-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>직원정보 입력</strong>
    </div>
    <div class="panel-body">
      <div class="row">
        <form action="employee_manage_process.php" method="POST">
          <div class="col-lg-6">
            <div class="form-group">
              <label>이름</label>
              <input type="text" name="ename" class="form-control">
            </div>
            <div class="form-group">
              <label>휴대전화</label>
              <input type="tel" name="ephone" class="form-control" placeholder="010-0000-0000">
            </div>
            <div class="form-group">
              <label>주소</label>
              <textarea class="form-control" name="eaddress" rows="3"></textarea>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label>직급</label>
              <select name="rank" class="form-control">
                <option value="">직업선택</option>
                <?php
                include_once("db.php");
                $query = "SELECT RANK FROM HOURLY_WAGE_INFO";
                $stid = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));
                if(oci_execute($stid)==false) die("oci query error [$query] message : ".oci_error($stid));
                while ( ($res = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                  $tmp = $res['RANK'];
                  echo "<option value=$tmp>{$res['RANK']}</option>";
                }
                oci_free_statement($stid);
                ?> 
              </select>
            </div>
            <div class="form-group">
              <label>생년월일</label>
              <input type="date" name="birth" class="form-control">
            </div>
            <div class="form-group">
              <label>고용일자</label>
              <input type="date" name="hire" class="form-control">
            </div>
            <div class="pull-right">
              <button type="submit" class="btn btn-primary mr-3">직원입력</button>
              <button type="reset" class="btn btn-primary">입력리셋</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="col-lg-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>직원 정보</strong>
    </div>
    <div class="panel-body">
      <table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable">
        <thead>
          <tr>
            <th>직원번호</th>
            <th>이름</th>
            <th>직급</th>
            <th>휴대전화</th>
            <th>주소</th>
            <th>생년월일</th>
            <th>고용일자</th>
            <th>퇴사일자</th>
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
              echo "<td>".($item?htmlentities($item):"<button onclick=discharge(".$count.");>퇴사처리</button>")."</td>";
            }
            echo "</tr>";
            $count = $count + 1;
          }
        }


        $stid = oci_parse($conn, 'SELECT COUNT(*) FROM EMPLOYEE') or die('oci parse error: '.oci_error($conn));
        if(oci_execute($stid) == false) die("oci query error [$query] message : ".oci_error($stid));
        while (($res = oci_fetch_array($stid, OCI_ASSOC)) != false) {
          $count = $res['COUNT(*)'];
        }
        oci_free_statement($stid);

        $query = "SELECT EMPLOYEE_NUM, NAME, RANK, PHONE_NUM, ADR, TO_CHAR(BIRTH, 'YYMMDD'), TO_CHAR(EMPLOYMENT_DATE,'YYYY/MM/DD'), TO_CHAR(DISCHARGE_DATE,'YYYY/MM/DD') FROM EMPLOYEE";
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
<script>
  function discharge(row) {

    var tr = document.getElementById("tr_"+row);
    var empnum = tr.cells[0].innerHTML;
    var empname = tr.cells[1].innerHTML;
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "./discharge_process.php");

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("name", "empnum");
    hiddenField.setAttribute("value", empnum);
    form.appendChild(hiddenField);
    document.body.appendChild(form);

    alert(empname+" 은 퇴사처리되었습니다");
    form.submit();
        //location.replace('./discharge_process.php');
      }
    </script>
    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../js/metisMenu.js"></script>
    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.js"></script>
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
          document.location.reload(true);
        }
      }  
    </script>
  </body>
  </html>