<?php 
session_start();
$id = "sujin";
$pwd = "kh1200";
if(!empty($_POST['id']) && !empty($_POST['password'])) {
	if($_POST['id'] == $id && $_POST['password']==$pwd){
		$_SESSION['is_login'] = true;
		header('Location: ./index.html');
		exit;
	}
}
echo '로그인 하지 못했습니다..............';
 ?>
