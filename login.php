<?php
//  login.php

session_start();

if  (isset($_SESSION['username']))  {
   header("Location:  index.php");
   exit();
}

if  ($_SERVER['REQUEST_METHOD']  ===  'POST')  {
   require_once('config.php');

   $username  =  $_POST['username'];
   $password  =  $_POST['password'];

   $stmt  =  $conn->prepare("SELECT  *  FROM  users  WHERE  username  =  :username");
   $stmt->bindParam(':username',  $username);
   $stmt->execute();
   $user  =  $stmt->fetch();

   if  ($user  &&  password_verify($password,  $user['password']))  {
      $_SESSION['username']  =  $username;
      $_SESSION['role']  =  $user['role'];

      header("Location:  index.php");
      exit();
   }  else  {
      $login_error  =  "Username  or  password  is  incorrect";
   }
}
?>

<!DOCTYPE  html>
<html>

<head>
   <title>Apotek  Login</title>
   <link  rel="stylesheet"  type="text/css"  href="css/style.css">
</head>

<body>
   <div  class="container">
      <h1>Apotek  Login</h1>

      <?php  if  (isset($login_error))  :  ?>
         <p  style="color:  red;"><?php  echo  $login_error;  ?></p>
      <?php  endif;  ?>

      <form  method="POST">
         <div  class="form-group">
            <label  for="username">Username:</label>
            <input  type="text"  id="username"  name="username"  required>
         </div>

         <div  class="form-group">
            <label  for="password">Password:</label>
            <input  type="password"  id="password"  name="password"  required>
         </div>

         <div  class="form-group">
            <input  type="submit"  value="Login">
         </div>
         <p>Don't  have  an  account?  <a  href="register.php">Register</a></p>
      </form>
   </div>
</body>

</html>
