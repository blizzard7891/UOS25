<?php
session_start();
	date_default_timezone_set('Asia/Seoul');

	include_once("./db.php");
if(isset($_POST['salenum'])){
//순서번호
	$query = "SELECT COUNT(*) FROM REFUND";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
    $refundnum = $row['COUNT(*)']+1;
    oci_free_statement($s);


	$salenum=$_POST['salenum'];
	$enum = $_SESSION['empnum'];
	$date=date("Y/m/d");

//환불테이블에 추가
	$query = "INSERT INTO REFUND(REFUND_NUM, REFUND_DATE, SALE_NUM, EMPLOYEE_NUM) VALUES ('$refundnum', TO_DATE(:wdate,'yyyy/mm/dd'),'$salenum', '$enum')";
	$s = oci_parse($conn,$query);
	oci_bind_by_name($s,':wdate',$date);


	oci_execute($s);
	oci_free_statement($s);

//영수증 테이블에 추가
    $query = "SELECT count(*) FROM RECEIPT";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
    $receiptnum = $row['COUNT(*)']+1;
    oci_free_statement($s);
    
    $flag='1';
    $query = "INSERT INTO RECEIPT(RECEIPT_NUM, RECEIPT_FLAG, SALE_NUM ) VALUES ('$receiptnum','$flag','$salenum')";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    oci_free_statement($s);

//업데이트

    $query = "UPDATE SALE SET REFUND_FLAG = '1'  WHERE SALE_NUM='$salenum'" ;   
    $s1 = oci_parse($conn,$query);
    oci_execute($s1);
    oci_free_statement($s1);


//해당 품목 조회

    $query = "SELECT PROD_NUM, SALE_QTY, SALE_AMOUNT, EXPDATE FROM SALE_LIST WHERE SALE_NUM='$salenum'";
    $s1 = oci_parse($conn,$query);
    oci_execute($s1);
   

	
	while($row = oci_fetch_array($s1,OCI_RETURN_NULLS + OCI_ASSOC)){

		$query1 = "SELECT count(*) FROM ENTER";
		$s = oci_parse($conn,$query1);
		oci_execute($s);
		$res = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
		$seq = $res['COUNT(*)']+1;	
		oci_free_statement($s);


		//입고//
		$query = "INSERT INTO ENTER (
		SEQ_NUM,
		ENT_DATE,
		ENT_GROUP,
		PROD_NUM,
		QTY
		)
		VALUES (
		:seq,
		TO_DATE(:enterdate,'yyyy/mm/dd'),
		:enterflag,
		:enterproduct,
		:enterqty
		)";

		$s = oci_parse($conn,$query);
		$enterflag = '01';
		$saleqty = $row['SALE_QTY'];

		oci_bind_by_name($s, ':seq', $seq);
		oci_bind_by_name($s, ':enterdate', $date);
		oci_bind_by_name($s, ':enterflag', $enterflag);
		oci_bind_by_name($s, ':enterproduct', $row['PROD_NUM']);
		oci_bind_by_name($s, ':enterqty', $saleqty);

		oci_execute($s);
		oci_free_statement($s);



		$prodnum=$row['PROD_NUM'];
		//현재재고
		$query = "SELECT STOCK_QTY FROM PRODUCT WHERE PROD_NUM='$prodnum' " ;   
	    $s2 = oci_parse($conn,$query);
	    oci_execute($s2);
	    $res = oci_fetch_array($s2,OCI_RETURN_NULLS + OCI_ASSOC);
	    $currentqty = $res['STOCK_QTY'];
	    oci_free_statement($s2);

	    //재고수량 증가 product 테이블
	    $qty=$currentqty+$row['SALE_QTY'];
		$query = "UPDATE PRODUCT SET STOCK_QTY ='$qty' WHERE PROD_NUM='$prodnum'" ;   
	    $s2 = oci_parse($conn,$query);
	    oci_execute($s2);
	    oci_free_statement($s2);

	    // 유통기한 테이블에서 재고량 수정
	    $query = "SELECT EXPDATE FROM SALE_LIST WHERE PROD_NUM='$prodnum' and SALE_NUM='$salenum'" ;   
	    $s2 = oci_parse($conn,$query);
	    oci_execute($s2);
	    $res = oci_fetch_array($s2,OCI_RETURN_NULLS + OCI_ASSOC);
	    $expdate=$res['EXPDATE'];
	    oci_free_statement($s2);



        $query = "UPDATE EXP_DATE_MANAGEMENT SET QTY =(SELECT QTY FROM EXP_DATE_MANAGEMENT WHERE PROD_NUM='$prodnum')+:saleqty WHERE  PROD_NUM='$prodnum'" ;   
        $s2 = oci_parse($conn,$query);

        oci_bind_by_name($s2,':saleqty', $saleqty);
        oci_execute($s2);
        oci_free_statement($s2);

	    //재고수량 증가에 따른 유통관리테이블 증가
	 //    $query = "SELECT ENT_DATE FROM EXP_DATE_MANAGEMENT WHERE PROD_NUM='$prodnum' " ;   
	 //    $s2 = oci_parse($conn,$query);
	 //    oci_execute($s2);
	 //    $res = oci_fetch_array($s2,OCI_RETURN_NULLS + OCI_ASSOC);
	 //    $entdate = $res['ENT_DATE'];
	 //    oci_free_statement($s2);

	 //    $qty=$currentqty+$row['SALE_QTY'];

		// $query = "UPDATE PRODUCT SET STOCK_QTY ='$qty' WHERE PROD_NUM='$prodnum'" ;   
	 //    $s2 = oci_parse($conn,$query);
	 //    oci_execute($s2);
	 //    oci_free_statement($s2);



	}

	 oci_free_statement($s1);
	


}
	oci_close($conn);

?>