<?php echo $this->extend('v_template'); ?>

<?php echo $this->section('content'); ?>

<style>
    #loader {
	    position: fixed;
	    left: 0;
	    top: 0;
	    width: 100%;
	    height: 100%;
	    z-index: 9999;
	    background: rgba(255, 255, 255, 0.6);
	    display: none;
	}

	.spinner {
	    position: absolute;
	    top: 50%;
	    left: 50%;
	    width: 40px;
	    height: 40px;
	    margin: -20px 0 0 -20px;
	    border: 4px solid #ccc;
	    border-top: 4px solid #007bff;
	    border-radius: 50%;
	    animation: spin 1s linear infinite;
	}

	@keyframes spin {
	    0%   { transform: rotate(0deg); }
	    100% { transform: rotate(360deg); }
	}
</style>

<section class="content-header" style="padding-top: 1px;">
	<div class="container-fluid"></div>
</section>

<!-- Main content -->
<section class="content">
  	<div class="container-fluid">
        <div class="row">
        	<div class="col-md-12">
	          	<div class="card">
	          		<!-- Loader -->
					<div id="loader">
						<div class="spinner"></div>
					</div>
	              	<div class="card-body table-responsive">
	              		<form id="f_lap_produksi" method="post" action="<?php echo base_url() ?>laporan_hasil_produksi/view_data/xls" target="_blank" autocomplete="off">
		              		<table style="white-space: nowrap;">
		              			<tr>
		              				<td style="width: 125px;">Format Laporan</td>
		              				<td style="width: 13px;">:</td>
		              				<td style="width: 100px;">
		              					<select class="form-control" name="format" id="format" style="width: 200px;" onchange="pilih_format(this)">
		              						<option value="">-Pilih-</option>
		              						<option value="per_tgl">1. Produksi Per Tanggal</option>
		              						<option value="per_shift">2. Produksi Per Shift</option>
		              						<option value="per_mesin">3. Produksi Per Mesin</option>
		              						<option value="per_operator">4. Produksi Per Operator</option>
		              						<option value="tidak_mencapai_target">5. Produksi Tidak Mencapai Target</option>
		              					</select>
		              				</td>
		              			</tr>
			              	</table>
			            </form>
			            <div id="view_data" class="table-responsive" style="height: 34vh;"></div>
	              	</div>
	            </div>
	        </div>
	    </div>
	</div>
</section>

<script type="text/javascript">
	function pilih_format() {
		format = $('#format').val();

		$.post('<?php echo base_url() ?>laporan_hasil_produksi/pilih_format', {format}, function(data) {
			$('#f_lap_produksi').html(data);
			$('#view_data').empty();
		});
	}

	function view_data() {
		$('#loader').show();
		$.post('<?php echo base_url() ?>laporan_hasil_produksi/view_data', $('#f_lap_produksi').serialize(), function(data) {
		    $('#view_data').html(data);
		    $('#loader').fadeOut('slow');
		}).fail(function(data) {
			$('#loader').fadeOut('slow');
			error_notif('Terjadi suatu kesalahan. Hubungi Admin IT');
			return false;
		});
	}
</script>

<?php echo $this->endSection(); ?>