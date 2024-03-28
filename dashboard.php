<?php
session_start();
include('connect/connection.php');
if (!isset($_SESSION['email'])) {
    # code...
    header('Location: index.php');
    die();
}
?>
<?php
if (isset($_SESSION['email'])) {
  # code...
  $email = $_SESSION['email'];
  $sql = $connect->query("SELECT username FROM loginusers WHERE email = '$email' limit 1");
  if ($sql->num_rows != 0) {
    # code...
    while ($rows = $sql->fetch_assoc()) {
      # code...
      $name = $rows['username'];
          }
  }
}

?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
    <title>Home</title>
  <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet">
  <link rel="stylesheet" href="./index.css">
<style>
  body{
    background-color: #5499C7 ;
    font-family: "Times New Roman", sans-serif;
  }

  .form-head{
    background-color:#473060;
    margin-left:-50px;
    margin-top: -50px;
    font-size: 25px;
    margin-right: 50px;
    height: 90px;
    left: -90p;
    width: 700px;
  }
  .form-head > h2{
    color: #fff;
    padding-top: 30px;
    padding-left: 70px;
  }
  
.button {
    position: fixed;
    top: 10px;
    right: 10px;
    width: 80px;
    height: 40px;
    font-size: 24px;
    text-align: center;
    cursor: pointer;
    outline: none;
    background-color: #BB822A;
    border: none;
    border-radius: 15px;
}
.button a{
  text-decoration:none;
  color: #ffffff;
  padding-bottom: 2px;
}

.button:hover {
    background-color: #492809;
}

.button1 {
    position: fixed;
    top: 10px;
    right: 100px;
    font-size: 24px;
    width: 115px;
    height: 40px;
    text-align: center;
    cursor: pointer;
    outline: none;
    background-color: #BB822A;
    border: none;
    border-radius: 15px;
}
.button1 a{
  text-decoration:none;
  color: #ffffff;
  padding-bottom: 3px;
}

.button1:hover {
    background-color: #492809;
}

.button2 {
    position: fixed;
    top: 10px;
    right: 230px;
    font-size: 24px;
    width: 80px;
    height: 40px;
    text-align: center;
    cursor: pointer;
    outline: none;
    background-color: #BB822A;
    border: none;
    border-radius: 15px;
}
.button2 a{
  text-decoration:none;
  color: #ffffff;
  padding-bottom: 3px;
  padding-right: 12px;
}

.button2:hover {
    background-color: #492809;
}

.img{
  margin-top: 10px;
  width: 410px;
  height: 330px;
}
img{
  width: 100%;
  height: 100%;
}
</style>
</head>
<body>
  <head>
<!--     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"> -->
  <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
</head>
<!-- partial:index.partial.html -->
<form method="POST" name="login" class="login">
  <div class="form-head">
    <h2>Welcome <strong><?php echo $name; ?></strong></h2>
    <div  class="img">
      <img src="./img/welcome.jpg" alt="">
    </div>
  </div>

</form>
<div class="button2">
    <a href="dashboard.php">
        <span><strong>Home</strong></span>
    </a>
</div>
<div class="button1">
    <a href="change_psw.php">
        <span><strong>Change Password</strong></span>
    </a>
</div>

<div class="button">
    <a href="logout.php">
        <span><strong>LOGOUT</strong></span>
    </a>
</div>
</body>
</html>
