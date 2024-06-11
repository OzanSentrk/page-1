<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <!-- Title -->
    <title>Calorie Crafter</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="">
    <meta name="robots" content="">
    <meta property="og:title" content="Gymove  - Fitness Bootstrap Admin Dashboard Template">
    <meta property="og:description" content="Discover Gymove, the ultimate fitness solution that is designed to help you achieve a healthier lifestyle with its cutting-edge features and personalized programs. Gymove is a fully mobile-responsive admin dashboard template that provides the perfect blend of exercise, nutrition, and motivation. Begin your fitness journey today with Gymove and visit DexignZone for more information.">
    <meta property="og:image" content="https://gymove.dexignzone.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">

    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
    <link href="vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="../css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>

<body class="h-100">
<div class="authincation h-100">
    <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
            <div class="col-md-6">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <?php
                                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    $name = $_POST['name'];
                                    $email = $_POST['email'];
                                    $pass = $_POST['password'];
                                    $date = $_POST['date'];
                                    $height = $_POST['height'];
                                    $weight = $_POST['weight'];
                                    $gender = $_POST['gender'];
                                    $activity_status = $_POST['activity_status'];
                                    $aim = $_POST['aim'];

                                    try {
                                        include "ayar.php";

                                        // Email'in veritabanında zaten olup olmadığını kontrol et
                                        $checkEmailQuery = "SELECT COUNT(*) FROM user WHERE mail = :email";
                                        $stmt = $conn->prepare($checkEmailQuery);
                                        $stmt->bindParam(':email', $email);
                                        $stmt->execute();
                                        $emailCount = $stmt->fetchColumn();

                                        if ($emailCount > 0) {
                                            echo '<div class="alert alert-warning solid alert-dismissible fade show">
									<strong>Warning</strong> A user with this email address already exists. Please use a different email.
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                                        } else {
                                            // Yaş hesaplama
                                            $birthdate = new DateTime($date);
                                            $today = new DateTime('today');
                                            $age = $birthdate->diff($today)->y;

                                            // Bazal Metabolizma Hızını (BMR) Hesaplayalım (Mifflin-St Jeor Denklemi)
                                            if ($gender == "Male") {
                                                $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
                                            } else {
                                                $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
                                            }

                                            // Günlük Kalori İhtiyacını Hesaplayalım
                                            $daily_calories = $bmr * $activity_status * $aim;

                                            // Makro besin ihtiyaçlarını hesaplayalım
                                            $protein_calories = $daily_calories * 0.20; // %20 protein
                                            $carb_calories = $daily_calories * 0.50; // %50 karbonhidrat
                                            $fat_calories = $daily_calories * 0.30; // %30 yağ

                                            // Gram cinsinden makro besinler
                                            $protein_grams = $protein_calories / 4; // 1 gram protein = 4 kalori
                                            $carb_grams = $carb_calories / 4; // 1 gram karbonhidrat = 4 kalori
                                            $fat_grams = $fat_calories / 9; // 1 gram yağ = 9 kalori

                                            // Veritabanına veri ekleme
                                            $sql = "INSERT INTO user (name, mail, password, date, height, weight, gender, activity_status, aim, daily_calories, protein_grams, carb_grams, fat_grams) 
                    VALUES (:name, :email, :password, :date, :height, :weight, :gender, :activity_status, :aim, :daily_calories, :protein_grams, :carb_grams, :fat_grams)";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bindParam(':name', $name);
                                            $stmt->bindParam(':email', $email);
                                            $stmt->bindParam(':password', $pass);
                                            $stmt->bindParam(':date', $date);
                                            $stmt->bindParam(':height', $height);
                                            $stmt->bindParam(':weight', $weight);
                                            $stmt->bindParam(':gender', $gender);
                                            $stmt->bindParam(':activity_status', $activity_status);
                                            $stmt->bindParam(':aim', $aim);
                                            $stmt->bindParam(':daily_calories', $daily_calories);
                                            $stmt->bindParam(':protein_grams', $protein_grams);
                                            $stmt->bindParam(':carb_grams', $carb_grams);
                                            $stmt->bindParam(':fat_grams', $fat_grams);

                                            $stmt->execute();

                                            echo '<div class="alert alert-success solid alert-dismissible fade show">
									<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
									<strong>Success</strong> Registration successful.
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                                            header("Refresh: 2; url=login.php");
                                        }

                                    } catch (PDOException $e) {
                                        echo '<div class="alert alert-danger solid alert-dismissible fade show">
									<strong>Error</strong> Registration failed: ' . $e->getMessage() . '
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>';
                                    }
                                }
                                ?>
                                <div class="text-center mb-3">
                                    <a href="index.html"><img src="images/logo.png" alt="" style="width: 100px; height: auto;"></a>
                                </div>
                                <h4 class="text-center mb-4 ">Sign up your account</h4>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label class="mb-1 form-label">Name</label>
                                        <input type="text" class="form-control" placeholder="Your Name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1 form-label">Email</label>
                                        <input type="email" class="form-control" placeholder="Your Email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1 form-label">Password</label>
                                        <input type="password" class="form-control" placeholder="Your Password" name="password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1 form-label">Birthday</label>
                                        <input type="date" class="form-control" name="date" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1 form-label">Height (cm)</label>
                                        <input type="number" class="form-control" placeholder="Your Height" name="height" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1 form-label">Weight (kg)</label>
                                        <input type="number" class="form-control" placeholder="Your Weight" name="weight" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1 form-label">Gender</label>
                                        <select class="form-control" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1 form-label">Activity Status</label>
                                        <select class="form-control" name="activity_status" required>
                                            <option value="">Select Activity Status</option>
                                            <option value="1.2">Sedentary</option>
                                            <option value="1.375">Lightly Active</option>
                                            <option value="1.55">Moderately Active</option>
                                            <option value="1.725">Very Active</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-1 form-label">Goal Factors</label>
                                        <select class="form-control" name="aim" required>
                                            <option value="">Select Your Goal</option>
                                            <option value="0.8">Lose Weight</option>
                                            <option value="1.2">Gain Weight</option>
                                            <option value="1">Maintain Weight</option>
                                            <option value="1.1">Get Fit</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary light btn-block">Register</button>
                                </form>
                                <div class="new-account mt-3">
                                    <p>Already have an account? <a class="text-primary" href="login.php">Sign in</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="vendor/global/global.min.js"></script>
<script src="vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="js/custom.min.js"></script>
<script src="js/deznav-init.js"></script>

</body>
</html>
