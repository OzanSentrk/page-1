<?php
// Veritabanı bağlantısı
$conn = new mysqli('localhost', 'root', '', 'signup');

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
} else {
    session_start();

    // Kullanıcı bilgilerini alın
    $age = $_POST['age'];
    $gender = $_POST['gender']; // 'male' veya 'female'
    $height = $_POST['height']; // cm cinsinden
    $weight = $_POST['weight']; // kg cinsinden
    $activity_level = $_POST['activity_level']; // Kullanıcının günlük aktivite seviyesi (sedentary, lightly_active, moderately_active, very_active, extra_active gibi)

    // Günlük alınması gereken kalori miktarını hesaplayın
    if ($gender === 'male') {
        $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
    } elseif ($gender === 'female') {
        $bmr = 10 * $weight + 6.25 * $height - 5 * $age - 161;
    }

    // Aktivite seviyesine göre bmr'yi çarpın
    if ($activity_level === 'sedentary') {
        $calories_needed = $bmr * 1.2;
    } elseif ($activity_level === 'lightly_active') {
        $calories_needed = $bmr * 1.375;
    } elseif ($activity_level === 'moderately_active') {
        $calories_needed = $bmr * 1.55;
    } elseif ($activity_level === 'very_active') {
        $calories_needed = $bmr * 1.725;
    } elseif ($activity_level === 'extra_active') {
        $calories_needed = $bmr * 1.9;
    }

    // Günlük protein, yağ ve karbonhidrat gereksinimlerini belirleyin
    // Bu oranlar genellikle kilogram başına 1.2 - 2.2 gram protein, 0.5 - 0.9 gram yağ ve geri kalanı karbonhidrat olacak şekilde hesaplanır
    $protein_needed = 1.2 * $weight; // Günlük protein gereksinimi (gram cinsinden)
    $fat_needed = 0.3 * $weight; // Günlük yağ gereksinimi (gram cinsinden)
    $carbohydrate_needed = ($calories_needed - (4 * $protein_needed) - (9 * $fat_needed)) / 4; // Günlük karbonhidrat gereksinimi (gram cinsinden)

    // Hesaplanan değerleri kullanarak bir dizi oluşturun
    $daily_requirements = array(
        'calories' => $calories_needed,
        'protein' => $protein_needed,
        'fat' => $fat_needed,
        'carbohydrate' => $carbohydrate_needed
    );

    // Sonuçları döndürün
    echo json_encode($daily_requirements);
}

// Bağlantıyı kapat
$conn->close();
?>
