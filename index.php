<?php
session_start();
//databse connection
include('connect/connection.php');
function redirectToLoginPage()
{
    header('Location: dashboard.php?LoginSuccess');
    exit();
}

//function for checking email or username
function Is_email($user)
{
//If the username input string is an e-mail, return true
if(filter_var($user, FILTER_VALIDATE_EMAIL)) {
return true;
} else {
return false;
}
}
if (isset($_POST["login"])) {
    $email = mysqli_real_escape_string($connect, trim($_POST['email']));
    $password = trim($_POST['password']);

    $sql = mysqli_query($connect, "SELECT * FROM loginusers where email = '$email' OR username='$email'");
    $count = mysqli_num_rows($sql);

     //reCaptcha
     $secret = "6LfYlM4gAAAAAK4xFZmZUYGBCQMiLfvjAhJcwINE";
     $response = $_POST['g-recaptcha-response'];
     $IP = $_SERVER['REMOTE_ADDR'];
     $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$IP";
     $fire = file_get_contents($url);
     $data = json_decode($fire);

     if ($count > 0) {
        $fetch = mysqli_fetch_assoc($sql);
        $hashpassword = $fetch["password"];

        if ($fetch["status"] == 0) {
            ?>
            <script>
                alert("Please verify email account before login.");
            </script>
            <?php
        } 
        elseif (!$data->success) {
            ?>
            <script>
                alert("Check the box, to ensure your not a robot.");
            </script>
            <?php
  
        }
        else if (password_verify($password, $hashpassword)) {

            date_default_timezone_set("Asia/Kathmandu");
            $sql1 = "SELECT TIMESTAMPDIFF (MONTH, date, NOW()) AS tdif FROM loginusers WHERE email = '$email' OR username='$email'";
            $result = $connect->prepare($sql1);
            $result->execute();
            $result->store_result();
            $result->bind_result($tdif);
            $result->fetch();
            if ($tdif >= 2) {
                header('Location: passexpire.php');
                exit();
            } else {
                //include seession user here
                $_SESSION['email'] = $email;
                //log use in
                redirectToLoginPage();
            }


        }
        
         else {
            ?>
            <script>
                alert("email or password invalid, please try again.");
            </script>
            <?php
        }
    }
    
    else {
        ?>
        <script>
            alert("All fields are required.");
        </script>
        <?php
    }



    
   
}

?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>login form</title>
  <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet">
  <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link href="./bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <link rel="stylesheet" href="./index.css">
<style>
i {
        position: absolute;
        right: 50px;
        transform: translate(0, -50%);
        top: 160px;
        cursor: pointer;
    }
.form-head{
    background-color:#473060;
    margin-left: -50px;
    margin-top: -75px;
    height: 100px;
    padding-top: 50px ;
    padding-left: 65px;
    width: 700px;
  }
  h4{
    color: #FFF;

  }

</style>
</head>
<body>
    <head>
    <script src="https://kit.fontawesome.com/1c2c2462bf.js" crossorigin="anonymous"></script>
    </head>

    <form method="POST" name="login" class="login">
        <div class="form-head">
            <h4>LOGIN ACCOUNT</h4>
        </div>
        <input id="email" name="email" value="<?php if(isset($_POST['email'])){echo htmlentities ($_POST['email']);}?>"type="text"  placeholder="Enter Your Email">
            <input name="password"type="password"  id="password" placeholder="Enter Your Password" style="margin-bottom: 5px;">
            <i class="far fa-eye" id="togglePassword"></i><br>
            <div class="g-recaptcha" data-sitekey="6LfYlM4gAAAAAJGiLSLstATKkePpJlFsv8OhXPfL"></div>
            <!-- <div class="g-recaptcha" style="margin-top:-5px" data-theme="light" data-sitekey="6LftnMwgAAAAAFhwUSNuBJRI4WOeuhZRi1OylBQj">
            </div> -->
            <button type="submit" value="Login" name="login" style="width:100px;">Login</button>
            <label><a style="color:#022642; margin-right: 25px;font-size: 17px;
            bottom: 70px;" href="reset_password.php" >Forget Your Password ?</a></label>
            
            <label><a style="color:#022642; margin-right: 105px; font-size: 18px; text-decoration:underline;  bottom: 20px " href="register.php">Create A New Account </a></label>
            </div>

        </div>
    </form>



</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    toggle.addEventListener('click', function () {
        if (password.type === "password") {
            password.type = 'text';
        } else {
            password.type = 'password';
        }
        this.classList.toggle('bi-eye');
    });
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</html>
