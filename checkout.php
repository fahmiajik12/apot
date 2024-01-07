<?php
//  checkout.php

session_start();

if  (!isset($_SESSION['username'])  ||  $_SESSION['role']  !==  'user')  {
   header("Location:  login.php");
   exit();
}

require_once('config.php');

if  ($_SERVER['REQUEST_METHOD']  ===  'POST')  {
   $obat_id  =  $_POST['obat_id'];
   $jumlah  =  $_POST['jumlah'];

   $stmt  =  $conn->prepare("SELECT  *  FROM  obat  WHERE  id  =  :obat_id");
   $stmt->bindParam(':obat_id',  $obat_id);
   $stmt->execute();
   $obat  =  $stmt->fetch();

   if  ($obat)  {
      if  ($jumlah  <=  $obat['stok'])  {
         $total_harga  =  $obat['harga']  *  $jumlah;

         $stmt  =  $conn->prepare("INSERT  INTO  transaksi  (id_user,  id_obat,  jumlah,  total_harga,  tanggal)  VALUES  (:id_user,  :id_obat,  :jumlah,  :total_harga,  NOW())");
         $stmt->bindParam(':id_user',  $_SESSION['user_id']);
         $stmt->bindParam(':id_obat',  $obat_id);
         $stmt->bindParam(':jumlah',  $jumlah);
         $stmt->bindParam(':total_harga',  $total_harga);
         $stmt->execute();

         //  Update  jumlah  stok  obat
         $new_stok  =  $obat['stok']  -  $jumlah;
         $stmt  =  $conn->prepare("UPDATE  obat  SET  stok  =  :stok  WHERE  id  =  :obat_id");
         $stmt->bindParam(':stok',  $new_stok);
         $stmt->bindParam(':obat_id',  $obat_id);
         $stmt->execute();

         header("Location:  index.php");
         exit();
      }  else  {
         $checkout_error  =  "Insufficient  stock";
      }
   }  else  {
      $checkout_error  =  "Invalid  obat";
   }
}

$obat_id  =  $_GET['obat_id'];
?>

<!DOCTYPE  html>
<html>

<head>
   <title>Checkout</title>
   <link  rel="stylesheet"  type="text/css"  href="css/style.css">
</head>

<body>
   <div  class="container">
      <h1>Checkout</h1>

      <?php  if  (isset($checkout_error))  :  ?>
         <p  style="color:  red;"><?php  echo  $checkout_error;  ?></p>
      <?php  endif;  ?>

      <form  method="POST">
         <div  class="form-group">
            <label  for="jumlah">Jumlah:</label>
            <input  type="number"  id="jumlah"  name="jumlah"  min="1"  required>
         </div>

         <div  class="form-group">
            <input  type="hidden"  name="obat_id"  value="<?php  echo  $obat_id;  ?>">
            <input  type="submit"  value="Checkout">
         </div>
      </form>

      <br>
      <a  class="button"  href="index.php">Kembali  ke  Home</a>
   </div>
</body>

</html>
