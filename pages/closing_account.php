<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>UOS25</title>

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
   <?php
	class item{
		var $td1;
		var $td2;
		var $td3;
		var $td4;
	}
	
	class items{
		var $item;
		
		function set($d1,$d2,$d3,$d4)
		{
			if(!isset($this->item[$d1]))
				$this->item[$d1] = new item;
			
			$this->item[$d1]->td1 = $d1;
			$this->item[$d1]->td2 = $d2;
			$this->item[$d1]->td3 = $d3;
			$this->item[$d1]->td4 = $d4;
		}
		
		function get($name)
		{
			return $this->item[$name];
		}
		
		function fetch()
		{
			$tmp =current($this->item);
			if(!$tmp)
				reset($this->item);
			else
				next($this->item);
			return $tmp;
		}
		
		function remote($name)
		{
			unset($this->item[$name]);
		}
	}
	
	if(!isset($items))
		$items = new items;
	?>

   <div class="col-lg-12">
   	<h1 class="page-header">결산관리</h1>
   </div>
   
   <div class="col-lg-6">
   	<div class="panel panel-blue">
   		<div class="panel-heading">
   			<strong>매출입력</strong>
   		</div>
   		<div class="panel-body">
   			<div class="row">
   				<div class="col-lg-12">
   					<form onsubmit="return false;" method="POST">
   						<div id="form1" class="form-group">
   							<label>구분</label>
   							<div>
   								<label class="radio-inline">
   									<input type="radio" name="flag" id="cash" value="00" checked>현금
   								</label>
   								<label class="radio-inline">
   									<input type="radio" name="flag" id="card" value="01">카드
   								</label>
   							</div>
   						</div>
   						<div class="form-group">
   							<label>수익</label>
   							<input type="number" id="profit" name="profit" class="form-control">
   						</div>
   						<div class="form-group">
   							<label>지출(손실)</label>
   							<input type="number" id="spending" name="spending" class="form-control">
   						</div>
   						<div class="form-group">
   							<label>결산일</label>
   							<input type="date" id="closingdate" name="closingdate" class="form-control">
   						</div>
   						<div class="pull-right">
   							<button onClick="writeTd(this.form)" class="btn btn-primary mr-3">내역추가</button>
   							<button type="reset" class="btn btn-primary">입력리셋</button>
   						</div>
   					</form>
   				</div>
   			</div>
   		</div>
   	</div>
   </div>
   
   <div class="col-lg-6">
   	<div class="panel panel-blue">
   		<div class="panel-heading">
   			<strong>일 매출</strong>
   		</div>
   		<div class="panel-body">
   			<table id="tb" class="table table-striped table-bordered table-hover">
   				<thead>
   					<tr>
   						<th width="25%">구분</th>
   						<th width="25%">수익</th>
   						<th width="25%">지출</th>
   						<th width="25%">결산일</th>
   					</tr>
   				</thead>
   				<tbody>
   					<tr id="cashTr">
   						<td>현금</td>
   						<td>0</td>
   						<td>0</td>
   						<td>#</td>
   					</tr>
   					<tr id="cardTr">
   						<td>카드</td>
   						<td>0</td>
   						<td>0</td>
   						<td>#</td>
   					</tr>
   				</tbody>
   			</table>
   			<div class="pull-right mt-3">
   				<label class="mr-3">매출 : <span id="amount">0원</span></label>
   				<button type="submit" class="btn btn-primary">결산</button>
   			</div>
   		</div>
   	</div>
   </div>
   
   <div class="col-lg-12">
   	<div class="panel panel-blue">
   		<div class="panel-heading">
   			<strong>결산내역</strong>
   		</div>
   		<div class="panel-body">
   			<table width="100%" class="table table-striped table-bordered table-hover" id="myTable">
   				<thead>
   					<tr>
   						<th width="1%">결산번호</th>
   						<th width="1%">결산일</th>
   						<th width="1%">구분</th>
   						<th width="1%">수익</th>
   						<th width="1%">지출</th>
   						<th width="1%">결산액</th>
   						<th width="1%">결산자</th>
   					</tr>
   				</thead>
   				<tbody>
   					<tr>
   						<td>#</td>
   						<td>#</td>
   						<td>#</td>
   						<td>#</td>
   						<td>#</td>
   						<td>#</td>
   						<td>#</td>
   					</tr>
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
    <script src="../js/jquery.json-2.4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true
        });
    });
		
	Date.prototype.toDateInputValue = (function() {
		var local = new Date(this);
		local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
		return local.toJSON().slice(0,10);
	});
		
	$(document).ready( function() {
		$('#closingdate').val(new Date().toDateInputValue());
	});
	
	document.getElementById('closingdate').value = new Date().toDateInputValue();
		
	function writeTd(form){
		if(form.cash.checked)
			var tmpTr = "#cashTr";
		else
			var tmpTr = "#cardTr";
		
		var profit = document.getElementById("profit").value;
		var closingDate = document.getElementById("closingdate").value;
		var spending = document.getElementById("spending").value;
		
		if(profit != '')
			$(tmpTr).find("td").eq(1).html(profit);
		else
			$(tmpTr).find("td").eq(1).html(0);
		if(spending != '')
			$(tmpTr).find("td").eq(2).html(spending);
		else
			$(tmpTr).find("td").eq(2).html(0);
		$(tmpTr).find("td").eq(3).html(closingDate);
		
		form.reset();
		document.getElementById('closingdate').value = closingDate;
		
		var cashProfit = parseInt($("#cashTr").find("td").eq(1).html());
		var cashSpending =parseInt($("#cashTr").find("td").eq(2).html());
		var cardProfit = parseInt($("#cardTr").find("td").eq(1).html());
		var cardSpending = parseInt($("#cardTr").find("td").eq(2).html());
		
		var result = cashProfit + cardProfit - cashSpending - cardSpending;
		
		document.getElementById("amount").innerHTML = result+"원";
	}
		
		var TableData;
		TableData = storeTblValues()
		TableData = $.toJSON(TableData);

		function storeTblValues()
		{
			var TableData = new Array();

			$('#tb tr').each(function(row, tr){
				TableData[row]={
					"flag" : $(tr).find('td:eq(0)').text()
					, "profit" :$(tr).find('td:eq(1)').text()
					, "spending" : $(tr).find('td:eq(2)').text()
					, "closingdate" : $(tr).find('td:eq(3)').text()
				}    
			}); 
			TableData.shift();  // first row will be empty - so remove
			return TableData;
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