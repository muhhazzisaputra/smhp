<table>
    <tr>
        <td rowspan="2" style="text-align: center; vertical-align: top;">No.</td>
        <td rowspan="2" style="vertical-align: top;">Tgl Produksi</td>
        <td colspan="<?php echo count($karyawan) ?>" style="text-align: center;">Operator</td>
        <td rowspan="2" style="text-align: right; vertical-align: top;">Total</td>
    </tr>
    <tr>
        <?php foreach ($nama as $nm) : ?>
            <td><?php echo $nm->NamaKaryawan ?></td>
        <?php endforeach; ?>
    </tr>
    <?php $no = 0; foreach ($pivot as $tanggal => $row): $no += 1; ?>
        <tr>
            <td style="text-align: center;"><?php echo $no ?></td>
            <td><?php echo date('d-M-Y', strtotime($tanggal)) ?></td>
            <?php 
            $rowTotal = 0; 
            foreach ($karyawan as $kry) :
                $val = $row[$kry] ?? 0; 
                $rowTotal += $val; ?>
                <td style="text-align: right;"><?php echo ($row[$kry] > 0) ? $row[$kry] : '' ?></td>
            <?php endforeach; ?>
            <td style="text-align: right;"><?php echo $rowTotal ?></td>
        </tr>
    <?php endforeach; ?>
</table>