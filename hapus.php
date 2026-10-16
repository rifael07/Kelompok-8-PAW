<?php
require "koneksi.php";

$id =  $_GET ["id"];
mysqli_execute_query($koneksi, "DELETE FROM data_pelanggan_dan_alamat WHERE id = ?", [$id]);
header("Location: index.php");
exit;
?>