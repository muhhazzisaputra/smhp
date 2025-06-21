<div class="modal-body">
    <h4>Detail Hasil Produksi Per Produk</h4>
    <form method="post" action="<?php echo base_url() ?>laporan_hasil_produksi/detail_hasil_permesin/xls" target="_blank">
        <input type="hidden" name="tgl_produksi" value="<?php echo $row->TglProduksi ?>">
        <input type="hidden" name="id_produk" value="<?php echo $row->IdProduk ?>">
        <table class="table table-sm table-borderless text-nowrap">
            <tr>
                <td style="width: 100px;">Periode Tgl</td>
                <td style="width: 13px;">:</td>
                <td><?php echo date('d-M-Y', strtotime($dari_tgl)) ?> s/d <?php echo date('d-M-Y', strtotime($ke_tgl)) ?></td>
            </tr>
            <tr>
                <td>Produk</td>
                <td>:</td>
                <td><?php echo $row->NamaProduk ?></td>
            </tr>
        </table>
        <div class="table-responsive">
            <table class="table table-sm table-bordered text-nowrap">
                <thead>
                    <tr>
                        <th class="bg-info color-palette" style="font-weight: normal; text-align: center;">No.</th>
                        <th class="bg-info color-palette" style="font-weight: normal;">Id Produksi</th>
                        <th class="bg-info color-palette" style="font-weight: normal;">Tgl Produksi</th>
                        <th class="bg-info color-palette" style="font-weight: normal; text-align: center;">Shift</th>
                        <th class="bg-info color-palette" style="font-weight: normal;">No Mesin</th>
                        <th class="bg-info color-palette" style="font-weight: normal;">Operator</th>
                        <th class="bg-info color-palette" style="font-weight: normal; text-align: right;">Qty Hasil</th>
                        <th class="bg-info color-palette" style="font-weight: normal; text-align: right;">Qty Waste</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no         = 0;
                    $totalQty   = 0;
                    $totalWaste = 0;
                    foreach($detail as $data) { 
                        $no += 1; 
                        $totalQty   += $data->QtyHasil;
                        $totalWaste += $data->QtyWaste; ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $no ?></td>
                            <td><?php echo $data->IdProduksi ?></td>
                            <td><?php echo date('d-M-Y', strtotime($data->TglProduksi)) ?></td>
                            <td style="text-align: center;"><?php echo $data->Shift ?></td>
                            <td><?php echo $data->NoMesin ?></td>
                            <td><?php echo $data->NamaKaryawan ?></td>
                            <td style="text-align: right;"><?php echo $data->QtyHasil ?></td>
                            <td style="text-align: right;"><?php echo $data->QtyWaste ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold;">
                        <td colspan="6" style="text-align: right;">Total</td>
                        <td style="text-align: right;"><?php echo $totalQty ?></td>
                        <td style="text-align: right;"><?php echo $totalWaste ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <button type="submit" class="btn btn-sm btn-success">Export</button>
        <button type="button" class="btn btn-sm btn-danger" onclick="close_modal_lg()">Tutup</button>
    </form>
</div>