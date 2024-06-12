<?php
session_start();
ob_start();
include "ayar.php";
oturumKontrol();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
$user_id = $_SESSION['user_id']; // Giriş yapmış kullanıcının ID'sini al

// Kullanıcının ihtiyaçlarını veritabanından çek
$stmt = $conn->prepare("SELECT daily_calories, protein_grams, fat_grams, carb_grams FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user_requirements = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user_requirements) {
    die("Kullanıcı bilgileri bulunamadı.");
}

// Random yemekleri çekmek için sorgu
$random_meals_stmt = $conn->prepare("SELECT name, image, category, calories, cholesterol, carbohydrate, sugar, protein, fat FROM meals_updated ORDER BY RAND() LIMIT 5");
$random_meals_stmt->execute();
$random_meals = $random_meals_stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Title -->
    <title>Calori Crafter</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="DexignZone">
    <meta name="robots" content="">

    <meta name="keywords" content="admin, admin dashboard, admin template, bootstrap, bootstrap 5, bootstrap 5 admin template, fitness, fitness admin, modern, responsive admin dashboard, sales dashboard, sass, ui kit, web app">
    <meta name="description" content="Discover Gymove, the ultimate fitness solution that is designed to help you achieve a healthier lifestyle with its cutting-edge features and personalized programs. Gymove is a fully mobile-responsive admin dashboard template that provides the perfect blend of exercise, nutrition, and motivation. Begin your fitness journey today with Gymove and visit DexignZone for more information.">

    <meta property="og:title" content="Gymove  - Fitness Bootstrap Admin Dashboard Template">
    <meta property="og:description" content="Discover Gymove, the ultimate fitness solution that is designed to help you achieve a healthier lifestyle with its cutting-edge features and personalized programs. Gymove is a fully mobile-responsive admin dashboard template that provides the perfect blend of exercise, nutrition, and motivation. Begin your fitness journey today with Gymove and visit DexignZone for more information.">
    <meta property="og:image" content="https://gymove.dexignzone.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">

    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">
    <link rel="stylesheet" href="vendor/chartist/css/chartist.min.css">
    <link href="vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
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

    <!--**********************************
        Nav header start
    ***********************************-->

    <!--**********************************
        Nav header end
    ***********************************-->

    <!--**********************************
        Chat box start
    ***********************************-->

    <!--**********************************
        Chat box End
    ***********************************-->

    <!--**********************************
        Header start
    ***********************************-->
  <?php include ("menu.php")?>
    <!--**********************************
        Sidebar end

    ***********************************-->

    <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body default-height">
        <!-- row -->

        <div class="container-fluid">
            <div class="row">

                <div class="col-xl-9 col-xxl-8">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header d-sm-flex d-block pb-0 border-0">
                                    <div class="me-auto pe-3">
                                        <h4 class="text-black font-w600 fs-20">Recomended Daily Menu for You</h4>
                                        <p class="fs-13 mb-0">Generate daily menu with Calorie Crafter</p>
                                    </div>
                                    <form id="pythonForm" method="post">
                                    <button class="btn btn-primary rounded d-none d-md-block">GENERATE</button>
                                    </form>
                                </div>
                                <div class="card-body pt-2">
                                    <div class="testimonial-one owl-carousel">
                                        <?php

                                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                            $meals = get_meals_from_db();
                                            $categorized_meals = categorize_meals($meals);

                                            // Kullanıcının günlük ihtiyacı veritabanından çekildi
                                            $user_requirements = [
                                                'calories' => $user_requirements['daily_calories'], 
                                                'carbs' => $user_requirements['carb_grams'], 
                                                'protein' => $user_requirements['protein_grams'], 
                                                'fat' => $user_requirements['fat_grams']
                                            ];

                                            // Genetik algoritmayı çalıştırma
                                            $best_meal_plan = genetic_algorithm(100, 50, $categorized_meals, $user_requirements);

                                            if ($best_meal_plan) {
                                                $total_calories = 0;
                                                $total_carbohydrate = 0;
                                                $total_protein = 0;
                                                $total_fat = 0;

                                                // Kahvaltı
                                                $breakfast_calories = round($best_meal_plan['Breakfast']['calories'] * $best_meal_plan['Breakfast Portion']);
                                                $breakfast_carbohydrate = round($best_meal_plan['Breakfast']['carbohydrate'] * $best_meal_plan['Breakfast Portion']);
                                                $breakfast_protein = round($best_meal_plan['Breakfast']['protein'] * $best_meal_plan['Breakfast Portion']);
                                                $breakfast_fat = round($best_meal_plan['Breakfast']['fat'] * $best_meal_plan['Breakfast Portion']);

                                                $total_calories += $breakfast_calories;
                                                $total_carbohydrate += $breakfast_carbohydrate;
                                                $total_protein += $breakfast_protein;
                                                $total_fat += $breakfast_fat;

                                                echo "<div class='item'>";
                                                echo "<div class='card text-center'>";
                                                echo "<div class='card-body'>";
                                                echo "<img src='" . $best_meal_plan['Breakfast']['image'] . "' alt='" . $best_meal_plan['Breakfast']['name'] . "' '><br>";
                                                echo "<h5 class='fs-16 font-w500 mb-1'>" . $best_meal_plan['Breakfast']['name'] . "</h5>";
                                                echo "<p>Pcs/Spoon: " . round($best_meal_plan['Breakfast Portion'] * 2) / 2 . "</p>";
                                                echo "<p>Calorie: $breakfast_calories</p>";
                                                echo "<p>Carbohydrate: $breakfast_carbohydrate g</p>";
                                                echo "<p>Protein: $breakfast_protein g</p>";
                                                echo "<p>Fat: $breakfast_fat g</p>";
                                                echo "</div>";
                                                echo "</div>";
                                                echo "</div>";

                                                // Öğle Yemeği
                                                $lunch_calories = round($best_meal_plan['Lunch/Snacks']['calories'] * $best_meal_plan['Lunch/Snacks Portion']);
                                                $lunch_carbohydrate = round($best_meal_plan['Lunch/Snacks']['carbohydrate'] * $best_meal_plan['Lunch/Snacks Portion']);
                                                $lunch_protein = round($best_meal_plan['Lunch/Snacks']['protein'] * $best_meal_plan['Lunch/Snacks Portion']);
                                                $lunch_fat = round($best_meal_plan['Lunch/Snacks']['fat'] * $best_meal_plan['Lunch/Snacks Portion']);

                                                $total_calories += $lunch_calories;
                                                $total_carbohydrate += $lunch_carbohydrate;
                                                $total_protein += $lunch_protein;
                                                $total_fat += $lunch_fat;

                                                echo "<div class='item'>";
                                                echo "<div class='card text-center'>";
                                                echo "<div class='card-body'>";
                                                echo "<img src='" . $best_meal_plan['Lunch/Snacks']['image'] . "' alt='" . $best_meal_plan['Lunch/Snacks']['name'] . "' '><br>";
                                                echo "<h5 class='fs-16 font-w500 mb-1'>" . $best_meal_plan['Lunch/Snacks']['name'] . "</h5>";
                                                echo "<p>Pcs/Spoon: " . round($best_meal_plan['Lunch/Snacks Portion'] * 2) / 2 . "</p>";
                                                echo "<p>Calorie: $lunch_calories</p>";
                                                echo "<p>Carbohydrate: $lunch_carbohydrate g</p>";
                                                echo "<p>Protein: $lunch_protein g</p>";
                                                echo "<p>Fat: $lunch_fat g</p>";
                                                echo "</div>";
                                                echo "</div>";
                                                echo "</div>";

                                                // Akşam Yemeği
                                                $dinner_calories = round($best_meal_plan['One Dish Meal']['calories'] * $best_meal_plan['One Dish Meal Portion']);
                                                $dinner_carbohydrate = round($best_meal_plan['One Dish Meal']['carbohydrate'] * $best_meal_plan['One Dish Meal Portion']);
                                                $dinner_protein = round($best_meal_plan['One Dish Meal']['protein'] * $best_meal_plan['One Dish Meal Portion']);
                                                $dinner_fat = round($best_meal_plan['One Dish Meal']['fat'] * $best_meal_plan['One Dish Meal Portion']);

                                                $total_calories += $dinner_calories;
                                                $total_carbohydrate += $dinner_carbohydrate;
                                                $total_protein += $dinner_protein;
                                                $total_fat += $dinner_fat;

                                                echo "<div class='item'>";
                                                echo "<div class='card text-center'>";
                                                echo "<div class='card-body'>";
                                                echo "<img src='" . $best_meal_plan['One Dish Meal']['image'] . "' alt='" . $best_meal_plan['One Dish Meal']['name'] . "' '><br>";
                                                echo "<h5 class='fs-16 font-w500 mb-1'>" . $best_meal_plan['One Dish Meal']['name'] . "</h5>";
                                                echo "<p>Pcs/Spoon: " . round($best_meal_plan['One Dish Meal Portion'] * 2) / 2 . "</p>";
                                                echo "<p>Calorie: $dinner_calories</p>";
                                                echo "<p>Carbohydrate: $dinner_carbohydrate g</p>";
                                                echo "<p>Protein: $dinner_protein g</p>";
                                                echo "<p>Fat: $dinner_fat g</p>";
                                                echo "</div>";
                                                echo "</div>";
                                                echo "</div>";

                                                // Toplam değerleri göster
                                                echo "<div class='total-values'>";
                                                echo "<h3>Total Values</h3>";
                                                echo "<p>Total Calories: $total_calories</p>";
                                                echo "<p>Total Carbohydrates: $total_carbohydrate g</p>";
                                                echo "<p>Total Protein: $total_protein g</p>";
                                                echo "<p>Total Fat: $total_fat g</p>";
                                                echo "</div>";
                                            }
                                            else {
                                                echo "<h1>Yemek verileri yetersiz!</h1>";
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            echo '<div class="col-xl-12 col-xxl-12">
        <div class="row">
            <div class="col-sm-6">
                <div class="card avtivity-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <span class="activity-icon" style="background-color: #FF4500; me-md-4 me-3">
                                <img src="images/Total_Calories.png" width="40" height="40" alt="Total Calories Icon">
                            </span>
                            <div class="media-body">
                                <p class="fs-14 mb-2">Total Calories</p>
                                <span class="title text-black font-w600">' . $total_calories . ' kcal</span>
                            </div>
                        </div>
                        <div class="progress" style="height:5px;">
                            <div class="progress-bar" style="background-color: #FF4500; width: 42%; height:5px;" aria-label="Progess-success" role="progressbar">
                                <span class="sr-only">42% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="effect" style="background-color: #FF4500;"></div>
                 </div>
            </div>
    
            <div class="col-sm-6">
                <div class="card avtivity-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <span class="activity-icon" style="background-color: #FFD700; me-md-4 me-3">
                                <img src="images/Total_Carbohydrates.png" width="40" height="37" alt="Total Carbohydrates Icon">
                            </span>
                            <div class="media-body">
                                <p class="fs-14 mb-2">Total Carbohydrates</p>
                                <span class="title text-black font-w600">' . $total_carbohydrate . ' g</span>
                            </div>
                        </div>
                        <div class="progress" style="height:5px;">
                            <div class="progress-bar" style="background-color: #FFD700; width: 82%; height:5px;" aria-label="Progess-secondary" role="progressbar">
                                <span class="sr-only">82% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="effect" style="background-color: #FFD700;"></div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card avtivity-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <span class="activity-icon" style="background-color: #1E90FF; me-md-4 me-3">
                                <img src="images/Total_Protein.png" width="40" height="39" alt="Total Protein Icon">
                            </span>
                            <div class="media-body">
                                <p class="fs-14 mb-2">Total Protein</p>
                                <span class="title text-black font-w600">' . $total_protein . ' g</span>
                            </div>
                        </div>
                        <div class="progress" style="height:5px;">
                            <div class="progress-bar" style="background-color: #1E90FF; width: 90%; height:5px;" aria-label="Progess-danger" role="progressbar">
                                <span class="sr-only">90% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="effect" style="background-color: #1E90FF;"></div>
                </div>
            </div>
        
            <div class="col-sm-6">
                <div class="card avtivity-card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <span class="activity-icon" style="background-color: #32CD32; me-md-4 me-3">
                                <img src="images/Total_Fat.png" width="40" height="40" alt="Total Fat Icon">
                            </span>
                            <div class="media-body">
                                <p class="fs-14 mb-2">Total Fat</p>
                                <span class="title text-black font-w600">' . $total_fat . ' g</span>
                            </div>
                        </div>
                        <div class="progress" style="height:5px;">
                            <div class="progress-bar" style="background-color: #32CD32; width: 42%; height:5px;" role="progressbar">
                                <span class="sr-only">42% Complete</span>
                            </div>
                        </div>
                    </div>
                    <div class="effect" style="background-color: #32CD32;"></div>
                </div>
            </div>

        </div>
    </div>';
                        }
                        ?>

                    </div>
                </div>

                <div class="col-xl-3 col-xxl-4">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card featuredMenu">
                                <div class="card-header border-0">
                                    <h4 class="text-black font-w600 fs-20 mb-0">Some Foods</h4>
                                </div>
                                <div class="card-body loadmore-content height700 dz-scroll pt-0" id="FeaturedMenusContent">
                                    <?php foreach ($random_meals as $meal): ?>
                                        <div class="media mb-4">
                                            <img src="<?php echo $meal['image']; ?>" width="85" alt="" class="rounded me-3">
                                            <div class="media-body">
                                                <h5><a href="food-menu.html" class="text-black fs-16"><?php echo $meal['name']; ?></a></h5>
                                                <span class="fs-14 text-primary font-w500"><?php echo $meal['category']; ?></span>
                                            </div>
                                        </div>
                                        <ul class="d-flex flex-wrap pb-2 border-bottom mb-3 justify-content-between">
                                            <li class="me-3 mb-2"><i class=""></i><span class="fs-14 text-black">Calories: <?php echo $meal['calories']; ?></span></li>
                                            <li class="me-3 mb-2"><i class=""></i><span class="fs-14 text-black">Carbs: <?php echo $meal['carbohydrate']; ?>g</span></li>
                                            <li class="me-3 mb-2"><i class=""></i><span class="fs-14 text-black">Protein: <?php echo $meal['protein']; ?>g</span></li>
                                            <li class="me-3 mb-2"><i class=""></i><span class="fs-14 text-black">Fat: <?php echo $meal['fat']; ?>g</span></li>
                                        </ul>
                                    <?php endforeach; ?>
                                </div>
                                <div class="card-footer style-1 text-center border-0 pt-0 pb-4">
                                    <a class="text-primary dz-load-more fa fa-chevron-down" aria-label="Featured-icon" id="FeaturedMenus" href="javascript:void(0);" rel="ajax/featured-menu-list.html">
                                    </a>
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
</div>
<!--**********************************
    Main wrapper end
***********************************-->

<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<script src="vendor/global/global.min.js"></script>
<script src="vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="vendor/chart-js/chart.bundle.min.js"></script>
<script src="vendor/owl-carousel/owl.carousel.js"></script>

<!-- Chart piety plugin files -->
<script src="vendor/peity/jquery.peity.min.js"></script>

<!-- Apex Chart -->
<script src="vendor/apexchart/apexchart.js"></script>

<!-- Dashboard 1 -->
<script src="js/dashboard/dashboard-1.js"></script>
<script src="js/custom.min.js"></script>
<script src="js/deznav-init.js"></script>
<script>

    function carouselReview(){
        /*  testimonial one function by = owl.carousel.js */
        jQuery('.testimonial-one').owlCarousel({
            nav:true,
            margin:30,
            responsive:{
                0:{
                    items:1
                },
                484:{
                    items:2
                },
                882:{
                    items:3
                },
                1200:{
                    items:3
                },

                1540:{
                    items:3
                },
                1740:{
                    items:3
                }
            }
        })
    }
    jQuery(window).on('load',function(){
        setTimeout(function(){
            carouselReview();
        }, 1000);
    });
</script>
</body>
</html>
