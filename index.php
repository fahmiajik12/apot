<?php
// index.php

session_start();

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

require_once('config.php');

$stmt = $conn->query("SELECT * FROM obat");
$obat_data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
  <title>Apotek</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
  <div class="container">
    <h1>Apotek</h1>

    <?php if ($_SESSION['role'] === 'admin') : ?>
      <a class="button" href="obat.php">Manage Obat</a>
    <?php endif; ?>

    <table>
      <tr>
        <th>ID</th>
        <th>Nama Obat</th>
        <th>Harga</th>
        <th>Stok</th>
        <?php if ($_SESSION['role'] === 'user') : ?>
          <th></th>
        <?php endif; ?>
      </tr>
      <?php foreach ($obat_data as $obat) : ?>
        <tr>
          <td><?php echo $obat['id']; ?></td>
          <td><?php echo $obat['nama_obat']; ?></td>
          <td><?php echo $obat['harga']; ?></td>
          <td><?php echo $obat['stok']; ?></td>
          <?php if ($_SESSION['role'] === 'user') : ?>
            <td><a class="button" href="checkout.php?obat_id=<?php echo $obat['id']; ?>">Beli</a></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
    </table>

    <br>
    <a class="button" href="logout.php">Logout</a>
  </div>
</body>

</html>
