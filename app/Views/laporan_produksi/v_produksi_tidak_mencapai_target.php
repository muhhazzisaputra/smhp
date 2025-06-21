<style type="text/css">
    tfoot td {
        font-weight: bold;
        background-color: #f0f0f0;
    }
</style>
<?php //echo '<pre>'; print_r($pivot); '</pre>'; ?>
<table class="table table-sm table-bordered table-head-fixed" style="margin-top: 4px; white-space: nowrap;">
    <thead>
        <tr>
            <th class="bg-info color-palette" style="text-align: center; font-weight: normal;">No.</th>
            <th class="bg-info color-palette" style="font-weight: normal;">Id Produksi</th>
            <th class="bg-info color-palette" style="font-weight: normal;">Tgl Produksi</th>
            <th class="bg-info color-palette" style="text-align: center; font-weight: normal;">Shift</th>
            <th class="bg-info color-palette" style="font-weight: normal;">No Mesin</th>
            <th class="bg-info color-palette" style="font-weight: normal;">Operator</th>
            <th class="bg-info color-palette" style="font-weight: normal;">Produk</th>
            <th class="bg-info color-palette" style="text-align: right; font-weight: normal;">Qty Hasil</th>
            <th class="bg-info color-palette" style="text-align: right; font-weight: normal;">Qty Waste</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 0;
        foreach($hasilProduksi as $hprd) {
            $no += 1; ?>
        <tr>
            <td style="text-align: center;"><?php echo $no ?></td>
            <td><?php echo $hprd->IdProduksi ?></td>
            <td><?php echo date('d-M-Y', strtotime($hprd->TglProduksi)) ?></td>
            <td style="text-align: center;"><?php echo $hprd->Shift ?></td>
            <td><?php echo $hprd->NoMesin ?></td>
            <td><?php echo $hprd->NamaKaryawan ?></td>
            <td><?php echo $hprd->NamaProduk ?></td>
            <td style="text-align: right;"><?php echo $hprd->QtyHasil ?></td>
            <td style="text-align: right;"><?php echo $hprd->QtyWaste ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>