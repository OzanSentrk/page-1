<?php
session_start();
ob_start();
include "ayar.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Title -->
    <title>Calorie Crafter</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="">
    <meta name="robots" content="">

    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
    <link href="vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <!-- Light Gallery -->
    <link href="vendor/lightgallery/dist/css/lightgallery.css" rel="stylesheet">
    <link href="vendor/lightgallery/dist/css/lg-thumbnail.css" rel="stylesheet">
    <link href="vendor/lightgallery/dist/css/lg-zoom.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
    <link href="../css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>

<body>

<!--*******************
    Preloader start
********************-->
<div id="preloader">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>
<!--*******************
    Preloader end
********************-->


<!--**********************************
    Main wrapper start
***********************************-->
<div id="main-wrapper">


   <?php
    include "menu.php";
    ?>


    <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body default-height">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Calorie Crafter</a></li>
                    <li class="breadcrumb-item active"><a>Profile</a></li>
                </ol>
            </div>
            <!-- row -->
            <?php

            try {
                // Kullanıcı ID'sini al
                $user_id = $_SESSION['user_id'];

                // Veritabanından kullanıcı bilgilerini çekmek için sorgu
                $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
                $stmt->execute([$user_id]);

                // Kullanıcı bilgilerini al
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    echo "Kullanıcı bulunamadı.";
                    exit();
                }
            } catch (PDOException $e) {
                echo "Veritabanı hatası: " . $e->getMessage();
                die();
            }
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="profile card card-body px-3 pt-3 pb-0">
                        <div class="profile-head">
                            <div class="photo-content">
                                <div class="cover-photo"></div>
                            </div>
                            <div class="profile-info">
                                <div class="profile-photo">
                                    <img src="images/profile/profile.png" class="img-fluid rounded-circle" alt="">
                                </div>
                                <div class="profile-details">
                                    <div class="profile-name px-3 pt-2">
                                        <h4 class="text-primary mb-0"><?php echo $_SESSION['user_name'] ?></h4>
                                        <p>new user</p>
                                    </div>
                                    <div class="profile-email px-2 pt-2">
                                        <h4 class="text-muted mb-0"><?php echo $_SESSION['user_mail'] ?></h4>
                                        <p>Email</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $birthday=$user["date"];
            $birthdate = new DateTime($birthday);
            $today = new DateTime('today');
            $age = $birthdate->diff($today)->y;

            $height = $user["height"]; // cm cinsinden boy
            $weight = $user["weight"]; // kg cinsinden kilo
            $gender = $user["gender"]; // Cinsiyet
            $activity_level = $user["activity_status"]; // Aktivite seviyesi
            $goal = $user["aim"]; // Hedef


            // Bazal Metabolizma Hızını (BMR) Hesaplayalım (Mifflin-St Jeor Denklemi)
            if ($gender == "Male") {
                $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
            } else {
                $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
            }

            // Günlük Kalori İhtiyacını Hesaplayalım
             $daily_calories = $bmr * $activity_level * $goal;
             $protein_calories = $daily_calories * 0.20; // %20 protein
             $carb_calories = $daily_calories * 0.50; // %50 karbonhidrat
             $fat_calories = $daily_calories * 0.30; // %30 yağ
             
             $protein_grams = $protein_calories / 4; // 1 gram protein = 4 kalori
             $carb_grams = $carb_calories / 4; // 1 gram karbonhidrat = 4 kalori
             $fat_grams = $fat_calories / 9; // 1 gram yağ = 9 kalori

            ?>
            <div class="row">
                <div class="col-xl-4">
                    <div class="card h-auto">
                        <div class="card-body">
                            <div class="profile-statistics mb-5">
                                <div class="text-center">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="m-b-0"><?php echo $user['height'] ?> cm</h3><span>Height</span>
                                        </div>
                                        <div class="col">
                                            <h3 class="m-b-0"><?php echo $user['weight'] ?> kg</h3><span>Weight</span>
                                        </div>
                                        <div class="col">
                                            <h3 class="m-b-0"><?php echo $bmr; ?></h3><span>BMI</span>
                                        </div>
                                    </div>

                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="sendMessageModal">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Send Message</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="comment-form">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="text-black font-w600">Name <span class="required">*</span></label>
                                                                <input type="text" class="form-control" value="Author" name="Author" placeholder="Author">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label class="text-black font-w600">Email <span class="required">*</span></label>
                                                                <input type="text" class="form-control" value="Email" placeholder="Email" name="Email">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group">
                                                                <label class="text-black font-w600">Comment</label>
                                                                <textarea rows="8" class="form-control" name="comment" placeholder="Comment"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <div class="form-group mb-0">
                                                                <input type="submit" value="Post Comment" class="submit btn btn-primary" name="submit">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-interest mb-5">
                                <h4 class="text-primary d-inline">Interest</h4>
                                <div class="row mt-3 sp4" id="lightgallery">
                                    <a href="images/profile/2.jpg" data-exthumbimage="images/profile/2.jpg" data-src="images/profile/2.jpg" class="lg-item mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                        <img src="images/profile/2.jpg" alt="" class="img-fluid rounded">
                                    </a>
                                    <a href="images/profile/img9.jpg" data-exthumbimage="images/profile/img9.jpg" data-src="images/profile/img9.jpg" class="lg-item mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                        <img src="images/profile/img9.jpg" alt="" class="img-fluid rounded">
                                    </a>
                                    <a href="images/profile/4.jpg" data-exthumbimage="images/profile/4.jpg" data-src="images/profile/4.jpg" class="lg-item mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                        <img src="images/profile/4.jpg" alt="" class="img-fluid rounded">
                                    </a>
                                    <a href="images/profile/3.jpg" data-exthumbimage="images/profile/3.jpg" data-src="images/profile/3.jpg" class="lg-item mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                        <img src="images/profile/3.jpg" alt="" class="img-fluid rounded">
                                    </a>
                                    <a href="images/profile/img10.jpg" data-exthumbimage="images/profile/img10.jpg" data-src="images/profile/img10.jpg" class="lg-item mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                        <img src="images/profile/img10.jpg" alt="" class="img-fluid rounded">
                                    </a>
                                    <a href="images/profile/2.jpg" data-exthumbimage="images/profile/2.jpg" data-src="images/profile/2.jpg" class="lg-item mb-1 col-lg-4 col-xl-4 col-sm-4 col-6">
                                        <img src="images/profile/2.jpg" alt="" class="img-fluid rounded">
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="card h-auto">
                        <div class="card-body">
                            <div class="profile-tab">
                                <div class="custom-tab-1">
                                    <ul class="nav nav-tabs">

                                        <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link">Profile Update</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">

                                        <?php
                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            // Kullanıcı bilgilerini al
                                            $height = $_POST['height'];
                                            $weight = $_POST['weight'];
                                            $activity_status = $_POST['activity_status'];
                                            $goal_factors = $_POST['goal_factors'];

                                            // Kullanıcı ID'sini session'dan alalım (Örneğin)
                                            $user_id = $_SESSION['user_id'];

                                            // Kullanıcının yaşını hesaplamak için doğum tarihini alalım
                                            $sql = "SELECT date, gender FROM user WHERE id = :user_id";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bindParam(':user_id', $user_id);
                                            $stmt->execute();
                                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                            $birthday = $user['date'];
                                            $gender = $user['gender'];

                                            $birthdate = new DateTime($birthday);
                                            $today = new DateTime('today');
                                            $age = $birthdate->diff($today)->y;

                                            // Bazal Metabolizma Hızını (BMR) Hesaplayalım (Mifflin-St Jeor Denklemi)
                                            if ($gender == "Male") {
                                                $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
                                            } else {
                                                $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
                                            }

                                            // Günlük Kalori İhtiyacını Hesaplayalım
                                            $daily_calories = $bmr * $activity_status * $goal_factors;
                                            $protein_calories = $daily_calories * 0.20; // %20 protein
                                            $carb_calories = $daily_calories * 0.50; // %50 karbonhidrat
                                            $fat_calories = $daily_calories * 0.30; // %30 yağ

                                            $protein_grams = $protein_calories / 4; // 1 gram protein = 4 kalori
                                            $carb_grams = $carb_calories / 4; // 1 gram karbonhidrat = 4 kalori
                                            $fat_grams = $fat_calories / 9; // 1 gram yağ = 9 kalori

                                            // Kullanıcı bilgilerini güncelle
                                            $sql = "UPDATE user SET height = :height, weight = :weight, activity_status = :activity_status, aim = :aim, daily_calories = :daily_calories, protein_grams = :protein_grams, carb_grams = :carb_grams, fat_grams = :fat_grams WHERE id = :user_id";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bindParam(':height', $height);
                                            $stmt->bindParam(':weight', $weight);
                                            $stmt->bindParam(':activity_status', $activity_status);
                                            $stmt->bindParam(':aim', $goal_factors);
                                            $stmt->bindParam(':daily_calories', $daily_calories);
                                            $stmt->bindParam(':protein_grams', $protein_grams);
                                            $stmt->bindParam(':carb_grams', $carb_grams);
                                            $stmt->bindParam(':fat_grams', $fat_grams);
                                            $stmt->bindParam(':user_id', $user_id);
                                            $stmt->execute();

                                            echo "Profiliniz başarıyla güncellendi!";
                                        }

                                        // Kullanıcı bilgilerini almak için mevcut veriyi çekelim
                                        $user_id = $_SESSION['user_id'];
                                        $sql = "SELECT * FROM user WHERE id = :user_id";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bindParam(':user_id', $user_id);
                                        $stmt->execute();
                                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                                        ?>

                                        <div id="profile-settings" class="tab-pane active show">
                                            <div class="pt-3">
                                                <div class="settings-form">
                                                    <h4 class="text-primary">Account Setting</h4>
                                                    <form action="" method="POST">

                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label>Height (cm)</label>
                                                                <input type="number" class="form-control" placeholder="Your Height" name="height" value="<?php echo $user['height']; ?>" required>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Weight (kg)</label>
                                                                <input type="number" class="form-control" placeholder="Your Weight" name="weight" value="<?php echo $user['weight']; ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Activity Status</label>
                                                            <select class="form-control" name="activity_status" required>
                                                                <option value="">Select Activity Status</option>
                                                                <option value="1.2" <?php if($user['activity_status'] == '1.2') echo 'selected'; ?>>Sedentary</option>
                                                                <option value="1.375" <?php if($user['activity_status'] == '1.375') echo 'selected'; ?>>Lightly Active</option>
                                                                <option value="1.55" <?php if($user['activity_status'] == '1.55') echo 'selected'; ?>>Moderately Active</option>
                                                                <option value="1.725" <?php if($user['activity_status'] == '1.725') echo 'selected'; ?>>Very Active</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Goal Factors</label>
                                                            <select class="form-control" name="goal_factors" required>
                                                                <option value="">Select Your Goal</option>
                                                                <option value="0.8" <?php if($user['aim'] == '0.8') echo 'selected'; ?>>Lose Weight</option>
                                                                <option value="1.2" <?php if($user['aim'] == '1.2') echo 'selected'; ?>>Gain Weight</option>
                                                                <option value="1" <?php if($user['aim'] == '1') echo 'selected'; ?>>Maintain Weight</option>
                                                                <option value="1.1" <?php if($user['aim'] == '1.1') echo 'selected'; ?>>Get Fit</option>
                                                            </select>
                                                        </div>
                                                        <button class="btn btn-primary" type="submit">Update Profile</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="replyModal">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Post Reply</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    <textarea class="form-control" rows="4">Message</textarea>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Reply</button>
                                            </div>
                                        </div>
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
        Content body end
    ***********************************-->


    <!--**********************************
        Footer start
    ***********************************-->
    <footer class="footer">
        <div class="copyright">
            <p>Copyright © Designed &amp; Developed by <a href="http://dexignzone.com/" target="_blank">DexignZone</a> 2023</p>
        </div>
    </footer>
    <!--**********************************
        Footer end
    ***********************************-->

    <!--**********************************
       Support ticket button start
    ***********************************-->

    <!--**********************************
       Support ticket button end
    ***********************************-->


</div>
<!--**********************************
    Main wrapper end
***********************************-->

<!--removeIf(production)-->

<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="vendor/global/global.min.js"></script>
<script src="vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
<!-- Light Gallery -->
<script src="vendor/lightgallery/dist/lightgallery.min.js"></script>
<script src="vendor/lightgallery/dist/plugins/thumbnail/lg-thumbnail.min.js"></script>
<script src="vendor/lightgallery/dist/plugins/zoom/lg-zoom.min.js"></script>
<script src="js/custom.min.js"></script>
<script src="js/deznav-init.js"></script>
</body>

</html>
