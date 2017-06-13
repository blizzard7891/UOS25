<?php
include_once( "./db.php" );

$query = "SELECT count(*) FROM CLOSING_ACCOUNT WHERE TO_CHAR(clacnt_date,'yyyy-mm-dd')=:cdate";
$s = oci_parse( $conn, $query );
//$tmp = $_POST['td1_3'];
oci_bind_by_name( $s, ':cdate', $_POST[ 'td1_3' ] );
oci_execute( $s );
$row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC );
$result = $row[ 'COUNT(*)' ];
oci_free_statement( $s );

if ( isset( $_POST[ 'td1_3' ] ) && isset( $_POST[ 'td2_3' ] ) && $_POST[ 'td1_3' ] == $_POST[ 'td2_3' ] && $result < 2 ) {
	$query = "SELECT count(*) FROM CLOSING_ACCOUNT";
	$s = oci_parse( $conn, $query );
	oci_execute( $s );
	while ( $row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC ) ) {
		$seq = $row[ 'COUNT(*)' ];
	}
	oci_free_statement( $s );

	$cashprofit = $_POST[ 'td1_1' ];
	$cashspending = $_POST[ 'td1_2' ];
	$cashdate = $_POST[ 'td1_3' ];
	$cashflag = "00";
	$cashamount = $cashprofit - $cashspending;
	$cardprofit = $_POST[ 'td2_1' ];
	$cardspending = $_POST[ 'td2_2' ];
	$carddate = $_POST[ 'td2_3' ];
	$cardflag = "01";
	$cardamount = $cardprofit - $cardspending;
	$user = "2017001";


	$seq++;
	$query = "INSERT INTO CLOSING_ACCOUNT (
	CLACNT_NUM,
	CLACNT_DATE,
	CLACNT_GROUP,
	PROFIT,
	EXPENSE,
	CLACNT_AMOUNT,
	EMPLOYEE_NUM
	)
	VALUES (
	:num1,
	TO_DATE(:cashdate,'yyyy-mm-dd'),
	:flag1,
	:cashprofit,
	:cashspending,
	:cashamount,
	:user1
	)";

	$s = oci_parse( $conn, $query );

	oci_bind_by_name( $s, ':num1', $seq );
	oci_bind_by_name( $s, ':cashdate', $cashdate );
	oci_bind_by_name( $s, ':flag1', $cashflag );
	oci_bind_by_name( $s, ':cashprofit', $cashprofit );
	oci_bind_by_name( $s, ':cashspending', $cashspending );
	oci_bind_by_name( $s, ':cashamount', $cashamount );
	oci_bind_by_name( $s, ':user1', $user );

	oci_execute( $s );
	oci_free_statement( $s );

	$seq++;
	$query = "INSERT INTO CLOSING_ACCOUNT (
	CLACNT_NUM,
	CLACNT_DATE,
	CLACNT_GROUP,
	PROFIT,
	EXPENSE,
	CLACNT_AMOUNT,
	EMPLOYEE_NUM
	)
	VALUES (
	:num2,
	TO_DATE(:carddate,'yyyy-mm-dd'),
	:flag2,
	:cardprofit,
	:cardspending,
	:cardamount,
	:user2
	)";

	$s = oci_parse( $conn, $query );

	oci_bind_by_name( $s, ':num2', $seq );
	oci_bind_by_name( $s, ':carddate', $carddate );
	oci_bind_by_name( $s, ':flag2', $cardflag );
	oci_bind_by_name( $s, ':cardprofit', $cardprofit );
	oci_bind_by_name( $s, ':cardspending', $cardspending );
	oci_bind_by_name( $s, ':cardamount', $cardamount );
	oci_bind_by_name( $s, ':user2', $user );

	oci_execute( $s );
	oci_free_statement( $s );

	$query = "SELECT count(*) FROM MONEY_MANAGEMENT";
	$s = oci_parse( $conn, $query );
	oci_execute( $s );
	while ( $row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC ) ) {
		$seq = $row[ 'COUNT(*)' ];
	}
	oci_free_statement( $s );



	$ramount = 0;
	// 현금 결산을 자금관리에 입력
	$query = "SELECT remain_amount FROM MONEY_MANAGEMENT WHERE seq_num = (SELECT MAX(seq_num) FROM MONEY_MANAGEMENT WHERE pay_method = :paymethod)";
	$s = oci_parse( $conn, $query );
	oci_bind_by_name( $s, ':PAYMETHOD', $cashflag );
	oci_execute( $s );
	while ( $row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC ) ) {
		$ramount = $row[ 'REMAIN_AMOUNT' ];
	}
	oci_free_statement( $s );

	if ( $cashprofit > 0 ) {
		$seq++;
		$query = "INSERT INTO MONEY_MANAGEMENT (
	SEQ_NUM,
	WRITE_DATE,
	WRITE_GROUP,
	REMAIN_AMOUNT,
	AMOUNT,
	DESCRIPTION,
	PAY_METHOD
	)
	VALUES (
	:seq,
	TO_DATE(:writedate,'yyyy-mm-dd'),
	:writegroup,
	:remainamount,
	:amount,
	:description,
	:paymethod
	)";

		$s = oci_parse( $conn, $query );

		$writegroup = "00";
		$ramount = $ramount + $cashprofit;
		$description =  date("Y년 m월 d일", strtotime( $cashdate ) ) . "자 현금매출 결산";
		
		oci_bind_by_name( $s, ':seq', $seq );
		oci_bind_by_name( $s, ':writedate', $cashdate );
		oci_bind_by_name( $s, ':writegroup', $writegroup );
		oci_bind_by_name( $s, ':remainamount', $ramount );
		oci_bind_by_name( $s, ':amount', $cashprofit );
		oci_bind_by_name( $s, ':description', $description );
		oci_bind_by_name( $s, ':paymethod', $cashflag );

		oci_execute( $s );
		oci_free_statement( $s );
	}

	if ( $cashspending > 0 ) {
		$seq++;
		$query = "INSERT INTO MONEY_MANAGEMENT (
		SEQ_NUM,
		WRITE_DATE,
		WRITE_GROUP,
		REMAIN_AMOUNT,
		AMOUNT,
		DESCRIPTION,
		PAY_METHOD
		)
		VALUES (
		:seq,
		TO_DATE(:writedate,'yyyy-mm-dd'),
		:writegroup,
		:remainamount,
		:amount,
		:description,
		:paymethod
		)";

		$s = oci_parse( $conn, $query );

		$writegroup = "01";
		$ramount = $ramount - $cashspending;
		$description = date("Y년 m월 d일", strtotime( $cashdate ) ) . "자 현금매출 결산";
		
		oci_bind_by_name( $s, ':seq', $seq );
		oci_bind_by_name( $s, ':writedate', $cashdate );
		oci_bind_by_name( $s, ':writegroup', $writegroup );
		oci_bind_by_name( $s, ':remainamount', $ramount );
		oci_bind_by_name( $s, ':amount', $cashspending );
		oci_bind_by_name( $s, ':description', $description );
		oci_bind_by_name( $s, ':paymethod', $cashflag );

		oci_execute( $s );
		oci_free_statement( $s );
	}
	
	// 카드 결산을 자금관리에 입력
	$query = "SELECT remain_amount FROM MONEY_MANAGEMENT WHERE seq_num = (SELECT MAX(seq_num) FROM MONEY_MANAGEMENT WHERE pay_method = :paymethod)";
	$s = oci_parse( $conn, $query );
	oci_bind_by_name( $s, ':PAYMETHOD', $cardflag );
	oci_execute( $s );
	while ( $row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC ) ) {
		$ramount = $row[ 'REMAIN_AMOUNT' ];
	}
	oci_free_statement( $s );

	if ( $cardprofit > 0 ) {
		$seq++;
		$query = "INSERT INTO MONEY_MANAGEMENT (
		SEQ_NUM,
		WRITE_DATE,
		WRITE_GROUP,
		REMAIN_AMOUNT,
		AMOUNT,
		DESCRIPTION,
		PAY_METHOD
		)
		VALUES (
		:seq,
		TO_DATE(:writedate,'yyyy-mm-dd'),
		:writegroup,
		:remainamount,
		:amount,
		:description,
		:paymethod
		)";

		$s = oci_parse( $conn, $query );

		$writegroup = "00";
		$ramount = $ramount + $cardprofit;
		$description = date("Y년 m월 d일", strtotime( $carddate ) ) . "자 카드매출 결산";

		oci_bind_by_name( $s, ':seq', $seq );
		oci_bind_by_name( $s, ':writedate', $carddate );
		oci_bind_by_name( $s, ':writegroup', $writegroup );
		oci_bind_by_name( $s, ':remainamount', $ramount );
		oci_bind_by_name( $s, ':amount', $cardprofit );
		oci_bind_by_name( $s, ':description', $description );
		oci_bind_by_name( $s, ':paymethod', $cardflag );

		oci_execute( $s );
		oci_free_statement( $s );
	}

	if ( $cardspending > 0 ) {
		$seq++;
		$query = "INSERT INTO MONEY_MANAGEMENT (
		SEQ_NUM,
		WRITE_DATE,
		WRITE_GROUP,
		REMAIN_AMOUNT,
		AMOUNT,
		DESCRIPTION,
		PAY_METHOD
		)
		VALUES (
		:seq,
		TO_DATE(:writedate,'yyyy-mm-dd'),
		:writegroup,
		:remainamount,
		:amount,
		:description,
		:paymethod
		)";

		$s = oci_parse( $conn, $query );

		$writegroup = "01";
		$ramount = $ramount - $cardspending;
		$description = date("Y년 m월 d일", strtotime( $carddate ) ) . "자 카드매출 결산";

		oci_bind_by_name( $s, ':seq', $seq );
		oci_bind_by_name( $s, ':writedate', $carddate );
		oci_bind_by_name( $s, ':writegroup', $writegroup );
		oci_bind_by_name( $s, ':remainamount', $ramount );
		oci_bind_by_name( $s, ':amount', $cardspending );
		oci_bind_by_name( $s, ':description', $description );
		oci_bind_by_name( $s, ':paymethod', $cardflag );

		oci_execute( $s );
		oci_free_statement( $s );
	}



	oci_close( $conn );
} elseif ( $_POST[ 'td1_3' ] != $_POST[ 'td2_3' ] ) {
	echo "<script>alert('현금/카드 결산일을 일치 시켜주세요.')</script>";
}
else if ( $result > 0 ) {
	echo "<script>alert('해당 날짜는 이미 결산처리 되었습니다.')</script>";
} else {
	echo "<script>alert('현금/카드 매출을 모두 입력 해주세요.')</script>";
}
echo( "<script>location.replace('./closing_account.php');</script>" );
?>