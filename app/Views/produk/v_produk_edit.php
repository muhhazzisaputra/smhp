<div class="modal-body">
    <h4>Edit Data Produk</h4>
    <form id="form_edit" autocomplete="off" style="margin-top: 12px;">
        <?php echo csrf_field(); ?>
        <div class="table-responsive">
            <table class="table table-sm table-borderless text-nowrap">
                <tr>
                    <td style="width: 115px;">Id Produk<span style="color: red;">*</span></td>
                    <td style="width: 14px;">:</td>
                    <td><input type="text" name="id_produk" id="id_produk" value="<?php echo $produk->IdProduk ?>" class="form-control" style="width: 70px; cursor: not-allowed;" readonly required></td>
                </tr>
                <tr>
                    <td>Nama Produk<span style="color: red;">*</span></td>
                    <td>:</td>
                    <td><input type="text" name="nama_produk" id="nama_produk" value="<?php echo $produk->NamaProduk ?>" class="form-control" style="width: 325px;" required></td>
                </tr>
                <tr>
                    <td>Aktif<span style="color: red;">*</span></td>
                    <td>:</td>
                    <td>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="1" name="aktif" value="1" style="margin-top: 6px;" <?php echo ($produk->Aktif==1) ? 'checked' : '' ?>>
                                <label for="1" class="form-check-label">Ya</label>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input class="form-check-input" type="radio" id="2" name="aktif" value="2" style="margin-top: 6px;" <?php echo ($produk->Aktif==0) ? 'checked' : '' ?>>
                                <label for="2" class="form-check-label">Tidak</label>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Gambar</td>
                    <td>:</td>
                    <td><input type="file" accept="image/png,image/jpg,image/jpeg" name="image" class="form-control" id="image" onchange="preview_awal(this)" style="width: 325px;" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <?php 
                            $foto = ( strlen($produk->Gambar) > 10 ) ? base_url('uploads/produk/'.$produk->Gambar) : base_url().'uploads/no-product-image.png';
                        ?>
                        <img src="<?php echo $foto ?>" id="preview_img_set" alt="Gambar Produk" class="img-thumbnail" style="height: 115px; width: 120px;" onclick="preview_img(this)">
                    </td>
                </tr>
            </table>
        </div>
        <button type="button" class="btn btn-sm btn-success" id="btn_store" onclick="update_data()">Update</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="close_modal_md()">Tutup</button>
    </form>
</div>

<script type="text/javascript">
    function preview_awal() {
        preview_img_set.src=URL.createObjectURL(event.target.files[0]);
        // $('#gambar_default').prop('checked', false);
    }

    function preview_img(data) {
        id = data.id;
        $('#set_img').attr('src', $('#'+id).attr('src'));
        $('#modal-img').modal('show');
    }

    function update_data() {
        id_produk = $('#id_produk').val();
        if(id_produk=="") {
            alert('Id Produk harus dipilih');
            $('#id_produk').focus();
            return false;
        }

        nama_produk = $('#nama_produk').val();
        if(nama_produk=="") {
            alert('Nama Produk harus diisi');
            $('#nama_produk').focus();
            return false;
        }

        let formData = new FormData($('#form_edit')[0]);

        jQuery.ajax({
            type       : "POST",
            url        : "<?php echo base_url() ?>produk/update_data",
            data       : formData,
            processData: false,
            contentType: false,
            dataType   : "json",
            success    : function(result) {
                if(result.status=="success") {
                    close_modal_md();
                    $('#produkTable').DataTable().ajax.reload();
                    success_notif('Data berhasil diupdate');
                }
            },
            error: function(xhr, status, error) {
                error_notif("Terjadi suatu kesalahan. Hubungi Admin IT");
            }
        });
    }
</script>