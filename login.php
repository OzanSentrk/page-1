<?php session_start();ob_start(); ?>
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
                                <div class="text-center mb-3">
                                    <a ><img src="images/logo-full.png" alt=""></a>
                                </div>

                                <h4 class="text-center mb-4">Sign in your account</h4>
                                <?php

                                include "ayar.php";

                                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                    $email = $_POST['email'];
                                    $password = $_POST['password'];

                                    try {
                                        // Check if the email and password match a user in the database
                                        $sql = "SELECT * FROM user WHERE mail = :email AND password = :password";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bindParam(':email', $email);
                                        $stmt->bindParam(':password', $password);
                                        $stmt->execute();
                                        $user = $stmt->fetch();

                                        if ($user) {
                                            echo '<div class="alert alert-success solid alert-dismissible fade show">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 1 2h11"></path></svg>
                <strong>Success</strong> Login successful. Wait.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
                                            $_SESSION['user_id'] = $user['id'];
                                            $_SESSION['user_name'] = $user['name'];
                                            $_SESSION['user_mail'] = $user['mail'];
                                            header("Refresh: 1; url=index.php");
                                            exit();
                                        } else {
                                            echo '<div class="alert alert-danger solid alert-dismissible fade show">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="me-2"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 1 2h11"></path></svg>
                <strong>Error</strong> Invalid email or password.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';

                                        }

                                    } catch (PDOException $e) {
                                        $error_message = "Error: " . $e->getMessage();
                                    }
                                }
                                ?>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label class="mb-1 form-label"> Email</label>
                                        <input type="email" class="form-control" name="email" placeholder="hello@example.com">
                                    </div>
                                    <div class="mb-4 position-relative">
                                        <label class="mb-1 form-label">Password</label>
                                        <input type="password" id="dz-password" name="password" class="form-control">
                                        <span class="show-pass eye">

												<i class="fa fa-eye-slash"></i>
												<i class="fa fa-eye"></i>

											</span>
                                    </div>
                                    <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                        <div class="form-group">
                                            <div class="form-check custom-checkbox ms-1">
                                                <input type="checkbox" class="form-check-input" id="basic_checkbox_1">
                                                <label class="form-check-label" for="basic_checkbox_1">Beni Hatırla</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <!-- şifremiunuttum -->
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary light btn-block">Sign Me In</button>
                                    </div>
                                </form>
                                <div class="new-account mt-3">
                                    <p>Don't have an account? <a class="text-primary" href="register.php">Sign up</a></p>
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