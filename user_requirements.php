<?php
session_start();
include 'ayar.php';

$user_id = $_SESSION['user_id'];

try {
    $sql = "SELECT daily_calories, protein_grams, carb_grams, fat_grams FROM user WHERE id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user_requirements = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user_requirements) {
        // Bilgileri JSON formatında kaydedelim
        file_put_contents('user_requirements.json', json_encode($user_requirements));
    } else {
        echo "Kullanıcı bilgileri bulunamadı.";
    }
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>
