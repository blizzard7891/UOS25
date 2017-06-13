<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php
echo $_SESSION['user_id']."님 안녕하세요\n";
echo "사번은 ".$_SESSION['empnum']."입니다";
?>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true
        });
    });
		
	
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