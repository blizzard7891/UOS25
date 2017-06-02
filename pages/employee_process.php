<?php 

function do_fetch($s)
{
	while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
	{
		echo "<tr>";
		foreach ($row as $item) {
			echo "<td>".($item?htmlentities($item):'&nbsp;')."</td>";
		}
		echo "</tr>";
	}
}

$conn = oci_connect('juneui', 'helloworld','juneui.cwpxsqzgvzt5.ap-northeast-2.rds.amazonaws.com:1521/orcl', 'UTF8');
$query = "SELECT employee_num,name,rank,phone_num,adr,birth,employment_date,discharge_date FROM EMPLOYEE";
$s = oci_parse($conn,$query);

oci_execute($s);
do_fetch($s);

 ?>