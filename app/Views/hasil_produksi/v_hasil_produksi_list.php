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
	              	<div class="card-header">
                		<!-- <a href="/bank/trash" class="btn btn-sm btn-warning"><i class="fa fa-trash"></i> Trash</a> -->
		              	<button class="btn btn-sm btn-success" onclick="add()"><i class="fas fa-plus"></i> Input Data</button>
	              	</div>
	              	<div class="card-body table-responsive">
	              		<table id="produksiTable" class="table table-sm table-bordered table-hover text-nowrap">
						    <thead>
						        <tr>
						            <th>#</th>
						            <th>Opsi</th>
						            <th>Id Produksi</th>
						            <th>Tgl Produksi</th>
						            <th>Shift</th>
						            <th>No Mesin</th>
						            <th>Operator</th>
						            <th>Produk</th>
						            <th>Qty Hasil</th>
						            <th>Qty Waste</th>
						            <th>User Input</th>
						            <th>Tanggal Input</th>
						            <th>User Edit</th>
						            <th>Tanggal Edit</th>
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
              	<form method="post" action="/hasil_produksi/export_xls" target="_blank">
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
	              					<select class="form-control form-control-sm" name="shift_src" id="shift_src" style="width: 68px;">
			                            <option value="">-Pilih-</option>
			                            <option value="1">1</option>
			                            <option value="2">2</option>
			                            <option value="3">3</option>
			                        </select>
	              				</td>
	              				<td style="width: 64px;">Operator</td>
	              				<td style="width: 13px;">:</td>
	              				<td style="width: 290px;">
	              					<select class="form-control form-control-sm" name="operator_src" id="operator_src" style="width: 280px;">
			                            <option value="">-Pilih-</option>
	                                    <?php foreach($operator as $opt) { ?>
	                                        <option value="<?php echo $opt->IdKaryawan ?>"><?php echo $opt->IdKaryawan.' - '.$opt->NamaKaryawan ?></option>
	                                    <?php } ?>
			                        </select>
	              				</td>
	              				<td>
	              					<button type="button" class="btn btn-sm btn-primary" id="searchBtn" title="Cari"><i class="fas fa-search"></i></button>
	              					<button type="submit" class="btn btn-sm btn-success" title="Export Xls"><i class="fas fa-file-excel"></i></button>
	              					<button type="button" class="btn btn-sm btn-danger" title="Tutup" onclick="close_modal_filter(this)"><i class="fas fa-times"></i></button>
	              				</td>
	              			</tr>
	              			<tr>
	              				<td>Produk</td>
	              				<td>:</td>
	              				<td><input type="text" class="form-control form-control-sm" name="produk_src" id="produk_src" style="text-transform: uppercase;"></td>
	              				<td>Qty Hasil</td>
	              				<td>:</td>
	              				<td>
	              					<select class="form-control form-control-sm" name="qty_hasil_src" id="qty_hasil_src">
	              						<option value="">-Pilih-</option>
	              						<option value="lebih">>= 30</option>
	              						<option value="kurang">< 30</option>
	              					</select>
	              				</td>
	              				<td colspan="6">
	              					Tgl Produksi &nbsp;&nbsp;: 
	              					&nbsp;&nbsp;<input type="text" name="tgl_src" id="tgl_src" value="<?php echo date('Y-m-01') ?>" class="form-control form-control-sm dateclass" style="width: 107px; background-color: white; display: inline;" readonly> -
	              					<input type="text" name="tgl2_src" id="tgl2_src" value="<?php echo date('Y-m-d') ?>" class="form-control form-control-sm dateclass" style="width: 107px; background-color: white; display: inline;" readonly>
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
		$('#operator_src').select2({
		  	theme: 'bootstrap4'
		});
		
	    let table = $('#produksiTable').DataTable({
	        processing: true,
	        serverSide: true,
	        ordering  : false,
	        searching : false,
	        ajax: {
	            url: "<?php echo site_url('hasil_produksi/datatables') ?>",
	            type: "POST",
	            data: function (d) {
	                d.id_produksi_src = $('#id_produksi_src').val(); // custom filter
	                d.no_mesin_src    = $('#no_mesin_src').val();
	                d.shift_src       = $('#shift_src').val();
	                d.shift_src       = $('#shift_src').val();
	                d.operator_src    = $('#operator_src').val();
	                d.produk_src      = $('#produk_src').val();
	                d.qty_hasil_src   = $('#qty_hasil_src').val();
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
	            {
	                data: null,
	                render: function (data, type, row) {
	                    return `
	                        <button type="button" class="btn btn-sm btn-primary" title="Edit produk" onclick="edit('${row.IdProduksi}')">Edit</button>
	                        <button type="button" class="btn btn-sm btn-danger" title="Hapus produk" onclick="confirmDelete('${row.IdProduksi}', '#produksiTable', '<?php echo base_url() ?>hasil_produksi/hapus_data')">Hapus</button>`;
	                }
	            },
	            { data: 'IdProduksi' },
	            { data: 'TglProduksi' },
	            { data: 'Shift' },
	            { data: 'NoMesin' },
	            { data: 'NamaOperator' },
	            { data: 'NamaProduk' },
	            { data: 'QtyHasil' },
	            { data: 'QtyWaste' },
	           	{ data: 'UserInput'},
	            { data: 'TglInput'},
	            { data: 'UserEdit'},
	            { data: 'TglEdit'}
	        ]
	    });

	    $('#searchBtn').on('click', function () {
	    	$('#modal-filter').modal('hide');
	        table.ajax.reload();
	    });

	    $('#produksiTable_length').append(`<button type="button" style="margin-left: 17px;" class="btn btn-sm bg-purple" data-toggle="modal" data-target="#modal-filter"><i class="fas fa-filter"></i> Filter Data</button>`);

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
</script>

<?php echo $this->endSection(); ?>