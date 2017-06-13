<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>판매</title>

    <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">
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
        <h1 class="page-header">판매</h1>
    </div>

    <div class="col-lg-6">
        <div class="panel panel-blue">
            <div class="panel-heading">
                <strong>판매 입력</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="./sales_insert_process.php" method="POST">   
                            
                            <div class="form-group">
                                <label>판매제품번호</label>
                                <input type="text" class="form-control" name="salenum">
                            </div>

                            <div class="form-group mb-5">
                                <label>판매수량</label>
                                <input type="number" class="form-control" name="salecount">
                            </div>
                           
                                <div class="pull-right ">
                                <input type="submit" class="btn btn-primary mb-5 " name="type" value="구매물품추가">
                                     
                                </div>

                              
                                <div class="pull-right ">
                                    <input type="submit" class="btn btn-primary mb-5 mr-3" name="type" value="모두비우기">
                                </div>

                            <table class="table table-striped table-bordered table-hover mt-5">
                                    <thead>
                                        <tr>
                                            <th>판매제품명</th>
                                            <th>판매가격</th>
                                            <th>행사여부</th>
                                            <th>총 수량</th>
                                            <th>원 가격</th>
                                            <th>할인 후 가격</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    
                                    <?php
                                         include_once("db.php");

                                        function do_fetch($s)
                                        {
                                            while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
                                            {
                                                echo "<tr>";
                                                foreach ($row as $item) 
                                                {
                                                        echo "<td>".($item?htmlentities($item):'&nbsp;')."</td>";
                                                }
                                                echo "</tr>";
                                            }
                                        }

                                        $query = "SELECT ATT1,ATT2,ATT3,ATT4,ATT7,ATT5 FROM TEMP";
                                        $s = oci_parse($conn,$query);



                                        oci_execute($s);
                                        do_fetch($s);
                                        oci_free_statement($s);
                                    

                                        $query = "SELECT SUM(ATT4),SUM(ATT5) FROM TEMP";
                                        $s = oci_parse($conn,$query);

                                        oci_execute($s);
                                        $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
                                        $countsum=$row['SUM(ATT4)'];
                                        $pricesum=$row['SUM(ATT5)'];
                                    
                                        oci_close($conn);
                                    

                                    ?>
                                                                
                                 </tbody> 
                            </table>

                                <br>
                                <label class="mr-2">총 수량: </label>
                                <label class="mr-2"><?php echo $countsum?> 개</label>
                                <br>
                                <label class="mr-2">총 금액: </label>
                                <label class="mr-2"><?php echo $pricesum?> 원</label>
                           
                            </div>
                      




                        
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="panel panel-blue">
            <div class="panel-heading">
                <strong>결제 정보</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-12">
                            <strong>결제금액 : </strong>
                            <input type="text" class="form-control" name="amount" value=<?php echo $pricesum?>>
                        </div>
                        <div class="col-lg-12 mt-5">
                            <strong>결제자 : </strong>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col-lg-12 mt-5">
                            
                            <div class="form-group">
                                <label class="mr-2">결제수단: </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payMethod" value="cash" checked>현금
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="payMethod" value="card">카드
                                </label>
                            </div>
                            <div class="pull-right">
                                <input type="submit" class="btn btn-primary" name="type" value="결제">
                           
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
  
    <!-- <!— jQuery —> -->
    <script src="../js/jquery.js"></script>
    <!-- <!— Bootstrap Core JavaScript —> -->
    <script src="../js/bootstrap.js"></script>
    <!-- <!— Metis Menu Plugin JavaScript —> -->
    <script src="../js/metisMenu.js"></script>
    <!-- <!— DataTables JavaScript —> -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
    <!-- <!— Custom Theme JavaScript —> -->
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