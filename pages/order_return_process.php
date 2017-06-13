<?php
include_once("./db.php");

$onum = $_POST['onum'];

$query1 = "SELECT RETURN_NUM FROM ORDER_INFO WHERE ORDER_NUM = :onum";
$compiled1 = oci_parse($conn, $query1);
oci_bind_by_name($compiled1, ':onum', $onum);
oci_execute($compiled1);
$res1 = oci_fetch_array($compiled1, OCI_RETURN_NULLS+ OCI_ASSOC);
$returnnum = $res1['RETURN_NUM'];
oci_free_statement($compiled1);

$pname = $_POST['pname'];

$query2 = "SELECT PROD_NUM FROM PRODUCT WHERE PROD_NAME = :pname";

$compiled2 = oci_parse($conn, $query2);
oci_bind_by_name($compiled2, ':pname', $pname);
oci_execute($compiled2);
$res2 = oci_fetch_array($compiled2, OCI_RETURN_NULLS+ OCI_ASSOC);

$pnum = $res2['PROD_NUM'];

oci_free_statement($compiled2);

$returndate = date('y/m/d');
$rqty = $_POST['returnqty'];
$ramt = $_POST['returnamt'];
$flag = $_POST['flag'];
$enum = "170601";



//환불번호 생성 필요
if(is_null($returnnum)){
	if($flag == "입고후") {

		$query1 = "SELECT count(*) FROM RETURN";

		$compiled1 = oci_parse($conn, $query1);
		oci_execute($compiled1);
		$res1 = oci_fetch_array($compiled1, OCI_RETURN_NULLS + OCI_ASSOC);

		$returnnum = $res1['COUNT(*)'];
		$returnnum++;
		$rand = mt_rand(1,999);
		if($returnnum <10){
			$returnnum = date('ymd')."000".$returnnum;
		}else if($returnnum <100){
			$returnnum = date('ymd')."00".$returnnum;
		}else if($returnnum <1000){
			$returnnum = date('ymd')."0".$returnnum;
		}

		if($rand <10){
			$returnnum = $returnnum."00".$rand;
		}else if($rand <100){
			$returnnum = $returnnum."0".$rand;
		}else{
			$returnnum = $returnnum.$rand;
		}


		$query2 = "INSERT INTO RETURN (
		RETURN_NUM,
		RETURN_GROUP,
		RETURN_DATE,
		RETURN_AMT,
		EMPLOYEE_NUM
		)
		VALUES (
		:rnum,
		'00',
		TO_DATE(:rdate,'yy/mm/dd'),
		:ramt,
		:enum
		)
		";

		$compiled2 = oci_parse($conn, $query2);
		oci_bind_by_name($compiled2, ':rnum', $returnnum);
		oci_bind_by_name($compiled2, ':rdate', $returndate);
		oci_bind_by_name($compiled2, ':ramt', $ramt);
		oci_bind_by_name($compiled2, ':enum', $enum);

		oci_execute($compiled2);

		

		$query3 = "INSERT INTO RETURN_LIST (
		PROD_NUM,
		RETURN_NUM,
		QTY,
		RETURN_AMOUNT
		)
		VALUES (
		:pnum,
		:rnum,
		:qty,
		:amt
		)
		";

		$compiled3 = oci_parse($conn, $query3);
		oci_bind_by_name($compiled3, ':pnum', $pnum);
		oci_bind_by_name($compiled3, ':rnum', $returnnum);
		oci_bind_by_name($compiled3, ':qty', $rqty);
		oci_bind_by_name($compiled3, ':amt', $ramt);

		oci_execute($compiled3);

		$query4 = "SELECT count(*) FROM RELEASE";

		$compiled4 = oci_parse($conn, $query4);
		oci_execute($compiled4);
		$res4 = oci_fetch_array($compiled4, OCI_RETURN_NULLS + OCI_ASSOC);

		$seq = $res4['COUNT(*)'];

		$seq++;

		$query5 = "INSERT INTO RELEASE (
		REL_GROUP,
		REL_DATE,
		QTY,
		PROD_NUM,
		SEQ_NUM
		)
		VALUES (
		'01',
		TO_DATE(:rdate,'yy/mm/dd'),
		:rqty,
		:pnum,
		:seq
		)
		";

		$compiled5 = oci_parse($conn, $query5);
		oci_bind_by_name($compiled5, ':rdate', $returndate);
		oci_bind_by_name($compiled5, ':rqty', $rqty);
		oci_bind_by_name($compiled5, ':pnum', $pnum);
		oci_bind_by_name($compiled5, ':seq', $seq);

		oci_execute($compiled5);

		$query6 = "UPDATE ORDER_INFO SET RETURN_NUM = :rnum WHERE  ORDER_NUM = :onum";
		$compiled6 = oci_parse($conn, $query6);

		oci_bind_by_name($compiled6, 'rnum', $returnnum);
		oci_bind_by_name($compiled6, 'onum', $onum);

		oci_execute($compiled6);


		$query7 = "UPDATE ORDER_LIST SET RETURN_NUM = :rnum WHERE  ORDER_NUM = :onum AND PROD_NUM = :pnum";
		$compiled7 = oci_parse($conn, $query7);

		oci_bind_by_name($compiled7, 'rnum', $returnnum);
		oci_bind_by_name($compiled7, 'onum', $onum);
		oci_bind_by_name($compiled7, 'pnum', $pnum);

		oci_execute($compiled7);



		oci_free_statement($compiled1);
		oci_free_statement($compiled2);
		oci_free_statement($compiled3);
		oci_free_statement($compiled4);
		oci_free_statement($compiled5);
		oci_free_statement($compiled6);
		oci_free_statement($compiled7);


		oci_close($conn);

	}else if($flag == "입고전"){

		$query1 = "SELECT count(*) FROM RETURN";

		$compiled1 = oci_parse($conn, $query1);
		oci_execute($compiled1);
		$res1 = oci_fetch_array($compiled1, OCI_RETURN_NULLS + OCI_ASSOC);

		$returnnum = $res1['COUNT(*)'];
		$returnnum = $returnnum + 1;
		$rand = mt_rand(1,999);
		if($returnnum <10){
			$returnnum = date('ymd')."000".$returnnum;
		}else if($returnnum <100){
			$returnnum = date('ymd')."00".$returnnum;
		}else if($returnnum <1000){
			$returnnum = date('ymd')."0".$returnnum;
		}

		if($rand <10){
			$returnnum = $returnnum."00".$rand;
		}else if($rand <100){
			$returnnum = $returnnum."0".$rand;
		}else{
			$returnnum = $returnnum.$rand;
		}

		$query2 = "INSERT INTO RETURN (
		RETURN_NUM,
		RETURN_GROUP,
		RETURN_DATE,
		RETURN_AMT,
		EMPLOYEE_NUM
		)
		VALUES (
		:rnum,
		'01',
		TO_DATE(:rdate,'yy/mm/dd'),
		:ramt,
		:enum
		)
		";

		$compiled2 = oci_parse($conn, $query2);
		oci_bind_by_name($compiled2, ':rnum', $returnnum);
		oci_bind_by_name($compiled2, ':rdate', $returndate);
		oci_bind_by_name($compiled2, ':ramt', $ramt);
		oci_bind_by_name($compiled2, ':enum', $enum);

		oci_execute($compiled2);


		$query3 = "INSERT INTO RETURN_LIST (
		PROD_NUM,
		RETURN_NUM,
		QTY,
		RETURN_AMOUNT
		)
		VALUES (
		:pnum,
		:rnum,
		:qty,
		:amt
		)
		";

		$compiled3 = oci_parse($conn, $query3);
		oci_bind_by_name($compiled3, ':pnum', $pnum);
		oci_bind_by_name($compiled3, ':rnum', $returnnum);
		oci_bind_by_name($compiled3, ':qty', $rqty);
		oci_bind_by_name($compiled3, ':amt', $ramt);

		oci_execute($compiled3);

		$query4 = "UPDATE ORDER_INFO SET RETURN_NUM = :rnum WHERE  ORDER_NUM = :onum";
		$compiled4 = oci_parse($conn, $query4);

		oci_bind_by_name($compiled4, 'rnum', $returnnum);
		oci_bind_by_name($compiled4, 'onum', $onum);

		oci_execute($compiled4);

		$query5 = "UPDATE ORDER_LIST SET RETURN_NUM = :rnum WHERE  ORDER_NUM = :onum AND PROD_NUM = :pnum";
		$compiled5 = oci_parse($conn, $query5);

		oci_bind_by_name($compiled5, 'rnum', $returnnum);
		oci_bind_by_name($compiled5, 'onum', $onum);
		oci_bind_by_name($compiled5, 'pnum', $pnum);

		oci_execute($compiled5);

		oci_free_statement($compiled1);
		oci_free_statement($compiled2);
		oci_free_statement($compiled3);
		oci_free_statement($compiled4);
		oci_free_statement($compiled5);

		oci_close($conn);
	}	
}
//환불번호 생성 불필요
else{
	if($flag == "입고후") {

		$query1 = "INSERT INTO RETURN_LIST (
		PROD_NUM,
		RETURN_NUM,
		QTY,
		RETURN_AMOUNT
		)
		VALUES (
		:pnum,
		:rnum,
		:qty,
		:amt
		)
		";

		$compiled1 = oci_parse($conn, $query1);
		oci_bind_by_name($compiled1, ':pnum', $pnum);
		oci_bind_by_name($compiled1, ':rnum', $returnnum);
		oci_bind_by_name($compiled1, ':qty', $rqty);
		oci_bind_by_name($compiled1, ':amt', $ramt);

		oci_execute($compiled1);

		$query2 = "SELECT count(*) FROM RELEASE";

		$compiled2 = oci_parse($conn, $query2);
		oci_execute($compiled2);
		$res2 = oci_fetch_array($compiled2, OCI_RETURN_NULLS + OCI_ASSOC);

		$seq = $res2['COUNT(*)'];

		$seq++;

		$query3 = "INSERT INTO RELEASE (
		REL_GROUP,
		REL_DATE,
		QTY,
		PROD_NUM,
		SEQ_NUM
		)
		VALUES (
		'01',
		TO_DATE(:rdate,'yy/mm/dd'),
		:rqty,
		:pnum,
		:seq
		)
		";

		$compiled3 = oci_parse($conn, $query3);
		oci_bind_by_name($compiled3, ':rdate', $returndate);
		oci_bind_by_name($compiled3, ':rqty', $rqty);
		oci_bind_by_name($compiled3, ':pnum', $pnum);
		oci_bind_by_name($compiled3, ':seq', $seq);

		oci_execute($compiled3);

		$query4 = "UPDATE ORDER_INFO SET RETURN_NUM = :rnum WHERE  ORDER_NUM = :onum";
		$compiled4 = oci_parse($conn, $query4);

		oci_bind_by_name($compiled4, 'rnum', $returnnum);
		oci_bind_by_name($compiled4, 'onum', $onum);

		oci_execute($compiled4);


		$query5 = "UPDATE ORDER_LIST SET RETURN_NUM = :rnum WHERE  ORDER_NUM = :onum AND PROD_NUM = :pnum";
		$compiled5 = oci_parse($conn, $query5);

		oci_bind_by_name($compiled5, 'rnum', $returnnum);
		oci_bind_by_name($compiled5, 'onum', $onum);
		oci_bind_by_name($compiled5, 'pnum', $pnum);

		oci_execute($compiled5);

		oci_free_statement($compiled1);
		oci_free_statement($compiled2);
		oci_free_statement($compiled3);
		oci_free_statement($compiled4);
		oci_free_statement($compiled5);

		oci_close($conn);

	}else if($flag == "입고전"){

		$query1 = "INSERT INTO RETURN_LIST (
		PROD_NUM,
		RETURN_NUM,
		QTY,
		RETURN_AMOUNT
		)
		VALUES (
		:pnum,
		:rnum,
		:qty,
		:amt
		)
		";

		$compiled1 = oci_parse($conn, $query1);
		oci_bind_by_name($compiled1, ':pnum', $pnum);
		oci_bind_by_name($compiled1, ':rnum', $returnnum);
		oci_bind_by_name($compiled1, ':qty', $rqty);
		oci_bind_by_name($compiled1, ':amt', $ramt);

		oci_execute($compiled1);

		$query2 = "UPDATE ORDER_INFO SET RETURN_NUM = :rnum WHERE  ORDER_NUM = :onum";
		$compiled2 = oci_parse($conn, $query2);

		oci_bind_by_name($compiled2, 'rnum', $returnnum);
		oci_bind_by_name($compiled2, 'onum', $onum);

		oci_execute($compiled2);

		$query3 = "UPDATE ORDER_LIST SET RETURN_NUM = :rnum WHERE  ORDER_NUM = :onum AND PROD_NUM = :pnum";
		$compiled3 = oci_parse($conn, $query3);

		oci_bind_by_name($compiled3, 'rnum', $returnnum);
		oci_bind_by_name($compiled3, 'onum', $onum);
		oci_bind_by_name($compiled3, 'pnum', $pnum);

		oci_execute($compiled3);

		oci_free_statement($compiled1);
		oci_free_statement($compiled2);
		oci_free_statement($compiled3);


		oci_close($conn);
	}

}


echo " 
<script> 
	history.back();
</script> 
"; 




?>