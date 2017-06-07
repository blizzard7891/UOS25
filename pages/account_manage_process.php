<?php 
include_once("./db.php");

if(isset($_POST['amount']))
{	
	$date = date("Y/m/d");
	$desc = $_POST['description'];
	$amount = $_POST['amount'];
	if($_POST['flag'] == "inAccount")
		$flag = '00';
	else
		$flag = '01';
	
	if($_POST['paymethod'] == "cash")
		$paymethod = '00';
	else
		$paymethod = '01';
	
	
//get count of row
	$query = "SELECT count(*) FROM MONEY_MANAGEMENT";
	$s = oci_parse($conn,$query);
	oci_execute($s);
	while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
	{
		$seq = $row['COUNT(*)'];
	}
	oci_free_statement($s);
	

//get remain_amount
	$query = "SELECT remain_amount FROM MONEY_MANAGEMENT WHERE seq_num = (SELECT MAX(seq_num) FROM MONEY_MANAGEMENT WHERE pay_method = :paymethod)";
	$s = oci_parse($conn,$query);
	oci_bind_by_name($s, ':PAYMETHOD', $paymethod);
	oci_execute($s);
	while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
	{
		$ramount = $row['REMAIN_AMOUNT'];
	}
	oci_free_statement($s);
	

	if($flag=='00')
		$ramount = $ramount + $amount;
	elseif($flag=='01')
		$ramount = $ramount - $amount;
	
	if($ramount < 0){
		echo"<script>alert('잔여액이 마이너스입니다. 바르게 입력해 주세요.');</script>";
	}else{
		$seq = $seq + 1;

		$query = "INSERT INTO MONEY_MANAGEMENT (
		SEQ_NUM,
		CHANGE_DATE,
		DESCRIPTION,
		FLAG,
		PAY_METHOD,
		AMOUNT,
		REMAIN_AMOUNT 
		)
		VALUES (
		:seq,
		TO_DATE(:wdate,'yyyy/mm/dd'),
		:description,
		:flag,
		:paymethod,
		:amount,
		:ramount 
		)";

		$s = oci_parse($conn,$query);

		oci_bind_by_name($s, ':seq', $seq);
		oci_bind_by_name($s, ':wdate', $date);
		oci_bind_by_name($s, ':description', $desc);
		oci_bind_by_name($s, ':flag', $flag);
		oci_bind_by_name($s, ':paymethod', $paymethod);
		oci_bind_by_name($s, ':amount', $amount);
		oci_bind_by_name($s, ':ramount', $ramount);

		oci_execute($s);
		oci_free_statement($s);

		oci_close($conn);
}

echo("<script>location.replace('./account_manage.php');</script>"); 
}else
{

}

?>