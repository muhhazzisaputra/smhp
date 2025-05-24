<div class="modal-body">
    <h4>Input Data Mesin</h4>
    <form id="form_add" autocomplete="off" style="margin-top: 12px;">
        <?php echo csrf_field(); ?>
        <div class="table-responsive">
            <table class="table table-sm table-borderless text-nowrap">
                <tr>
                    <td style="width: 80px;">Id Mesin<span style="color: red;">*</span></td>
                    <td style="width: 14px;">:</td>
                    <td><input class="form-control" type="text" name="id_mesin" id="id_mesin" value="<?php echo $maxCode ?>" style="width: 65px; cursor: not-allowed;" readonly required></td>
                </tr>
                <tr>
                    <td>No Mesin<span style="color: red;">*</span></td>
                    <td>:</td>
                    <td><input class="form-control numeric-only" type="text" name="no_mesin" id="no_mesin" style="width: 62px;" maxlength="3" required></td>
                </tr>
            </table>
        </div>
        <button type="button" class="btn btn-sm btn-success" id="btn_store" onclick="simpan_data()">Simpan</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="close_modal_sm()">Batal</button>
    </form>
</div>

<script type="text/javascript">
    function simpan_data() {
        id_mesin = $('#id_mesin').val();
        if(id_mesin=="") {
            alert('Id Mesin harus diisi');
            return false;
        }

        no_mesin = $('#no_mesin').val();
        if( (no_mesin=="") || (no_mesin=="0") ) {
            alert('No Mesin harus diisi');
            return false;
        }

        $.post('<?php echo base_url() ?>mesin/simpan_data', {id_mesin, no_mesin}, function(result) {
            if(result.status=="success") {
                close_modal_sm();
                $('#mesinTable').DataTable().ajax.reload();
                success_notif('Data berhasil disimpan');
            }
        }, 'json').fail(function() {
            error_notif('Data gagal disimpan. '+result.message);
        });
    }
</script>