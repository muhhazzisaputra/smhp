<table>
    <tr>
        <td rowspan="2">Produk</td>
        <td colspan="5" style="text-align: center;">No Mesin</td>
        <td rowspan="2">Total</td>
    </tr>
    <tr>
        <?php foreach ($mesin as $msn): ?>
            <td><?php echo $msn->NoMesin ?></td>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($pivot as $produk => $row): ?>
        <tr>
            <td><?php echo esc($produk) ?></td>
            <?php 
            $rowTotal = 0; 
            foreach ($mesin as $msn2):
                $val = $row[$msn2->IdMesin] ?? 0; 
                $rowTotal += $val; ?>
                <td><?php echo ($row[$msn2->IdMesin] > 0) ? $row[$msn2->IdMesin] : '' ?></td>
            <?php endforeach; ?>
            <td><?php echo $rowTotal ?></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td>Total</td>
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
                echo '<td>' .$colTotals[$data->IdMesin]. '</td>';
                $grandTotal += $colTotals[$data->IdMesin];
            }
        ?>
        <td><?php echo $grandTotal ?></td>
    </tr>
</table>