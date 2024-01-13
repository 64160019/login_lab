<?php
  session_start();
  $captcha = 0;
  $login_success = 0;
  $limitTime = 3;

  function validate_captcha($user_input) {
      if(!isset($_SESSION['captcha'])){
        return false;
      }
    $correct_captcha = $_SESSION['captcha'];
    unset($_SESSION['captcha']);

    return strtolower($user_input) === strtolower($correct_captcha);
  }
  
  if(isset($_GET['login'])) { 
    $username = $_GET['username'];
    $password = $_GET['password'];


    $dbservername = "database";
    $dbusername = "docker";
    $dbpassword = "docker";
    $dbname = "docker";
    
    $user_captcha = $_GET['captcha'];

    if (!validate_captcha($user_captcha)){
      $captcha = 1;
      if($user_captcha == null){
        $captcha = 3;
      }
    }else {
      $captcha = 2;
    
    // Create connection
    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
  }
    $sql = "SELECT username, LoginCount , status , Time , password FROM users WHERE username='" . $username ."' AND status = 'Y' " ;
    
    $result = $conn->query($sql);
   
    
    if ($result->num_rows > 0) {
      $row =$result->fetch_assoc();
      if(SHA1($password) === $row['password']) {
        $login_success = 1;
        $sql = "UPDATE users SET LoginCount = 0 WHERE username = '" . $username ."'";
        $conn->query($sql);
      }  else {
      $login_success = 2;
      $sql = "UPDATE users SET LoginCount = LoginCount +1 WHERE username = '" . $username ."'";
      $conn->query($sql);
      if ($row['LoginCount'] + 1 >= $limitTime){
        $ban_time = date('Y-m-d H:i:s', time() + 60 );
        $sql = "UPDATE users SET status = 'N', Time='" . $ban_time . "'  WHERE username = '" . $username ."'" ;
        $conn->query($sql);
      }
    }
  }else {
    $login_success = 3;
  }
    
    $conn->close();
  }
  $dbservername = "database";
    $dbusername = "docker";
    $dbpassword = "docker";
    $dbname = "docker";

    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect-error);
    }

    $sql = "SELECT username FROM users WHERE status = 'N' AND Time < NOW() - INTERVAL 1 MINUTE";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()){
        $sql = "UPDATE users SET status = 'Y', LoginCount = 0 WHERE username = '" . $row['username'] . "'";
        $conn-> query($sql);
      }
      $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login Template</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="assets/images/login.jpg" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body">
              <div class="brand-wrapper">
                <img src="assets/images/logo.svg" alt="logo" class="logo">
              </div>
              <p class="login-card-description">Sign into your account</p>
              <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <div class="form-group">
                    <label for="username" class="sr-only">Email</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="your username">
                  </div>
                  <div class="form-group mb-4">
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="***********">
                  </div>
                  <div class="form-group mb-4">
                    CAPTCHA:<img src = "captcha.php" alt="CAPTCHA"><br>
                    Enter CAPTCHA: <input type ="text" name = "captcha">
                  </div>
                  <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Login">
                </form>
<?php if($login_success == 1 && $captcha == 2) { ?> 
                <p class="login-card-footer-text">Authentication Success</p>
<?php } else if($login_success == 2 || $captcha == 1) { ?>
                <p class="login-card-footer-text">Authentication Failure</p>
<?php } else if($login_success == 3 ) { ?>
                <p class="login-card-footer-text">Account locked</p>
<?php } ?>
                <nav class="login-card-footer-nav">
                  <a href="#!">Terms of use.</a>
                  <a href="#!">Privacy policy</a>
                </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script>
      function togglePassword(){
        var passwordField = document getElemenById('password')
        if (passwordField.type === 'password'){
            passwordField.type  = 'text';
        } else{
          passwordfield.type = 'password';
        }
      }
  </script>
</body>
</html>
