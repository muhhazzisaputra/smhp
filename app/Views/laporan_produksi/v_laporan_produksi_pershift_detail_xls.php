<table>
    <tr>
        <td colspan="4">Shift : <?php echo $row->Shift ?></td>
    </tr>
    <tr>
        <td colspan="4">Produk : <?php echo $row->NamaProduk ?></td>
    </tr>
</table>
<table>
    <tr>
        <td>No.</td>
        <td>Id Produksi</td>
        <td>Tgl Produksi</td>
        <td style="text-align: center;">No Mesin</td>
        <td>Operator</td>
        <td style="text-align: right;">Qty Hasil</td>
        <td style="text-align: right;">Qty Waste</td>
    </tr>
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
            <td><?php echo $data->TglProduksi ?></td>
            <td style="text-align: center;"><?php echo $data->NoMesin ?></td>
            <td><?php echo $data->NamaKaryawan ?></td>
            <td style="text-align: right;"><?php echo $data->QtyHasil ?></td>
            <td style="text-align: right;"><?php echo $data->QtyWaste ?></td>
        </tr>
    <?php } ?>
    <tr style="font-weight: bold;">
        <td colspan="5" style="text-align: right;">Total</td>
        <td style="text-align: right;"><?php echo $totalQty ?></td>
        <td style="text-align: right;"><?php echo $totalWaste ?></td>
    </tr>
</table>