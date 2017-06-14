<?php
include_once("./db.php");

$query = "SELECT max(seq) FROM BOARD";
$s = oci_parse( $conn, $query );
oci_execute( $s );
$row = oci_fetch_array( $s, OCI_RETURN_NULLS + OCI_ASSOC );

$seq = $row[ 'MAX(SEQ)' ] + 1;

oci_free_statement( $s );

$description = $_POST['description'];
$query = "INSERT INTO BOARD (seq, description) VALUES(:seq, :description)";
$s = oci_parse( $conn, $query );

oci_bind_by_name( $s, ':seq', $seq );
oci_bind_by_name( $s, ':description', $description );

oci_execute( $s );
oci_free_statement( $s );

echo("<script>location.replace('./home.php');</script>"); 
?>