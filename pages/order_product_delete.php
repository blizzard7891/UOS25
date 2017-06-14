<?php
include_once("./db.php");
$pname = $_POST['pname'];
$query1 = "DELETE FROM TEMP2 WHERE ATT1 = :pname";
$compiled1 = oci_parse($conn, $query1);
oci_bind_by_name($compiled1, ':pname', $pname);
oci_execute($compiled1);
oci_free_statement($compiled1);
oci_close($conn);
echo "<script>location.replace('./order_product.php');</script>"; 
?>