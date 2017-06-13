<?php


	include_once("db.php");
	
	
	$prodname=$_POST['eventname'];
	

	$query = "SELECT PROD_NUM, PROD_PRICE FROM PRODUCT WHERE prod_name='$prodname'";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
    $prodnum = $row['PROD_NUM'];
    $prodprice = $row['PROD_PRICE'];
    oci_free_statement($s);



	//순서번호
	$query = "SELECT COUNT(*) FROM EVENT_PRODUCT";
    $s = oci_parse($conn,$query);
    oci_execute($s);
    $row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC);
    $seqnum = $row['COUNT(*)']+1;
    oci_free_statement($s);



//가격결정
    
    if($_POST['eventclass']=='1+1'){
    	$class='000';
		$discountprice=$prodprice;
    } 
	elseif($_POST['eventclass']=='2+1'){
		$discountprice=$prodprice;
		$class='001';
	} 
	elseif($_POST['eventclass']=='할인행사'){
		$class='010';
		$eventprice=$_POST['eventprice'];
		$discountprice=($eventprice/100)*$prodprice;	
	} else{
		$class='011';
		$discountprice=$prodprice;
	}


//event db에 삽입
	$query = "INSERT INTO EVENT_PRODUCT(EVENT_NUM, EVENT_GROUP, PROD_NUM,  DISCOUNT_PRICE) VALUES ('$seqnum','$class','$prodnum','$discountprice')";
	$s = oci_parse($conn,$query);
	oci_execute($s);
	oci_free_statement($s);
	

//각 품목 이벤트 업데이트


	oci_close($conn);

	echo "<script>location.replace('./product_event.php');</script>"; 

?>