<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>비밀번호변경</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/signin.css" rel="stylesheet">
  </head>

  <body>
    <div class="container">
	
      <form class="form-signin" action="./change_password_process.php" method="POST">
        <h2 class="form-signin-heading text-center">UOS25</h2>
        <input type="text" name="id" class="form-control" placeholder="사번" maxlength="20" required autofocus>
        <input type="password" name="oldpwd" class="form-control" placeholder="기존비밀번호" required>
        <input type="password" name="newpwd" class="form-control" placeholder="변경비밀번호" >
        <button class="btn btn-lg btn-primary btn-block" type="submit">비밀번호변경</button>
      </form>

    </div> <!-- /container -->

  </body>


</html>
