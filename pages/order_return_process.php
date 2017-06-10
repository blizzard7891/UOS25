<?php
include_once("./db.php");

$returndate = date('y/m/d');
$pname = $_POST['pname'];
$rqty = $_POST['returnqty'];
$ramt = $_POST['returnamt'];
$flag = $_POST['flag'];
$enum = "170601";


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

	$query3 = "SELECT PROD_NUM FROM PRODUCT WHERE PROD_NAME = :pname";

	$compiled3 = oci_parse($conn, $query3);
	oci_bind_by_name($compiled3, ':pname', $pname);
	oci_execute($compiled3);
	$res3 = oci_fetch_array($compiled3, OCI_RETURN_NULLS+ OCI_ASSOC);

	$pnum = $res3['PROD_NUM'];

	$query4 = "INSERT INTO RETURN_LIST (
	PROD_NUM,
	RETURN_NUM,
	수량,
	반품액수
	)
	VALUES (
	:pnum,
	:rnum,
	:qty,
	:amt
	)
	";

	$compiled4 = oci_parse($conn, $query4);
	oci_bind_by_name($compiled4, ':pnum', $pnum);
	oci_bind_by_name($compiled4, ':rnum', $returnnum);
	oci_bind_by_name($compiled4, ':qty', $rqty);
	oci_bind_by_name($compiled4, ':amt', $ramt);

	oci_execute($compiled4);

	$query5 = "SELECT count(*) FROM RELEASE";

	$compiled5 = oci_parse($conn, $query5);
	oci_execute($compiled5);
	$res5 = oci_fetch_array($compiled5, OCI_RETURN_NULLS + OCI_ASSOC);

	$seq = $res5['COUNT(*)'];

	$seq++;

	$query6 = "INSERT INTO RELEASE (
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

	$compiled6 = oci_parse($conn, $query6);
	oci_bind_by_name($compiled6, ':rdate', $returndate);
	oci_bind_by_name($compiled6, ':rqty', $rqty);
	oci_bind_by_name($compiled6, ':pnum', $pnum);
	oci_bind_by_name($compiled6, ':seq', $seq);

	oci_execute($compiled6);

	oci_free_statement($compiled1);
	oci_free_statement($compiled2);
	oci_free_statement($compiled3);
	oci_free_statement($compiled4);
	oci_free_statement($compiled5);
	oci_free_statement($compiled6);

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


	$query3 = "SELECT PROD_NUM FROM PRODUCT WHERE PROD_NAME = :pname";

	$compiled3 = oci_parse($conn, $query3);
	oci_bind_by_name($compiled3, ':pname', $pname);
	oci_execute($compiled3);
	$res3 = oci_fetch_array($compiled3, OCI_RETURN_NULLS+ OCI_ASSOC);

	$pnum = $res3['PROD_NUM'];


	$query4 = "INSERT INTO RETURN_LIST (
	PROD_NUM,
	RETURN_NUM,
	수량,
	반품액수
	)
	VALUES (
	:pnum,
	:rnum,
	:qty,
	:amt
	)
	";

	$compiled4 = oci_parse($conn, $query4);
	oci_bind_by_name($compiled4, ':pnum', $pnum);
	oci_bind_by_name($compiled4, ':rnum', $returnnum);
	oci_bind_by_name($compiled4, ':qty', $rqty);
	oci_bind_by_name($compiled4, ':amt', $ramt);

	oci_execute($compiled4);

	oci_free_statement($compiled1);
	oci_free_statement($compiled2);
	oci_free_statement($compiled3);
	oci_free_statement($compiled4);

	oci_close($conn);
}

echo " 
<script> 
	history.back();
</script> 
"; 




?>