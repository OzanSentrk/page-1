<?php
session_start();
require "ayar.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meal_id = $_POST['meal_id'];
    $meal_name = $_POST['meal_name'];
    $meal_date = $_POST['meal_date'];
    $meal_type = $_POST['meal_type'];
    $user_id = $_SESSION["user_id"];

    try {
        // meals_updated tablosundan yemek bilgilerini al
        $stmt = $conn->prepare("SELECT calories, protein, carbohydrate, fat, sugar FROM meals_updated WHERE id = ?");
        $stmt->execute([$meal_id]);
        $meal = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($meal) {
            $calorie = $meal['calories'];
            $protein = $meal['protein'];
            $carb = $meal['carbohydrate'];
            $fat = $meal['fat'];
            $sugar = $meal['sugar'];

            // Veritabanına ekleme sorgusu
            $sql = "INSERT INTO history (user_id, date, meal, food_id, calorie, protein, carb, fat, sugar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $result = $stmt->execute([$user_id, $meal_date, $meal_type, $meal_id, $calorie, $protein, $carb, $fat, $sugar]);

            if ($result) {
                echo 'success';
            } else {
                throw new Exception("Database insert error.");
            }
        } else {
            throw new Exception("Meal not found. Meal ID: " . $meal_id);
        }
    } catch (Exception $e) {
        echo 'error: ' . $e->getMessage();
    }
}
?>
