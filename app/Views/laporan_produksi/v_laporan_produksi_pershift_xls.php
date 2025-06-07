<table>
    <tr>
        <td rowspan="2">No.</td>
        <td rowspan="2">Produk</td>
        <td colspan="3">Shift</td>
        <td rowspan="2">Total</td>
    </tr>
    <tr>
        <?php foreach ($shift as $sh): ?>
            <td><?php echo $sh->IdShift ?></td>
        <?php endforeach; ?>
    </tr>
    <?php $no = 0; foreach ($pivot as $produk => $row): $no += 1; ?>
        <tr>
            <td><?php echo $no ?></td>
            <td><?php echo esc($produk) ?></td>
            <?php 
            $rowTotal = 0; 
            foreach ($shift as $sh2):
                $val = $row[$sh2->IdShift] ?? 0; 
                $rowTotal += $val; ?>
                <td style="text-align: right;"><?php echo ($row[$sh2->IdShift] > 0) ? number_format($row[$sh2->IdShift], 2) : '' ?></td>
            <?php endforeach; ?>
            <td style="text-align: right;"><?php echo number_format($rowTotal, 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>