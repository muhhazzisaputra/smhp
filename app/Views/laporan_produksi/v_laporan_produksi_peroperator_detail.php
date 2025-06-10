<div class="modal-body">
    <h4>Detail Hasil Produksi Per Operator</h4>
    <form method="post" action="<?php echo base_url() ?>laporan_hasil_produksi/detail_hasil_peroperator/xls" target="_blank">
        <input type="hidden" name="bulan" value="">
        <input type="hidden" name="id_mesin" value="">
        <input type="hidden" name="id_produk" value="">
        <table class="table table-sm table-borderless text-nowrap">
            <tr>
                <td style="width: 120px;">Id Produksi</td>
                <td style="width: 13px;">:</td>
                <td><?php echo $produksi->IdProduksi ?></td>
            </tr>
            <tr>
                <td>Tgl Produksi</td>
                <td>:</td>
                <td><?php echo $produksi->TglProduksi ?></td>
            </tr>
            <tr>
                <td>Shift</td>
                <td>:</td>
                <td><?php echo $produksi->Shift ?></td>
            </tr>
            <tr>
                <td>No Mesin</td>
                <td>:</td>
                <td><?php echo $produksi->NoMesin ?></td>
            </tr>
            <tr>
                <td>Nama Operator</td>
                <td>:</td>
                <td><?php echo $produksi->NamaKaryawan ?></td>
            </tr>
            <tr>
                <td>Produk</td>
                <td>:</td>
                <td><?php echo $produksi->NamaProduk ?></td>
            </tr>
            <tr>
                <td>Qty Hasil</td>
                <td>:</td>
                <td><?php echo $produksi->QtyHasil ?></td>
            </tr>
            <tr>
                <td>Qty Waste</td>
                <td>:</td>
                <td><?php echo $produksi->QtyWaste ?></td>
            </tr>
        </table>
        <!-- <button type="submit" class="btn btn-sm btn-success">Export</button> -->
        <button type="button" class="btn btn-sm btn-danger" onclick="close_modal_lg()">Tutup</button>
    </form>
</div>