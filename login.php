<?php
include 'db.php';
if(isset($_SESSION['status_login']) && $_SESSION['status_login'] == true){
    echo '<script>window.location="dashboard.php"</script>';
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login | pckomp</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Quicksand&display=swap" rel="stylesheet">
    </head>
    <body id="bg-Login">
        <div class="box-login">
            <h2>Login</h2>
            <form action="" method="POST">
                <input type="text" name="user" placeholder="username" class="input-control" required>
                <input type="password" name="pass" placeholder="password" class="input-control" required>
                <input type="submit" name="submit" value="login" class="btn">
            </form>
            <?php
                if(isset($_POST['submit'])){
                    $user = trim($_POST['user']);
                    $pass = trim($_POST['pass']);

                    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :user AND password = :pass");
                    $stmt->execute([
                        ':user' => $user,
                        ':pass' => md5($pass)
                    ]);
                    $d = $stmt->fetch(PDO::FETCH_OBJ);

                    if($d){
                        $_SESSION['status_login'] = true;
                        $_SESSION['status login'] = true;
                        $_SESSION['a_global']     = $d;
                        $_SESSION['id']           = $d->admin_id;
                        echo '<script>window.location="dashboard.php"</script>';
                    } else {
                        echo '<script>alert("username atau password anda salah!")</script>';
                    }
                }
            ?>
        </div>
    </body>
</html>
