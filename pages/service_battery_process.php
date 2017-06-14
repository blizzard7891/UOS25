<?php
session_start();
include_once( "./db.php" );

//배터리 입력
if ( $_POST[ 'type' ] == "0" ) {
	if ( isset( $_POST[ 'productnum' ] ) ) {
		$productnum = $_POST[ 'productnum' ];
		$device = $_POST[ 'device' ];

		$query = "INSERT INTO BATTERY (
		MANAGEMENT_NUM,
		DEVICE
		)
		VALUES (
		:productnum,
		:device
		)";

		$s = oci_parse( $conn, $query );

		oci_bind_by_name( $s, ':productnum', $productnum );
		oci_bind_by_name( $s, ':device', $device );

		oci_execute( $s );
		oci_free_statement( $s );

		oci_close( $conn );

		echo( "<script>location.replace('./service_battery.php');</script>" );
	} else {

	}
} elseif ( $_POST[ 'type' ] == "1" ) // 배터리 대여
	{
		if ( isset( $_POST[ 'managenum' ] ) ) {
			$managenum = $_POST[ 'managenum' ];
			$rentaldate = date( "Y/m/d" );
			$rentalperiod = $_POST[ 'rentalperiod' ];
			$phonenum = $_POST[ 'phonenum' ];
			$rentalprice = $_POST[ 'rentalprice' ];

			$query = "UPDATE BATTERY SET 
			RENTAL_DATE = TO_DATE(:rentaldate,'yyyy-mm-dd'),
			PHONE_NUM = :phonenum,
			RENTAL_PRICE = :rentalprice,
			RENTAL_PERIOD = :rentalperiod 
			WHERE MANAGEMENT_NUM = :managenum";

			$s = oci_parse( $conn, $query );

			oci_bind_by_name( $s, ':managenum', $managenum );
			oci_bind_by_name( $s, ':rentaldate', $rentaldate );
			oci_bind_by_name( $s, ':rentalperiod', $rentalperiod );
			oci_bind_by_name( $s, ':phonenum', $phonenum );
			oci_bind_by_name( $s, ':rentalprice', $rentalprice );

			oci_execute( $s );
			oci_free_statement( $s );

			$query = "SELECT count(*) FROM SALE";

			$s = oci_parse( $conn, $query );
			oci_execute( $s );

			$row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC );
			$salenum = $row[ 'COUNT(*)' ] + 1;
			oci_free_statement( $s );

			$query = "SELECT prod_price,prod_num FROM PRODUCT WHERE prod_name = '배터리'";

			$s = oci_parse( $conn, $query );
			oci_execute( $s );

			$row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC );
			$saleamount = $_POST[ 'rentalperiod' ] * $row[ 'PROD_PRICE' ];
			$prodnum = $row[ 'PROD_NUM' ];
			oci_free_statement( $s );

			$employeenum = $_SESSION['empnum'];
			$paymethod = $_POST[ 'flag' ];
			$refundflag = '0';

			$query = "INSERT INTO SALE(SALE_NUM, SALE_DATE, REFUND_FLAG, SALE_AMOUNT, EMPLOYEE_NUM,PAY_METHOD )VALUES (:salenum, TO_DATE(:wdate,'yyyy/mm/dd'), :refundflag, :saleamount, :employeenum, :paymethod)";
			$s = oci_parse( $conn, $query );

			oci_bind_by_name( $s, ':salenum', $salenum ); //
			oci_bind_by_name( $s, ':wdate', $rentaldate );
			oci_bind_by_name( $s, ':refundflag', $refundflag ); ///
			oci_bind_by_name( $s, ':saleamount', $saleamount ); //
			oci_bind_by_name( $s, ':employeenum', $employeenum );
			oci_bind_by_name( $s, ':paymethod', $paymethod );

			oci_execute( $s );
			oci_free_statement( $s );

			$saleqty = $_POST[ 'rentalperiod' ];

			$query = "INSERT INTO SALE_LIST(PROD_NUM, SALE_NUM, SALE_QTY, SALE_AMOUNT ) VALUES (:prodnum, :salenum, :saleqty, :saleamount)";
			$s = oci_parse( $conn, $query );

			oci_bind_by_name( $s, ':prodnum', $prodnum );
			oci_bind_by_name( $s, ':salenum', $salenum );
			oci_bind_by_name( $s, ':saleqty', $saleqty );
			oci_bind_by_name( $s, ':saleamount', $saleamount );

			oci_execute( $s );
			oci_free_statement( $s );

			$query = "SELECT count(*) FROM RECEIPT";
			$s = oci_parse( $conn, $query );
			oci_execute( $s );
			$row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC );
			$receiptnum = $row[ 'COUNT(*)' ]+1;
			oci_free_statement( $s );

			$flag = '0';
			$query = "INSERT INTO RECEIPT(RECEIPT_NUM, RECEIPT_FLAG, SALE_NUM ) VALUES (:receiptnum,:flag,:salenum)";
			$s = oci_parse( $conn, $query );
			
			oci_bind_by_name( $s, ':receiptnum', $receiptnum );
			oci_bind_by_name( $s, ':flag', $flag );
			oci_bind_by_name( $s, ':salenum', $salenum );
			oci_execute( $s );
			oci_free_statement( $s );

			oci_close( $conn );

			echo( "<script>location.replace('./service_battery.php');</script>" );
		} else {

		}
	} elseif ( $_POST[ 'type' ] == "2" ) //배터리 반납
		{
			if ( isset( $_POST[ 'managenum' ] ) ) {
				$managenum = $_POST[ 'managenum' ];

				$query = "UPDATE BATTERY SET 
				RENTAL_DATE = null,
				PHONE_NUM = null,
				RENTAL_PRICE = null,
				RENTAL_PERIOD = null 
				WHERE MANAGEMENT_NUM = :managenum";

				$s = oci_parse( $conn, $query );

				oci_bind_by_name( $s, ':managenum', $managenum );

				oci_execute( $s );
				oci_free_statement( $s );

				oci_close( $conn );

				echo( "<script>location.replace('./service_battery.php');</script>" );
			} else {

			}
		}

?>