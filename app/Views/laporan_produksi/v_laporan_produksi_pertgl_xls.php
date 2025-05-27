<table>
    <tr>
        <td>Produk</td>
        <?php foreach ($dates as $date): ?>
            <td><?php echo date('d-M-Y', strtotime($date)) ?></td>
        <?php endforeach; ?>
        <td>Total</td>
    </tr>
    <?php foreach ($pivot as $produk => $row): ?>
    <tr>
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
        <td>Total</td>
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