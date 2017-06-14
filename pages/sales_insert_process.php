
    <?php
    date_default_timezone_set('Asia/Seoul');


    if($_POST['type']=="구매물품추가"){


        if(!empty($_POST['salenum'])&&!empty($_POST['salecount'])){

        include_once("./db.php");
        $prodnum = $_POST['salenum'];
        $query = "SELECT PROD_NAME, PROD_PRICE FROM PRODUCT WHERE PROD_NUM = '$prodnum'";
        $s = oci_parse($conn,$query);
        oci_execute($s);
        $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
        $prod_price = $row['PROD_PRICE'];
        $prod_name = $row['PROD_NAME'];

        //행사상품 여부 조회
        $query = "SELECT COUNT(*) FROM EVENT_PRODUCT WHERE PROD_NUM='$prodnum'" ;   
        $s1 = oci_parse($conn,$query);
        oci_execute($s1);
        $row = oci_fetch_array($s1,OCI_RETURN_NULLS + OCI_ASSOC);
        oci_free_statement($s1);

        if($row['COUNT(*)']==0)$prod_event = "이벤트 행사없음";
        else $prod_event = "이벤트 행사중";
        
        
        $prod_count = $_POST['salecount'];
        $beforeprice = $_POST['salecount']*$prod_price;


        if($prod_event=="이벤트 행사없음"){
            $prod_sum=$beforeprice;
        }else{
                //행사 종류 조회
            $query = "SELECT EVENT_GROUP,DISCOUNT_PRICE FROM EVENT_PRODUCT WHERE PROD_NUM='$prodnum'" ;   
            $s1 = oci_parse($conn,$query);
            oci_execute($s1);
            $row = oci_fetch_array($s1,OCI_RETURN_NULLS + OCI_ASSOC);
            $eventgroup=$row['EVENT_GROUP'];
            $discount_price=$row['DISCOUNT_PRICE'];
            oci_free_statement($s1);


            if($eventgroup=='000'){ 
                $groupcnt=floor($prod_count/2);
                $prod_event="1+1 행사중";
                $prod_sum=$beforeprice-($groupcnt*$discount_price);

            }else if($eventgroup=='001'){
                $groupcnt=floor($prod_count/3);
                $prod_event="2+1 행사중";
                $prod_sum=$beforeprice-($groupcnt*$discount_price);

            }else if($eventgroup=='010'){
                $prod_event="할인 행사중";
                $prod_sum=$beforeprice-($discount_price*$prod_count);
            } 
           
        }


        //재고량 조회
        $query = "SELECT MAX(QTY) FROM EXP_DATE_MANAGEMENT WHERE PROD_NUM='$prodnum'" ;   
        $s1 = oci_parse($conn,$query);
        oci_execute($s1);
        $row = oci_fetch_array($s1,OCI_RETURN_NULLS + OCI_ASSOC);
        $stockqty=$row['MAX(QTY)'];
        oci_free_statement($s1);


        $query = "SELECT COUNT(*) FROM TEMP WHERE ATT6='$prodnum'" ;   
        $s1 = oci_parse($conn,$query);
        oci_execute($s1);
        $row = oci_fetch_array($s1,OCI_RETURN_NULLS + OCI_ASSOC);
        $double=$row['COUNT(*)'];
        oci_free_statement($s1);

        if($stockqty-$prod_count<0){
            echo "<script>alert(\"재고가 부족합니다. 현재재고량 : ".$stockqty."개\");</script>"; 

            echo( "<script>location.replace('./sales_insert.php');</script>" );
     
        }else if($double>0){
            echo "<script>alert(\"동일한 품목이 존재합니다.\");</script>"; 

            echo( "<script>location.replace('./sales_insert.php');</script>" );
     
        }else{


            $query = "INSERT INTO TEMP (
            ATT1,
            ATT2,
            ATT3,
            ATT4,
            ATT5,
            ATT6,
            ATT7
            )
            VALUES (
            :name,
            :price,
            :event,
            :count,
            :sumprice, 
            :prodnum,
            :beforeprice
            )";

            $s = oci_parse($conn,$query);
           
            oci_bind_by_name($s, ':name', $prod_name);
            oci_bind_by_name($s, ':price', $prod_price);
            oci_bind_by_name($s, ':event', $prod_event);
            oci_bind_by_name($s, ':count', $prod_count);
            oci_bind_by_name($s, ':sumprice', $prod_sum);
            oci_bind_by_name($s, ':prodnum', $prodnum);
            oci_bind_by_name($s, ':beforeprice', $beforeprice);

            oci_execute($s);
            oci_free_statement($s);
            oci_close($conn);
        }

        }else{
            echo "<script>alert(\"판매제품번호와 판매수량을 입력하세요.\");</script>"; 
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

   
        $query = "SELECT ATT6, ATT4, ATT5  FROM TEMP" ;   
        $s = oci_parse($conn,$query);
        oci_execute($s);

        while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC)){

            $prodnum=$row['ATT6'];
            $saleqty=$row['ATT4'];
            $saleamount=$row['ATT5'];
            
            // $query = "SELECT ENT_DATE FROM ENTER WHERE PROD_NUM='$prodnum'" ;   
            // $s1 = oci_parse($conn,$query);
            // oci_execute($s1);
            // $row = oci_fetch_array($s1,OCI_RETURN_NULLS + OCI_ASSOC);
            // $entdate=$row['ENT_DATE'];
            // oci_free_statement($s1);
            
            $query = "SELECT EXPDATE FROM EXP_DATE_MANAGEMENT WHERE QTY > '$saleqty' and PROD_NUM='$prodnum' ORDER BY EXPDATE ASC" ;   
            $s1 = oci_parse($conn,$query);
            oci_execute($s1);
            $row = oci_fetch_array($s1,OCI_RETURN_NULLS + OCI_ASSOC);

            $expdate = $row['EXPDATE'];
            oci_free_statement($s1);
            
            //product 테이블에서 재고량 수정
            $query = "UPDATE PRODUCT SET STOCK_QTY = (SELECT STOCK_QTY FROM PRODUCT WHERE PROD_NUM='$prodnum')-'$saleqty' WHERE PROD_NUM='$prodnum'" ;   
            $s1 = oci_parse($conn,$query);
            oci_execute($s1);
            oci_free_statement($s1);


            // 유통기한 테이블에서 재고량 수정
            $query = "UPDATE EXP_DATE_MANAGEMENT SET QTY = (SELECT QTY FROM EXP_DATE_MANAGEMENT WHERE EXPDATE='$expdate'
                    and PROD_NUM='$prodnum')-'$saleqty' WHERE EXPDATE='$expdate' and PROD_NUM='$prodnum'" ;   
            $s1 = oci_parse($conn,$query);
            oci_execute($s1);
            oci_free_statement($s1);





            $query = "INSERT INTO SALE_LIST(PROD_NUM, SALE_NUM, SALE_QTY, SALE_AMOUNT, EXPDATE  ) VALUES (:prodnum, :salenum, :saleqty, 
                            :saleamount, TO_DATE(:expdate,'yyyy/mm/dd'))";
            $s2 = oci_parse($conn,$query);

            oci_bind_by_name($s2, ':prodnum', $prodnum);//
            oci_bind_by_name($s2, ':salenum', $salenum);
            oci_bind_by_name($s2, ':saleqty', $saleqty);///
            oci_bind_by_name($s2, ':saleamount', $saleamount);//
            oci_bind_by_name($s2, ':expdate', $expdate);
            oci_execute($s2);
            oci_free_statement($s2);

            $query = "SELECT count(*) FROM RELEASE";
            $s3 = oci_parse($conn,$query);
            oci_execute($s3);
            $row = oci_fetch_array($s3,OCI_RETURN_NULLS + OCI_ASSOC);
            $releasenum = $row['COUNT(*)']+1;
            oci_free_statement($s3);


            $flag = '00';
            $query = "INSERT INTO RELEASE(SEQ_NUM, PROD_NUM, REL_DATE, REL_GROUP, QTY ) VALUES ('$releasenum','$prodnum',TO_DATE(:wdate,'dd/mm/yyyy'),'$flag','$saleqty')";
            $s4 = oci_parse($conn,$query);
            oci_bind_by_name($s4, ':wdate', $date);
            oci_execute($s4);
            oci_free_statement($s4);
        }
        oci_free_statement($s);





    $query = "SELECT count(*) FROM RECEIPT";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
    $receiptnum = $row['COUNT(*)']+1;
    oci_free_statement($s);
    
    $flag='0';
    $query = "INSERT INTO RECEIPT(RECEIPT_NUM, RECEIPT_FLAG, SALE_NUM ) VALUES ('$receiptnum','$flag','$salenum')";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    oci_free_statement($s);
 
    


    $query = "DELETE FROM TEMP";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    oci_free_statement($s);
    oci_close($conn);

    }


    
    

    
        
     // echo( "<script>location.replace('./sales_insert.php');</script>" );
    
?>