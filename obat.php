<?php
// obat.php

session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}

require_once('config.php');

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
  $nama_obat = $_POST['nama_obat'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];

  $stmt = $conn->prepare("INSERT INTO obat (nama_obat, harga, stok) VALUES (:nama_obat, :harga, :stok)");
  $stmt->bindParam(':nama_obat', $nama_obat);
  $stmt->bindParam(':harga', $harga);
  $stmt->bindParam(':stok', $stok);
  $stmt->execute();

  header("Location: obat.php");
  exit();
}

// Handle Read
$stmt = $conn->query("SELECT * FROM obat");
$obat_data = $stmt->fetchAll();

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
  $obat_id = $_POST['obat_id'];
  $nama_obat = $_POST['nama_obat'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];

  $stmt = $conn->prepare("UPDATE obat SET nama_obat = :nama_obat, harga = :harga, stok = :stok WHERE id = :obat_id");
  $stmt->bindParam(':obat_id', $obat_id);
  $stmt->bindParam(':nama_obat', $nama_obat);
  $stmt->bindParam(':harga', $harga);
  $stmt->bindParam(':stok', $stok);
  $stmt->execute();

  header("Location: obat.php");
  exit();
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
  $obat_id = $_POST['obat_id'];

  // Delete obat from the table
  $stmt = $conn->prepare("DELETE FROM obat WHERE id = :obat_id");
  $stmt->bindParam(':obat_id', $obat_id);
  $stmt->execute();

  // Delete transactions related to the obat
  $stmt = $conn->prepare("DELETE FROM transaksi WHERE id_obat = :obat_id");
  $stmt->bindParam(':obat_id', $obat_id);
  $stmt->execute();

  header("Location: obat.php");
  exit();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Manage Obat</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
  <div class="container">
    <h1>Manage Obat</h1>

    <form method="POST">
      <div class="form-group">
        <label for="nama_obat">Nama Obat:</label>
        <input type="text" id="nama_obat" name="nama_obat" required>
      </div>

      <div class="form-group">
        <label for="harga">Harga:</label>
        <input type="text" id="harga" name="harga" required>
      </div>

      <div class="form-group">
        <label for="stok">Stok:</label>
        <input type="text" id="stok" name="stok" required>
      </div>

      <div class="form-group">
        <input type="submit" name="create" value="Tambah">
      </div>
    </form>

    <table>
      <tr>
        <th>ID</th>
        <th>Nama Obat</th>
        <th>Harga</th>
        <th>Stok</th>
        <th></th>
      </tr>
      <?php foreach ($obat_data as $obat) : ?>
        <tr>
          <form method="POST">
            <td><?php echo $obat['id']; ?></td>
            <td><input type="text" name="nama_obat" value="<?php echo $obat['nama_obat']; ?>" required></td>
            <td><input type="text" name="harga" value="<?php echo $obat['harga']; ?>" required></td>
            <td><input type="text" name="stok" value="<?php echo $obat['stok']; ?>" required></td>
            <td>
              <input type="hidden" name="obat_id" value="<?php echo $obat['id']; ?>">
              <input type="submit" name="update" value="Ubah">
              <input type="submit" name="delete" value="Hapus">
            </td>
          </form>
        </tr>
      <?php endforeach; ?>
    </table>

    <br>
    <a class="button" href="index.php">Kembali ke Home</a>
  </div>
</body>

</html>
