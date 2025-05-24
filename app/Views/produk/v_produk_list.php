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
	              		<table id="produkTable" class="table table-sm table-bordered table-hover text-nowrap">
						    <thead>
						        <tr>
						            <th>#</th>
						            <th>Opsi</th>
						            <th>Id Produk</th>
						            <th>Nama Produk</th>
						            <th>Aktif</th>
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

<script>
	$(document).ready(function () {
	    let table = $('#produkTable').DataTable({
	        processing: true,
	        serverSide: true,
	        ordering  : false,
	        ajax: {
	            url: "<?php echo site_url('produk/datatables') ?>",
	            type: "POST",
	            data: function (d) {
	                // d.username = $('#username_filter').val(); // custom filter
	                // d.status   = $('#status_filter').val();
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
	                        <button type="button" class="btn btn-sm btn-primary" title="Edit produk" onclick="edit('${row.IdProduk}')">Edit</button>
	                        <button type="button" class="btn btn-sm btn-danger" title="Hapus produk" onclick="confirmDelete('${row.IdProduk}', '#produkTable', '<?php echo base_url() ?>produk/hapus_data')">Hapus</button>
	                        <button type="button" class="btn btn-sm btn-info" onclick="view_gambar('${row.Gambar}')" title="Lihat gambar produk">Gambar</button>
	                    `;
	                }
	            },
	            { data: 'IdProduk' },
	            { data: 'NamaProduk' },
	            { 
	            	data: null,
	            	render: function (data, type, row) {
	                    return (row.Aktif==1) ? 'Ya' : 'Tidak';
	                }
	           	},
	           	{ data: 'UserInput'},
	            { data: 'TglInput'},
	            { data: 'UserEdit'},
	            { data: 'TglEdit'}
	        ]
	    });

	    // Trigger the custom filter on button click
	    // $('#searchBtn').on('click', function () {
	    //     table.ajax.reload();
	    // });
	});

	function add() {
		$.post('<?php echo base_url() ?>produk/form_data', {jenis: "input"}, function(data) {
			$('#modal_body_md').html(data);
			$('#modal-md').modal('show');
		});
	}

	function edit(id_produk) {
		$.post('<?php echo base_url() ?>produk/form_data', {jenis: "edit", id_produk}, function(data) {
			$('#modal_body_md').html(data);
			$('#modal-md').modal('show');
		});
	}

	function view_gambar(gambar) {
		$('#set_img').attr('src', '<?php echo base_url() ?>uploads/produk/'+gambar);
        $('#modal-img').modal('show');
	}
</script>
<?php echo $this->endSection(); ?>