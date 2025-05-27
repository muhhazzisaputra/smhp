<style type="text/css">
    tfoot td {
        font-weight: 900;
        background-color: #f0f0f0;
    }
</style>

<table class="table table-sm table-bordered table-head-fixed" style="margin-top: 4px; white-space: nowrap;">
    <thead>
        <tr>
            <th class="bg-info color-palette" style="font-weight: normal;">Produk</th>
            <?php foreach ($dates as $date): ?>
                <th class="bg-info color-palette" style="font-weight: normal; text-align: right;"><?php echo date('d-M-Y', strtotime($date)) ?></th>
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
                foreach ($dates as $date):
                    $val = $row[$date] ?? 0; 
                    $rowTotal += $val; ?>
                    <td style="text-align: right;"><span  style="color: blue; cursor: pointer;" onclick="detail_hasil('<?php echo $date ?>', '<?php echo $row['IdProduk'] ?>');"><?php echo ($row[$date] > 0) ? number_format($row[$date], 2) : '' ?></span></td>
                <?php endforeach; ?>
                <td style="text-align: right;"><?php echo number_format($rowTotal, 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align: right;">Total</td>
            <?php
                $colTotals = [];
                $grandTotal = 0;

                foreach ($dates as $date) {
                    $colTotals[$date] = 0;
                }

                foreach ($pivot as $row) {
                    foreach ($dates as $date) {
                        $colTotals[$date] += $row[$date] ?? 0;
                    }
                }

                foreach ($dates as $date) {
                    echo '<td style="text-align: right;">' . number_format($colTotals[$date], 2) . '</td>';
                    $grandTotal += $colTotals[$date];
                }
            ?>
            <td style="text-align: right;"><?php echo number_format($grandTotal, 2) ?></td>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
    function detail_hasil(tgl_produksi, id_produk) {
        $.post('<?php echo base_url() ?>laporan_hasil_produksi/detail_hasil_pertgl', {tgl_produksi, id_produk}, function(data) {
            $('#modal_body_lg').html(data);
            $('#modal-lg').modal('show');
        });
    }
</script>