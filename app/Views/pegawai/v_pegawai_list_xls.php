<table>
	<tr>
		<td>No</td>
		<td>Id Pegawai</td>
		<td>Nama Pegawai</td>
		<td>Departemen</td>
		<td>Jabatan</td>
		<td>Alamat</td>
		<td>No HP</td>
		<td>User Input</td>
		<td>Tanggal Input</td>
		<td>User Edit</td>
		<td>Tanggal Edit</td>
	</tr>
	<?php 
	$no = 0;
	foreach ($list as $data) {
		$no += 1;
	?>
	<tr>
		<td><?php echo $no ?></td>
		<td><?php echo $data->IdKaryawan ?></td>
		<td><?php echo htmlspecialchars($data->NamaKaryawan) ?></td>
		<td><?php echo htmlspecialchars($data->NamaDepartemen) ?></td>
		<td><?php echo $data->NamaJabatan ?></td>
		<td><?php echo htmlspecialchars($data->Alamat) ?></td>
		<td><?php echo $data->NoHp ?></td>
		<td><?php echo $data->UserInput ?></td>
		<td><?php echo $data->TglInput ?></td>
		<td><?php echo $data->UserEdit ?></td>
		<td><?php echo $data->TglEdit ?></td>
	</tr>
	<?php } ?>
</table>