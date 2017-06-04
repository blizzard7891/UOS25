<?php
  include_once("db.php");

  if(isset($_POST['ename']) && isset($_POST['ephone']) && isset($_POST['eaddress']) && isset($_POST['rank']) && isset($_POST['birth']) && isset($_POST['hire']))
  {

  $stid = oci_parse($conn, 'SELECT count(*) FROM EMPLOYEE') or die('oci parse error: '.oci_error($conn));
  if(oci_execute($stid) === false) die("oci query error [ $query ] message : ".oci_error($stid));
  while( ($res = oci_fetch_array($stid, OCI_ASSOC)) != false)
  {
  $enum=$res['COUNT(*)'];
  }
  oci_free_statement($stid);

    $enum = $enum + 1;
    $ename = $_POST['ename'];
    $ephone = $_POST['ephone'];
    $eaddress = $_POST['eaddress'];
    $rank = $_POST['rank'];
    $birth = $_POST['birth'];
    $hire = $_POST['hire'];

    $query = "INSERT INTO EMPLOYEE (
    EMPLOYEE_NUM,
    NAME,
    PHONE_NUM,
    ADR,
    BIRTH,
    EMPLOYMENT_DATE,
    DISCHARGE_DATE,
    ACCOUNT_NUM,
    RANK
    ) VALUES (
    :num,
    :name,
    :phone,
    :address,
    TO_DATE(:birth,'yy/mm/dd'),
    TO_DATE(:emp_date,'yy/mm/dd'),
    NULL,
    NULL,
    :rank
    )";

    $compiled = oci_parse($conn, $query) or die('oci parse error: '.oci_error($conn));

    oci_bind_by_name($compiled, ':num', $enum);
    oci_bind_by_name($compiled, ':name', $ename);
    oci_bind_by_name($compiled, ':phone', $ephone);
    oci_bind_by_name($compiled, ':address', $eaddress);
    oci_bind_by_name($compiled, ':birth', $birth);
    oci_bind_by_name($compiled, ':emp_date', $hire);
    oci_bind_by_name($compiled, ':rank', $rank);

    if(oci_execute($compiled) == false) die("oci query error [ $query ] message : ".oci_error($compiled));

    oci_free_statement($compiled);

  }
  else {

  }
?>