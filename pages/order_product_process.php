<?php
if($_POST['type']=="주문추가"){
	if(!empty($_POST['pname'])&&!empty($_POST['quantity'])){

		include_once("./db.php");
		$query1 = "SELECT SUPPLIER_NAME FROM SUPPLIER WHERE SUPPLIER_NUM =(SELECT SUPPLIER_NUM FROM PRODUCT WHERE PROD_NAME = :pname)";
		$query2 = "SELECT PROD_WSALE_PRICE FROM PRODUCT WHERE PROD_NAME = :pname";
		$compiled1 = oci_parse($conn, $query1) or die('oci parse error: '.oci_error($conn));
		$compiled2 = oci_parse($conn, $query2) or die('oci parse error: '.oci_error($conn));
		oci_bind_by_name($compiled1, ':pname', $_POST['pname']);
		oci_bind_by_name($compiled2, ':pname', $_POST['pname']);
		if(oci_execute($compiled1)==false) die("oci query error [$query] message : ".oci_error($stid));
		if(oci_execute($compiled2)==false) die("oci query error [$query] message : ".oci_error($stid));
		$res1 = oci_fetch_array($compiled1,OCI_RETURN_NULLS + OCI_ASSOC);
		$res2 = oci_fetch_array($compiled2,OCI_RETURN_NULLS + OCI_ASSOC);

		$prod_name = $_POST['pname'];
		$prod_quantity = $_POST['quantity'];
		$prod_wprice = $res2['PROD_WSALE_PRICE'];
		$prod_total = $prod_quantity * $prod_wprice;
		$supplier = $res1['SUPPLIER_NAME'];

		$query3 = "INSERT INTO TEMP2 (
		ATT1,
		ATT2,
		ATT3,
		ATT4,
		ATT5
		)
		VALUES (
		:name,
		:quantity,
		:wprice,
		:total,
		:supplier 
		)";

		$compiled3 = oci_parse($conn,$query3);

		oci_bind_by_name($compiled3, ':name', $prod_name);
		oci_bind_by_name($compiled3, ':quantity', $prod_quantity);
		oci_bind_by_name($compiled3, ':wprice', $prod_wprice);
		oci_bind_by_name($compiled3, ':total', $prod_total);
		oci_bind_by_name($compiled3, ':supplier', $supplier);

		oci_execute($compiled3);
		oci_free_statement($compiled1);
		oci_free_statement($compiled2);
		oci_free_statement($compiled3);
		oci_close($conn);


	}
	else {
    echo "<script>alert(\"제품명 및 수량을 입력해주세요\");</script>";
    echo "<script>location.replace('./order_product.php');</script>"; 
  }
}elseif($_POST['type']=="주문삭제"){

	include_once("./db.php");

	$query = "DELETE FROM TEMP2";
	$s = oci_parse($conn,$query);
	oci_execute($s);
	oci_free_statement($s);
	oci_close($conn);
}

echo( "<script>location.replace('./order_product.php');</script>" );

?>