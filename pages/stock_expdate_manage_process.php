<?php
	include_once("./db.php");

	$query = "SELECT count(*) FROM RELEASE";
	$s = oci_parse($conn,$query);
	oci_execute($s);
	while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
	{
		$seq = $row['COUNT(*)'];
	}
	oci_free_statement($s);
	
	

	$query2 = "SELECT PROD_NUM,QTY FROM EXP_DATE_MANAGEMENT WHERE TO_CHAR(EXPDATE,'yyyymmdd') = TO_CHAR(sysdate,'yyyymmdd')";
	$s2 = oci_parse($conn,$query2);
	oci_execute($s2);
	while($row = oci_fetch_array($s2, OCI_RETURN_NULLS + OCI_ASSOC))
	{
		$seq++;
		$releaseproduct = $row['PROD_NUM'];
		$releaseqty =  $row['QTY'];
		$releasedate = date("Y/m/d");
		$releaseflag = "10";
		
		if($releaseqty != 0){
			$query = "INSERT INTO RELEASE (
			SEQ_NUM,
			REL_DATE,
			REL_GROUP,
			PROD_NUM,
			QTY
			)
			VALUES (
			:seq,
			TO_DATE(:releasedate,'yyyy/mm/dd'),
			:releaseflag,
			:releaseproduct,
			:releaseqty
			)";

			$s = oci_parse($conn,$query);

			oci_bind_by_name($s, ':seq', $seq);
			oci_bind_by_name($s, ':releasedate', $releasedate);
			oci_bind_by_name($s, ':releaseflag', $releaseflag);
			oci_bind_by_name($s, ':releaseproduct', $releaseproduct);
			oci_bind_by_name($s, ':releaseqty', $releaseqty);

			oci_execute($s);
			oci_free_statement($s);
			
			$query = "UPDATE PRODUCT SET stock_qty = stock_qty-:releaseqty WHERE prod_num = :releaseproduct";
			
			$s = oci_parse($conn,$query);

			oci_bind_by_name($s, ':releaseproduct', $releaseproduct);
			oci_bind_by_name($s, ':releaseqty', $releaseqty);

			oci_execute($s);
			oci_free_statement($s);
		}
	}

	$query = "UPDATE EXP_DATE_MANAGEMENT SET QTY=0 WHERE TO_CHAR(EXPDATE,'YYYYMMDD') = TO_CHAR(SYSDATE,'YYYYMMDD') AND QTY NOT IN(0)";
	$s = oci_parse($conn,$query);
	oci_execute($s);
	oci_free_statement($s);

	oci_close($conn);

	echo("<script>location.replace('./stock_expdate_manage.php');</script>"); 
?>