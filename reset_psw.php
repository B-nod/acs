<?php session_start() ?>
<?php
if (isset($_POST["reset"])) {
    include('connect/connection.php');
    $password = $_POST["password"];
    $cpassword = $_POST['cpassword'];

    //validate paswword
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    $token = $_SESSION['token'];
    $Email = $_SESSION['email'];

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = mysqli_query($connect, "SELECT * FROM loginusers WHERE email='$Email'");
    $query = mysqli_num_rows($sql);
    $fetch = mysqli_fetch_assoc($sql);

    if ($password == "" || $cpassword == "") {
        ?>
        <script>
            alert("<?php echo "Please enter password and confirm password"?>");
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
                            window.location.replace("index.php");
                            alert("<?php echo "your password has been succesful reset"?>");
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
  <title>login form</title>
  <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet">
  <link rel="stylesheet" href="./index.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css'>
<style>
    body{
        background-color: #5499C7;
        font-family: "Times New Roman", sans-serif;
    }
    h4{
    margin-left: 80px;
    font-size: 25px; 
    color:#44107E;
    letter-spacing: 1px;
    font-weight:bold;
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

<form  method="POST" name="login" class="login">
  <div>
    <h4>Reset Password</h4>
  </div>


<input name="password"type="password"  id="password" placeholder="Password">

<input name="cpassword" type="password"  id="cpassword" placeholder="confirm-password">
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
<button style="width:329px; margin-top: -10px;" type="submit" value="Reset" name="reset" >Reset</button>
</form>



</body>
<script src="script.js">

</script>

</html>
