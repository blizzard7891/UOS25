<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>배터리대여서비스</title>

   <!-- Bootstrap Core CSS -->
    <link href="../css/bootstrap.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../css/metisMenu.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../css/content.css" rel="stylesheet" type="text/css">
</head>

<body class="whitebody">
	<div class="col-lg-12">
		<h1 class="page-header">배터리대여서비스</h1>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>배터리 품목 입력</strong>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<form action="./service_battery_process.php" method="POST">
							<div class="form-group">
								<label>배터리 제품번호</label>
								<input type="text" name="productnum" class="form-control" maxlength="20" required>
							</div>
							<div class="form-group">
								<label>호환기종</label>
								<input type="text" name="device" class="form-control" maxlength="20"  required>
							</div>
							<div class="pull-right">
								<button type="submit" class="btn btn-primary mr-3">추가하기</button>	
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>배터리 대여/반납</strong>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<form action="#" method="POST">
							<div class="form-group">
								<label>관리 번호</label>
								<input id="managenum" type="text" class="form-control" readonly>
							</div>
							<div class="form-group">
								<label>대여 일자</label>
								<input id="rentaldate" type="date" class="form-control">
							</div>
							<div class="form-group">
								<label>대여 기간(일)</label>
								<input id="rentalperiod" type="number" class="form-control">
							</div>
							<div class="form-group">
								<label>전화 번호</label>
								<input id="phonenum" type="text" class="form-control">
							</div>
							<div class="form-group">
								<label>대여 금액</label>
								<input id="rentalprice" type="number" class="form-control">
							</div>
							<div class="pull-right">
								<button type="submit" class="btn btn-primary mr-3">대여</button>	
								<button type="submit" class="btn btn-primary">반납</button>	
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
    
    <div class="col-lg-12">
    	<div class="panel panel-default">
    		<div class="panel-heading">
    			<strong>배터리 대여상황</strong>
    		</div>
    		<div class="panel-body">
				<table width="100%" class="table table-striped table-bordered table-hover mb-0" id="myTable">
					<thead>
						<tr>
							<th>관리번호</th>
							<th>호환기종</th>
							<th>대여일자</th>
							<th>대여기간(일)</th>
							<th>전화번호</th>
							<th>금액</th>
						</tr>
					</thead>
					<tbody>
						<?php
						include_once("./db.php");

						function do_fetch($s)
						{
							while($row = oci_fetch_array($s,OCI_RETURN_NULLS + OCI_ASSOC))
							{
								echo "<tr id='a' onclick='loadTd(this)'>";
								foreach ($row as $item) 
								{
									echo "<td>".($item?htmlentities($item):'&nbsp;')."</td>";
								}
								echo "</tr>";
							}
						}

						$query = "SELECT management_num,device,rental_date,rental_period,phone_num,rental_price FROM BATTERY";
						$s = oci_parse($conn,$query);
						oci_execute($s);
						do_fetch($s);

						oci_close($conn);
						?>
					</tbody>
				</table>
    		</div>
    	</div>
    </div>


    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../js/metisMenu.js"></script>
    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../js/sb-admin-2.js"></script>
    <script src="../js/content.js"></script>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true
        });
    });

    function stringToDate(_date,_format,_delimiter)
	{
            var formatLowerCase=_format.toLowerCase();
            var formatItems=formatLowerCase.split(_delimiter);
            var dateItems=_date.split(_delimiter);
            var monthIndex=formatItems.indexOf("mm");
            var dayIndex=formatItems.indexOf("dd");
            var yearIndex=formatItems.indexOf("yyyy");
            var month=parseInt(dateItems[monthIndex]);
            month-=1;
            var formatedDate = new Date(dateItems[yearIndex],month,dateItems[dayIndex]);
            return formatedDate;
	}

    function loadTd(heTr)
    {
    	document.getElementById("managenum").setAttribute("value",$(heTr).find("td").eq(0).html());
    	document.getElementById("rentaldate").setAttribute("value", $(heTr).find("td").eq(2).html());
    
    	//document.getElementById("rentaldate").value = $(heTr).find("td").eq(2).html();

    	document.getElementById("rentalperiod").setAttribute("value",$(heTr).find("td").eq(3).html());
    	document.getElementById("phonenum").setAttribute("value",$(heTr).find("td").eq(4).html());
    	document.getElementById("rentalprice").setAttribute("value",$(heTr).find("td").eq(5).html());

    	//stringToDate($(heTr).find("td").eq(2).html(),"yy/MM/dd","/")
    }
		
	document.onkeydown = trapRefresh;
	 function trapRefresh()
	 {
	  if (event.keyCode == 116)
	   {
			event.keyCode = 0; 
			event.cancelBubble = true; 
			event.returnValue = false;
			document.location.reload(1);
	   }
	 }  
    </script>
</body>

</html>
