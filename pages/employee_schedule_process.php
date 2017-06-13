<?php
include_once( "./db.php" );

$wokinghour = $_POST['workinghour'];
$workingdate = $_POST['workingdate'];
$employeenum = $_POST['empname'];

$query = "INSERT INTO WORKING_MANAGEMENT(working_hour, working_date, employee_num)VALUES (:workinghour, TO_DATE(:workingdate,'yyyy-mm-dd'),:employeenum)";
$s = oci_parse( $conn, $query );

oci_bind_by_name( $s, ':workinghour', $wokinghour );
oci_bind_by_name( $s, ':workingdate', $workingdate );
oci_bind_by_name( $s, ':employeenum', $employeenum );

oci_execute( $s );
oci_free_statement( $s );

echo( "<script>location.replace('./employee_schedule.php');</script>" );
?>