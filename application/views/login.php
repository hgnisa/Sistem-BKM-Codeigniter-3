<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sistem BKM - Login</title>
    <!-- Custom fonts for this template-->
    <link href="<?php print base_url();?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?php print base_url();?>css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .form-control {
            border-radius: 2rem;
            font-size: 0.8rem;
            height: 50px;
        }

        body {
            background-image: url("../img/bgkbp.jpg");
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-12 col-md-9">
                <div id="resultnopadding"></div>
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <img src="<?php print base_url();?>img/logokbp.jpeg" style="width: 130px; height: 100px; margin-bottom: 30px">
                                    </div>
                                    <form class="user" action="<?php print base_url(); ?>auth/process_login" method="post"  name="login" id="login" autocomplete="off">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control" name="type" required>
                                                <option value="" disabled selected>Login sebagai: </option>
                                                <option value="admin">Admin</option>
                                                <option value="mandor">Mandor</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary btn-user btn-block" value="Login">
                                        </div>
                                    </form>
                                    <?php
                                        $message = $this->session->flashdata('error-message');
                                        if (isset($message)) {
                                            print '<div class="alert alert-danger">'.$message.'</div>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="<?php print base_url();?>js/jquery.min.js"></script>
    <script src="<?php print base_url();?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php print base_url();?>js/jquery.easing.1.3.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php print base_url();?>js/sb-admin-2.min.js"></script>
</body>
</html>