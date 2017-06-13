<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>행사 상품</title>

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
        <h1 class="page-header">행사 상품</h1>
    </div>

    <div class="col-lg-12">
        <div class="panel panel-blue">
            <div class="panel-heading">
                <strong>행사상품 등록</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <form action="product_event_process.php" method="POST">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>행사구분</label>
                                 <select class="form-control" name="eventclass" required>
                                    <option value="">행사선택</option>
                                    <?php
                                        echo "<option value='1+1'>1+1 Event</option>";
                                        echo "<option value='2+1'>2+1 Event</option>";
                                        echo "<option value='할인행사'>할인행사</option>";
                                    ?> 
                                </select>
                            </div>
                            <div class="form-group">
                                 <label type="hidden">할인율(%)</label>
                                 <input name="eventprice" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>행사품목</label>
                                <select class="form-control" name="eventname" required>
                                    <option value="a">행사상품선택</option>
                                    <?php

                                    include_once("db.php");
                                    $query = "SELECT PROD_NAME FROM PRODUCT";
                                    $stid = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));
                                    if(oci_execute($stid)==false) die("oci query error [$query] message : ".oci_error($stid));
                                    while ( ($res = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                                        $tmp = $res['PROD_NAME'];
                                        echo "<option value='".$tmp."'>{$res['PROD_NAME']}</option>";
                                    }
                                    oci_free_statement($stid);
                                    ?> 
                                </select>
                            </div>
                            
                            <div class="pull-right mt-5">
                                <button type="submit" class="btn btn-primary mr-3 ">등록</button>
                                <button type="reset" class="btn btn-primary">입력리셋</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <form action="product_event.php" method="POST">
    <div class="col-lg-12">
        <div class="panel panel-blue">
            <div class="panel-heading">
                <strong>행사상품 목록조회</strong>
            </div>
            <div class="panel-body">
                <div class="col-lg-6"> 
                </div>
                
                <div class="col-lg-6">
                    <div class="col-lg-11">
                    <select class="form-control mb-4" name="class" required>
                            <option id='event' value="">행사선택</option>
                            <option value='1+1'>1+1 Event</option>
                            <option value='2+1'>2+1 Event</option>
                            <option value='할인행사'>할인행사</option>
                                
                    </select>
                    </div>

                     <div class="col-lg-1 pl-0">
                        <input type="submit" class="btn btn-danger" name="type1" value="조회">
                    </div>
                </div>


                <div class="col-lg-12">
                     <table width="100%" class="table table-striped table-bordered table-hover " id="myTable">
                                <thead>
                                    <tr>
                                        <th>품목명</th>
                                        <th>품목가격</th>
                                        <th>품목수량</th>
                                        <th>할인전 가격</th>
                                        <th>할인후 가격</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        if(isset($_POST['type1'])&&$_POST['type1']=="조회"){
                                            include_once("db.php");
                                            
                                            $num=0;
                                            
                                            if($_POST['class']=='1+1'){
                                                $class='000';
                                                $num=2;
                                            } 
                                            elseif($_POST['class']=='2+1'){
                                                $class='001';
                                                $num=3;
                                            } 
                                            elseif($_POST['class']=='할인행사'){
                                                $class='010';
                                                $num=1;
                                            } else{
                                                $class='011';
                                            }

                                            $query = "SELECT prod_num, discount_price FROM EVENT_PRODUCT WHERE event_group='$class'";
                                            $s = oci_parse($conn,$query);
                                            
                                            oci_execute($s);
                                            
                                            while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC)){

                                                 $prodnum = $row['PROD_NUM'];
                                                 $discountprice = $row['DISCOUNT_PRICE'];

                                               

                                                // 해당 품목 가격, 이름 찾기
                                                $query = "SELECT PROD_NAME, PROD_PRICE FROM PRODUCT WHERE prod_num='$prodnum'";
                                                $s1 = oci_parse($conn,$query);
                                                oci_execute($s1);
                                                $row = oci_fetch_array($s1,OCI_RETURN_NULLS + OCI_ASSOC);
                                                $prodname = $row['PROD_NAME'];
                                                $prodprice = $row['PROD_PRICE'];
                                                oci_free_statement($s1);

                                                echo "<tr>";
                                                echo "<td>".$prodname."</td>";
                                                echo "<td>".$prodprice."</td>";
                                                echo "<td>".$num."</td>";
                                                echo "<td>".($prodprice*$num)."원</td>";
                                                echo "<td>".($prodprice*$num-$discountprice)."원</td>";
                                                echo "</tr>";

                                            }
                                             oci_free_statement($s);
                                            oci_close($conn);
                                        }
                                    ?>

                                </tbody>
                        </tbody>
                    </table>
                </div>
         </div>
    </div>
</div>
</form>

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
