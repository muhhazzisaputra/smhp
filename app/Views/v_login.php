<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMHP | Log in</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page" style="background-image: url('<?php echo base_url() ?>uploads/nigerian-satinwood.jpg'); background-size: cover;">
    <div class="login-box">
        <div class="login-logo">
            <a style="color: white;"><span class="fas fa-desktop"></span> <b>SMHP</b></a>
        </div>

        <div id="response"></div>

        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sistem Monitoring Hasil Produksi</p>
                <form id="loginForm">
                    <?php echo csrf_field(); ?>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="nip" placeholder="NIP" maxlength="5">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Log In</button>
                        </div>
                      <!-- /.col -->
                    </div>
                </form>
            </div>
        <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?php echo base_url() ?>admin-lte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url() ?>admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url() ?>admin-lte/dist/js/adminlte.min.js"></script>

    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url     : "<?php echo base_url('auth/process') ?>",
                headers : {'X-Requested-With': 'XMLHttpRequest'},
                type    : "POST",
                data    : $('#loginForm').serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        window.location.href = "/home"; // or wherever
                    } else {
                        $('#response').html('<p style="color:red;">' + response.message + '</p>');
                    }
                }
            });
        });
    </script>
</body>
</html>
