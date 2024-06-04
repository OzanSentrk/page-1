<?php
session_start();
ob_start();

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
<style>
    .row {
    display: flex;
    flex-wrap: nowrap;
}

.col {
    flex: 1;
    margin: 50px;
    box-sizing: border-box;
}

img {
    width: 100%;
    height: auto;
}

.progress-label {
    width: 100px;
    text-align: right;
    padding-right: 10px;
    display: inline-block;
    margin-bottom: 20px;
}

.progress-bar-container {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}
.featuredMenu {
    position: fixed;
    top: 20px;
    right: 20px;
    width: 300px; /* Genişlik ihtiyacınıza göre ayarlanabilir */
        }
</style>
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
        

        <div class="row">
            <div class="col-xl-6 col-xxl-12">
                <!-- Progress Bar 1 -->
                <div class="progress-bar-container">
                    <div class="progress-label">Kalori</div>
                    <div class="progress mb-4 w-100">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Progress Bar 2 -->
                <div class="progress-bar-container">
                    <div class="progress-label">Karbonhidrat</div>
                    <div class="progress mb-4 w-100">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Progress Bar 3 -->
                <div class="progress-bar-container">
                    <div class="progress-label">Protein</div>
                    <div class="progress mb-4 w-100">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Progress Bar 4 -->
                <div class="progress-bar-container">
                    <div class="progress-label">Yağ</div>
                    <div class="progress mb-4 w-100">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                <div class="col-xl-9 col-xxl-8">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header d-sm-flex d-block pb-0 border-0">
                                    <div class="me-auto pe-3">
                                        
<div class="container-fluid">
    
    <div id="pythonOutput">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $python_path = 'C:\Users\ozanu\AppData\Local\Programs\Python\Python312\python.exe';
            $script_path = 'C:\xampp\htdocs\calori\suggestion.py';
    
            $command = escapeshellcmd($python_path . ' ' . $script_path);
            $output = array();
            $return_var = null;

            exec($command . ' 2>&1', $output, $return_var);

            if ($return_var === 0) {
                $output_str = implode("\n", $output);
                $result = json_decode($output_str, true);

                if ($result) {
                    $meal_plan = $result['best_meal_plan'];
                    $fitness_score = $result['fitness_score'];

                    function round_portion($value) {
                        return round($value * 2) / 2;
                    }

                    function round_value($value) {
                        return round($value);
                    }

                    echo "<h1>Günlük Yemek Önerimiz</h1>";
                    
                    echo "<div class='row'>";
                    // Kahvaltı
                    echo "<div class='col'>";
                    echo "<h3>Kahvaltı :" . $meal_plan['Breakfast']['name'] . "</h3>";
                    echo "<img src='" . $meal_plan['Breakfast']['image'] . "' alt='" . $meal_plan['Breakfast']['name'] . "' style='width:200px;'><br>";
                    echo "<p>Porsiyon/Adet/Kaşık: " . round_portion(number_format($meal_plan['Breakfast Portion'], 2)) . "</p>";
                    echo "<p>Kalori: " . round_value($meal_plan['Breakfast']['calories'] * $meal_plan['Breakfast Portion']) . "</p>";
                    echo "<p>Karbonhidrat: " . round_value($meal_plan['Breakfast']['carbohydrate'] * $meal_plan['Breakfast Portion']) . " g</p>";
                    echo "<p>Protein: " . round_value($meal_plan['Breakfast']['protein'] * $meal_plan['Breakfast Portion']) . " g</p>";
                    echo "<p>Yağ: " . round_value($meal_plan['Breakfast']['fat'] * $meal_plan['Breakfast Portion']) . " g</p>";
                    echo "</div>";

                    // Öğle/Yemekler
                    echo "<div class='col'>";
                    echo "<h3>Öğle Yemeği : " . $meal_plan['Lunch/Snacks']['name'] . "</h3>";
                    echo "<img src='" . $meal_plan['Lunch/Snacks']['image'] . "' alt='" . $meal_plan['Lunch/Snacks']['name'] . "' style='width:200px;'><br>";
                    echo "<p>Porsiyon/Adet/Kaşık: " . round_portion(number_format($meal_plan['Lunch/Snacks Portion'], 2)) . "</p>";
                    echo "<p>Kalori: " . round_value($meal_plan['Lunch/Snacks']['calories'] * $meal_plan['Lunch/Snacks Portion']) . "</p>";
                    echo "<p>Karbonhidrat: " . round_value($meal_plan['Lunch/Snacks']['carbohydrate'] * $meal_plan['Lunch/Snacks Portion']) . " g</p>";
                    echo "<p>Protein: " . round_value($meal_plan['Lunch/Snacks']['protein'] * $meal_plan['Lunch/Snacks Portion']) . " g</p>";
                    echo "<p>Yağ: " . round_value($meal_plan['Lunch/Snacks']['fat'] * $meal_plan['Lunch/Snacks Portion']) . " g</p>";
                    echo "</div>";

                    // Ana Yemek
                    echo "<div class='col'>";
                    echo "<h3>Akşam Yemeği :" . $meal_plan['One Dish Meal']['name'] . "</h3>";
                    echo "<img src='" . $meal_plan['One Dish Meal']['image'] . "' alt='" . $meal_plan['One Dish Meal']['name'] . "' style='width:200px;'><br>";
                    echo "<p>Porsiyon/Adet/Kaşık: " . round_portion(number_format($meal_plan['One Dish Meal Portion'], 2)) . "</p>";
                    echo "<p>Kalori: " . round_value($meal_plan['One Dish Meal']['calories'] * $meal_plan['One Dish Meal Portion']) . "</p>";
                    echo "<p>Karbonhidrat: " . round_value($meal_plan['One Dish Meal']['carbohydrate'] * $meal_plan['One Dish Meal Portion']) . " g</p>";
                    echo "<p>Protein: " . round_value($meal_plan['One Dish Meal']['protein'] * $meal_plan['One Dish Meal Portion']) . " g</p>";
                    echo "<p>Yağ: " . round_value($meal_plan['One Dish Meal']['fat'] * $meal_plan['One Dish Meal Portion']) . " g</p>";
                    echo "</div>";

                    echo "</div>";
                    
                } else {
                    echo "Python komut dosyası çalıştırılamadı veya çıktı alınamadı.";
                    echo "Çıkış Mesajı: " . htmlentities(implode("<br>", $output));
                    echo "JSON Hatası: " . json_last_error_msg();
                }
            } else {
                echo "Hata: Python betiği çalıştırılamadı.<br>";
                echo "Çıkış Kodu: $return_var<br>";
                echo "Çıkış Mesajı: " . htmlentities(implode("<br>", $output));
            }
        }
        ?>
    </div>
    <form method="post" id="pythonForm">
        <button type="submit" class="btn btn-primary">Yeni Günlük Öğün Önerisi Al</button>
    </form>
</div>

                                        <p class="fs-13 mb-0"></p>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-xl-3 col-xxl-4">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card featuredMenu">
                                <div class="card-header border-0">
                                    <h4 class="text-black font-w600 fs-20 mb-0">Featured Diet Menu</h4>
                                </div>
                                <div class="card-body loadmore-content height700 dz-scroll pt-0" id="FeaturedMenusContent">
                                    <div class="media mb-4">
                                        <img src="images/menus/1.png" width="85" alt="" class="rounded me-3">
                                        <div class="media-body">
                                            <h5><a href="food-menu.html" class="text-black fs-16">Chinese Orange Fruit With Avocado Salad</a></h5>
                                            <span class="fs-14 text-primary font-w500">Kevin Ignis</span>
                                        </div>
                                    </div>
                                    <ul class="d-flex flex-wrap pb-2 border-bottom mb-3 justify-content-between">
                                        <li class="me-3 mb-2"><i class="las la-clock scale5 me-2"></i><span class="fs-14 text-black">4-6 mins </span></li>
                                        <li class="mb-2"><i class="fa-regular fa-star me-2 scale1 text-warning" aria-hidden="true"></i><span class="fs-14 text-black font-w500">176 Reviews</span></li>
                                    </ul>
                                    <div class="media mb-4">
                                        <img src="images/menus/2.png" width="85" alt="" class="rounded me-3">
                                        <div class="media-body">
                                            <h5><a href="food-menu.html" class="text-black fs-16">Fresh or Frozen (No Sugar Added) Fruits</a></h5>
                                            <span class="fs-14 text-primary font-w500">Olivia Johanson</span>
                                        </div>
                                    </div>
                                    <ul class="d-flex flex-wrap pb-2 border-bottom mb-3 justify-content-between">
                                        <li class="me-3 mb-2"><i class="las la-clock scale5 me-2"></i><span class="fs-14 text-black">4-6 mins </span></li>
                                        <li class="mb-2"><i class="fa-regular fa-star me-2 scale1 text-warning" aria-hidden="true"></i><span class="fs-14 text-black font-w500">176 Reviews</span></li>
                                    </ul>
                                    <div class="media mb-4">
                                        <img src="images/menus/3.png" width="85" alt="" class="rounded me-3">
                                        <div class="media-body">
                                            <h5><a href="food-menu.html" class="text-black fs-16">Fresh or Frozen (No Sugar Added) Fruits</a></h5>
                                            <span class="fs-14 text-primary font-w500">Stefanny Raharjo</span>
                                        </div>
                                    </div>
                                    <ul class="d-flex flex-wrap pb-2 border-bottom mb-3 justify-content-between">
                                        <li class="me-3 mb-2"><i class="las la-clock scale5 me-2"></i><span class="fs-14 text-black">4-6 mins </span></li>
                                        <li class="mb-2"><i class="fa-regular fa-star me-2 scale1 text-warning" aria-hidden="true"></i><span class="fs-14 text-black font-w500">176 Reviews</span></li>
                                    </ul>
                                    <div class="media mb-4">
                                        <img src="images/menus/4.png" width="85" alt="" class="rounded me-3">
                                        <div class="media-body">
                                            <h5><a href="food-menu.html" class="text-black fs-16">Original Boiled Egg with Himalaya Salt</a></h5>
                                            <span class="fs-14 text-primary font-w500">Peter Parkur</span>
                                        </div>
                                    </div>
                                    <ul class="d-flex flex-wrap pb-2 border-bottom mb-3 justify-content-between">
                                        <li class="me-3 mb-2"><i class="las la-clock scale5 me-2"></i><span class="fs-14 text-black">4-6 mins </span></li>
                                        <li class="mb-2"><i class="fa-regular fa-star me-2 scale1 text-warning" aria-hidden="true"></i><span class="fs-14 text-black font-w500">176 Reviews</span></li>
                                    </ul>
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
            loop:true,
            autoplay:true,
            margin:30,
            dots: false,
            left:true,
            navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>', '<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
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
                    items:2
                },

                1540:{
                    items:3
                },
                1740:{
                    items:4
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