<?php 
include_once("./db.php");

if(isset($_POST['writedate']))
{	
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
$query = "SELECT remain_amount FROM MONEY_MANAGEMENT WHERE seq_num = :num";
$s = oci_parse($conn,$query);
oci_bind_by_name($s, ':NUM', $seq);
oci_execute($s);

while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
{
	$ramount = $row['REMAIN_AMOUNT'];
}
oci_free_statement($s);

$seq = $seq + 1;
$date = $_POST['writedate'];
$desc = $_POST['description'];
$amount = $_POST['amount'];
if($_POST['flag'] == "inAccount"){
	$flag = 0;
	$ramount = $ramount + $amount;
}
else{
	$flag = 1;
	$ramount = $ramount - $amount;
}

$query = "INSERT INTO MONEY_MANAGEMENT (
SEQ_NUM,
CHANGE_DATE,
DESCRIPTION,
FLAG,
AMOUNT,
REMAIN_AMOUNT 
)
VALUES (
:seq,
TO_DATE(:wdate,'yyyy/mm/dd'),
:description,
:flag,
:amount,
:ramount 
)";

$s = oci_parse($conn,$query);

oci_bind_by_name($s, ':seq', $seq);
oci_bind_by_name($s, ':wdate', $date);
oci_bind_by_name($s, ':description', $desc);
oci_bind_by_name($s, ':flag', $flag);
oci_bind_by_name($s, ':amount', $amount);
oci_bind_by_name($s, ':ramount', $ramount);

oci_execute($s);
oci_free_statement($s);

oci_close($conn);

echo("<script>location.replace('./account_manage.php');</script>"); 
}else
{

}

 ?>