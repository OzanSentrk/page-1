<?php
session_start();
ob_start();
require "ayar.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Varsayılan olarak bugünün tarihi
$selected_date = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['selected_date'])) {
        $selected_date = $_POST['selected_date'];
    }

    if (isset($_POST['meal_id'])) {
        $meal_id = $_POST['meal_id'];
        $meal_name = $_POST['meal_name'];
        $meal_date = $_POST['meal_date'];
        $meal_type = $_POST['meal_type'];
        $user_id = $_SESSION["user_id"];

        // Veritabanına ekleme sorgusu
        $sql = "INSERT INTO history (user_id, date, meal_name, food_id, calorie, protein, carb, fat, sugar, portion, meal_type, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $user_id, 
            $meal_date, 
            $meal_name, 
            $meal_id, 
            $_POST['calorie'], 
            $_POST['protein'], 
            $_POST['carb'], 
            $_POST['fat'], 
            $_POST['sugar'], 
            $_POST['portion'],
            $meal_type,
            $_POST['image']
        ]);

        if ($stmt) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
}

// Veritabanından verileri çek
$user_id = $_SESSION["user_id"];
$sql = "SELECT meal_name, calorie, protein, carb, fat, sugar, meal_type, image FROM history WHERE user_id = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id, $selected_date]);
$meals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Toplam değerleri hesapla
$total_calories = 0;
$total_protein = 0;
$total_carbs = 0;
$total_fat = 0;
$total_sugar = 0;

foreach ($meals as $meal) {
    $total_calories += $meal['calorie'];
    $total_protein += $meal['protein'];
    $total_carbs += $meal['carb'];
    $total_fat += $meal['fat'];
    $total_sugar += $meal['sugar'];
}

// Kullanıcının günlük hedef değerlerini çek
$sql = "SELECT daily_calories, protein_grams, carb_grams, fat_grams FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user_goals = $stmt->fetch(PDO::FETCH_ASSOC);

$daily_calories = round($user_goals['daily_calories']);
$protein_grams = round($user_goals['protein_grams']);
$carb_grams = round($user_goals['carb_grams']);
$fat_grams = round($user_goals['fat_grams']);

$calorie_percentage = ($total_calories / $user_goals['daily_calories']) * 100;
$protein_percentage = ($total_protein / $user_goals['protein_grams']) * 100;
$carb_percentage = ($total_carbs / $user_goals['carb_grams']) * 100;
$fat_percentage = ($total_fat / $user_goals['fat_grams']) * 100;
$sugar_percentage = ($total_sugar / 50) * 100; // Örnek olarak 50 gram şeker limiti
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
    <link href="vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="vendor/chartist/css/chartist.min.css">
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

    <?php include 'menu.php'; ?>
    <!--**********************************
        Sidebar end
    ***********************************-->
    <!-- Modal -->
    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="aAddDietMenus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add to History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="dietMenuForm" action="" method="POST">
                        <input type="hidden" id="mealId" name="meal_id">
                        <input type="hidden" id="mealName" name="meal_name">

                        <div class="mb-3">
                            <label for="mealDate" class="form-label">Meal Date</label>
                            <input type="date" class="form-control" id="mealDate" name="meal_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="mealType" class="form-label">Meal Type</label>
                            <select class="form-control" id="mealType" name="meal_type" required>
                                <option value="Breakfast">Breakfast</option>
                                <option value="Lunch">Lunch</option>
                                <option value="Dinner">Dinner</option>
                                <option value="Snack">Snack</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="portion" class="form-label">Portion</label>
                            <input type="number" step="0.1" class="form-control" id="portion" name="portion" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                                <div class="card-header d-block pb-0 border-0">
                                    <div class="d-sm-flex flex-wrap align-items-center d-block mb-md-3 mb-0">
                                        <div class="me-auto pe-3 mb-3">
                                            <h4 class="text-black fs-20">Create a Meal</h4>
                                            <p class="fs-13 mb-0">Save Your Daily Meal</p>
                                        </div>

  
                                    </div>
                                    <nav>
                                        <div class="nav nav-tabs diet-tabs" id="nav-tab" role="tablist">
                                            <?php
                                            $activeTab = isset($_GET['x']) ? $_GET['x'] : 'Recomended';
                                            ?>

                                            <a class="nav-link <?php echo ($activeTab == 'Recomended') ? 'active' : ''; ?>" id="nav-home-tab" href="index.php">Recomended for you</a>
                                            <a class="nav-link <?php echo ($activeTab == 'Breakfast') ? 'active' : ''; ?>" href="create.php?x=Breakfast">Breakfast</a>
                                            <a class="nav-link <?php echo ($activeTab == 'Lunch/Snacks') ? 'active' : ''; ?>" href="create.php?x=Lunch/Snacks">Lunch/Snacks</a>
                                            <a class="nav-link <?php echo ($activeTab == 'One Dish Meal') ? 'active' : ''; ?>" href="create.php?x=One Dish Meal">Dinner</a>
                                        </div>
                                    </nav>
                                    <div class="tab-content diet-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                                            <div class="card-body loadmore-content dz-scroll height750" id="DietMenusContent">
                                                <div class="table-responsive">
                                                    <table id="example3" class="display min-w850" style="width: 100%">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Meal</th>
                                                                <th>Calories</th>
                                                                <th>Fat</th>
                                                                <th>Carbohydrate</th>
                                                                <th>Sugar</th>
                                                                <th>Protein</th>
                                                                <th>Add</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            // Veritabanı bağlantısı ayar.php dosyasında
                                                            try {
                                                                $meal_type = ''; // Varsayılan boş

                                                                if (isset($_GET['x'])) {
                                                                    $meal_type = $_GET['x'];
                                                                }

                                                                if (in_array($meal_type, ['Breakfast', 'Lunch/Snacks', 'One Dish Meal'])) {
                                                                    $stmt = $conn->prepare("SELECT id, name, category, calories, cholesterol, carbohydrate, sugar, protein, image FROM meals_updated WHERE category = ?");
                                                                    $stmt->execute([$meal_type]);
                                                                } else {
                                                                    $stmt = $conn->prepare("SELECT id, name, category, calories, cholesterol, carbohydrate, sugar, protein, image FROM meals_updated");
                                                                    $stmt->execute();
                                                                }

                                                                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                            } catch (PDOException $e) {
                                                                echo "Veritabanı hatası: " . $e->getMessage();
                                                                die();
                                                            }
                                                            ?>

                                                            <?php
                                                            foreach ($results as $row) {
                                                                echo '<tr>
                                                                    <td><img class="" width="90" src="' . htmlspecialchars($row['image']) . '" alt=""></td>
                                                                    <td>' . htmlspecialchars($row['name']) . '</td>
                                                                    <td>' . htmlspecialchars($row['calories']) . '</td>
                                                                    <td>' . htmlspecialchars($row['cholesterol']) . '</td>
                                                                    <td>' . htmlspecialchars($row['carbohydrate']) . '</td>
                                                                    <td>' . htmlspecialchars($row['sugar']) . '</td>
                                                                    <td>' . htmlspecialchars($row['protein']) . '</td>
                                                                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#aAddDietMenus" class="plus-icon" data-id="' . $row['id'] . '" data-name="' . htmlspecialchars($row['name']) . '"><i class="las la-plus"></i></a></td>
                                                                </tr>';
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-xxl-4">
                    <div class="row">
                        <div class="col-xl-12 col-md-6">
                            <div class="card">
                                <div class="card-header border-0">
                                    <div class="me-auto pe-3">
                                        <form method="post">
                                            Select Day:
                                            <input type="date" class="form-control" name="selected_date" value="<?php echo htmlspecialchars($selected_date); ?>" onchange="this.form.submit()">
                                        </form>
                                        <h4 class="text-black fs-20">Today Meal Menu</h4>
                                        <p class="fs-13 mb-0">
                                             CALORIES: <?php echo $total_calories; ?>, 
                                             FAT: <?php echo $total_fat; ?>, 
                                             CARB: <?php echo $total_carbs; ?>, 
                                             SUGAR: <?php echo $total_sugar; ?>, 
                                             PROTEIN: <?php echo $total_protein; ?>
                                        </p>
                                        <div class="progress-container mt-3">
                                            <div class="progress-label">Calorie</div>
                                            <div class="progress">
                                                <div class="progress-bar bg-primary" style="width: <?php echo $calorie_percentage; ?>%;" role="progressbar" title="Alınan kalori: <?php echo $total_calories; ?>/<?php echo $user_goals['daily_calories']; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="progress-container mt-3">
                                            <div class="progress-label">Carbohydrate</div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" style="width: <?php echo $carb_percentage; ?>%;" role="progressbar" title="Alınan karbonhidrat: <?php echo $total_carbs; ?>/<?php echo $user_goals['carb_grams']; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="progress-container mt-3">
                                            <div class="progress-label">Protein</div>
                                            <div class="progress">
                                                <div class="progress-bar bg-info" style="width: <?php echo $protein_percentage; ?>%;" role="progressbar" title="Alınan protein: <?php echo $total_protein; ?>/<?php echo $user_goals['protein_grams']; ?>"></div>
                                            </div>
                                        </div>
                                        <div class="progress-container mt-3">
                                            <div class="progress-label">Fat</div>
                                            <div class="progress">
                                                <div class="progress-bar bg-danger" style="width: <?php echo $fat_percentage; ?>%;" role="progressbar" title="Alınan yağ: <?php echo $total_fat; ?>/<?php echo $user_goals['fat_grams']; ?>"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php foreach ($meals as $meal): ?>
                                <div class="card-body pb-3">
                                    <div class="media mb-3">
                                        <a href="#"><img src="<?php echo htmlspecialchars($meal['image']); ?>" alt="" class="rounded me-3" width="86"></a>
                                        <div class="media-body">
                                            <h6 class="fs-16 font-w500"><a href="#" class="text-black"><?php echo htmlspecialchars($meal['meal_name']); ?></a></h6>
                                            <p></p> <!-- Boş satır için -->
                                            <p></p> <!-- Boş satır için -->
                                            <span class="fs-14"><?php echo htmlspecialchars($meal['meal_type']); ?></span>
                                        </div>
                                    </div>
                                    <ul class="m-md-auto mt-2 pe-4">
                                        <li class="mb-2 text-nowrap">
                                            <span class="fs-14 text-black font-w500">
                                                <i class="scale1 me-1">CALORIE =</i><?php echo htmlspecialchars($meal['calorie']); ?>
                                            </span>
                                        </li>
                                        <li class="mb-2 text-nowrap">
                                            <span class="fs-14 text-black font-w500">
                                                <i class="scale1 me-3">FAT =</i><?php echo htmlspecialchars($meal['fat']); ?>
                                            </span>
                                        </li>
                                        <li class="mb-2 text-nowrap">
                                            <span class="fs-14 text-black font-w500">
                                                <i class="scale1 me-3">CARBOHYDRATE =</i><?php echo htmlspecialchars($meal['carb']); ?>
                                            </span>
                                        </li>
                                        <li class="mb-2 text-nowrap">
                                            <span class="fs-14 text-black font-w500">
                                                <i class="scale1 me-3">SUGAR =</i><?php echo htmlspecialchars($meal['sugar']); ?>
                                            </span>
                                        </li>
                                        <li class="mb-2 text-nowrap">
                                            <span class="fs-14 text-black font-w500">
                                                <i class="scale1 me-3">PROTEIN =</i><?php echo htmlspecialchars($meal['protein']); ?>
                                            </span>
                                        </li>

                                    </ul>
                                </div>
                                <?php endforeach; ?>
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

    <!-- Modal Box Start -->
    <!-- Modal Box End -->

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
<script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="js/plugins-init/datatables.init.js"></script>
<script src="vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="vendor/chart-js/chart.bundle.min.js"></script>
<script src="js/custom.min.js"></script>
<script src="js/deznav-init.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
$(document).ready(function() {
    // Her sayfa yüklendiğinde veya yenilendiğinde çalışacak olan kod
    function bindEvents() {
        // Mevcut olay dinleyicilerini kaldır
        $('.plus-icon').off('click');
        $('#dietMenuForm').off('submit');

        // Olay dinleyicilerini yeniden ekle
        $('.plus-icon').on('click', function() {
            var mealId = $(this).data('id');
            var mealName = $(this).data('name');
            $('#mealId').val(mealId);
            $('#mealName').val(mealName);
        });

        $('#dietMenuForm').on('submit', function(e) {
            e.preventDefault(); // Formun geleneksel gönderimini engelle
            $.ajax({
                url: 'save_to_history.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.trim() == 'success') {
                        // Başarılı mesaj göster
                        alert('Yemek Başarıyla Kaydedildi');
                        $('#aAddDietMenus').modal('hide'); // Modal'ı kapat
                    } else {
                        // Hata mesajını göster
                        var errorMessage = response.replace('error: ', '');
                        alert('Yemek Kaydedilirken Bir Hata Oluştu: ' + errorMessage);
                        console.error('Error response: ' + response); // Konsola hata mesajını yazdır
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Sunucuya bağlanırken bir hata oluştu: ' + textStatus);
                    console.error('AJAX error: ' + textStatus + ': ' + errorThrown); // Konsola AJAX hata mesajını yazdır
                }
            });
        });
    }

    // İlk sayfa yüklendiğinde olayları bağla
    bindEvents();

    // Sayfalama (pagination) olaylarını dinle ve her sayfa değişiminde olayları yeniden bağla
    $(document).on('click', '.paginate_button', function() {
        bindEvents();
    });
});
</script>

</body>
</html>
