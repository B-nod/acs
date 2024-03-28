<?php session_start(); ?>
<?php
//databse connection
include('connect/connection.php');
//error variable declare
$err = "";
if (isset($_POST["submit"])) {
  $fname = mysqli_real_escape_string($connect, $_POST['fname']);
  $lname = mysqli_real_escape_string($connect, $_POST['lname']);
  $uname = mysqli_real_escape_string($connect, $_POST['uname']);
  $email = mysqli_real_escape_string($connect, $_POST['email']);
  $password = mysqli_real_escape_string($connect, $_POST['password']);
  $cpassword = mysqli_real_escape_string($connect, $_POST['cpassword']);

    //validate paswword
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    //date
    date_default_timezone_set("Asia/Kathmandu");
    $date_created = date('M d, Y \a\t h:ia', time());
    //reCaptcha
    $secretKey = "6LdonjcgAAAAACwbKZdQmsCyTUfyKqrhr7jhOZ4n";
    $responseKey = $_POST['g-recaptcha-response'];
    $userIP = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";

    $response = file_get_contents($url);
    $data = json_decode($response);
    //var_dump($response);

    $check_query = mysqli_query($connect, "SELECT * FROM loginusers where email ='$email'");
    $rowCount = mysqli_num_rows($check_query);
if ($email == "" || $password == "" || $cpassword == ""){
  $err = "<div class='alert alert-danger alert-dismissible'>
   <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please fill the information</div>";

}else {
  if (!empty($email) && !empty($password)) {
      if ($rowCount > 0) {
          $err = "<div class='alert alert-danger alert-dismissible'>
           <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>The entered Email is already exist!</div>";

      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          # code...
          $err = "<div class='alert alert-danger alert-dismissible'>
              <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>You entered an Invalid Email Format!</div>";
      } elseif ($password != $cpassword) {
          # code...
          $err = "<div class='alert alert-danger alert-dismissible'>
            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Your password does not match!</div>";
      } elseif (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
          # code...
          $err = "<div class='alert alert-danger alert-dismissible'>
            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Password must be 8 characters in length with atleast one uppercase and lowercase letter, one numeric and special character <strong>King4life+</strong></div>";

      } elseif (!$data->success) {
          # code...
          $err = "<div class='alert alert-danger alert-dismissible'>
                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Check the box, to ensure your not a robot.</div>";

      } else {
        $check_query = mysqli_query($connect, "SELECT * FROM loginusers where username ='$uname'");
        $rowCount = mysqli_num_rows($check_query);
            if ($rowCount > 0) {
              $err = "<div class='alert alert-danger alert-dismissible'>
               <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>The entered Username is already exist!</div>";

            }
            else {
              $password_hash = password_hash($password, PASSWORD_BCRYPT);

              $result = mysqli_query($connect, "INSERT INTO loginusers (fname, lname,username, email, password, status,date) VALUES ('$fname','$lname','$uname','$email', '$password_hash', 0,NOW())");

              if ($result) {
                //send opt in email with phpmailer
                  $otp = rand(100000, 999999);
                  $_SESSION['otp'] = $otp;
                  $_SESSION['mail'] = $email;
                  require "Mail/phpmailer/PHPMailerAutoload.php";
                  $mail = new PHPMailer;

                  $mail->isSMTP();
                  $mail->Host = 'smtp.zoho.com';
                  $mail->Port = 587;
                  $mail->SMTPAuth = true;
                  $mail->SMTPSecure = 'tls';
                  // set your gmail to username and exact password
                  $mail->Username = 'ismt.services@zohomail.com';
                  $mail->Password = '#Abshah@17#';
                  // send by email
                  $mail->setFrom('ismt.services@zohomail.com', 'OTP Verification');
                  // get email from input
                  $mail->addAddress($_POST["email"]);
                  //$mail->addReplyTo('abc@gmail.com');
                  $mail->isHTML(true);
                  $mail->Subject = "Your verify code";
                  $mail->Body = "<p>Dear user, </p> <h3>Your verify OTP code is $otp <br></h3>
                    ";

                  if (!$mail->send()) {
                      ?>
                      <script>
                          alert("<?php echo "Register Failed, Invalid Email "?>");
                      </script>
                      <?php
                  } else {
                      ?>
                      <script>
                          alert("<?php echo "Register Successfully, OTP sent to " . $email ?>");
                          window.location.replace('verification.php');
                      </script>
                      <?php
                  }
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
  <title>login form</title>
  <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet">
  <link rel="stylesheet" href="./index.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
<style>
  body{
    background-color: #5499C7 ;
  }
  .form-head{
    background-color:#473060;
    margin-left: -50px;
    margin-top: -75px;
    height: 100px;
    padding-top: 30px ;
    padding-left: 120px;
    width: 700px;
  }
 h4{
    font-family: "Times New Roman", sans-serif;
    color: #FFFFFF; 
    font-size: 28px;
    letter-spacing: 1px;
    font-weight: bold;
  }
.progress {
  height: 3px !important;
  margin-right: 26px;
}

.form-group {
  margin-bottom: 10px;
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
.err{
  width: 340px;
margin-left: -9px;
}
ul > li {
  font-weight:bold;
}
p{
  margin: 20px 110px;
  font-size: 18px;
}
</style>
</head>
<body>
  <head>
      <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
    </head>
<!-- partial:index.partial.html -->

<form method="post" class="signin">
<div  class="form-head">
    <h4>Register Your Account</h4>
</div>
<div class="err">
  <p><?php echo $err; ?></p>
</div>
  <input type="text" name="fname" value="<?php if(isset($_POST['fname'])){echo htmlentities ($_POST['fname']);}?>" id="name" placeholder="Enter Your First Name">
  <input type="text" name="lname" value="<?php if(isset($_POST['lname'])){echo htmlentities ($_POST['lname']);}?>" id="name" placeholder="Enter Your Last Name">
  <input type="text" name="uname" value="<?php if(isset($_POST['uname'])){echo htmlentities ($_POST['uname']);}?>" id="name" placeholder="Enter Your Username">
  <input id="email" name="email" value="<?php if(isset($_POST['email'])){echo htmlentities ($_POST['email']);}?>"type="text"  placeholder="Enter Your Email">
 

<input name="password"type="password"  id="password" placeholder="Choose Your Password">

<input name="cpassword" type="password"  id="cpassword" placeholder="Confirm-Password">
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
<div class="g-recaptcha" style="transform: scale(1.0);
                            -webkit-transform: scale(1.07); transform-origin: 0 0;
                            -webkit-transform-origin: 0 0; margin-left:100px; margin-bottom: 10px;" data-theme="light" data-sitekey="6LdonjcgAAAAABqCctQ_uh6335ks4vWwlxpeAHVE">
</div>
  <button style="width:525px;" name="submit" type="submit">Sign Up</button>
  <p >If you have an account,
     <a style="color:rgb(63,94,251); font-size: 18px;font-weight: bold; margin-right:150px;bottom: 50px;" href="index.php">Login Here !</a></p>
</form>



</body>
<script src="script.js">

</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</html>
