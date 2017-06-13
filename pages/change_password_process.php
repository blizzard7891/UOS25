<?php
	include_once("./db.php");


	$id = $_POST['id'];
	$oldpwd = $_POST['oldpwd'];
	$newpwd = $_POST['newpwd'];

	$query1 = "SELECT ID FROM LOGIN_INFO WHERE ID = :id";
	$compiled1 = oci_parse($conn, $query1);
	oci_bind_by_name($compiled1, ':id', $id);

	oci_execute($compiled1);

	$res1 = oci_fetch_array($compiled1,OCI_RETURN_NULLS + OCI_ASSOC);

	$check_id = $res1['ID'];



	if(!is_null($check_id)){

		$query2 = "SELECT PW FROM LOGIN_INFO WHERE ID = :id";
		$compiled2 = oci_parse($conn, $query2);
		oci_bind_by_name($compiled2, ':id', $check_id);
		oci_execute($compiled2);
		$res2 = oci_fetch_array($compiled2,OCI_RETURN_NULLS + OCI_ASSOC);

		var_dump($res2);
		$check_pw = $res2['PW'];

		if($oldpwd == $check_pw){

		$query3 = "UPDATE LOGIN_INFO SET PW = :newpwd WHERE ID = :id";
		$compiled3 = oci_parse($conn, $query3);
		oci_bind_by_name($compiled3, ':newpwd', $newpwd);
		oci_bind_by_name($compiled3, ':id', $check_id);

		oci_execute($compiled3);

		echo "<script>";
		echo "alert(\"비밀번호가 변경되었습니다\")";
		echo "</script>";

		}else{
		echo "<script>";
		echo "alert(\"비밀번호가 올바르지 않습니다\")";
		echo "</script>";
		}
	}else{
		echo "<script>";
		echo "alert(\"사번이 올바르지 않습니다\")";
		echo "</script>";
	}

	echo "<script>";
		echo "history.back();";
		echo "</script>";

?>