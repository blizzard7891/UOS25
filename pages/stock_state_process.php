<?php
include_once("./db.php");

if($_POST['type'] == "0")
{
	$enterproduct = $_POST['enterproduct'];
	$enterqty =  $_POST['enterqty'];
	$enterdate = date("Y/m/d");
	$enterflag = "10";
	
	$query = "SELECT count(*) FROM ENTER";
	$s = oci_parse($conn,$query);
	oci_execute($s);
	while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
	{
		$seq = $row['COUNT(*)'];
	}
	oci_free_statement($s);
	
	$seq++;

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

	oci_bind_by_name($s, ':seq', $seq);
	oci_bind_by_name($s, ':enterdate', $enterdate);
	oci_bind_by_name($s, ':enterflag', $enterflag);
	oci_bind_by_name($s, ':enterproduct', $enterproduct);
	oci_bind_by_name($s, ':enterqty', $enterqty);
	
	oci_execute($s);
	oci_free_statement($s);

	$query = "UPDATE PRODUCT 
	SET STOCK_QTY = STOCK_QTY+:enterqty
	WHERE PROD_NUM = :enterproduct";

	$s = oci_parse($conn,$query);

	oci_bind_by_name($s, ':enterproduct', $enterproduct);
	oci_bind_by_name($s, ':enterqty', $enterqty);
	
	oci_execute($s);
	oci_free_statement($s);

	oci_close($conn);
	
}elseif($_POST['type'] == "1")
{
	$releaseproduct = $_POST['releaseproduct'];
	$releaseqty =  $_POST['releaseqty'];
	$releasedate = date("Y/m/d");
	$releaseflag = "10";
	
	$query = "SELECT count(*) FROM RELEASE";
	$s = oci_parse($conn,$query);
	oci_execute($s);
	while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
	{
		$seq1 = $row['COUNT(*)'];
	}
	oci_free_statement($s);
	
	$seq1++;

	$query = "INSERT INTO RELEASE (
	SEQ_NUM,
	REL_DATE,
	REL_GROUP,
	PROD_NUM,
	QTY
	)
	VALUES (
	:seq1,
	TO_DATE(:releasedate,'yyyy/mm/dd'),
	:releaseflag,
	:releaseproduct,
	:releaseqty
	)";

	$s = oci_parse($conn,$query);

	oci_bind_by_name($s, ':seq1', $seq1);
	oci_bind_by_name($s, ':releasedate', $releasedate);
	oci_bind_by_name($s, ':releaseflag', $releaseflag);
	oci_bind_by_name($s, ':releaseproduct', $releaseproduct);
	oci_bind_by_name($s, ':releaseqty', $releaseqty);
	
	oci_execute($s);
	oci_free_statement($s);

	$query = "UPDATE PRODUCT 
	SET STOCK_QTY = STOCK_QTY-:releaseqty
	WHERE PROD_NUM = :releaseproduct";

	$s = oci_parse($conn,$query);

	oci_bind_by_name($s, ':releaseproduct', $releaseproduct);
	oci_bind_by_name($s, ':releaseqty', $releaseqty);
	
	oci_execute($s);
	oci_free_statement($s);

	oci_close($conn);
}

echo("<script>location.replace('./stock_state.php');</script>"); 
?>