<?php 
    require('init.php');
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Transport - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/fonts.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container">

        <div class="ps-center">
            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Login!</h1>
                                        </div>
                                        <form class="user" id="create-login">
                                            <div class="form-group">
                                                <input type="text" class="form-control"
                                                    style="padding: 25px 10px; width: 100%;" name="Account_ID"
                                                    aria-describedby="emailHelp"
                                                    placeholder="Vui lòng nhập tài khoản...">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control"
                                                    style="padding: 25px 10px; width: 100%;" name="Password"
                                                    placeholder="Vui lòng nhập mật khẩu...">
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control" name="Company" style="width: 100%;">
                                                    <?php 
                                                        $query_company = "SELECT DISTINCT Company FROM  Transport_User";
                                                        $rs_company = odbc_exec($conn, $query_company);

                                                        while (odbc_fetch_row($rs_company)) {
                                                            $company = odbc_result($rs_company, "Company");
                                                            echo "<option value='$company'>$company</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- <a href="index.php" class="btn btn-primary btn-block"
                                                style="padding: 12px 10px;">
                                                Login
                                            </a> -->
                                            <button type="submit" class="btn btn-primary btn-block"
                                                style="padding: 12px 10px;">Login</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script src="vendor/validation/jquery.validate.min.js"></script>
    <script src="vendor/validation/jquery.validate-init.js"></script>

    <script src="vendor/sweetalert2/sweetalert2.all.min.js"></script>

    <script src="js/logins.js"></script>
</body>

</html>