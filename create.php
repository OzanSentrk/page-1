<?php
session_start();
ob_start();
require "ayar.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meal_id = $_POST['meal_id'];
    $meal_date = $_POST['meal_date'];
    $meal_type = $_POST['meal_type'];
    $user_id = $_SESSION["user_id"];

    // Veritabanına ekleme sorgusu
    $sql = "INSERT INTO diet_menus (user_id, meal_id, meal_name, meal_date, meal_type) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $meal_id, $meal_name, $meal_date, $meal_type]);

    if ($stmt) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?><!DOCTYPE html>
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
                                            <p class="fs-13 mb-0">Lorem ipsum dolor sit amet, consectetur</p>
                                        </div>

                                        <a href="javascript:void(0);" class="btn rounded  btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <svg class="me-2" width="25" height="24" viewbox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3.31615 6H14.4744C14.4744 6.53043 14.6882 7.03914 15.0686 7.41421C15.4491 7.78929 15.9651 8 16.5032 8H18.532C19.07 8 19.5861 7.78929 19.9665 7.41421C20.347 7.03914 20.5607 6.53043 20.5607 6H21.5751C21.8442 6 22.1022 5.89464 22.2924 5.70711C22.4827 5.51957 22.5895 5.26522 22.5895 5C22.5895 4.73478 22.4827 4.48043 22.2924 4.29289C22.1022 4.10536 21.8442 4 21.5751 4H20.5607C20.5607 3.46957 20.347 2.96086 19.9665 2.58579C19.5861 2.21071 19.07 2 18.532 2H16.5032C15.9651 2 15.4491 2.21071 15.0686 2.58579C14.6882 2.96086 14.4744 3.46957 14.4744 4H3.31615C3.04711 4 2.7891 4.10536 2.59887 4.29289C2.40863 4.48043 2.30176 4.73478 2.30176 5C2.30176 5.26522 2.40863 5.51957 2.59887 5.70711C2.7891 5.89464 3.04711 6 3.31615 6ZM16.5032 4H18.532V5V6H16.5032V4ZM21.5751 11H12.4456C12.4456 10.4696 12.2319 9.96086 11.8514 9.58579C11.471 9.21071 10.9549 9 10.4169 9H8.38809C7.85002 9 7.334 9.21071 6.95353 9.58579C6.57306 9.96086 6.35931 10.4696 6.35931 11H3.31615C3.04711 11 2.7891 11.1054 2.59887 11.2929C2.40863 11.4804 2.30176 11.7348 2.30176 12C2.30176 12.2652 2.40863 12.5196 2.59887 12.7071C2.7891 12.8946 3.04711 13 3.31615 13H6.35931C6.35931 13.5304 6.57306 14.0391 6.95353 14.4142C7.334 14.7893 7.85002 15 8.38809 15H10.4169C10.9549 15 11.471 14.7893 11.8514 14.4142C12.2319 14.0391 12.4456 13.5304 12.4456 13H21.5751C21.8442 13 22.1022 12.8946 22.2924 12.7071C22.4827 12.5196 22.5895 12.2652 22.5895 12C22.5895 11.7348 22.4827 11.4804 22.2924 11.2929C22.1022 11.1054 21.8442 11 21.5751 11ZM8.38809 13V11H10.4169V12V13H8.38809ZM21.5751 18H18.532C18.532 17.4696 18.3182 16.9609 17.9378 16.5858C17.5573 16.2107 17.0413 16 16.5032 16H14.4744C13.9364 16 13.4203 16.2107 13.0399 16.5858C12.6594 16.9609 12.4456 17.4696 12.4456 18H3.31615C3.04711 18 2.7891 18.1054 2.59887 18.2929C2.40863 18.4804 2.30176 18.7348 2.30176 19C2.30176 19.2652 2.40863 19.5196 2.59887 19.7071C2.7891 19.8946 3.04711 20 3.31615 20H12.4456C12.4456 20.5304 12.6594 21.0391 13.0399 21.4142C13.4203 21.7893 13.9364 22 14.4744 22H16.5032C17.0413 22 17.5573 21.7893 17.9378 21.4142C18.3182 21.0391 18.532 20.5304 18.532 20H21.5751C21.8442 20 22.1022 19.8946 22.2924 19.7071C22.4827 19.5196 22.5895 19.2652 22.5895 19C22.5895 18.7348 22.4827 18.4804 22.2924 18.2929C22.1022 18.1054 21.8442 18 21.5751 18ZM14.4744 20V18H16.5032V19V20H14.4744Z" fill="#fff"></path>
                                            </svg>Filter
                                        </a>
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
                                            <input type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">


                                        </form>
                                        <h4 class="text-black fs-20">Today Meal Menu</h4>
                                        <p class="fs-13 mb-0">TOPLAM KALORİ,FAT,CARB,SUGAR VE PROTEİN DEĞERİ GELİCEK GÜNLÜK</p>
                                    </div>
                                </div>
                                //OGUN
                                <div class="card-body pb-3">
                                    <div class="media mb-3">
                                        <a href="ecom-product-detail.html"><img src="images/menus/8.png" alt="" class="rounded me-3" width="86"></a>
                                        <div class="media-body">
                                            <h6 class="fs-16 font-w500"><a href="ecom-product-detail.html" class="text-black">yemek adı</a></h6>
                                            <span class="fs-14">öğün / kategori</span>
                                        </div>
                                    </div>
                                    <ul class="m-md-auto mt-2 pe-4">
                                        <li class="mb-2 text-nowrap"><i class=" scale1 me-1">CAL</i><span class="fs-14 text-black  font-w500">Calori  </span></li>
                                        <li class="mb-2 text-nowrap"><i class=" scale1 me-3">FAT</i><span class="fs-14 text-black  font-w500">fat</span></li>
                                        <li class="mb-2 text-nowrap"><i class=" scale1 me-3">CARB</i><span class="fs-14 text-black  font-w500">carbonhidrat</span></li>
                                        <li class="mb-2 text-nowrap"><i class=" scale1 me-3">SUG</i><span class="fs-14 text-black  font-w500">sugar</span></li>
                                        <li class="mb-2 text-nowrap"><i class=" scale1 me-3">PRO</i><span class="fs-14 text-black  font-w500">protein</span></li>
                                   </ul>
                                </div>
                                //OGUN
                                <div class="card-body pb-3">
                                    <div class="media mb-3">
                                        <a href="ecom-product-detail.html"><img src="images/menus/8.png" alt="" class="rounded me-3" width="86"></a>
                                        <div class="media-body">
                                            <h6 class="fs-16 font-w500"><a href="ecom-product-detail.html" class="text-black">yemek adı</a></h6>
                                            <span class="fs-14">öğün / kategori</span>
                                        </div>
                                    </div>
                                    <ul class="m-md-auto mt-2 pe-4">
                                        <li class="mb-2 text-nowrap"><i class=" scale1 me-1">CAL</i><span class="fs-14 text-black  font-w500">Calori  </span></li>
                                        <li class="mb-2 text-nowrap"><i class=" scale1 me-3">FAT</i><span class="fs-14 text-black  font-w500">fat</span></li>
                                        <li class="mb-2 text-nowrap"><i class=" scale1 me-3">CARB</i><span class="fs-14 text-black  font-w500">carbonhidrat</span></li>
                                        <li class="mb-2 text-nowrap"><i class=" scale1 me-3">SUG</i><span class="fs-14 text-black  font-w500">sugar</span></li>
                                        <li class="mb-2 text-nowrap"><i class=" scale1 me-3">PRO</i><span class="fs-14 text-black  font-w500">protein</span></li>
                                    </ul>
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

    <!-- Modal Box Strat -->
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
});
</script>






</body>
</html>