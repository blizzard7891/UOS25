<?php 
include_once("./db.php");

if(isset($_POST['productnum']))
{	
$productnum = $_POST['productnum'];
$device = $_POST['device'];

$query = "INSERT INTO BATTERY (
MANAGEMENT_NUM,
DEVICE
)
VALUES (
:productnum,
:device
)";

$s = oci_parse($conn,$query);

oci_bind_by_name($s, ':productnum', $productnum);
oci_bind_by_name($s, ':device', $device);

oci_execute($s);
oci_free_statement($s);

oci_close($conn);

echo("<script>location.replace('./service_battery.php');</script>"); 
}else
{

}

 ?>