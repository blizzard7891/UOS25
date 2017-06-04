<?php 

include_once("db.php");
if(isset($_POST['pname'])){
$pname = $_POST['pname'];
$ptype = $_POST['ptype'];
$supl = $_POST['supplier'];
$price = $_POST['customer_price'];
$wprice = $_POST['supplier_price'];
$limit = $_POST['limit'];

$query = "SELECT count(*) FROM PRODUCT";
$stid = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));
  if(oci_execute($stid) == false) die("oci query error [ $query ] message : ".oci_error($stid));
  while( ($res = oci_fetch_array($stid, OCI_ASSOC)) != false)
{
  $pnum=$res['COUNT(*)'];
}

$pnum = $pnum+1;

oci_free_statement($stid);

$query = "INSERT INTO product (
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
TO_DATE(:dat,'yymmdd'),
'1',
'1',
'0'
)";

$compiled = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));

oci_bind_by_name($compiled, ':count', $pnum);
oci_bind_by_name($compiled, ':name', $pname);
oci_bind_by_name($compiled, ':price', $price);
oci_bind_by_name($compiled, ':wprice', $wprice);
oci_bind_by_name($compiled, ':dat', $limit);

if(oci_execute($compiled) === false) die("oci query error [ $query ] message : ".oci_error($query));

oci_free_statement($compiled);

}

?>