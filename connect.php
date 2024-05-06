<?php


// Veritabanı bağlantısını oluştur
$link = new mysqli('localhost', 'root', '', 'signup');

// Bağlantıyı kontrol et
if ($link->connect_error) {
    echo "Veritabanına bağlanılamadı: " . mysqli_connect_error();
}

?>
