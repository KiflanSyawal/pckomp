<?php
session_start();
include 'db.php';

if(!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true){
    echo '<script>window.location="login.php"</script>';
    exit;
}

// Delete Category
if(isset($_GET['idk'])){
    $stmt = $conn->prepare("DELETE FROM category WHERE kategori_id = :id");
    $stmt->execute([':id' => $_GET['idk']]);
    echo '<script>window.location="data_kategori.php"</script>';
    exit;
}

// Delete Product
if(isset($_GET['idp'])){
    // Optionally delete uploaded image if exists
    $stmt = $conn->prepare("SELECT product_image FROM product WHERE product_id = :id");
    $stmt->execute([':id' => $_GET['idp']]);
    $prod = $stmt->fetch(PDO::FETCH_OBJ);
    if($prod && !empty($prod->product_image) && file_exists('produk/' . $prod->product_image)){
        unlink('produk/' . $prod->product_image);
    }

    $stmtDelete = $conn->prepare("DELETE FROM product WHERE product_id = :id");
    $stmtDelete->execute([':id' => $_GET['idp']]);
    echo '<script>window.location="data_produk.php"</script>';
    exit;
}

// Default redirect
echo '<script>window.location="dashboard.php"</script>';
exit;
?>

