<!DOCTYPE html>
<html>
<head>
    <title>Pivot Data Hasil Produksi</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #888;
            padding: 6px;
            text-align: right;
        }
        th {
            background: #d9d9d9;
        }
        td:first-child, th:first-child {
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Pivot Tabel Hasil Produksi</h2>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <?php foreach ($dates as $date): ?>
                    <th><?= date('Y-m-d', strtotime($date)) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pivot as $produk => $row): ?>
                <tr>
                    <td><?= esc($produk) ?></td>
                    <?php foreach ($dates as $date): ?>
                        <td><?= isset($row[$date]) ? number_format($row[$date], 2) : '0.00' ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
