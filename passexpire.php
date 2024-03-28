<?php
session_start();
?>

<?php
if (isset($_POST["recover"])) {
  // connection to database
    include('connect/connection.php');
    $email = $_POST["email"];

    $sql = mysqli_query($connect, "SELECT * FROM loginusers WHERE email='$email'");
    $query = mysqli_num_rows($sql);
    $fetch = mysqli_fetch_assoc($sql);
    // check for email
    if (mysqli_num_rows($sql) <= 0) {
        ?>
        <script>
            alert("<?php  echo "Sorry, no emails exists "?>");
        </script>
        <?php
    } else if ($fetch["status"] == 0) {
        ?>
        <script>
            alert("Sorry, your account must verify first, before you recover your password !");
            window.location.replace("dashboard.php");
        </script>
        <?php
    } else {
        // generate token by binaryhexa
        $token = bin2hex(random_bytes(50));

        //session_start ();
        $_SESSION['token'] = $token;
        $_SESSION['email'] = $email;
        //email sender for password reset
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

        // HTML body
        $mail->isHTML(true);
        $mail->Subject = "Recover your password";
        $mail->Body = "<b>Dear User</b>
            <h3>We received a request to reset your password.</h3>
            <p>Kindly click the below link to reset your password</p>
          http://localhost/acs/reset_psw.php
            ";

        if (!$mail->send()) {
            ?>
            <script>
                alert("<?php echo " Invalid Email "?>");
            </script>
            <?php
        } else {
            ?>
            <script>
                alert("<?php echo "Email send out ! Kindly check your email inbox."?>");
                window.location.replace('dashboard.php');
            </script>
            <?php
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Reset form</title>
  <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet"><link rel="stylesheet" href="./style.css">
</head>
<body>
<!-- partial:index.partial.html -->
<form method="POST" name="recover_psw" class="login">
  <div style="    margin-left: 122px;
    font-size: 25px;">
    <h4 style="color:red">Account Expire</h4>
  </div>
  <input id="email" name="email" value="<?php if(isset($_POST['email'])){echo htmlentities ($_POST['email']);}?>"type="text"  placeholder="Email">

<button style="margin-left: 135px;
    width: 100px;
    height: 50px;" type="submit" type="submit" value="Recover" name="recover">Reset Link</button><br>

</form>
</body>
</html>
