<div class="modal-body">
    <h4>Edit Hasil Produksi</h4>
    <form id="form_edit_hasil" autocomplete="off" style="margin-top: 12px;">
        <?php echo csrf_field(); ?>
        <div class="table-responsive">
            <table>
                <tr>
                <td valign="top">
                    <table class="table table-sm table-borderless text-nowrap">
                        <tr>
                            <td style="width: 102px;">ID Produksi</td>
                            <td style="width: 14px;">:</td>
                            <td>
                                <?php echo $hasil->IdProduksi ?>
                                <input type="hidden" name="id_produksi" value="<?php echo $hasil->IdProduksi ?>">   
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 102px;">Tgl Produksi<span style="color: red;">*</span></td>
                            <td style="width: 14px;">:</td>
                            <td><input type="text" name="tgl_produksi" id="tgl_produksi" value="<?php echo $hasil->TglProduksi ?>" class="form-control" style="width: 107px; background-color: white; cursor: not-allowed;" readonly required></td>
                        </tr>
                        <tr>
                            <td>Shift<span style="color: red;">*</span></td>
                            <td>:</td>
                            <td>
                                <select name="shift" id="shift" class="form-control" style="width: 75px; cursor: not-allowed;">
                                    <option value="<?php echo $hasil->Shift ?>"><?php echo $hasil->Shift ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>No Mesin<span style="color: red;">*</span></td>
                            <td>:</td>
                            <td>
                                <select name="id_mesin" id="id_mesin" class="form-control" style="width: 125px;">
                                    <option value="">-Pilih-</option>
                                    <?php foreach($mesin as $ms) { ?>
                                        <option value="<?php echo $ms->IdMesin ?>"<?php echo ($ms->IdMesin==$hasil->IdMesin) ? ' selected' : '' ?>><?php echo $ms->IdMesin.' - '.$ms->NoMesin ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Operator<span style="color: red;">*</span></td>
                            <td>:</td>
                            <td>
                                <select name="id_pegawai" id="id_pegawai" class="form-control select2bs4" style="width: 270px;">
                                    <option value="">-Pilih-</option>
                                    <?php foreach($operator as $opt) { ?>
                                        <option value="<?php echo $opt->IdKaryawan ?>"<?php echo ($opt->IdKaryawan==$hasil->IdKaryawan) ? ' selected' : '' ?>><?php echo $opt->IdKaryawan.' - '.$opt->NamaKaryawan ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Produk<span style="color: red;">*</span></td>
                            <td>:</td>
                            <td>
                                <select name="id_produk" id="id_produk" class="form-control select2bs4" style="width: 270px;">
                                    <option value="">-Pilih-</option>
                                    <?php foreach($produk as $prd) { ?>
                                        <option value="<?php echo $prd->IdProduk ?>"<?php echo ($prd->IdProduk==$hasil->IdProduk) ? ' selected' : '' ?>><?php echo $prd->IdProduk.' - '.$prd->NamaProduk ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign="top" style="width: 70px;">&nbsp;</td>
                <td valign="top">
                    <table class="table table-sm table-borderless text-nowrap">
                        <tr>
                            <td>Qty Hasil<span style="color: red;">*</span></td>
                            <td>:</td>
                            <td><input type="text" name="qty_hasil" id="qty_hasil" value="<?php echo number_format($hasil->QtyHasil,2) ?>" class="form-control" onkeyup="num_only(this)" style="width: 72px; text-align: right; display: inline;" maxlength="6" required> Kg</td>
                        </tr>
                        <tr>
                            <td>Qty Waste<span style="color: red;">*</span></td>
                            <td>:</td>
                            <td><input type="text" name="qty_waste" id="qty_waste" value="<?php echo number_format($hasil->QtyWaste,2) ?>" class="form-control" onkeyup="num_only(this)" style="width: 72px; text-align: right; display: inline;" maxlength="6" required> Kg</td>
                        </tr>
                        <tr>
                            <td>Kode Produksi Bahan<span style="color: red;">*</span></td>
                            <td>:</td>
                            <td><input type="text" name="kd_produksi_bahan" id="kd_produksi_bahan" value="<?php echo $hasil->KdProduksi_RefBahan ?>" class="form-control numeric-only" style="width: 122px;" maxlength="11" required></td>
                        </tr>
                        <tr>
                            <td>Qty Produksi Bahan<span style="color: red;">*</span></td>
                            <td>:</td>
                            <td><input type="text" name="qty_produksi_bahan" id="qty_produksi_bahan" value="<?php echo number_format($hasil->Qty_RefBahan,2) ?>" class="form-control" onkeyup="num_only(this)" style="width: 72px; text-align: right; display: inline;" maxlength="6" required> Kg</td>
                        </tr>
                        <tr>
                            <td>Qty Sisa Bahan<span style="color: red;">*</span></td>
                            <td>:</td>
                            <td><input type="text" name="qty_sisa_bahan" id="qty_sisa_bahan" value="<?php echo number_format($hasil->QtySisa_RefBahan,2) ?>" class="form-control" onkeyup="num_only(this)" style="width: 72px; text-align: right; display: inline;" maxlength="6" required> Kg</td>
                        </tr>
                        <tr>
                            <td>QC/Inspector<span style="color: red;">*</span></td>
                            <td>:</td>
                            <td>
                                <select name="id_qc" id="id_qc" class="form-control" style="width: 270px;">
                                    <option value="">-Pilih-</option>
                                    <?php foreach($inspector as $ins) { ?>
                                        <option value="<?php echo $ins->IdKaryawan ?>"<?php echo ($ins->IdKaryawan==$hasil->IdQc) ? ' selected' : '' ?>><?php echo $ins->IdKaryawan.' - '.$ins->NamaKaryawan ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>
                </tr>
            </table>
        </div>

        <button type="button" class="btn btn-sm btn-info" onclick="add_baris(this)"><i class="fas fa-plus nav-icon"></i> Baris</button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-danger"><i>*Note : Wajib isi jika ada hasil NG dan komplain</i></span>
        <div class="table-responsive" style="height: 34vh;">
            <table class="table table-sm table-bordered table-head-fixed" style="margin-top: 4px; white-space: nowrap;">
                <thead>
                    <tr>
                        <th class="bg-info color-palette" style="font-weight: normal;">No</th>
                        <th class="bg-info color-palette" style="font-weight: normal;"></th>
                        <th class="bg-info color-palette" style="width: 175px; font-weight: normal;">Jam Setting</th>
                        <th class="bg-info color-palette" style="width: 62px; font-weight: normal;">Speed</th>
                        <th class="bg-info color-palette" style="width: 95px; font-weight: normal;">Temperatur</th>
                        <th class="bg-info color-palette" style="width: 124px; font-weight: normal;">Kategori NG</th>
                        <th class="bg-info color-palette" style="width: 62px; font-weight: normal;">Qty NG</th>
                        <th class="bg-info color-palette" style="font-weight: normal;">Keterangan Komplain QC</th>
                    </tr>
                </thead>
                <tbody id="parameter_list">
                <?php
                $no = 0;
                foreach($detail as $data) {
                    $no += 1;
                ?>
                    <tr>
                        <td style="text-align: center; width: 0px;">
                            <?php echo $no ?>
                            <input type="hidden" name="no_urut[]" id="no_urut_<?php echo $no ?>" class="no_urut" value="<?php echo $no ?>" readonly>
                        </td>
                        <td style="text-align: center; width: 55px;">
                            <?php if($no > 1) { ?>
                            <button class="btn btn-danger btn-sm" type="button" onclick="delete_row(this,'<?php echo $no ?>')" title="Hapus Baris"><i class="fas fa-trash"></i></button>
                            <?php } ?>
                        </td>
                        <td>
                            <input type="time" class="form-control" name="jam[]" id="jam_<?php echo $no ?>" value="<?php echo $data->Jam ?>" style="width: 75px; display: inline;"> -
                            <input type="time" class="form-control" name="jam2[]" id="jam2_<?php echo $no ?>" value="<?php echo $data->Jam2 ?>" style="width: 75px; display: inline;">
                        </td>
                        <td>
                            <input type="text" class="form-control numeric-only" name="speed[]" id="speed_<?php echo $no ?>" value="<?php echo $data->Speed ?>" style="text-align: right; width: 52px;" maxlength="3">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="temperatur[]" id="temperatur_<?php echo $no ?>" value="<?php echo $data->Temperatur ?>" style="text-align: right; width: 65px; display: inline;" maxlength="5" onkeyup="num_only(this)"> &deg;C
                        </td>
                        <td>
                            <select class="form-control" name="id_kategori_ng[]" id="id_kategori_ng_<?php echo $no ?>">
                                <?php foreach($ms_kategori_ng as $kng) { ?>
                                    <option value="<?php echo $kng->IdKategori ?>"<?php echo ($kng->IdKategori==$data->IdKategoriNG) ? ' selected' : '' ?>><?php echo $kng->NamaKategori ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="qty_ng[]" id="qty_ng_<?php echo $no ?>" value="<?php echo $data->QtyNG ?>" style="text-align: right; width: 55px;" maxlength="4" onkeyup="num_only(this)">
                        </td>
                        <td>
                            <input class="form-control" name="keterangan[]" id="keterangan_<?php echo $no ?>" value="<?php echo $data->Keterangan ?>" style="width: 485px; resize: none;" maxlength="150">
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <button type="button" class="btn btn-sm btn-success" id="btn_store" onclick="update_data()">Update</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="close_modal_xl()">Tutup</button>
    </form>
</div>

<script type="text/javascript">
    $(function() {
        $('.select2bs4').select2({
          theme: 'bootstrap4'
        });
    });

    function add_baris(baris='') {
        no_urut      = document.getElementsByClassName("no_urut");
        no_akhir     = (no_urut.length=='0') ? 0 : (no_urut[no_urut.length-1].value);
        no_akhir_set = (parseInt(no_akhir) > 0) ? (parseInt(no_akhir)+1) : (1);
        kategori_ng  = '<?php echo $kategori_ng ?>';

        rowCount = $('#parameter_list').find('tr').length+1;
        if(rowCount > 8) {
            error_notif('Jumlah baris sudah maksimal');
            return false;
        }

        btn_hapus = (no_akhir_set > 1) ? '<button class="btn btn-danger btn-sm" type="button" onclick="delete_row(this,'+no_akhir_set+')" title="Hapus Baris"><i class="fas fa-trash"></i></button>' : "";
        new_row = `<tr>
                        <td style="text-align: center; width: 0px;">
                            ${no_akhir_set}
                            <input type="hidden" name="no_urut[]" id="no_urut_${no_akhir_set}" class="no_urut" value="${no_akhir_set}" readonly>
                        </td>
                        <td style="text-align: center; width: 55px;">${btn_hapus}</td>
                        <td>
                            <input type="time" class="form-control" name="jam[]" id="jam_${no_akhir_set}" style="width: 75px; display: inline;"> -
                            <input type="time" class="form-control" name="jam2[]" id="jam2_${no_akhir_set}" style="width: 75px; display: inline;">
                        </td>
                        <td>
                            <input type="text" class="form-control numeric-only" name="speed[]" id="speed_${no_akhir_set}" style="text-align: right; width: 52px;" maxlength="3">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="temperatur[]" id="temperatur_${no_akhir_set}" style="text-align: right; width: 65px; display: inline;" maxlength="5" onkeyup="num_only(this)"> &deg;C
                        </td>
                        <td>
                            <select class="form-control" name="id_kategori_ng[]" id="id_kategori_ng_${no_akhir_set}">
                                ${kategori_ng}
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="qty_ng[]" id="qty_ng_${no_akhir_set}" style="text-align: right; width: 55px;" maxlength="4" onkeyup="num_only(this)">
                        </td>
                        <td>
                            <input class="form-control" name="keterangan[]" id="keterangan_${no_akhir_set}" value="" style="width: 485px; resize: none;" maxlength="150">
                        </td>
                    </tr>`;

        $("#parameter_list").append(new_row);
    }

    function delete_row(data){
        $(data).closest('tr').remove();
        return false;
    }

    function update_data() {
        tgl_produksi = $('#tgl_produksi').val();
        if(tgl_produksi=="") {
            alert('Tgl produksi harus diisi');
            return false;
        }

        shift = $('#shift').val();
        if(shift=="") {
            alert('Shift harus dipilih');
            $('#shift').focus();
            return false;
        }

        id_mesin = $('#id_mesin').val();
        if(id_mesin=="") {
            alert('Mesin harus dipilih');
            $('#id_mesin').focus();
            return false;
        }

        id_pegawai = $('#id_pegawai').val();
        if(id_pegawai=="") {
            alert('Operator harus dipilih');
            $('#id_pegawai').focus();
            return false;
        }

        qty_hasil = $('#qty_hasil').val();
        if(qty_hasil=="") {
            alert('Qty Hasil harus diisi');
            $('#qty_hasil').focus();
            return false;
        }

        qty_waste = $('#qty_waste').val();
        if(qty_waste=="") {
            alert('Qty Waste harus diisi');
            $('#qty_waste').focus();
            return false;
        }

        kd_produksi_bahan = $('#kd_produksi_bahan').val();
        if(kd_produksi_bahan=="") {
            alert('Kode Produksi Bahan harus diisi');
            $('#kd_produksi_bahan').focus();
            return false;
        }

        kd_produksi_bahan = $('#kd_produksi_bahan').val();
        if(kd_produksi_bahan=="") {
            alert('Kode Produksi Bahan harus diisi');
            $('#kd_produksi_bahan').focus();
            return false;
        }

        qty_produksi_bahan = $('#qty_produksi_bahan').val();
        if(qty_produksi_bahan=="") {
            alert('Qty Produksi Bahan harus diisi');
            $('#qty_produksi_bahan').focus();
            return false;
        }

        qty_sisa_bahan = $('#qty_sisa_bahan').val();
        if(qty_sisa_bahan=="") {
            alert('Qty Sisa Bahan harus diisi');
            $('#qty_sisa_bahan').focus();
            return false;
        }

        id_qc = $('#id_qc').val();
        if(id_qc=="") {
            alert('QC/Inspector harus dipilih');
            $('#id_qc').focus();
            return false;
        }
        
        let jam = document.getElementsByName('jam[]');

        for (i=1; i < jam.length+1; i++) {
            if ($("#jam_"+i).val() == "") {
                alert("Jam baris ke "+i+" harus diisi.");
                $("#jam_"+i).focus();
                return false;
            }
        }

        for (i=1; i < jam.length+1; i++) {
            if ($("#jam2_"+i).val() == "") {
                alert("Jam 2 baris ke "+i+" harus diisi.");
                $("#jam2_"+i).focus();
                return false;
            }
        }

        for (i=1; i < jam.length+1; i++) {
            if ($("#speed_"+i).val() == "") {
                alert("Speed baris ke "+i+" harus diisi.");
                $("#speed_"+i).focus();
                return false;
            }
        }

        for (i=1; i < jam.length+1; i++) {
            if ($("#temperatur_"+i).val() == "") {
                alert("Temperatur baris ke "+i+" harus diisi.");
                $("#temperatur_"+i).focus();
                return false;
            }
        }

        for (i=1; i < jam.length+1; i++) {
            if ($("#id_kategori_ng_"+i).val() == "") {
                alert("Kategori NG baris ke "+i+" harus dipilih.");
                $("#id_kategori_ng_"+i).focus();
                return false;
            }
        }

        for (i=1; i < jam.length+1; i++) {
            if($("#id_kategori_ng_"+i).val() != "1") {
                setQty = $("#qty_ng_"+i).val();

                if ( (setQty== "") || (setQty == "0") || (setQty == "0.0") || (setQty == "0.00") ) {
                    alert("Qty NG baris ke "+i+" harus diisi.");
                    $("#qty_ng_"+i).focus();
                    return false;
                }
            }
        }

        for (i=1; i < jam.length+1; i++) {
            if($("#id_kategori_ng_"+i).val() != "1") {
                if ($("#keterangan_"+i).val() == "") {
                    alert("Katerangan komplain QC baris ke "+i+" harus diisi.");
                    $("#keterangan_"+i).focus();
                    return false;
                }
            }
        }

        $.post('<?php echo base_url() ?>hasil_produksi/update_data', $('#form_edit_hasil').serialize(), function(result) {
            if(result.status=="success") {
                close_modal_xl();
                $('#produksiTable').DataTable().ajax.reload();
                success_notif('Data berhasil diupdate');
            }
        }, 'json').fail(function() {
            error_notif('Data gagal diupdate. '+result.message);
        });
    }

    function num_only(data) {
        var isi = data.value;
        let qty = format_number(isi);
        $(data).val(qty);
    }
</script>