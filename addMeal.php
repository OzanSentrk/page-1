<?php
session_start();

// Veritabanı bağlantısı
$conn = new mysqli('localhost', 'root', '', 'signup');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
} else {
    // POST isteğinden verileri al
    $mealName = $_POST['mealName'];
    $calories = intval($_POST['calories']);
    $carbohydrate = intval($_POST['carbohydrate']);
    $fat = intval($_POST['fat']);
    $protein = intval($_POST['protein']);
    $username = $_SESSION['username'];
    $userId = $_SESSION['user_id'];
    $date = date("Y-m-d"); // Şu anki tarih

    // Veritabanına verileri ekle
    $stmt = $conn->prepare("INSERT INTO meals (Meal_name, Calories, Carbonhydrate, Fat, Protein, username, User_id, Date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiisss", $mealName, $calories, $carbohydrate, $fat, $protein, $username, $userId, $date);
    $stmt->execute();

    // İşlem sonucunu kontrol et
    if ($stmt->affected_rows > 0) {
        echo "Yemek başarıyla eklendi.";
    } else {
        echo "Yemek eklenirken bir hata oluştu.";
    }

    // Sorguyu kapat ve veritabanı bağlantısını kapat
    $stmt->close();
    $conn->close();
}
?>
