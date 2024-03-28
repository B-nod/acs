<?php session_start() ?>
<?php
//databse connection
include('connect/connection.php');
if (isset($_POST["verify"])) {
    $otp = $_SESSION['otp'];
    $email = $_SESSION['mail'];
    $otp_code = $_POST['otp_code'];
//check for OTP from mail
    if ($otp != $otp_code) {
        ?>
        <script>
            alert("Invalid OTP code");
        </script>
        <?php
    } else {
        mysqli_query($connect, "UPDATE loginusers SET status = 1 WHERE email = '$email'");
        ?>
        <script>
            alert("Verfiy account done, you may sign in now");
            window.location.replace("index.php");
        </script>
        <?php
    }

}

?>


<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - Wavy login form</title>
  <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet">
  <link rel="stylesheet" href="./index.css">
  <style>
    .form-head{
    background-color:#473060;
    margin-left: -50px;
    margin-top: -75px;
    height: 100px;
    padding-top: 50px ;
    padding-left: 100px;
    width: 700px;
  }
  h4{
    color: #fff;
  }
  </style>
</head>
<body>

<!-- partial:index.partial.html -->
<form method="POST" class="login">
  <div class="form-head">
    <h4>OTP CODE</h4>
  </div>
  <input type="text" id="otp" name="otp_code" required autofocus>



  <button style="margin-left: 95px;
    width: 130px;
    height: 50px;"type="submit" value="Verify" name="verify">Verify Here</button>
<div class="link">
    <p> </p>
</div>
</form>



</body>

</html>
