<?php
session_start();
ob_start();
require "ayar.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meal_id = $_POST['meal_id'];
    $meal_name = $_POST['meal_name'];
    $meal_date = $_POST['meal_date'];
    $portion = $_POST['portion'];
    $user_id = $_SESSION["user_id"];
    $meal_type = $_POST['meal_type'];


    // Yemek bilgilerini veritabanından al
    $sql = "SELECT calories, protein, carbohydrate, fat, sugar, image FROM meals_updated WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$meal_id]);
    $meal = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($meal) {
        
        $image = $meal['image'];
        // Değerleri porsiyon ile çarp

        $calories = $meal['calories'] * $portion;
        $protein = $meal['protein'] * $portion;
        $carbohydrate = $meal['carbohydrate'] * $portion;
        $fat = $meal['fat'] * $portion;
        $sugar = $meal['sugar'] * $portion;
        ;
        // Veritabanına ekleme sorgusu
        $sql = "INSERT INTO history (user_id, date, meal_name, food_id, calorie, protein, carb, fat, sugar, `portion`,`image`,`meal_type`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id, $meal_date, $meal_name, $meal_id, $calories, $protein, $carbohydrate, $fat, $sugar, $portion,$image, $meal_type]);

        if ($stmt) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'error: Meal not found';
    }
}
?>
