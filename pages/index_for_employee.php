<?php session_start(); ?>
<?php
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['empnum'])) {
    echo "<script>alert(\"로그인이 필요합니다\");</script>";
    echo "<meta http-equiv='refresh' content='0;url=login.html'>";    
    exit;
}
?>
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

    <!-- Custom CSS -->
    <link href="../css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index_for_employee.php">UOS25</a>
            </div>
            <!-- /.navbar-header -->
            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="logout_process.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.html"><i class="fa fa-dashboard fa-fw"></i> Home</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-won fa-fw"></i> 판매관리<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#" onclick="changeUrl('sales_insert.php')">판매</a>
                                </li>
                                <li>
                                    <a href="#" onclick="changeUrl('sales_log.php')">판매내역</a>
                                </li>
                                <li>
                                    <a href="#" onclick="changeUrl('sales_refund_log.php')">환불내역</a>
                                </li>
                                <li>
                                    <a href="#" onclick="changeUrl('sales_receipt.php')">영수증</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="#"><i class="fa fa-inbox fa-fw"></i> 재고관리<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
								<li>
                            		<a href="#" onclick="changeUrl('stock_state.php')">재고관리</a>
                            	</li>
                                <li>
                                    <a href="#" onclick="changeUrl('stock_expdate_manage.php')">폐기관리</a>
                                </li>
                            	<li>
                            		<a href="#" onclick="changeUrl('stock_enter_log.php')">입고내역</a>
                            	</li>
                            	<li>
                            		<a href="#" onclick="changeUrl('stock_release_log.php')">출고내역</a>
                            	</li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-users fa-fw"></i> 직원관리<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                            		<a href="#" onclick="changeUrl('employee_schedule.php')">근무부</a>
                            	</li>
							</ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-cubes fa-fw"></i> 부가서비스<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<li>
                            		<a href="#" onclick="changeUrl('service_parcel.php')">택배서비스</a>
                            	</li>
                            	<li>
                            		<a href="#" onclick="changeUrl('service_battery.php')">배터리대여서비스</a>
                            	</li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
           <div class="row">
              <div class="col-12">
              	<iframe src="home.php" class="col-12" width="100%" height="700" frameborder="0" id="main_frame"></iframe>
              </div>
           </div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../js/bootstrap.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="../js/metisMenu.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="../js/sb-admin-2.js"></script>
    
    <script type="text/javascript">
		function changeUrl(url)
		{
			document.getElementById("main_frame").src = url;
		}
		
		document.onkeydown = trapRefresh;
		 function trapRefresh()
		 {
		  if (event.keyCode == 116)
		   {
			   event.keyCode = 0; 
			   event.cancelBubble = true; 
			   event.returnValue = false;
			   document.getElementById("main_frame").contentDocument.location.reload(true);
		   }
		 }  
	</script>
</body>
</html>