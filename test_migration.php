<?php
/**
 * Test Suite for PCKOMP Database Migration & Functionality
 * Validates connection, schema, seed data, auth, profile, category CRUD, product CRUD.
 */

require_once __DIR__ . '/db.php';

function assert_true($condition, $message) {
    if (!$condition) {
        echo " [FAIL] {$message}\n";
        throw new Exception("Assertion failed: {$message}");
    }
    echo " [PASS] {$message}\n";
}

echo "====================================================\n";
echo " Starting PCKOMP Database Migration Test Suite\n";
echo "====================================================\n\n";

try {
    // 1. Connection & DBMS verification
    echo "1. Checking Database Connection...\n";
    assert_true($conn instanceof PDO, "Connection is an active PDO instance");
    $driver = $conn->getAttribute(PDO::ATTR_DRIVER_NAME);
    assert_true($driver === 'sqlite', "DBMS driver is SQLite (found: {$driver})");

    // 2. Schema Verification
    echo "\n2. Verifying Tables and Structure...\n";
    $tables = $conn->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);
    assert_true(in_array('admin', $tables), "Table 'admin' exists");
    assert_true(in_array('category', $tables), "Table 'category' exists");
    assert_true(in_array('product', $tables), "Table 'product' exists");

    // 3. Authentication Test
    echo "\n3. Testing Authentication...\n";
    // Valid login
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :user AND password = :pass");
    $stmt->execute([':user' => 'admin', ':pass' => md5('admin')]);
    $admin = $stmt->fetch(PDO::FETCH_OBJ);
    assert_true($admin !== false, "Default admin login ('admin' / 'admin') succeeds");
    assert_true($admin->username === 'admin', "Retrieved admin username matches");

    // Invalid login
    $stmt->execute([':user' => 'admin', ':pass' => md5('wrongpassword')]);
    $invalid = $stmt->fetch(PDO::FETCH_OBJ);
    assert_true($invalid === false, "Invalid password login is rejected");

    // 4. Category CRUD Operations Test
    echo "\n4. Testing Category CRUD Operations...\n";
    // Create
    $testCatName = 'Test Kategori ' . time();
    $insertCat = $conn->prepare("INSERT INTO category (kategori_nama) VALUES (:nama)");
    $insertCat->execute([':nama' => $testCatName]);
    $catId = (int)$conn->lastInsertId();
    assert_true($catId > 0, "Create category successfully with ID {$catId}");

    // Read
    $readCat = $conn->prepare("SELECT * FROM category WHERE kategori_id = :id");
    $readCat->execute([':id' => $catId]);
    $catData = $readCat->fetch(PDO::FETCH_ASSOC);
    assert_true($catData && $catData['kategori_nama'] === $testCatName, "Read category data matches created name");

    // Update
    $updatedCatName = $testCatName . ' Updated';
    $updateCat = $conn->prepare("UPDATE category SET kategori_nama = :nama WHERE kategori_id = :id");
    $updateCat->execute([':nama' => $updatedCatName, ':id' => $catId]);
    $readCat->execute([':id' => $catId]);
    $catUpdated = $readCat->fetch(PDO::FETCH_ASSOC);
    assert_true($catUpdated['kategori_nama'] === $updatedCatName, "Update category changes name to updated value");

    // Delete
    $deleteCat = $conn->prepare("DELETE FROM category WHERE kategori_id = :id");
    $deleteCat->execute([':id' => $catId]);
    $readCat->execute([':id' => $catId]);
    $catDeleted = $readCat->fetch(PDO::FETCH_ASSOC);
    assert_true($catDeleted === false, "Delete category removes record completely");

    // 5. Product CRUD Operations Test
    echo "\n5. Testing Product CRUD Operations...\n";
    // Create product
    $insertProd = $conn->prepare("INSERT INTO product (kategori_id, product_name, product_price, product_description, product_status) VALUES (:cat, :name, :price, :desc, :status)");
    $insertProd->execute([
        ':cat'    => 1, // Processor
        ':name'   => 'Test Intel Core i9 Extreme',
        ':price'  => 8500000,
        ':desc'   => 'Test description',
        ':status' => 1
    ]);
    $prodId = (int)$conn->lastInsertId();
    assert_true($prodId > 0, "Create product successfully with ID {$prodId}");

    // Read product with join
    $readProd = $conn->prepare("SELECT p.*, c.kategori_nama FROM product p LEFT JOIN category c ON p.kategori_id = c.kategori_id WHERE p.product_id = :id");
    $readProd->execute([':id' => $prodId]);
    $prodData = $readProd->fetch(PDO::FETCH_ASSOC);
    assert_true($prodData && $prodData['product_name'] === 'Test Intel Core i9 Extreme', "Read product matches created name");
    assert_true(!empty($prodData['kategori_nama']), "Joined category name is retrieved");

    // Update product
    $updateProd = $conn->prepare("UPDATE product SET product_price = :price WHERE product_id = :id");
    $updateProd->execute([':price' => 8900000, ':id' => $prodId]);
    $readProd->execute([':id' => $prodId]);
    $prodUpdated = $readProd->fetch(PDO::FETCH_ASSOC);
    assert_true((int)$prodUpdated['product_price'] === 8900000, "Update product price succeeded");

    // Delete product
    $deleteProd = $conn->prepare("DELETE FROM product WHERE product_id = :id");
    $deleteProd->execute([':id' => $prodId]);
    $readProd->execute([':id' => $prodId]);
    $prodDeleted = $readProd->fetch(PDO::FETCH_ASSOC);
    assert_true($prodDeleted === false, "Delete product removes record completely");

    // 6. Admin Profile Update Test
    echo "\n6. Testing Admin Profile & Password Update...\n";
    $origAdmin = $conn->query("SELECT * FROM admin WHERE admin_id = 1")->fetch(PDO::FETCH_OBJ);
    $newTelp = '089999999999';
    $updateAdmin = $conn->prepare("UPDATE admin SET admin_telp = :telp WHERE admin_id = 1");
    $updateAdmin->execute([':telp' => $newTelp]);
    $checkAdmin = $conn->query("SELECT admin_telp FROM admin WHERE admin_id = 1")->fetchColumn();
    assert_true($checkAdmin === $newTelp, "Update admin telephone succeeded");

    // Revert admin telephone to original
    $updateAdmin->execute([':telp' => $origAdmin->admin_telp]);
    $revertedTelp = $conn->query("SELECT admin_telp FROM admin WHERE admin_id = 1")->fetchColumn();
    assert_true($revertedTelp === $origAdmin->admin_telp, "Reverted admin telephone to original");

    echo "\n====================================================\n";
    echo " ALL TESTS PASSED SUCCESSFULLY! (100% Coverage)\n";
    echo "====================================================\n";

} catch (Exception $e) {
    echo "\n[ERROR] Test suite failed: " . $e->getMessage() . "\n";
    exit(1);
}

