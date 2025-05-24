<table>
	<tr>
		<td>#</td>
		<td>Id Produksi</td>
		<td>Tgl Produksi</td>
		<td>Shift</td>
		<td>No Mesin</td>
		<td>Operator</td>
		<td>Produk</td>
		<td>Qty Hasil</td>
		<td>Qty Waste</td>
		<td>User Input</td>
		<td>Tgl Input</td>
		<td>User Edit</td>
		<td>Tgl Edit</td>
	</tr>
	<?php 
	$no = 0;
	foreach($list as $ls) {
		$no += 1;
	?>
	<tr>
		<td><?php echo $no ?></td>
		<td><?php echo $ls->IdProduksi ?></td>
		<td><?php echo $ls->TglProduksi ?></td>
		<td><?php echo $ls->Shift ?></td>
		<td><?php echo $ls->NoMesin ?></td>
		<td><?php echo $ls->NamaOperator ?></td>
		<td><?php echo $ls->NamaProduk ?></td>
		<td><?php echo $ls->QtyHasil ?></td>
		<td><?php echo $ls->QtyWaste ?></td>
		<td><?php echo $ls->UserInput ?></td>
		<td><?php echo $ls->TglInput ?></td>
		<td><?php echo $ls->UserEdit ?></td>
		<td><?php echo $ls->TglEdit ?></td>
	</tr>
	<?php } ?>
</table>