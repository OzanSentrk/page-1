<?php
session_start();

$email = $_POST['email'];
$password = $_POST['password'];
$height = $_POST['height'];
$weight = $_POST['weight'];
$age = $_POST['age'];
$activity_level = $_POST['activity_level'];
$gender = $_POST['gender'];
$username = $_POST['username'];
$conn = new mysqli('localhost', 'root', '', 'signup');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
} else {
    // Boş alanları kontrol et
    if (empty($email) || empty($password) || empty($height) || empty($weight) || empty($activity_level) || empty($gender) || empty($username) || empty($age)) {
        echo "<script>alert('Lütfen tüm alanları doldurunuz'); window.location.href='frame-2.html';</script>";
        exit;
    }

    // E-posta adresinin geçerli olup olmadığını kontrol et
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Geçerli bir e-posta adresi giriniz'); window.location.href='frame-2.html';</script>";
        exit;
    }

    // Veritabanında aynı e-posta adresinin olup olmadığını kontrol et
    $check_email_stmt = $conn->prepare("SELECT * FROM registiration WHERE email = ?");
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $result = $check_email_stmt->get_result();
    if ($result->num_rows > 0) {
        // E-posta adresi zaten kullanılıyor
        echo "<script>alert('Bu e-posta adresi zaten kullanılıyor'); window.location.href='frame-2.html';</script>";
    } else {
        // E-posta adresi kullanılabilir, kayıt işlemini devam ettir
        $stmt = $conn->prepare("INSERT INTO registiration(email, password, height, weight, age, activity_level, gender, username) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssddisss", $email, $password, $height, $weight, $age, $activity_level, $gender, $username);
        $stmt->execute();
        echo "Registration Successful";

        // Oturum değişkenlerini ayarla
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $conn->insert_id;

        header("Location: http://localhost/CalorieCrafter/frame-1.html");
        $stmt->close();
    }
    $check_email_stmt->close();
    $conn->close();
}
?>
