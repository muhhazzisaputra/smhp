<table>
	<tr>
		<td rowspan="2">#</td>
		<td rowspan="2">Id Produksi</td>
		<td rowspan="2">Tgl Produksi</td>
		<td rowspan="2">Shift</td>
		<td rowspan="2">No Mesin</td>
		<td rowspan="2">Operator</td>
		<td rowspan="2">Produk</td>
		<td colspan="2">Jam Setting</td>
		<td rowspan="2">Speed</td>
		<td rowspan="2">Temperatur</td>
		<td rowspan="2">Kategori NG</td>
		<td rowspan="2">Qty NG</td>
		<td rowspan="2">Keterangan Komplain QC</td>
	</tr>
	<tr>
		<td>Dari</td>
		<td>Jam</td>
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
		<td><?php echo $ls->Jam ?></td>
		<td><?php echo $ls->Jam2 ?></td>
		<td><?php echo $ls->Speed ?></td>
		<td><?php echo $ls->Temperatur ?></td>
		<td><?php echo $ls->NamaKategori ?></td>
		<td><?php echo $ls->QtyNG ?></td>
		<td><?php echo $ls->Keterangan ?></td>
	</tr>
	<?php } ?>
</table>