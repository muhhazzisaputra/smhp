<style type="text/css">
    tfoot td {
        font-weight: bold;
        background-color: #f0f0f0;
    }
</style>

<table class="table table-sm table-bordered table-head-fixed" style="margin-top: 4px; white-space: nowrap;">
    <thead>
        <tr>
            <th rowspan="2" class="bg-info color-palette" style="font-weight: normal; vertical-align: top; text-align: center;">No.</th>
            <th rowspan="2" class="bg-info color-palette" style="font-weight: normal; vertical-align: top;">Produk</th>
            <th colspan="5" class="bg-info color-palette" style="font-weight: normal; text-align: center;">No Mesin</th>
            <th rowspan="2" class="bg-info color-palette" style="font-weight: normal; vertical-align: top; text-align: right;">Total</th>
        </tr>
        <tr>
            <?php foreach ($mesin as $msn): ?>
                <th class="bg-info color-palette" style="font-weight: normal; text-align: right;"><?php echo $msn->NoMesin ?></th>
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
                foreach ($mesin as $msn2):
                    $val = $row[$msn2->IdMesin] ?? 0; 
                    $rowTotal += $val; ?>
                    <td style="text-align: right;"><span  style="color: blue; cursor: pointer;" onclick="detail_permesin('<?php echo $msn2->IdMesin ?>', '<?php echo $row['IdProduk'] ?>');"><?php echo ($row[$msn2->IdMesin] > 0) ? number_format($row[$msn2->IdMesin], 2) : '' ?></span></td>
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

                foreach ($mesin as $data) {
                    $colTotals[$data->IdMesin] = 0;
                }

                foreach ($pivot as $row) {
                    foreach ($mesin as $data) {
                        $colTotals[$data->IdMesin] += $row[$data->IdMesin] ?? 0;
                    }
                }

                foreach ($mesin as $data) {
                    echo '<td style="text-align: right;">' . number_format($colTotals[$data->IdMesin], 2) . '</td>';
                    $grandTotal += $colTotals[$data->IdMesin];
                }
            ?>
            <td style="text-align: right;"><?php echo number_format($grandTotal, 2) ?></td>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
    function detail_permesin(id_mesin, id_produk) {
        bulan = $('#tgl_src').val();

        $.post('<?php echo base_url() ?>laporan_hasil_produksi/detail_hasil_permesin', {id_mesin, id_produk, bulan}, function(data) {
            $('#modal_body_lg').html(data);
            $('#modal-lg').modal('show');
        });
    }
</script>