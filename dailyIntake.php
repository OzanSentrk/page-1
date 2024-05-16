<?php
session_start();

// Veritabanı bağlantısı
$conn = new mysqli('localhost', 'root', '', 'signup');

// Eğer bir hata oluşursa, hata mesajını yazdır ve işlemi sonlandır
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
} else {
    // Kullanıcı adı, kullanıcı ID'si ve tarih bilgisine göre yemek verilerini seçin
    $username = $_SESSION['username'];
    $userId = $_SESSION['user_id'];
    $date = date("Y-m-d"); // Şu anki tarih

    $sql = "SELECT Calories, Protein, Fat, Carbonhydrate FROM meals WHERE username = ? AND User_id = ? AND Date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $username, $userId, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Günlük toplam kalori, protein, yağ ve karbonhidrat miktarlarını saklamak için değişkenler oluşturun
    $dailyValues = array(
        'calories' => 0,
        'protein' => 0,
        'fat' => 0,
        'carbohydrate' => 0
    );

    // Yemek verilerini döngüyle alın ve günlük miktarları hesaplayın
    while ($row = $result->fetch_assoc()) {
        $dailyValues['calories'] += $row['Calories'];
        $dailyValues['protein'] += $row['Protein'];
        $dailyValues['fat'] += $row['Fat'];
        $dailyValues['carbohydrate'] += $row['Carbonhydrate'];
    }

    // Uyarıları gizle
    error_reporting(0);

    // JSON verisini döndür
    echo json_encode($dailyValues);
    
    // Sorguyu kapat ve veritabanı bağlantısını kapat
    $stmt->close();
    $conn->close();
}
?>
