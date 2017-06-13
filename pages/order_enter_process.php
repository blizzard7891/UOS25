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

$query2 = "SELECT PROD_NUM, STD_EXPDATE FROM PRODUCT WHERE PROD_NAME = :pname";
$s = oci_parse($conn,$query2);
oci_bind_by_name($s, ':pname', $pname);
oci_execute($s);
$res2 = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
$enterproduct = $res2['PROD_NUM'];
$std_expdate = $res2['STD_EXPDATE'];
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

$expdate = date("Y/m/d",strtotime($enterdate." + ".$std_expdate."day"));

$query6 = "INSERT INTO EXP_DATE_MANAGEMENT (
ENT_DATE,
EXPDATE,
PROD_NUM,
QTY
)
VALUES (
TO_DATE(:enterdate,'yyyy/mm/dd'),
TO_DATE(:expdate,'yyyy/mm/dd'),
:pnum,
:enterqty
)
";

$compiled6 = oci_parse($conn, $query6);
oci_bind_by_name($compiled6, ':enterdate', $enterdate);
oci_bind_by_name($compiled6, ':expdate', $expdate);
oci_bind_by_name($compiled6, ':pnum', $pnum);
oci_bind_by_name($compiled6, ':enterqty', $enterqty);

oci_execute($compiled6);
oci_free_statement($compiled6);

$query7 = "UPDATE ORDER_LIST
SET SEQ_NUM = :seq
WHERE PROD_NUM = :pnum AND ORDER_NUM = :onum
";

$compiled7 = oci_parse($conn, $query7);
oci_bind_by_name($compiled7, ':seq', $seq);
oci_bind_by_name($compiled7, ':pnum', $pnum);
oci_bind_by_name($compiled7, ':onum', $onum);

oci_execute($compiled7);
oci_free_statement($compiled7);


oci_close($conn);


echo "<script type=\"text/javascript\">

history.back();
</script>";
?>