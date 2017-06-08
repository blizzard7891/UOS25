<?php
include_once( "./db.php" );
if($_POST['td1_3'] != '#' && $_POST['td2_3'] != '#')
{
	$query = "SELECT count(*) FROM CLOSING_ACCOUNT";
	$s = oci_parse($conn,$query);
	oci_execute($s);
	while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
	{
		$seq = $row['COUNT(*)'];
	}
	oci_free_statement($s);

	$cashprofit = $_POST['td1_1'];
	$cashspending = $_POST['td1_2'];
	$cashdate = $_POST['td1_3'];
	$cashflag = "00";
	$cashamount = $cashprofit - $cashspending;
	$cardprofit = $_POST['td2_1'];
	$cardspending = $_POST['td2_2'];
	$carddate = $_POST['td2_3'];
	$cardflag="01";
	$cardamount = $cardprofit - $cardspending;
	$user = "2017001";

	
	$seq++;
	$query = "INSERT INTO CLOSING_ACCOUNT (
	CLACNT_NUM,
	CLACNT_DATE,
	CLACNT_GROUP,
	PROFIT,
	EXPENSE,
	CLACNT_AMOUNT,
	EMPLOYEE_NUM
	)
	VALUES (
	:num1,
	TO_DATE(:cashdate,'yyyy-mm-dd'),
	:flag1,
	:cashprofit,
	:cashspending,
	:cashamount,
	:user1
	)";

	$s = oci_parse( $conn, $query );

	oci_bind_by_name( $s, ':num1', $seq );
	oci_bind_by_name( $s, ':cashdate', $cashdate );
	oci_bind_by_name( $s, ':flag1', $cashflag );
	oci_bind_by_name( $s, ':cashprofit', $cashprofit );
	oci_bind_by_name( $s, ':cashspending', $cashspending );
	oci_bind_by_name( $s, ':cashamount',$cashamount );
	oci_bind_by_name( $s, ':user1',$user );

	oci_execute($s);
	oci_free_statement( $s );

	$seq++;
	$query = "INSERT INTO CLOSING_ACCOUNT (
	CLACNT_NUM,
	CLACNT_DATE,
	CLACNT_GROUP,
	PROFIT,
	EXPENSE,
	CLACNT_AMOUNT,
	EMPLOYEE_NUM
	)
	VALUES (
	:num2,
	TO_DATE(:carddate,'yyyy-mm-dd'),
	:flag2,
	:cardprofit,
	:cardspending,
	:cardamount,
	:user2
	)";

	$s = oci_parse( $conn, $query );

	oci_bind_by_name( $s, ':num2', $seq );
	oci_bind_by_name( $s, ':carddate', $carddate );
	oci_bind_by_name( $s, ':flag2', $cardflag );
	oci_bind_by_name( $s, ':cardprofit', $cardprofit );
	oci_bind_by_name( $s, ':cardspending', $cardspending );
	oci_bind_by_name( $s, ':cardamount',$cardamount );
	oci_bind_by_name( $s, ':user2',$user );
	

	oci_execute( $s );
	oci_free_statement( $s );

	oci_close( $conn );
}else
{
	echo "<script>alert('현금/카드 매출을 모두 입력 해주세요.')</script>";
}
echo("<script>location.replace('./closing_account.php');</script>"); 
?>