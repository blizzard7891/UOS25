<?php

include_once("./db.php");
$pname = $_POST['pname'];
$onum = $_POST['onum'];
$enterqty = $_POST['enterqty'];
$enterdate = date("Y/m/d");
$enterflag = "00";


$query1 = "SELECT count(*) FROM ENTER";
$s = oci_parse($conn,$query1);
oci_execute($s);
$res1 = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
$seq = $res1['COUNT(*)'];
oci_free_statement($s);

$seq++;

$query2 = "SELECT PROD_NUM FROM PRODUCT WHERE PROD_NAME = :pname";
$s = oci_parse($conn,$query2);
oci_bind_by_name($s, ':pname', $pname);
oci_execute($s);
$res2 = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
$enterproduct = $res2['PROD_NUM'];
$pnum = $enterproduct;
oci_free_statement($s);

$query3 = "INSERT INTO ENTER (
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

$s = oci_parse($conn,$query3);

oci_bind_by_name($s, ':seq', $seq);
oci_bind_by_name($s, ':enterdate', $enterdate);
oci_bind_by_name($s, ':enterflag', $enterflag);
oci_bind_by_name($s, ':enterproduct', $enterproduct);
oci_bind_by_name($s, ':enterqty', $enterqty);

oci_execute($s);
oci_free_statement($s);

$query4 = "UPDATE PRODUCT
SET STOCK_QTY = STOCK_QTY+:enterqty
WHERE PROD_NUM = :pnum
";

$s = oci_parse($conn, $query4);

oci_bind_by_name($s, ':enterqty', $enterqty);
oci_bind_by_name($s, ':pnum', $pnum);

oci_execute($s);
oci_free_statement($s);

$query5 = "UPDATE ORDER_LIST
SET ENT_FLAG = 00
WHERE PROD_NUM = :pnum AND ORDER_NUM = :onum
";

$s = oci_parse($conn, $query5);

oci_bind_by_name($s, ':pnum', $pnum);
oci_bind_by_name($s, ':onum', $onum);

oci_execute($s);
oci_free_statement($s);

echo "<script type=\"text/javascript\">

history.back();
</script>";
?>