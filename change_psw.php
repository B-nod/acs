<?php

session_start();

if (isset($_POST["change"])) {
  include('connect/connection.php');
  $oldpassword = $_POST['oldpassword'];
  $password = $_POST["password"];
  $cpassword = $_POST['cpassword'];

  //validate password
  $uppercase = preg_match('@[A-Z]@', $password);
  $lowercase = preg_match('@[a-z]@', $password);
  $number = preg_match('@[0-9]@', $password);
  $specialChars = preg_match('@[^\w]@', $password);
  $Email = $_SESSION['email'];

  $hash1 = password_hash($oldpassword, PASSWORD_DEFAULT);
  $hash = password_hash($password, PASSWORD_DEFAULT);

  $sql = mysqli_query($connect, "SELECT * FROM loginusers WHERE email='$Email' && password = '$oldpassword'");
  $query = mysqli_num_rows($sql);
  $fetch = mysqli_fetch_assoc($sql);

  if ($oldpassword == "" || $password == "" || $cpassword == "") {
      ?>
      <script>
          alert("<?php echo "All field are required !"?>");
      </script>
      <?php
  } else {
      if ($password != $cpassword) {
          ?>
          <script>
              alert("<?php echo "password and confirm password don't match!"?>");
          </script>
      <?php } elseif (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
          # code...
          ?>
          <script>
              alert("<?php echo "Password must be 8 characters in length with atleast one uppercase and lowercase letter, one numeric and special character e.g Passw0rd#"?>");
          </script>
          <?php
      } else {
          $sql = $connect->query("SELECT password FROM loginusers WHERE email = '$Email'");
          if ($sql->num_rows > 0) {
              # code...
              $data = $sql->fetch_array();
              if (password_verify($password, $data['password'])) {
                  ?>
                  <script>
                      alert("<?php echo "Old password! please enter new password!!"?>");
                  </script>
                  <?php
              } else {
                  if ($Email) {
                      $new_pass = $hash;
                      mysqli_query($connect, "UPDATE loginusers SET password='$new_pass' WHERE email='$Email'");
                      ?>
                      <script>
                          window.location.replace("change_psw.php");
                          alert("<?php echo "your password has been succesful change"?>");
                      </script>
                      <?php
                  } else {
                      ?>
                      <script>
                          alert("<?php echo "Please try again"?>");
                      </script>
                      <?php
                  }

              }
          }

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
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
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
.progress {
  height: 3px !important;
  margin-right: 26px;
}

.form-group {
  margin-bottom: 10px;
}
#eye1 {

color: black;
margin: -47px 0 0 0;
margin-right: 30px;
border-radius: 0px 5px 5px 0px;
float: right;
position: relative;
right: 1%;
top: 2px;
z-index: 5;
cursor: pointer;
height: 10px;
width: 10px;
}
#eye {

    color: black;
    margin: -106px 0 0 0;
    margin-right: 30px;
    border-radius: 0px 5px 5px 0px;
    float: right;
    position: relative;
    right: 1%;
    top: -0.2%;
    z-index: 5;
    cursor: pointer;
    height: 10px;
    width: 10px;
}

.progress-bar-danger {
  background-color: #e90f10;
}

.progress-bar-warning {
  background-color: #ffad00;
}

.progress-bar-success {
  background-color: #02b502;
}
ul > li{
    font-weight: bold;
}
.err{
  width: 340px;
margin-left: -9px;
}

</style>
</head>
<body>
  <head>
<!--     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"> -->
  <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
</head>
<!-- partial:index.partial.html -->
<form method="POST" name="login" class="change">
  <div class="form-head">
    <h2>Change Password</h2>
</div>

<input name="oldpassword" type="password"  id="oldpassword" placeholder="Old Password">
<span class="show-pass" onclick="toggle()">
   <i id="eye1" class="far fa-eye" onclick="myFunction(this)"></i>
</span>

<input name="password" type="password"  id="password" placeholder="New Password">
<input name="cpassword" type="password"  id="cpassword" placeholder="Confirm New Password">
<span class="show-pass" onclick="toggle()">
   <i id="eye" class="far fa-eye" onclick="myFunction(this)"></i>
</span>

<div id="popover-password">
   <p><span id="result"></span></p>
   <div class="progress">
       <div id="password-strength"
           class="progress-bar"
           role="progressbar"
           aria-valuenow="40"
           aria-valuemin="0"
           aria-valuemax="100"
           style="width:0%">
       </div>
   </div><br>
   <ul class="list-unstyled">
       <li class="">
           <span class="low-upper-case">
               <i class="fas fa-circle" aria-hidden="true"></i>
               &nbsp;Lowercase &amp; Uppercase
           </span>
       </li>
       <li class="">
           <span class="one-number">
               <i class="fas fa-circle" aria-hidden="true"></i>
               &nbsp;Number (0-9)
           </span>
       </li>
       <li class="">
           <span class="one-special-char">
               <i class="fas fa-circle" aria-hidden="true"></i>
               &nbsp;Special Character (!@#$%^&*)
           </span>
       </li>
       <li class="">
           <span class="eight-character">
               <i class="fas fa-circle" aria-hidden="true"></i>
               &nbsp;Atleast 8 Character
           </span>
       </li>
   </ul>
</div>
<button style="width:329px; margin-top: -10px;" type="submit" value="Change" name="change" >Change Password</button>
    

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
<script src="script.js">

</script>
</html>
