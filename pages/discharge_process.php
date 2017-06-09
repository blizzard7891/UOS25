<?php
	include_once("db.php");
	$query = "UPDATE EMPLOYEE SET DISCHARGE_DATE = TO_DATE(:ddate, 'yy/mm/dd') WHERE EMPLOYEE_NUM = :enum";
	$compiled1 = oci_parse($conn, $query);

	$enum = $_POST['empnum'];
	$date = date('ymd');

	oci_bind_by_name($compiled1, ':ddate', $date);
	oci_bind_by_name($compiled1, ':enum', $enum);

	oci_execute($compiled1);

	oci_free_statement($compiled1);
	oci_close($conn);
	echo "<script>location.replace('./employee_manage.php');</script>"; 
?>