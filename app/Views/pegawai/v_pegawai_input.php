<div class="modal-body">
    <h4>Input Data Karyawan</h4>
    <form id="form_add" autocomplete="off" style="margin-top: 12px;">
        <?php echo csrf_field(); ?>
        <div class="table-responsive">
            <table class="table table-sm table-borderless text-nowrap">
                <tr>
                    <td style="width: 115px;">Id Karyawan<span style="color: red;">*</span></td>
                    <td style="width: 14px;">:</td>
                    <td><input type="text" name="id_pegawai" id="id_pegawai" value="<?php echo $maxCode ?>" class="form-control" style="width: 70px; cursor: not-allowed;" readonly required></td>
                </tr>
                <tr>
                    <td>Nama Karyawan<span style="color: red;">*</span></td>
                    <td>:</td>
                    <td><input type="text" name="nama_pegawai" id="nama_pegawai" class="form-control" style="width: 325px;" required></td>
                </tr>
                <tr>
                    <td>Departemen<span style="color: red;">*</span></td>
                    <td>:</td>
                    <td>
                        <select class="form-control" name="id_departemen" id="id_departemen" style="width: 207px;" required>
                            <option value="">-Pilih-</option>
                            <option value="1">Information & Technology</option>
                            <option value="2">Produksi</option>
                            <option value="3">Quality Control</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Jabatan<span style="color: red;">*</span></td>
                    <td>:</td>
                    <td>
                        <select class="form-control" name="id_jabatan" id="id_jabatan" style="width: 207px;" required>
                            <option value="">-Pilih-</option>
                            <option value="1">Administrator</option>
                            <option value="2">Kashift</option>
                            <option value="3">Operator</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>No. HP<span style="color: red;">*</span></td>
                    <td>:</td>
                    <td><input type="text" name="no_hp" id="no_hp" class="form-control numeric-only" maxlength="13" style="width: 130px;" required></td>
                </tr>
                <tr>
                    <td>Alamat<span style="color: red;">*</span></td>
                    <td>:</td>
                    <td><input type="text" name="alamat" id="alamat" class="form-control" style="width: 325px;" required></td>
                </tr>
                <tr>
                    <td>Foto</td>
                    <td>:</td>
                    <td><input type="file" accept="image/png,image/jpg,image/jpeg" name="image" class="form-control" id="image" onchange="preview_awal(this)" style="width: 325px;" required></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td><img src="<?php echo base_url() ?>uploads/default-user.png" id="preview_img_set" alt="Foto Pegawai" class="img-thumbnail" style="height: 115px; width: 120px;" onclick="preview_img(this)"></td>
                </tr>
            </table>
        </div>
        <button type="button" class="btn btn-sm btn-success" id="btn_store" onclick="simpan_data()">Simpan</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="close_modal_lg()">Tutup</button>
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

    function simpan_data() {
        nama_pegawai = $('#nama_pegawai').val();
        if(nama_pegawai=="") {
            alert('Nama Pegawai harus diisi');
            $('#nama_pegawai').focus();
            return false;
        }

        id_departemen = $('#id_departemen').val();
        if(id_departemen=="") {
            alert('Departemen harus dipilih');
            $('#id_departemen').focus();
            return false;
        }

        id_jabatan = $('#id_jabatan').val();
        if(id_jabatan=="") {
            alert('Jabatan harus dipilih');
            $('#id_jabatan').focus();
            return false;
        }

        no_hp = $('#no_hp').val();
        if(no_hp=="") {
            alert('No HP harus diisi');
            $('#no_hp').focus();
            return false;
        }

        alamat = $('#alamat').val();
        if(alamat=="") {
            alert('Alamat harus diisi');
            $('#alamat').focus();
            return false;
        }

        let formData = new FormData($('#form_add')[0]);

        jQuery.ajax({
            type       : "POST",
            url        : "<?php echo base_url() ?>pegawai/simpan_data",
            data       : formData,
            processData: false,
            contentType: false,
            dataType   : "json",
            success    : function(result) {
                if(result.status=="success") {
                    close_modal_lg();
                    $('#pegawaiTable').DataTable().ajax.reload();
                    success_notif('Data berhasil disimpan');
                }
            },
            error: function(xhr, status, error) {
                error_notif("Terjadi suatu kesalahan. Hubungi Admin IT");
            }
        });
    }
</script>