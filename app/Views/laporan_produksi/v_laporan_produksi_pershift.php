<style type="text/css">
    tfoot td {
        font-weight: bold;
        background-color: #f0f0f0;
    }
</style>

<table class="table table-sm table-bordered table-head-fixed" style="margin-top: 4px; white-space: nowrap;">
    <thead>
        <tr>
            <th class="bg-info color-palette" style="font-weight: normal;">Produk</th>
            <?php foreach ($mesin as $msn): ?>
                <th class="bg-info color-palette" style="font-weight: normal; text-align: right;"><?php echo $msn->NoMesin ?></th>
            <?php endforeach; ?>
            <th class="bg-info color-palette" style="font-weight: normal; text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pivot as $produk => $row): ?>
            <tr>
                <td><?php echo esc($produk) ?></td>
                <?php 
                $rowTotal = 0; 
                foreach ($mesin as $msn2):
                    $val = $row[$msn2->IdMesin] ?? 0; 
                    $rowTotal += $val; ?>
                    <td style="text-align: right;"><span  style="color: blue; cursor: pointer;" onclick="detail_hasil('<?php echo $msn2->IdMesin ?>', '<?php echo $row['IdProduk'] ?>');"><?php echo ($row[$msn2->IdMesin] > 0) ? number_format($row[$msn2->IdMesin], 2) : '' ?></span></td>
                <?php endforeach; ?>
                <td style="text-align: right;"><?php echo number_format($rowTotal, 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script type="text/javascript">
    function detail_hasil(tgl_produksi, id_produk) {
        $.post('<?php echo base_url() ?>laporan_hasil_produksi/detail_hasil_pertgl', {tgl_produksi, id_produk}, function(data) {
            $('#modal_body_lg').html(data);
            $('#modal-lg').modal('show');
        });
    }
</script>