<?php 
include_once("./db.php");

if(isset($_POST['invoice']))
{	
$invoice = $_POST['invoice'];
$date = date("Y/m/d");
$weight = $_POST['weight'];
$price = 2500;

$query = "INSERT INTO PARCEL (
INVOICE_NUM,
ACCEPT_DATE,
WEIGHT,
SHIPPING_PRICE 
)
VALUES (
:invoice,
TO_DATE(:wdate,'yyyy/mm/dd'),
:weight,
:price
)";

$s = oci_parse($conn,$query);

oci_bind_by_name($s, ':invoice', $invoice);
oci_bind_by_name($s, ':wdate', $date);
oci_bind_by_name($s, ':weight', $weight);
oci_bind_by_name($s, ':price', $price);

oci_execute($s);
oci_free_statement($s);

oci_close($conn);

echo("<script>location.replace('./service_parcel.php');</script>"); 
}else
{

}

 ?>