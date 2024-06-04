<?php
include 'ayar.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meal_id = $_POST['meal_id'];
    $meal_name = $_POST['meal_name'];
    $meal_date = $_POST['meal_date'];
    $meal_type = $_POST['meal_type'];
    $user_id = $_SESSION["user_id"];

    // Veritabanına ekleme sorgusu
    $sql = "INSERT INTO diet_menus (user_id, meal_id, meal_name, meal_date, meal_type) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $meal_id, $meal_name, $meal_date, $meal_type]);

    if ($stmt) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
