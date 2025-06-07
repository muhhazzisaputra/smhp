<table>
    <tr>
        <td>No.</td>
        <td>Produk</td>
        <?php foreach ($dates as $date): ?>
            <td><?php echo date('d-M-Y', strtotime($date)) ?></td>
        <?php endforeach; ?>
        <td>Total</td>
    </tr>
    <?php $no = 0; foreach ($pivot as $produk => $row) : $no += 1; ?>
    <tr>
        <td><?php echo $no ?></td>
        <td><?php echo esc($produk) ?></td>
        <?php 
        $rowTotal = 0; 
        foreach ($dates as $date):
            $val = $row[$date] ?? 0; 
            $rowTotal += $val; ?>
            <td><?php echo ($row[$date] > 0) ? $row[$date] : '' ?></td>
        <?php endforeach; ?>
        <td><?php echo $rowTotal ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="2" style="text-align: right;">Total</td>
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
                echo '<td>'.$colTotals[$date].'</td>';
                $grandTotal += $colTotals[$date];
            }
        ?>
        <td><?php echo $grandTotal ?></td>
    </tr>
</table>