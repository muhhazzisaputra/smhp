<?php echo $this->extend('v_template'); ?>

<?php echo $this->section('content'); ?>
<section class="content-header" style="padding-top: 1px;">
	<div class="container-fluid"></div>
</section>

<!-- Main content -->
<section class="content">
  	<div class="container-fluid">
        <div class="row">
        	<div class="col-md-12">
	          	<div class="card">
	              	<div class="card-body table-responsive">
	              		<table id="speedtempTable" class="table table-sm table-bordered table-hover text-nowrap">
						    <thead>
						        <tr>
						            <th rowspan="2" style="vertical-align: top;">#</th>
						            <th rowspan="2" style="vertical-align: top;">Id Produksi</th>
						            <th rowspan="2" style="vertical-align: top;">Tgl Produksi</th>
						            <th rowspan="2" style="vertical-align: top;">Shift</th>
						            <th rowspan="2" style="vertical-align: top;">No Mesin</th>
						            <th rowspan="2" style="vertical-align: top;">Operator</th>
						            <th rowspan="2" style="vertical-align: top;">Produk</th>
						            <th colspan="2" style="text-align: center;">Jam Setting</th>
						            <th rowspan="2" style="vertical-align: top;">Speed</th>
						            <th rowspan="2" style="vertical-align: top;">Temperatur</th>
						            <th rowspan="2" style="vertical-align: top;">Kategori NG</th>
						            <th rowspan="2" style="vertical-align: top;">Qty NG</th>
						            <th rowspan="2" style="vertical-align: top;">Katerangan Komplain QC</th>
						        </tr>
						        <tr>
						        	<th>Dari</th>
						        	<th>Ke</th>
						        </tr>
						    </thead>
						</table>
	              	</div>
            	</div>
            </div>
        </div>
  	</div>
</section>

<!-- Modal data filter -->
<div class="modal fade" id="modal-filter" tabindex="" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      	<div class="modal-content">
            <div class="modal-body" id="modal_body_filter">
              	<form method="post" action="/speed_temperatur/export_xls" target="_blank">
				 	<?php echo csrf_field(); ?>
					<div class="table-responsive">
						<table class="table table-sm table-borderless text-nowrap">
	              			<tr>
	              				<td style="width: 90px;">Id Produksi</td>
	              				<td style="width: 13px;">:</td>
	              				<td style="width: 127px;"><input type="text" class="form-control form-control-sm" name="id_produksi_src" id="id_produksi_src" style="width: 117px; text-transform: uppercase;"></td>
	              				<td style="width: 64px;">No Mesin</td>
	              				<td style="width: 13px;">:</td>
	              				<td style="width: 118px;">
	              					<select class="form-control form-control-sm" name="no_mesin_src" id="no_mesin_src" style="width: 108px;">
			                            <<option value="">-Pilih-</option>
	                                    <?php foreach($mesin as $ms) { ?>
	                                        <option value="<?php echo $ms->IdMesin ?>"><?php echo $ms->IdMesin.' - '.$ms->NoMesin ?></option>
	                                    <?php } ?>
			                        </select>
	              				</td>
	              				<td style="width: 42px;">Shift</td>
	              				<td style="width: 13px;">:</td>
	              				<td style="width: 77px;">
	              					<select class="form-control form-control-sm" name="shift_src" id="shift_src" style="width: 95px;">
			                            <option value="">-Pilih-</option>
			                            <option value="1">1</option>
			                            <option value="2">2</option>
			                            <option value="3">3</option>
			                        </select>
	              				</td>
	              				<td style="width: 64px;">Operator</td>
	              				<td style="width: 13px;">:</td>
	              				<td style="width: 290px;">
	              					<select class="form-control form-control-sm select2bs4" name="operator_src" id="operator_src" style="width: 275px;">
			                            <option value="">-Pilih-</option>
	                                    <?php foreach($operator as $opt) { ?>
	                                        <option value="<?php echo $opt->IdKaryawan ?>"><?php echo $opt->IdKaryawan.' - '.$opt->NamaKaryawan ?></option>
	                                    <?php } ?>
			                        </select>
	              				</td>
	              			</tr>
	              			<tr>
	              				<td>Produk</td>
	              				<td>:</td>
	              				<td><input type="text" class="form-control form-control-sm" name="produk_src" id="produk_src" style="text-transform: uppercase;"></td>
	              				<td>Qty NG</td>
	              				<td>:</td>
	              				<td>
	              					<select class="form-control form-control-sm" name="lebih_atau_sama" id="lebih_atau_sama" style="width: 52px; display: inline;">
	              						<option value="lebih_sama"> >= </option>
	              						<option value="kurang_sama"> <= </option>
	              					</select>
	              					<input type="text" class="form-control form-control-sm" name="qty_ng_src" id="qty_ng_src" value="0" style="width: 52px; display: inline; text-align: right;" onkeyup="num_only(this)">
	              				</td>
	              				<td>Kategori NG</td>
	              				<td>:</td>
	              				<td>
	              					<select class="form-control form-control-sm" name="id_kategori_ng" id="id_kategori_ng">
	              						<option value="">-Pilih-</option>
	              						<?php foreach($kategori_ng as $kng) { ?>
	                                        <option value="<?php echo $kng->IdKategori ?>"><?php echo $kng->NamaKategori ?></option>
	                                    <?php } ?>
	              					</select>
	              				</td>
	              				<td colspan="3">
	              					Tgl Produksi &nbsp;&nbsp;: 
	              					&nbsp;&nbsp;<input type="text" name="tgl_src" id="tgl_src" value="<?php echo date('Y-m-01') ?>" class="form-control form-control-sm dateclass" style="width: 107px; background-color: white; display: inline;" readonly> -
	              					<input type="text" name="tgl2_src" id="tgl2_src" value="<?php echo date('Y-m-d') ?>" class="form-control form-control-sm dateclass" style="width: 107px; background-color: white; display: inline;" readonly>
	              				</td>
	              				<td>
	              					<button type="button" class="btn btn-sm btn-primary" id="searchBtn" title="Cari"><i class="fas fa-search"></i></button>
	              					<button type="submit" class="btn btn-sm btn-success" title="Export Xls"><i class="fas fa-file-excel"></i></button>
	              					<button type="button" class="btn btn-sm btn-danger" title="Tutup" onclick="close_modal_filter(this)"><i class="fas fa-times"></i></button>
	              				</td>
	              			</tr>
	              		</table>
					</div>
				</form>
            </div>
      	</div>
    </div>
</div>


<script type="text/javascript">
	$(document).ready(function () {
	    let table = $('#speedtempTable').DataTable({
	        processing: true,
	        serverSide: true,
	        ordering  : false,
	        searching : false,
	        ajax: {
	            url: "<?php echo site_url('speed_temperatur/datatables') ?>",
	            type: "POST",
	            data: function (d) {
	                d.id_produksi_src = $('#id_produksi_src').val(); // custom filter
	                d.no_mesin_src    = $('#no_mesin_src').val();
	                d.shift_src       = $('#shift_src').val();
	                d.shift_src       = $('#shift_src').val();
	                d.operator_src    = $('#operator_src').val();
	                d.produk_src      = $('#produk_src').val();
	                d.id_kategori_ng  = $('#id_kategori_ng').val();
	                d.lebih_atau_sama = $('#lebih_atau_sama').val();
	                d.qty_ng_src      = $('#qty_ng_src').val();
	                d.tgl_src         = $('#tgl_src').val();
	                d.tgl2_src        = $('#tgl2_src').val();
	            }
	        },
	        columns: [
	            {
	                data: null,
	                render: function (data, type, row, meta) {
	                    return meta.row + meta.settings._iDisplayStart + 1;
	                }
	            },
	            { data: 'IdProduksi' },
	            { data: 'TglProduksi' },
	            { data: 'Shift' },
	            { data: 'NoMesin' },
	            { data: 'NamaOperator' },
	            { data: 'NamaProduk' },
	            { data: 'Jam' },
	            { data: 'Jam2' },
	            { data: 'Speed' },
	            { data: 'Temperatur' },
	            { data: 'NamaKategori' },
	            { data: 'QtyNG' },
	            { data: 'Keterangan' }
	        ]
	    });

	    $('#searchBtn').on('click', function () {
	    	$('#modal-filter').modal('hide');
	        table.ajax.reload();
	    });

	    $('#speedtempTable_length').append(`<button type="button" style="margin-left: 17px;" class="btn btn-sm bg-purple" data-toggle="modal" data-target="#modal-filter"><i class="fas fa-filter"></i> Filter Data</button>`);

	    $('.select2bs4').select2({
          	theme: 'bootstrap4'
        });

        $('.dateclass').datepicker({
	        autoclose     : true,
	        format        : 'yyyy-mm-dd',
	        changeMonth   : true,
	        changeYear    : true,
	        todayHighlight: true,
	        toggleActive  : true,
	    });

	});

	function add() {
		$.post('<?php echo base_url() ?>hasil_produksi/form_data', {jenis: "input"}, function(data) {
			$('#modal_body_xl').html(data);
			$('#modal-xl').modal('show');
		});
	}

	function edit(id_produksi) {
		$.post('<?php echo base_url() ?>hasil_produksi/form_data', {jenis: "edit", id_produksi}, function(data) {
			$('#modal_body_xl').html(data);
			$('#modal-xl').modal('show');
		});
	}

	function close_modal_filter() {
		$('#modal-filter').modal('hide');
	}

	function num_only(data) {
        var isi = data.value;
        let qty = format_number(isi);
        $(data).val(qty);
    }
</script>

<?php echo $this->endSection(); ?>