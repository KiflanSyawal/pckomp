<?php
  session_start();
  include 'db.php';
  if($_SESSION['status_login'] != true){
    echo '<script>window.location="login.php"</script>';
  }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>pckomp</title>
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
                <h3>Data Kategori</h3>
                <div class="box">
                    <p><a href="tambah-kategori.php">Tambah Data</a></p>
                    <table border="1" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th width="60px">No</th>
                                <th>Kategori</th>
                                <th width="150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $kategori = mysqli_query($conn, "SELECT * FROM category ORDER BY kategori_id DESC");
                                while($row = mysqli_fetch_array($kategori)){
                            ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $row['kategori_nama']?></td>
                                <td>
                                    <a href="edit-kategori.php?id=<?php echo $row['kategori_id'] ?>">Edit</a> ||
                                    <a href="proses-hapus.php?idk=<?php echo $row['kategori_id'] ?>" onclick="return confirm('Apakah Anda Yakin Ingin Menghapus?')">Hapus</a>
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