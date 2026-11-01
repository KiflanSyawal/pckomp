<?php
session_start();
if(isset($_SESSION['status_login']) && $_SESSION['status_login'] == true){
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;

