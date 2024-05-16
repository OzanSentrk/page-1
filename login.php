<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'signup');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
} else {
    // Kullanıcının giriş bilgilerini alın
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kullanıcının bilgilerini kontrol edin ve doğrulayın
    $stmt = $conn->prepare("SELECT id, username, height, weight, activity_level, gender, age FROM registiration WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Kullanıcı giriş yaptı, oturum değişkenlerini ayarla
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['height'] = $row['height'];
        $_SESSION['weight'] = $row['weight'];
        $_SESSION['activity_level'] = $row['activity_level'];
        $_SESSION['gender'] = $row['gender'];
        $_SESSION['age'] = $row['age'];
    
        // Kullanıcı bilgilerini konsola yazdır
        echo "<script>";
        echo "console.log('Kullanıcı Adı:', '" . $_SESSION['username'] . "');";
        echo "console.log('Kullanıcı ID\'si:', '" . $_SESSION['user_id'] . "');";
        echo "console.log('Boy (cm):', '" . $_SESSION['height'] . "');";
        echo "console.log('Kilo (kg):', '" . $_SESSION['weight'] . "');";
        echo "console.log('Aktivite Seviyesi:', '" . $_SESSION['activity_level'] . "');";
        echo "console.log('Cinsiyet:', '" . $_SESSION['gender'] . "');";
        echo "console.log('Yaş:', '" . $_SESSION['age'] . "');";
        echo "</script>";
    
        // Başarı mesajını kullanıcıya gösterin veya başka bir işlem yapın
        echo "<script>alert('Giriş başarılı'); window.location.href='http://localhost/CalorieCrafter/desktop-1.html';</script>";
    } else {
        // E-posta veya şifre hatalı, hata mesajı göster
        echo "<script>alert('Hatalı e-posta veya şifre'); window.location.href='frame-1.html';</script>";
    }
    
    // Sorguları kapatın ve veritabanı bağlantısını kapatın
    $stmt->close();
    $conn->close();
}
?>
