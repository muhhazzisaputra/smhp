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
            <th rowspan="2" class="bg-info color-palette" style="font-weight: normal; text-align: center; width: 50px; vertical-align: top;">No.</th>
            <th rowspan="2" class="bg-info color-palette" style="font-weight: normal; vertical-align: top;">Bulan</th>
            <th colspan="<?php echo count($produk) ?>" class="bg-info color-palette" style="font-weight: normal; text-align: center;">Produk</th>
            <th rowspan="2" class="bg-info color-palette" style="font-weight: normal; text-align: right; vertical-align: top;">Total</th>
        </tr>
        <tr>
            <?php foreach ($nama as $nm) : ?>
                <th class="bg-info color-palette" style="font-weight: normal; text-align: right;"><?php echo $nm->NamaProduk ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php $no = 0; foreach ($pivot as $tanggal => $row) : $no += 1; ?>
            <tr>
                <td style="text-align: center;"><?php echo $no ?></td>
                <td><?php echo date('M Y', strtotime($row['Bulan'])) ?></td>
                <?php 
                $rowTotal = 0; 
                foreach ($produk as $prd) :
                    $val = $row[$prd] ?? 0; 
                    $rowTotal += $val; ?>
                    <td style="text-align: right;"><span  style="color: blue; cursor: pointer;" onclick="detail_hasil_minggu('<?php echo $row['Bulan'] ?>', '<?php echo $prd ?>');"><?php echo ($row[$prd] > 0) ? number_format($row[$prd], 2) : '' ?></span></td>
                <?php endforeach; ?>
                <td style="text-align: right;"><?php echo number_format($rowTotal, 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" style="text-align: right;">Total</td>
            <?php
                $colTotals = [];
                $grandTotal = 0;

                foreach ($produk as $data) {
                    $colTotals[$data] = 0;
                }

                foreach ($pivot as $row) {
                    foreach ($produk as $data2) {
                        $colTotals[$data2] += $row[$data2] ?? 0;
                    }
                }

                foreach ($produk as $data3) {
                    echo '<td style="text-align: right;">' . number_format($colTotals[$data3], 2) . '</td>';
                    $grandTotal += $colTotals[$data3];
                }
            ?>
            <td style="text-align: right;"><?php echo number_format($grandTotal, 2) ?></td>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
    function detail_hasil_minggu(bulan, id_produk) {
        $.post('<?php echo base_url() ?>laporan_hasil_produksi/detail_hasil_perproduk_bulan', {bulan, id_produk}, function(data) {
            $('#modal_body_lg').html(data);
            $('#modal-lg').modal('show');
        });
    }
</script>