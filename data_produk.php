<?php
  session_start();
  include 'db.php';
  if(!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true){
    echo '<script>window.location="login.php"</script>';
    exit;
  }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Data Produk | pckomp</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class="container">
            <h1><a href="dashboard.php">pckomp</a></h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="data_kategori.php">Data Kategori</a></li>
                <li><a href="data_produk.php">Data Produk</a></li>
                <li><a href="keluar.php">Log out</a></li>
            </ul>
            </div>
        </header>
        
        <div class="section">
            <div class="container">
                <h3>Data Produk</h3>
                <div class="box">
                    <p><a href="tambah-produk.php">+ Tambah Data Produk</a></p>
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="60px">No</th>
                                <th>Kategori</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $sql = "SELECT p.*, c.kategori_nama 
                                        FROM product p 
                                        LEFT JOIN category c ON p.kategori_id = c.kategori_id 
                                        ORDER BY p.product_id DESC";
                                $produk = $conn->query($sql);
                                while($row = $produk->fetch(PDO::FETCH_ASSOC)){
                            ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo htmlspecialchars($row['kategori_nama'] ?? 'Tanpa Kategori') ?></td>
                                <td><?php echo htmlspecialchars($row['product_name']) ?></td>
                                <td>Rp. <?php echo number_format($row['product_price'], 0, ',', '.') ?></td>
                                <td><?php echo htmlspecialchars($row['product_description'] ?? '') ?></td>
                                <td><?php echo ($row['product_status'] == 1) ? 'Aktif' : 'Tidak Aktif' ?></td>
                                <td>
                                    <a href="edit-produk.php?id=<?php echo $row['product_id'] ?>">Edit</a> ||
                                    <a href="proses-hapus.php?idp=<?php echo $row['product_id'] ?>" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Produk Ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <footer>
            <div class="container">
                <small>Copyright &copy; 2022 - pckomp.</small>
            </div>
        </footer>
    </body>
</html>

