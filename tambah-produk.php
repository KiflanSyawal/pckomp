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
        <title>Tambah Produk | pckomp</title>
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
                <h3>Tambah Data Produk</h3>
                <div class="box">
                    <form action="" method="POST">
                        <select name="kategori" class="input-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php
                                $categories = $conn->query("SELECT * FROM category ORDER BY kategori_nama ASC");
                                while($c = $categories->fetch(PDO::FETCH_ASSOC)){
                                    echo '<option value="' . $c['kategori_id'] . '">' . htmlspecialchars($c['kategori_nama']) . '</option>';
                                }
                            ?>
                        </select>
                        <input type="text" name="nama" placeholder="Nama Produk" class="input-control" required>
                        <input type="number" name="harga" placeholder="Harga Produk" class="input-control" required>
                        <textarea name="deskripsi" placeholder="Deskripsi Produk" class="input-control" rows="4"></textarea>
                        <select name="status" class="input-control">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        <input type="submit" name="submit" value="Submit" class="btn">
                    </form>
                    <?php
                        if(isset($_POST['submit'])){
                            $kategori  = $_POST['kategori'];
                            $nama      = trim($_POST['nama']);
                            $harga     = (int)$_POST['harga'];
                            $deskripsi = trim($_POST['deskripsi']);
                            $status    = (int)$_POST['status'];

                            $insert = $conn->prepare("INSERT INTO product (kategori_id, product_name, product_price, product_description, product_status) VALUES (:kategori, :nama, :harga, :deskripsi, :status)");
                            $isSuccess = $insert->execute([
                                ':kategori'  => $kategori,
                                ':nama'      => $nama,
                                ':harga'     => $harga,
                                ':deskripsi' => $deskripsi,
                                ':status'    => $status
                            ]);

                            if($isSuccess){
                                echo '<script>alert("Tambah Produk Berhasil")</script>';
                                echo '<script>window.location="data_produk.php"</script>';
                            }else{
                                echo 'gagal menambah data produk';
                            }                   
                        }
                    ?>
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

