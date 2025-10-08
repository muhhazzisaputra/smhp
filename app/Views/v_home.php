<?php echo $this->extend('v_template'); ?>

<?php echo $this->section('content'); ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 class="m-0">Selamat Datang di Sistem Monitoring Hasil Produksi, <?php echo $user->NamaKaryawan ?></h4>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<?php echo $this->endSection(); ?>