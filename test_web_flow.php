<?php
// Test full web flow
require_once __DIR__ . '/db.php';

echo "Testing Web Flow Simulation...\n";

// 1. Test Login
$user = 'admin';
$pass = 'admin';
$stmt = $conn->prepare("SELECT * FROM admin WHERE username = :user AND password = :pass");
$stmt->execute([':user' => $user, ':pass' => md5($pass)]);
$d = $stmt->fetch(PDO::FETCH_OBJ);
if (!$d) {
    die("Login failed!\n");
}
$_SESSION['status_login'] = true;
$_SESSION['id'] = $d->admin_id;
$_SESSION['a_global'] = $d;
echo "[PASS] Login simulation successful\n";

// 2. Test Tambah Kategori
$catName = 'Simulasi Kategori Testing';
$insert = $conn->prepare("INSERT INTO category (kategori_nama) VALUES (:nama)");
$res = $insert->execute([':nama' => $catName]);
$newCatId = (int)$conn->lastInsertId();
if (!$res || $newCatId <= 0) {
    die("Insert category failed!\n");
}
echo "[PASS] Insert category simulation successful (ID: {$newCatId})\n";

// 3. Test Edit Kategori
$catNameEdited = 'Simulasi Kategori Testing Edited';
$update = $conn->prepare("UPDATE category SET kategori_nama = :nama WHERE kategori_id = :id");
$res = $update->execute([':nama' => $catNameEdited, ':id' => $newCatId]);
$check = $conn->query("SELECT kategori_nama FROM category WHERE kategori_id = {$newCatId}")->fetchColumn();
if ($check !== $catNameEdited) {
    die("Update category failed!\n");
}
echo "[PASS] Edit category simulation successful (Value: {$check})\n";

// 4. Test Tambah Produk
$insertProd = $conn->prepare("INSERT INTO product (kategori_id, product_name, product_price, product_description, product_status) VALUES (:k, :n, :p, :d, :s)");
$res = $insertProd->execute([
    ':k' => $newCatId,
    ':n' => 'Simulasi Produk Keyboard Gaming',
    ':p' => 750000,
    ':d' => 'Mechanical Keyboard RGB',
    ':s' => 1
]);
$newProdId = (int)$conn->lastInsertId();
if (!$res || $newProdId <= 0) {
    die("Insert product failed!\n");
}
echo "[PASS] Insert product simulation successful (ID: {$newProdId})\n";

// 5. Test Edit Produk
$updateProd = $conn->prepare("UPDATE product SET product_price = :p WHERE product_id = :id");
$res = $updateProd->execute([':p' => 800000, ':id' => $newProdId]);
$checkPrice = $conn->query("SELECT product_price FROM product WHERE product_id = {$newProdId}")->fetchColumn();
if ((int)$checkPrice !== 800000) {
    die("Update product price failed!\n");
}
echo "[PASS] Edit product simulation successful (New Price: {$checkPrice})\n";

// 6. Test Hapus Produk
$delProd = $conn->prepare("DELETE FROM product WHERE product_id = :id");
$delProd->execute([':id' => $newProdId]);
$checkProd = $conn->query("SELECT product_id FROM product WHERE product_id = {$newProdId}")->fetchColumn();
if ($checkProd !== false) {
    die("Delete product failed!\n");
}
echo "[PASS] Delete product simulation successful\n";

// 7. Test Hapus Kategori
$delCat = $conn->prepare("DELETE FROM category WHERE kategori_id = :id");
$delCat->execute([':id' => $newCatId]);
$checkCat = $conn->query("SELECT kategori_id FROM category WHERE kategori_id = {$newCatId}")->fetchColumn();
if ($checkCat !== false) {
    die("Delete category failed!\n");
}
echo "[PASS] Delete category simulation successful\n";

echo "\n>>> ALL WEB FLOW SIMULATION CHECKS PASSED! <<<\n";
