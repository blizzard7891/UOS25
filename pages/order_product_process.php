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

}elseif($_POST['type']=="주문"){
	if(!empty($_POST['pricesum'])){
		include_once("./db.php");

		$query1 = "SELECT count(*) FROM ORDER_INFO";

		$compiled1 = oci_parse($conn, $query1);
		oci_execute($compiled1);
		$res1 = oci_fetch_array($compiled1, OCI_RETURN_NULLS + OCI_ASSOC);

		$ordernum = $res1['COUNT(*)'];
		$ordernum = $ordernum + 1;
		$rand = mt_rand(1,999);
		if($ordernum <10){
			$ordernum = date('ymd')."000".$ordernum;
		}else if($ordernum <100){
			$ordernum = date('ymd')."00".$ordernum;
		}else if($ordernum <1000){
			$ordernum = date('ymd')."0".$ordernum;
		}
		
		if($rand <10){
			$ordernum = $ordernum."00".$rand;
		}else if($rand <100){
			$ordernum = $ordernum."0".$rand;
		}else{
			$ordernum = $ordernum.$rand;
		}

		
		$orderdate = date('y/m/d');

	$branchnum = "A001"; //일단 하나만 있다고 가정

	$total = $_POST['pricesum'];

	$query2 = "INSERT INTO ORDER_INFO (
	ORDER_NUM,
	ORDER_DATE,
	BRANCH_NUM,
	ORDER_AMT
	)
	VALUES (
	:onum,
	TO_DATE(:odate,'yy/mm/dd'),
	:bnum,
	:iamount
	)
	";

	$compiled2 = oci_parse($conn, $query2);
	oci_bind_by_name($compiled2, ':onum', $ordernum);
	oci_bind_by_name($compiled2, ':odate', $orderdate);
	oci_bind_by_name($compiled2, ':bnum', $branchnum);
	oci_bind_by_name($compiled2, ':iamount', $total);

	oci_execute($compiled2);


	$query3 = "SELECT * FROM TEMP2";

	$compiled3 = oci_parse($conn, $query3);

	oci_execute($compiled3);

	$product_name_arr = array();
	$product_quantity_arr = array();
	$product_total_arr = array();


	while ($res3 = oci_fetch_array($compiled3, OCI_RETURN_NULLS+ OCI_ASSOC)) {
		array_push($product_name_arr, $res3['ATT1']);
		array_push($product_quantity_arr, $res3['ATT2']);
		array_push($product_total_arr, $res3['ATT4']);
	}

	$query4 = "SELECT PROD_NUM FROM PRODUCT WHERE PROD_NAME = :pname";

	$product_number_arr = array();

	for ($i=0; $i < count($product_name_arr); $i++) { 
		$compiled4 = oci_parse($conn, $query4);
		oci_bind_by_name($compiled4, ':pname', $product_name_arr[$i]);
		oci_execute($compiled4);
		$res4 = oci_fetch_array($compiled4, OCI_RETURN_NULLS + OCI_ASSOC);
		array_push($product_number_arr, $res4['PROD_NUM']);
		oci_free_statement($compiled4);
	}


	$query5 = "INSERT INTO ORDER_LIST (
	ORDER_NUM,
	PROD_NUM,
	ORDER_QTY,
	ORDER_AMT,
	ENT_FLAG
	) VALUES (
	:onum,
	:pnum,
	:quantity,
	:oamount,
	null
	)
	";

	for ($j=0; $j < count($product_number_arr); $j++) { 
		$compiled5 = oci_parse($conn, $query5);
		oci_bind_by_name($compiled5, 'onum', $ordernum);
		oci_bind_by_name($compiled5, 'pnum', $product_number_arr[$j]);
		oci_bind_by_name($compiled5, 'quantity', $product_quantity_arr[$j]);
		oci_bind_by_name($compiled5, 'oamount', $product_total_arr[$j]);
		if(oci_execute($compiled5) == false)die("oci query error [ $query ] message : ".oci_error($compiled5));
		oci_free_statement($compiled5);
	}

	$query6 = "DELETE FROM TEMP2";
	$compiled6 = oci_parse($conn, $query6);
	oci_execute($compiled6);
	oci_free_statement($compiled1);
	oci_free_statement($compiled2);
	oci_free_statement($compiled3);
	oci_free_statement($compiled6);

	oci_close($conn);

}
else{
	echo "<script>alert(\"주문서가 비었습니다\");</script>";
	echo "<script>location.replace('./order_product.php');</script>"; 
}
}

echo( "<script>location.replace('./order_product.php');</script>" );

?>