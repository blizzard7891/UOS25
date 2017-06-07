<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>판매상품</title>

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
        <h1 class="page-header">판매상품</h1>
    </div>

    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>신제품 입력</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <form action="product_list_process.php" method="POST">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>제품명</label>
                                <input type="text" name="pname" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>제품타입</label>
                                <select class="form-control" name="ptype">
                                    <option value="">제품타입선택</option>
                                    <?php
                                    include_once("db.php");
                                    $query = "SELECT PROD_CLASS_NAME FROM PRODUCT_CLASS";
                                    $stid = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));
                                    if(oci_execute($stid)==false) die("oci query error [$query] message : ".oci_error($stid));
                                    while ( ($res = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                                        $tmp = $res['PROD_CLASS_NAME'];
                                        echo "<option value=$tmp>{$res['PROD_CLASS_NAME']}</option>";
                                    }
                                    oci_free_statement($stid);
                                    ?> 
                                </select>
                            </div>
                            <div class="form-group">
                                <label>생산업체</label>
                                <select class="form-control" name="supplier">
                                    <option value="">생산업체선택</option>
                                    <?php
                                    include_once("db.php");
                                    $query = "SELECT SUPPLIER_NAME FROM SUPPLIER";
                                    $stid = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));
                                    if(oci_execute($stid)==false) die("oci query error [$query] message : ".oci_error($stid));
                                    while ( ($res = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                                        $tmp = $res['SUPPLIER_NAME'];
                                        echo "<option value=$tmp>{$res['SUPPLIER_NAME']}</option>";
                                    }
                                    oci_free_statement($stid);
                                    ?> 
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>소비자가</label>
                                <input type="number" name="customer_price" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>도매가</label>
                                <input type="number" name="supplier_price" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>표준유통기한</label>
                                <input type="number" name="limit" class="form-control">
                            </div>
                            <div class="pull-right">
                                <button type="submit" class="btn btn-primary mr-3">상품입력</button>
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
                <strong>판매상품 목록</strong>
            </div>
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable">
                    <thead>
                        <tr>
                            <th>제품번호</th>
                            <th>제품타입</th>
                            <th>제품명</th>
                            <th>소비자가</th>
                            <th>도매가</th>
                            <th>생산업체</th>
                            <th>표준유통기한(일)</th>
                            <th>재고량</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                      $stid = oci_parse($conn, 'SELECT COUNT(*) FROM PRODUCT') or die('oci parse error: '.oci_error($conn));
                      if(oci_execute($stid) == false) die("oci query error [$query] message : ".oci_error($stid));
                      while (($res = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                        $count = $res['COUNT(*)'];
                    }
                    oci_free_statement($stid);

                    $query1 = "SELECT * FROM PRODUCT";
                    $stid1 = oci_parse($conn, $query1) or die('oci parse error: '.oci_error($conn));
                    if(oci_execute($stid1) == false) die("oci query error [$query] message : ".oci_error($stid1));
                    while (($res1 = oci_fetch_array($stid1, OCI_ASSOC)) != false) {
                      if(isset($res1['STD_EXPDATE']))
                      {
                        $edate = $res1['STD_EXPDATE'];
                        
                    }else
                    {
                        $edate = '#';
                    }
                    $query2 = "SELECT PROD_CLASS_NAME FROM PRODUCT_CLASS WHERE PROD_CLASS_NUM = (SELECT PROD_CLASS_NUM FROM PRODUCT WHERE PROD_NAME = :pname)";
                    $stid2 = oci_parse($conn, $query2) or die('oci parse error: '.oci_error($conn));
                    oci_bind_by_name($stid2, ':pname', $res1['PROD_NAME']);
                    if(oci_execute($stid2) == false) die("oci query error [$query] message : ".oci_error($stid2));
                    while (($res2 = oci_fetch_array($stid2, OCI_ASSOC)) != false) {
                        $tmp1 = $res2['PROD_CLASS_NAME'];
                    }

                    $query3 = "SELECT SUPPLIER_NAME FROM SUPPLIER WHERE SUPPLIER_NUM = (SELECT SUPPLIER_NUM FROM PRODUCT WHERE PROD_NAME = :pname)";
                    $stid3 = oci_parse($conn, $query3) or die('oci parse error: '.oci_error($conn));
                    oci_bind_by_name($stid3, ':pname', $res1['PROD_NAME']);
                    if(oci_execute($stid3) == false) die("oci query error [$query] message : ".oci_error($stid3));
                    while (($res3 = oci_fetch_array($stid3, OCI_ASSOC)) != false) {
                        $tmp2 = $res3['SUPPLIER_NAME'];
                    }

                    echo "<tr>";
                    echo "<td>{$res1['PROD_NUM']}</td>";
                    echo "<td>$tmp1</td>";
                    echo "<td>{$res1['PROD_NAME']}</td>";
                    echo "<td>{$res1['PROD_PRICE']}</td>";
                    echo "<td>{$res1['PROD_WSALE_PRICE']}</td>";
                    echo "<td>$tmp2</td>";
                    echo "<td>$edate</td>";
                    echo "<td>{$res1['STOCK_QTY']}</td>";
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
</script>
</body>
</html>
