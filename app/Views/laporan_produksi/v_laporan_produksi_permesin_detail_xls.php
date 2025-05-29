<table>
    <tr>
        <td colspan="4">No Mesin : <?php echo $row->NoMesin ?></td>
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
        <td>Shift</td>
        <td>Operator</td>
        <td>Qty Hasil</td>
        <td>Qty Waste</td>
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
            <td><?php echo $no ?></td>
            <td><?php echo $data->IdProduksi ?></td>
            <td><?php echo $data->TglProduksi ?></td>
            <td><?php echo $data->Shift ?></td>
            <td><?php echo $data->NamaKaryawan ?></td>
            <td><?php echo $data->QtyHasil ?></td>
            <td><?php echo $data->QtyWaste ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="5">Total</td>
        <td><?php echo $totalQty ?></td>
        <td><?php echo $totalWaste ?></td>
    </tr>
</table>