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
            <th rowspan="2" class="bg-info color-palette" style="font-weight: normal; vertical-align: top;">Tgl Produksi</th>
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
                <td><?php echo date('d-M-Y', strtotime($tanggal)) ?></td>
                <?php 
                $rowTotal = 0; 
                foreach ($produk as $prd) :
                    $val = $row[$prd] ?? 0; 
                    $rowTotal += $val; ?>
                    <td style="text-align: right;"><span  style="color: blue; cursor: pointer;" onclick="detail_hasil('<?php echo $tanggal ?>', '<?php echo $prd ?>');"><?php echo ($row[$prd] > 0) ? number_format($row[$prd], 2) : '' ?></span></td>
                <?php endforeach; ?>
                <td style="text-align: right;"><?php echo number_format($rowTotal, 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script type="text/javascript">
    function detail_hasil(tgl_produksi, id_produk) {
        $.post('<?php echo base_url() ?>laporan_hasil_produksi/detail_hasil_perproduk', {tgl_produksi, id_produk}, function(data) {
            $('#modal_body_lg').html(data);
            $('#modal-lg').modal('show');
        });
    }
</script>