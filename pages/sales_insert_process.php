
    <?php

    date_default_timezone_set('Asia/Seoul');

    if($_POST['type']=="구매물품추가"){
        if(isset($_POST['salenum'])!=null){

        include_once("./db.php");
        $prodnum = $_POST['salenum'];
        $query = "SELECT PROD_NAME, PROD_PRICE FROM PRODUCT WHERE PROD_NUM = '$prodnum'";
        $s = oci_parse($conn,$query);
        oci_execute($s);
        $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
        $prod_price = $row['PROD_PRICE'];
        $prod_name = $row['PROD_NAME'];
        $prod_event = "없음";
        $prod_count = $_POST['salecount'];
        $prod_sum = $_POST['salecount']*$prod_price;

        
        $s = oci_parse($conn,$query);
        oci_free_statement($s);
        

        $query = "INSERT INTO TEMP (
        ATT1,
        ATT2,
        ATT3,
        ATT4,
        ATT5,
        ATT6
        )
        VALUES (
        :name,
        :price,
        :event,
        :count,
        :sumprice, 
        :prodnum
        )";

        $s = oci_parse($conn,$query);
       
        oci_bind_by_name($s, ':name', $prod_name);
        oci_bind_by_name($s, ':price', $prod_price);
        oci_bind_by_name($s, ':event', $prod_event);
        oci_bind_by_name($s, ':count', $prod_count);
        oci_bind_by_name($s, ':sumprice', $prod_sum);
        oci_bind_by_name($s, ':prodnum', $prodnum);
         

        oci_execute($s);
        oci_free_statement($s);
        oci_close($conn);
        

        }
    }elseif($_POST['type']=="모두비우기"){

        include_once("./db.php");

        $query = "DELETE FROM TEMP";
        $s = oci_parse($conn,$query);
        oci_execute($s);
        oci_free_statement($s);
        oci_close($conn);

    }elseif($_POST['type']=="결제"){

    include_once("./db.php");
    
    // $id="suzin";

    // $query = "SELECT EMPLOYEE_NUM FROM LOGIN_INFO WHERE ID= $id";

    // $s = oci_parse($conn,$query);
    // oci_execute($s); 

    // while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC)){
    //     $employeenum=$row['EMPLOYEE_NUM'];
    // }   
    //  oci_free_statement($s);

    $employeenum="2017001";
    

    $query = "SELECT count(*) FROM SALE";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    
    $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
    $salenum = $row['COUNT(*)']+1;
    oci_free_statement($s);



    $date=date("Y/m/d");

    $refundflag=0;

    $saleamount=$_POST['amount'];

    if($_POST['payMethod']=="card"){
        $paymethod='01';
    }elseif($_POST['payMethod']=="cash"){
        $paymethod='00';
    }
    



    $query = "INSERT INTO SALE(SALE_NUM, SALE_DATE, REFUND_FLAG, SALE_AMOUNT, EMPLOYEE_NUM,PAY_METHOD )VALUES (:salenum, TO_DATE(:wdate,'yyyy/mm/dd'), :refundflag, :saleamount, :employeenum, :paymethod)";
    $s = oci_parse($conn,$query);
    
    oci_bind_by_name($s, ':salenum', $salenum);//
    oci_bind_by_name($s, ':wdate', $date);
    oci_bind_by_name($s, ':refundflag', $refundflag);///
    oci_bind_by_name($s, ':saleamount', $saleamount);//
    oci_bind_by_name($s, ':employeenum', $employeenum);
    oci_bind_by_name($s, ':paymethod', $paymethod);

    oci_execute($s);
    oci_free_statement($s);


    $query = "SELECT count(ATT1) FROM TEMP";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
    $salelistnum = $row['COUNT(ATT1)'];
    oci_free_statement($s);
    


    for($i = 0 ;$i < $salelistnum ; $i++){
    
        $query = "SELECT ATT6, ATT4, ATT5  FROM TEMP" ;   
        $s = oci_parse($conn,$query);
        oci_execute($s);

        while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC)){
            $prodnum=$row['ATT6'];
            $saleqty=$row['ATT4'];
            $saleamount=$row['ATT5'];
            
            
            $query = "SELECT STD_EXPDATE FROM PRODUCT WHERE PROD_NUM='$prodnum'" ;   
            $s1 = oci_parse($conn,$query);
            oci_execute($s1);
            $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
            $expdate = $row['STD_EXPDATE'];
            oci_free_statement($s1);

            $query = "INSERT INTO SALE_LIST(PROD_NUM, SALE_NUM, SALE_QTY, SALE_AMOUNT, EXPDATE  )VALUES (:prodnum, :salenum, :saleqty, :saleamount, :expdate)";
            $s1 = oci_parse($conn,$query);
            

            oci_bind_by_name($s1, ':prodnum', $prodnum);//
            oci_bind_by_name($s1, ':salenum', $salenum);
            oci_bind_by_name($s1, ':saleqty', $saleqty);///
            oci_bind_by_name($s1, ':saleamount', $saleamount);//
            oci_bind_by_name($s1, ':expdate', $expdate);
          
            oci_execute($s1);
            oci_free_statement($s1);
            
        
        }
        oci_free_statement($s);


    }

    $query = "DELETE FROM TEMP";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    oci_free_statement($s);
    oci_close($conn);

    }


    
    

    
        
    echo( "<script>location.replace('./sales_insert.php');</script>" );
    
?>