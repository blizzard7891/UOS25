<?php
session_start();
include_once( "./db.php" );

if ( isset( $_POST[ 'invoice' ] ) ) {
	$flag = true;
	$invoice = $_POST[ 'invoice' ];
	$date = date( "Y/m/d" );
	$weight = $_POST[ 'weight' ];
	if ( $weight >= 10000 )
		$prodname = '택배_9500';
	elseif ( $weight >= 5000 )
		$prodname = '택배_8000';
	elseif ( $weight >= 0 )
		$prodname = '택배_6500';
	else
		$flag = false;

	if ( $flag == true ) {
		$query = "SELECT prod_price,prod_num FROM PRODUCT WHERE prod_name = :prodname";

		$s = oci_parse( $conn, $query );
		oci_bind_by_name( $s, ':prodname', $prodname );
		oci_execute( $s );

		$row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC );
		$price = $row[ 'PROD_PRICE' ];
		$prodnum = $row[ 'PROD_NUM' ];
		oci_free_statement( $s );

		$query = "INSERT INTO PARCEL (
		INVOICE_NUM,
		ACCEPT_DATE,
		WEIGHT,
		SHIPPING_PRICE 
		)
		VALUES (
		:invoice,
		TO_DATE(:wdate,'yyyy-mm-dd'),
		:weight,
		:price
		)";

		$s = oci_parse( $conn, $query );

		oci_bind_by_name( $s, ':invoice', $invoice );
		oci_bind_by_name( $s, ':wdate', $date );
		oci_bind_by_name( $s, ':weight', $weight );
		oci_bind_by_name( $s, ':price', $price );

		oci_execute( $s );
		oci_free_statement( $s );

		$query = "SELECT count(*) FROM SALE";

		$s = oci_parse( $conn, $query );
		oci_execute( $s );

		$row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC );
		$salenum = $row[ 'COUNT(*)' ] + 1;
		oci_free_statement( $s );

		$employeenum = $_SESSION['empnum'];
		$paymethod = $_POST[ 'payMethod' ];
		$refundflag = '0';

		$query = "INSERT INTO SALE(SALE_NUM, SALE_DATE, REFUND_FLAG, SALE_AMOUNT, EMPLOYEE_NUM,PAY_METHOD )VALUES (:salenum, TO_DATE(:wdate,'yyyy/mm/dd'), :refundflag, :saleamount, :employeenum, :paymethod)";
		$s = oci_parse( $conn, $query );

		oci_bind_by_name( $s, ':salenum', $salenum ); //
		oci_bind_by_name( $s, ':wdate', $date );
		oci_bind_by_name( $s, ':refundflag', $refundflag ); ///
		oci_bind_by_name( $s, ':saleamount', $price ); //
		oci_bind_by_name( $s, ':employeenum', $employeenum );
		oci_bind_by_name( $s, ':paymethod', $paymethod );

		oci_execute( $s );
		oci_free_statement( $s );

		$saleqty = 1;

		$query = "INSERT INTO SALE_LIST(PROD_NUM, SALE_NUM, SALE_QTY, SALE_AMOUNT ) VALUES (:prodnum, :salenum, :saleqty, :saleamount)";
		$s = oci_parse( $conn, $query );

		oci_bind_by_name( $s, ':prodnum', $prodnum );
		oci_bind_by_name( $s, ':salenum', $salenum );
		oci_bind_by_name( $s, ':saleqty', $saleqty );
		oci_bind_by_name( $s, ':saleamount', $price );

		oci_execute( $s );
		oci_free_statement( $s );

		$query = "SELECT count(*) FROM RECEIPT";
		$s = oci_parse( $conn, $query );
		oci_execute( $s );
		$row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC );
		$receiptnum = $row[ 'COUNT(*)' ] + 1;
		oci_free_statement( $s );

		$flag1 = '0';
		$query = "INSERT INTO RECEIPT(RECEIPT_NUM, RECEIPT_FLAG, SALE_NUM ) VALUES (:receiptnum,:flag,:salenum)";
		$s = oci_parse( $conn, $query );

		oci_bind_by_name( $s, ':receiptnum', $receiptnum );
		oci_bind_by_name( $s, ':flag', $flag1 );
		oci_bind_by_name( $s, ':salenum', $salenum );
		oci_execute( $s );
		oci_free_statement( $s );

		oci_close( $conn );
	}
	
	echo( "<script>location.replace('./service_parcel.php');</script>" );
} else {

}

?>