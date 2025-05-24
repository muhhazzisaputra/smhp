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
		              	<!-- <div class="table-responsive">
		              		<table class="table table-sm table-borderless text-nowrap">
		              			<tr>
		              				<td style="width: 126px;">Id/Nama Pegawai</td>
		              				<td style="width: 13px;">:</td>
		              				<td style="width: 208px;"><input type="text" class="form-control form-control-sm" name="pegawai_src" id="pegawai_src" style="width: 200px;"></td>
		              				<td style="width: 92px;">Departemen</td>
		              				<td style="width: 13px;">:</td>
		              				<td style="width: 216px;">
		              					<select class="form-control form-control-sm" name="departemen_src" id="departemen_src" style="width: 207px;" required>
				                            <option value="">-Pilih-</option>
				                            <option value="1">Information & Technology</option>
				                            <option value="2">Produksi</option>
				                            <option value="3">Quality Control</option>
				                        </select>
		              				</td>
		              				<td style="width: 64px;">Jabatan</td>
		              				<td style="width: 13px;">:</td>
		              				<td style="width: 145px;">
		              					<select class="form-control form-control-sm" name="jabatan_src" id="jabatan_src" style="width: 134px;" required>
				                            <option value="">-Pilih-</option>
				                            <option value="1">Administrator</option>
				                            <option value="2">Kashift</option>
				                            <option value="3">Operator</option>
				                        </select>
		              				</td>
		              				<td>
		              					<button type="button" class="btn btn-sm btn-primary" id="searchBtn" title="Cari"><i class="fas fa-search"></i></button>
		              					<button type="submit" class="btn btn-sm btn-success" title="Export Xls"><i class="fas fa-file-excel"></i></button>
		              				</td>
		              			</tr>
		              		</table>
		              	</div> -->
	              	</div>
	              	<div class="card-body table-responsive">
	              		<table id="pegawaiTable" class="table table-sm table-bordered table-hover text-nowrap">
						    <thead>
						        <tr>
						            <th>#</th>
						            <th>Opsi</th>
						            <th>Id Karaywan</th>
						            <th>Nama Karaywan</th>
						            <th>Departemen</th>
						            <th>Jabatan</th>
						            <th>Alamat</th>
						            <th>No HP</th>
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
              	<form method="post" action="/pegawai/export_xls" target="_blank">
				 	<?php echo csrf_field(); ?>
					<div class="table-responsive">
						<table class="table table-sm table-borderless text-nowrap">
	              			<tr>
	              				<td style="width: 126px;">Id/Nama Karyawan</td>
	              				<td style="width: 13px;">:</td>
	              				<td style="width: 208px;"><input type="text" class="form-control form-control-sm" name="pegawai_src" id="pegawai_src" style="width: 200px;"></td>
	              				<td style="width: 92px;">Departemen</td>
	              				<td style="width: 13px;">:</td>
	              				<td style="width: 216px;">
	              					<select class="form-control form-control-sm" name="departemen_src" id="departemen_src" style="width: 207px;">
			                            <option value="">-Pilih-</option>
			                            <option value="1">Information & Technology</option>
			                            <option value="2">Produksi</option>
			                            <option value="3">Quality Control</option>
			                        </select>
	              				</td>
	              				<td style="width: 64px;">Jabatan</td>
	              				<td style="width: 13px;">:</td>
	              				<td style="width: 145px;">
	              					<select class="form-control form-control-sm" name="jabatan_src" id="jabatan_src" style="width: 134px;">
			                            <option value="">-Pilih-</option>
			                            <option value="1">Administrator</option>
			                            <option value="2">Kashift</option>
			                            <option value="3">Operator</option>
			                        </select>
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

<script>
	$(document).ready(function () {
	    let table = $('#pegawaiTable').DataTable({
	        processing: true,
	        serverSide: true,
	        ordering  : false,
	        searching : false,
	        ajax: {
	            url: "<?php echo site_url('pegawai/datatables') ?>",
	            type: "POST",
	            data: function (d) {
	                d.pegawai_src    = $('#pegawai_src').val(); // custom filter
	                d.departemen_src = $('#departemen_src').val();
	                d.jabatan_src    = $('#jabatan_src').val();
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
	                        <button type="button" class="btn btn-sm btn-primary" onclick="edit('${row.IdKaryawan}')">Edit</button>
	                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('${row.IdKaryawan}', '#pegawaiTable', '<?php echo base_url() ?>pegawai/hapus_data')">Hapus</button>
	                    `;
	                }
	            },
	            { data: 'IdKaryawan' },
	            { data: 'NamaKaryawan' },
	            { data: 'NamaDepartemen' },
	            { data: 'NamaJabatan' },
	            { data: 'Alamat' },
	            { data: 'NoHp' },
	            { data: 'UserInput'},
	            { data: 'TglInput'},
	            { data: 'UserEdit'},
	            { data: 'TglEdit'}
	        ]
	    });

	    // Trigger the custom filter on button click
	    $('#searchBtn').on('click', function () {
	    	$('#modal-filter').modal('hide');
	        table.ajax.reload();
	    });

	    $('#pegawaiTable_length').append(`<button type="button" style="margin-left: 17px;" class="btn btn-sm bg-purple" data-toggle="modal" data-target="#modal-filter"><i class="fas fa-filter"></i> Filter Data</button>`);
	});

	function add() {
		$.post('<?php echo base_url() ?>pegawai/form_data', {jenis: "input"}, function(data) {
			$('#modal_body_lg').html(data);
			$('#modal-lg').modal('show');
		});
	}

	function edit(id_pegawai) {
		$.post('<?php echo base_url() ?>pegawai/form_data', {jenis: "edit", id_pegawai}, function(data) {
			$('#modal_body_lg').html(data);
			$('#modal-lg').modal('show');
		});
	}

	function close_modal_filter() {
		$('#modal-filter').modal('hide');
	}
</script>
<?php echo $this->endSection(); ?>