<?php
  $login_success = 0;
  if(isset($_GET['login'])) { 
    $username = $_GET['username'];
    $password = $_GET['password'];


    $dbservername = "database";
    $dbusername = "docker";
    $dbpassword = "docker";
    $dbname = "docker";
    
    // Create connection
    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "SELECT username FROM users WHERE username=? AND password=SHA1(?)";
    $stmt = $conn->prepare($sql);
    $stmt ->bind_param("ss",$username,$password);
    $stmt -> execute();
    $stmt -> store_result();
   
    
    if ($stmt->num_rows > 0) {
      $login_success = 1;
    } else {
      $login_success = 2;
    }
    $stmt->close();
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
                  <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Login">
                </form>
<?php if($login_success == 1) { ?> 
                <p class="login-card-footer-text">Authentication Success</p>
<?php } else if($login_success == 2) { ?>
                <p class="login-card-footer-text">Authentication Failure</p>
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
</body>
</html>
