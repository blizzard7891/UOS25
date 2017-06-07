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
              <option value="아르바이트">아르바이트</option>
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
                <th>해고일자</th>
              </tr>
            </thead>
            <tbody>
          <?
            include_once("db.php");
            $stid = oci_parse($conn, 'SELECT COUNT(*) FROM EMPLOYEE') or die('oci parse error: '.oci_error($conn));
            if(oci_execute($stid) == false) die("oci query error [$query] message : ".oci_error($stid));
            while (($res = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                $count = $res['COUNT(*)'];
            }
            oci_free_statement($stid);

            $query = "SELECT * FROM EMPLOYEE";
            $stid = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));
            if(oci_execute($stid) == false) die("oci query error [$query] message : ".oci_error($stid));
            while (($res = oci_fetch_array($stid, OCI_ASSOC)) != false) {
              if(isset($res['DISCHARGE_DATE']))
              {
                $ddate = $res['DISCHARGE_DATE'];
                
              }else
              {
                $ddate = '#';
              }
              echo "<tr>";
              echo "<td> {$res['EMPLOYEE_NUM']} </td>";
              echo "<td> {$res['NAME']} </td>";
              echo "<td> {$res['RANK']} </td>";
              echo "<td> {$res['PHONE_NUM']} </td>";
              echo "<td> {$res['ADR']} </td>";
              echo "<td> {$res['BIRTH']} </td>";
              echo "<td> {$res['EMPLOYMENT_DATE']} </td>";
              echo "<td> $ddate </td>";
              echo "</tr>";
            }
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