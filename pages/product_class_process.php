<?php
include_once("db.php");
if(!empty($_POST['tname'])){
	$stid1 = oci_parse($conn, 'SELECT count(*) FROM PRODUCT_CLASS') or die('oci parse error: '.oci_error($conn));
	if(oci_execute($stid1) === false) die("oci query error [ $query ] message : ".oci_error($stid1));
	while( ($res = oci_fetch_array($stid1, OCI_ASSOC)) != false)
	{
	$num=$res['COUNT(*)'];
	}

	$num = $num + 1;
	$tname = $_POST['tname'];

	$query = "INSERT INTO PRODUCT_CLASS (
	PROD_CLASS_NUM,
	PROD_CLASS_NAME
	) VALUES (
	:num,
	:name
	)";

	$stid2 = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));
	oci_bind_by_name($stid2, ':num', $num);
    oci_bind_by_name($stid2, ':name', $tname);
    if(oci_execute($stid2) == false) die("oci query error [ $query ] message : ".oci_error($stid2));
    oci_free_statement($stid1);
    oci_free_statement($stid2);

    oci_close($conn);
    echo("<script>location.replace('./product_class.php');</script>");
}
else{
	echo "<script>alert(\"내용을 입력해주세요\");</script>";
	echo "<script>location.replace('./product_list.php');</script>"; 
}
?>