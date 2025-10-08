<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Dashboard 3</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/plugins/fontawesome-free/css/all.min.css">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/dist/css/adminlte.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/plugins/izitoast/css/iziToast.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- DatePicker -->
    <link rel="stylesheet" href="<?php echo base_url() ?>admin-lte/dist/css/bootstrap-datepicker3.min.css">

    <style type="text/css">
        .iziToast-wrapper-topCenter {
            top: 70px !important; /* Adjust this based on your navbar height */
        }
    </style>

    <!-- jQuery -->
    <script src="<?php echo base_url() ?>admin-lte/plugins/jquery/jquery.min.js"></script>
</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->
<?php 
    $this->db = \Config\Database::connect();

    $router         = service('router');
    $fullController = $router->controllerName(); // \App\Controllers\Produk
    $controllerName = class_basename($fullController); // Produk
    $pathAlias      = $router->getMatchedRoute()[0];

    $idGroup = session()->get('id_group');
    $adminIT = ($idGroup==1) ? TRUE : FALSE;

    if(!$adminIT) {
        $role = $this->db->table('tb_akses_menu')->select('*')->where('IdGroup',$idGroup)->get()->getResultArray();

        $arr_role  = [];
        $arr_subid = [];
        if(count($role) > 0) {
            foreach($role as $rol) {
                array_push($arr_role, $rol['IdMenu']);
                array_push($arr_subid, $rol['IdSubMenu']);
            } 

        }
    }

    $menus = $this->db->table('tb_menu')->select('*')->orderBy('Urutan','ASC')->get()->getResultArray();
?>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="padding-right: 0.3rem; padding-left: 0.1rem; padding-top: 7px; font-size: 18px;"><?php echo $judul ?></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?php echo base_url() ?>home" class="brand-link">
                <img src="<?php echo base_url() ?>admin-lte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">SMHP</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
                       with font-awesome or any other icon font library -->
    <?php 
        if(count($menus) > 0) {
            foreach($menus as $menu) {
                if($adminIT) {
                    if($menu['IsParent'] == '1') { ?>
                        <li class="nav-item">
                            <a href="/<?php echo $menu['PathController'] ?>" class="nav-link <?php echo ($pathAlias==$menu['PathController']) ? 'active' : '' ?>">
                                <i class="nav-icon <?php echo $menu['Icon'] ?>"></i>
                                <p><?php echo $menu['NamaMenu'] ?></p>
                            </a>
                        </li>
                        <?php 
                    }
                } else {
                     if(in_array($menu['IdMenu'], $arr_role)) { ?>
                        <li class="nav-item">
                            <a href="/<?php echo $menu['PathController'] ?>" class="nav-link <?php echo ($pathAlias==$menu['PathController']) ? 'active' : '' ?>">
                                <i class="nav-icon <?php echo $menu['Icon'] ?>"></i>
                                <p><?php echo $menu['NamaMenu'] ?></p>
                            </a>
                        </li>
                        <?php
                    }
                }
            }
        }
    ?>              
                    <li class="nav-item">
                        <a href="/logout" class="nav-link">
                          <i class="nav-icon fas fa-sign-out-alt"></i>
                          <p>Log out</p>
                        </a>
                    </li>
                </ul>
              </nav>
              <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php echo $this->renderSection('content') ?>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>All rights reserved.
            <div class="float-right d-none d-sm-inline-block"><b>Version</b> 3.1.0</div>
        </footer>

        <!-- Modal small -->
        <div class="modal fade" id="modal-sm">
            <div class="modal-dialog modal-sm">
                <div class="modal-content" id="modal_body_sm">
                    
                </div>
            </div>
        </div>

        <!-- Modal md -->
        <div class="modal fade" id="modal-md">
            <div class="modal-dialog modal-md">
                <div class="modal-content" id="modal_body_md">
                    
                </div>
            </div>
        </div>

        <!-- Modal lg -->
        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="modal_body_lg">
                    
                </div>
            </div>
        </div>

        <!-- Modal xl -->
        <div class="modal fade" id="modal-xl">
            <div class="modal-dialog modal-xl">
                <div class="modal-content" id="modal_body_xl">
                    
                </div>
            </div>
        </div>

        <!-- Modal Image -->
        <div class="modal fade" id="modal-img">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Preview Gambar</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal_body_img">
                        <img src="" alt="Perview Gambar" id="set_img" style="height: 230px; width: 240px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./wrapper -->

    <!-- Bootstrap -->
    <script src="<?php echo base_url() ?>admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE -->
    <script src="<?php echo base_url() ?>admin-lte/dist/js/adminlte.js"></script>
    <!-- Toastr -->
    <script src="<?php echo base_url() ?>admin-lte/plugins/izitoast/js/iziToast.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="<?php echo base_url() ?>admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url() ?>admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo base_url() ?>admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url() ?>admin-lte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url() ?>admin-lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo base_url() ?>admin-lte/plugins/jszip/jszip.min.js"></script>
    <script src="<?php echo base_url() ?>admin-lte/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?php echo base_url() ?>admin-lte/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?php echo base_url() ?>admin-lte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url() ?>admin-lte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url() ?>admin-lte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- Select2 -->
    <script src="<?php echo base_url() ?>admin-lte/plugins/select2/js/select2.full.min.js"></script>
    <!-- DatePicker -->
    <script src="<?php echo base_url() ?>admin-lte/dist/js/bootstrap-datepicker.min.js"></script>

    <script type="text/javascript">
        $(function() {
            applyNumberOnly('.numeric-only');
        });

        function close_modal_sm() {
            $('#modal-sm').modal('hide');
        }

        function close_modal_md() {
            $('#modal-md').modal('hide');
        }

        function close_modal_lg() {
            $('#modal-lg').modal('hide');
        }

        function close_modal_xl() {
            $('#modal-xl').modal('hide');
        }

        function success_notif(pesan) {
            iziToast.success({
                title   : 'Success!',
                message : pesan,
                position: 'topRight'
            });
        }

        function error_notif(pesan) {
            iziToast.error({
                title   : 'Error!',
                message : pesan,
                position: 'topRight'
            });
        }

        function warning_notif(pesan) {
            iziToast.warning({
                title   : 'Warning!',
                message : pesan,
                position: 'topRight'
            });
        }

        function info_notif(pesan) {
            iziToast.info({
                title   : 'Info!',
                message : pesan,
                position: 'topRight'
            });
        }

        function confirmDelete(id, tableId, url) {
            iziToast.question({
                timeout    : 20000,
                close      : false,
                overlay    : true,
                displayMode: 'once',
                id         : 'question',
                zindex     : 999,
                title      : '',
                message    : 'Yakin ingin hapus data ?',
                position   : 'topCenter',
                buttons: [
                  ['<button><b>YES</b></button>', function (instance, toast) {
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');

                        // Call your delete function here
                        $.post(url, {id}, function(result) {
                            if(result.status=="success") {
                                $(tableId).DataTable().ajax.reload();
                                success_notif('Data berhasil dihapus');
                            }
                        }, 'json').fail(function() {
                            error_notif('Data gagal dihapus. '+result.message);
                        });
                    }, true],
                    ['<button>Cancel</button>', function (instance, toast) {
                        instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
                    }]
                ]
            });
        }

        function applyNumberOnly(selector) {
            $(document).on('keyup', selector, function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

        function num_only(data) {
            var isi   = data.value;
            var isi2  = $(this);
            let hasil = format_number(isi);
            $(data).val(hasil);
        }

        /* Fungsi formatRupiah */
        function formatRupiah(angka) {
            let str = angka.toString();
            var number_string = str.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return rupiah;
        }

        function format_number(number, prefix, thousand_separator, decimal_separator) {
            var thousand_separator = thousand_separator || ',',
                decimal_separator  = decimal_separator || '.',
                regex              = new RegExp('[^' + decimal_separator + '\\d]', 'g'),
                number_string      = number.replace(regex, '').toString(),
                split              = number_string.split(decimal_separator),
                rest               = split[0].length % 3,
                result             = split[0].substr(0, rest),
                thousands          = split[0].substr(rest).match(/\d{3}/g);

            if (thousands) {
                separator  = rest ? thousand_separator : '';
                result    += separator + thousands.join(thousand_separator);
            }
            result = split[1] != undefined ? result + decimal_separator + split[1] : result;
            return prefix == undefined ? result : (result ?  result  + prefix: '');
        }

        function format_number2(number,decimal=0) {
            num         = parseFloat(number)
            decimal_set = parseInt(decimal)
            var p = num.toFixed(decimal_set).split(".");
            if(decimal_set>=1){
                return "" + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
                    return num + (num != "-" && i && !(i % 3) ? "," : "") + acc;
                }, "") + "." + p[1];
            } else {
                return "" + p[0].split("").reverse().reduce(function(acc, num, i, orig) {
                    return num + (num != "-" && i && !(i % 3) ? "," : "") + acc;
                }, "")
            }
        }

        function replace_all_space(value=''){
            value_set = value.replace(/\s/g,'');
            return value_set
        }

        function replace_all_coma(value=''){
            value_set = value.replace(/,/g,"")
            return value_set
        }
    </script>
</body>
</html>
