<?php 
include_once("./db.php");

if($_POST['type'] == "0")
{
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
}
elseif($_POST['type'] == "1")
{
	if(isset($_POST['managenum']))
	{	
	$managenum = $_POST['managenum'];
	$rentaldate = date( "Y/m/d" );
	$rentalperiod = $_POST['rentalperiod'];
	$phonenum = $_POST['phonenum'];
	$rentalprice = $_POST['rentalprice'];

	$query = "UPDATE BATTERY SET 
	RENTAL_DATE = TO_DATE(:rentaldate,'yyyy-mm-dd'),
	PHONE_NUM = :phonenum,
	RENTAL_PRICE = :rentalprice,
	RENTAL_PERIOD = :rentalperiod 
	WHERE MANAGEMENT_NUM = :managenum";

	$s = oci_parse($conn,$query);

	oci_bind_by_name($s, ':managenum', $managenum);
	oci_bind_by_name($s, ':rentaldate', $rentaldate);
	oci_bind_by_name($s, ':rentalperiod', $rentalperiod);
	oci_bind_by_name($s, ':phonenum', $phonenum);
	oci_bind_by_name($s, ':rentalprice', $rentalprice);

	oci_execute($s);
	oci_free_statement($s);

	oci_close($conn);

	echo("<script>location.replace('./service_battery.php');</script>"); 
	}else
	{

	}
}elseif($_POST['type'] =="2")
{
	if(isset($_POST['managenum']))
	{	
	$managenum = $_POST['managenum'];

	$query = "UPDATE BATTERY SET 
	RENTAL_DATE = null,
	PHONE_NUM = null,
	RENTAL_PRICE = null,
	RENTAL_PERIOD = null 
	WHERE MANAGEMENT_NUM = :managenum";

	$s = oci_parse($conn,$query);

	oci_bind_by_name($s, ':managenum', $managenum);
	
	oci_execute($s);
	oci_free_statement($s);

	oci_close($conn);

	echo("<script>location.replace('./service_battery.php');</script>"); 
	}else
	{

	}
}

 ?>