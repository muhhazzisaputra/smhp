<?php echo $this->extend('v_template'); ?>

<?php echo $this->section('content'); ?>
<section class="content-header" style="padding-top: 1px;">
  <div class="container-fluid"></div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clipboard-check"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Hasil(Kg)</span>
                <span class="info-box-number"><?php echo number_format($total_hasil,2) ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-trash-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Waste(Kg)</span>
                <span class="info-box-number"><?php echo number_format($total_waste,2) ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-archive"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Produk</span>
                <span class="info-box-number"><?php echo $total_produk ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Operator</span>
                <span class="info-box-number"><?php echo $total_operator ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <div class="row">
            <!-- BAR CHART -->
            <div class="col-12 col-sm-12 col-md-6">
                <div class="card card-sdefault">
                  <div class="card-header">
                    <h3 class="card-title">Grafik Qty Hasil <?php echo $tahun ?></h3>
                  </div>
                  <div class="card-body">
                    <div class="chart">
                      <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
            </div>
            <!-- BAR CHART -->
            <div class="col-12 col-sm-12 col-md-6">
                <div class="card card-default">
                  <div class="card-header">
                    <h3 class="card-title">Grafik Qty Waste <?php echo $tahun ?></h3>
                  </div>
                  <div class="card-body">
                    <div class="chart2">
                      <canvas id="barChart2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ChartJS -->
<script src="<?php echo base_url() ?>admin-lte/plugins/chart.js/Chart.min.js"></script>
<script>
    //-------------
    //- Qty Hasil -
    //-------------
    var areaChartData = {
      labels  : [<?php echo $bulan_produksi ?>],
      datasets: [
        {
          label               : 'Qty Hasil',
          backgroundColor     : '#28a745',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#28a745',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [<?php echo $hasil_produksi ?>]
        }
      ]
    }

    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp1 = areaChartData.datasets[0]
    barChartData.datasets[0] = temp1

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type   : 'bar',
      data   : barChartData,
      options: barChartOptions
    })

    //-------------
    //- Qty Waste -
    //-------------
    var areaChartData2 = {
      labels  : [<?php echo $bulan_produksi ?>],
      datasets: [
        {
          label               : 'Qty Waste',
          backgroundColor     : '#dc3545',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#dc3545',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [<?php echo $waste_produksi ?>]
        }
      ]
    }

    var barChartCanvas2       = $('#barChart2').get(0).getContext('2d')
    var barChartData2         = $.extend(true, {}, areaChartData2)
    var temp2                 = areaChartData2.datasets[0]
    barChartData2.datasets[0] = temp2

    var barChartOptions2 = {
      responsive              : true,
      maintainAspectRatio     : true,
      datasetFill             : true
    }

    new Chart(barChartCanvas2, {
      type   : 'bar',
      data   : barChartData2,
      options: barChartOptions2
    })
</script>
<?php echo $this->endSection(); ?>