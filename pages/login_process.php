<?php 
$conn = oci_connect('juneui', 'helloworld', 'juneui.cwpxsqzgvzt5.ap-northeast-2.rds.amazonaws.com:1521/orcl', 'UTF8');
$query = "SELECT id,pw FROM LOGIN_INFO";
$stid = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));
if(oci_execute($stid) === false) die("oci query error [ $query ] message : ".oci_error($stid));


while( ($res = oci_fetch_array($stid, OCI_ASSOC)) != false)
{
   if($res['ID'] == $_POST['id'] && $res['PW'] == $_POST['password'])
   {
     if($res['ID']=="admin")
      echo("<script>location.replace('./index.html');</script>"); 
     else
      echo("<script>location.replace('./index.html');</script>"); 
   }
}
   echo "<script> alert('login failed'); location.replace('./login.html');</script>";

oci_close($conn);
?>