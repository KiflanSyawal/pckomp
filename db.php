<?php
   $hostname = 'localhost';
   $username = 'root';
   $password = '';
   $dbname = 'pckomp';

   $conn = mysqli_connect($hostname, $username, $password, $dbname) or die ('Gagal Terhubung Kedalam Database');
?>