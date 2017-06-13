<?php session_start(); ?>

<?php 
include_once("./db.php");
$query1 = "SELECT * FROM LOGIN_INFO WHERE ID = :id";
$stid1 = oci_parse($conn, $query1) or die('oci parse error: '.oci_error($conn));
oci_bind_by_name($stid1, ':id', $_POST['id']);
if(oci_execute($stid1) === false) die("oci query error [ $query ] message : ".oci_error($stid1));


$res1 = oci_fetch_array($stid1,OCI_RETURN_NULLS + OCI_ASSOC);
$check_id  = $res1['ID'];
$check_pw = $res1['PW'];
$enum = $res1['EMPLOYEE_NUM'];

$query2 = "SELECT RANK FROM EMPLOYEE WHERE EMPLOYEE_NUM = :enum";
$compiled2 = oci_parse($conn, $query2);
oci_bind_by_name($compiled2, ':enum', $enum);
oci_execute($compiled2);
$res2 = oci_fetch_array($compiled2,OCI_RETURN_NULLS + OCI_ASSOC);

$rank = $res2['RANK'];

oci_free_statement($stid1);
oci_free_statement($compiled2);

oci_close($conn);

if($check_id == $_POST['id'] && $check_pw == $_POST['password'])
{
	if($rank == "지점장" || $rank == "매니저"){
		$_SESSION['user_id'] = $check_id;
		$_SESSION['empnum'] = $enum;
		echo("<script>location.replace('./index.php');</script>"); 
	}else{
		$_SESSION['user_id'] = $check_id;
		$_SESSION['empnum'] = $enum;
		echo("<script>location.replace('./index_for_employee.php');</script>"); 
	}
}

echo "<script> alert('login failed'); location.replace('./login.html');</script>";


?>