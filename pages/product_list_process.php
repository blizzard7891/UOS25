<?php
$prevPage = $_SERVER['HTTP_REFERER'];
include_once("db.php");
if(!empty($_POST['pname']) && !empty($_POST['ptype']) && !empty($_POST['supplier']) && !empty($_POST['customer_price']) && !empty($_POST['supplier_price']) && !empty($_POST['limit'])){

	$pname = $_POST['pname'];
	$ptype = $_POST['ptype'];
	$supl = $_POST['supplier'];
	$price = $_POST['customer_price'];
	$wprice = $_POST['supplier_price'];
	$limit = $_POST['limit'];

	$query3 = "SELECT PROD_CLASS_NUM FROM PRODUCT_CLASS WHERE PROD_CLASS_NAME = :cname";
	$query4 = "SELECT SUPPLIER_NUM FROM SUPPLIER WHERE SUPPLIER_NAME = :sname";
	$stid3 = oci_parse($conn, $query3) or die('oci parse error: '.oci_error($conn));
	oci_bind_by_name($stid3, ':cname', $ptype);
	if(oci_execute($stid3) === false) die("oci query error [ $query ] message : ".oci_error($query3));
	$stid4 = oci_parse($conn, $query4) or die('oci parse error: '.oci_error($conn));
	oci_bind_by_name($stid4, ':sname', $supl);
	if(oci_execute($stid4) === false) die("oci query error [ $query ] message : ".oci_error($query4));
	while( ($res2 = oci_fetch_array($stid3, OCI_ASSOC)) != false)
	{
		$ptype_num=$res2['PROD_CLASS_NUM'];
	}
	while( ($res3 = oci_fetch_array($stid4, OCI_ASSOC)) != false)
	{
		$supl_num=$res3['SUPPLIER_NUM'];
	}

	$query1 = "SELECT count(*) FROM PRODUCT";
	$query2 = "INSERT INTO product (
	prod_num,
	prod_name,
	prod_price,
	prod_wsale_price,
	std_expdate,
	prod_class_num,
	supplier_num,
	stock_qty
) VALUES (
:count,
:name,
:price,
:wprice,
:expdate,
:ptype_num,
:supl_num,
'0'
)";
$stid1 = oci_parse($conn, $query1) or die('oci parse error: '.oci_error($conn));
if(oci_execute($stid1) == false) die("oci query error [ $query ] message : ".oci_error($stid1));
while( ($res1 = oci_fetch_array($stid1, OCI_ASSOC)) != false)
{
	$pnum=$res1['COUNT(*)'];
}

$pnum = $pnum+1;


$stid2 = oci_parse($conn, $query2) or die('oci parse error: '.oci_error($conn));

oci_bind_by_name($stid2, ':count', $pnum);
oci_bind_by_name($stid2, ':name', $pname);
oci_bind_by_name($stid2, ':price', $price);
oci_bind_by_name($stid2, ':wprice', $wprice);
oci_bind_by_name($stid2, ':expdate', $limit);
oci_bind_by_name($stid2, ':ptype_num', $ptype_num);
oci_bind_by_name($stid2, ':supl_num', $supl_num);

if(oci_execute($stid2) === false) die("oci query error [ $query ] message : ".oci_error($query2));

oci_free_statement($stid1);
oci_free_statement($stid2);
oci_free_statement($stid3);
oci_free_statement($stid4);


oci_close($conn);
header('location:'.$prevPage);
//echo("<script>location.replace('./product_list.php');</script>"); 
}
else {
	echo "<script>alert(\"내용을 입력해주세요\");</script>";
	echo "<script>location.replace('./product_list.php');</script>"; 
}
?>