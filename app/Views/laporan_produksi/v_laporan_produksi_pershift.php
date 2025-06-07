<style type="text/css">
    tfoot td {
        font-weight: bold;
        background-color: #f0f0f0;
    }
</style>

<table class="table table-sm table-bordered table-head-fixed" style="margin-top: 4px; white-space: nowrap;">
    <thead>
        <tr>
            <th rowspan="2" class="bg-info color-palette" style="font-weight: normal; text-align: center; width: 50px; vertical-align: top;">No.</th>
            <th rowspan="2" class="bg-info color-palette" style="font-weight: normal; vertical-align: top;">Produk</th>
            <th colspan="3" class="bg-info color-palette" style="font-weight: normal; text-align: center;">Shift</th>
            <th rowspan="2" class="bg-info color-palette" style="font-weight: normal; text-align: right; vertical-align: top;">Total</th>
        </tr>
        <tr>
            <?php foreach ($shift as $sh) :  ?>
                <th class="bg-info color-palette" style="font-weight: normal; text-align: right;"><?php echo $sh->IdShift ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php $no = 0; foreach ($pivot as $produk => $row): $no += 1; ?>
            <tr>
                <td style="text-align: center;"><?php echo $no ?></td>
                <td><?php echo esc($produk) ?></td>
                <?php 
                $rowTotal = 0; 
                foreach ($shift as $sh2):
                    $val = $row[$sh2->IdShift] ?? 0; 
                    $rowTotal += $val; ?>
                    <td style="text-align: right;"><span  style="color: blue; cursor: pointer;" onclick="detail_hasil('<?php echo $sh2->IdShift ?>', '<?php echo $row['IdProduk'] ?>');"><?php echo ($row[$sh2->IdShift] > 0) ? number_format($row[$sh2->IdShift], 2) : '' ?></span></td>
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

                foreach ($shift as $data) {
                    $colTotals[$data->IdShift] = 0;
                }

                foreach ($pivot as $row) {
                    foreach ($shift as $data) {
                        $colTotals[$data->IdShift] += $row[$data->IdShift] ?? 0;
                    }
                }

                foreach ($shift as $data) {
                    echo '<td style="text-align: right;">' . number_format($colTotals[$data->IdShift], 2) . '</td>';
                    $grandTotal += $colTotals[$data->IdShift];
                }
            ?>
            <td style="text-align: right;"><?php echo number_format($grandTotal, 2) ?></td>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
    function detail_hasil(id_shift, id_produk) {
        bulan = $('#tgl_src').val();

        $.post('<?php echo base_url() ?>laporan_hasil_produksi/detail_hasil_pershift', {id_shift, id_produk, bulan}, function(data) {
            $('#modal_body_lg').html(data);
            $('#modal-lg').modal('show');
        });
    }
</script>