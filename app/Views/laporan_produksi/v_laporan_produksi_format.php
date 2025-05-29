<?php if($format=="per_tgl") { ?>
	<table style="white-space: nowrap;">
		<tr>
			<?php echo $opt_format ?>
			<td style="width: 108px;">&nbsp;&nbsp;&nbsp;Tgl Produksi</td>
			<td style="width: 13px;">:</td>
			<td style="">
				<input type="text" name="tgl_src" id="tgl_src" value="<?php echo date('Y-m-01') ?>" class="form-control dateclass" style="width: 100px; background-color: white; display: inline;" readonly> -
				<input type="text" name="tgl2_src" id="tgl2_src" value="<?php echo date('Y-m-d') ?>" class="form-control dateclass" style="width: 100px; background-color: white; display: inline;" readonly>
			</td>
			<td style="width: 75px;">&nbsp;&nbsp;&nbsp;Produk</td>
			<td style="width: 13px;">:</td>
			<td style="">
				<select class="form-control" type="produk_src" name="produk_src" style="width: 175px;">
					<option value="">-Pilih-</option>
	                <?php foreach($produk as $prd) { ?>
	                    <option value="<?php echo $prd->IdProduk ?>"><?php echo $prd->NamaProduk ?></option>
	                <?php } ?>
               </select>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-primary" id="searchBtn" title="Cari" onclick="view_data(this)"><i class="fas fa-search"></i></button>
				<button type="submit" class="btn btn-sm btn-success" title="Export Xls"><i class="fas fa-file-excel"></i></button>
			</td>
		</tr>
  	</table>
<?php } else if( ($format=="per_shift") || ($format=="per_mesin") ) { ?>
	<table style="white-space: nowrap;">
		<tr>
			<?php echo $opt_format ?>
			<td style="width: 65px;">&nbsp;&nbsp;&nbsp;Bulan</td>
			<td style="width: 13px;">:</td>
			<td style="">
				<input type="text" name="tgl_src" id="tgl_src" value="<?php echo date('Y-m-01') ?>" class="form-control monthclass" style="width: 100px; background-color: white; display: inline;" readonly>
			</td>
			<?php if($format=="per_shift") { ?> 
			<td style="width: 55px;">&nbsp;&nbsp;&nbsp;Shift</td>
			<td style="width: 13px;">:</td>
			<td style="">
				<select name="shift" id="shift" class="form-control" style="width: 75px;">
                    <option value="">-Pilih-</option>
                    <?php foreach ($shift as $sf) : ?>
                        <option value="<?php echo $sf->IdShift ?>"><?php echo $sf->IdShift ?></option>
                    <?php endforeach; ?>
                </select>
			</td>
			<?php } else if($format=="per_mesin") { ?>
			<td style="width: 55px;">&nbsp;&nbsp;&nbsp;Mesin</td>
			<td style="width: 13px;">:</td>
			<td style="">
				<select name="mesin_src" id="mesin_src" class="form-control" style="width: 75px;">
                    <option value="">-Pilih-</option>
                    <?php foreach ($mesin as $ms) : ?>
                        <option value="<?php echo $ms->IdMesin ?>"><?php echo $ms->NoMesin ?></option>
                    <?php endforeach; ?>
                </select>
			</td>
			<?php } ?>
			<td style="width: 75px;">&nbsp;&nbsp;&nbsp;Produk</td>
			<td style="width: 13px;">:</td>
			<td style="">
				<select class="form-control" type="produk_src" name="produk_src" style="width: 175px;">
					<option value="">-Pilih-</option>
	                <?php foreach($produk as $prd) { ?>
	                    <option value="<?php echo $prd->IdProduk ?>"><?php echo $prd->NamaProduk ?></option>
	                <?php } ?>
               </select>
			</td>
			<td>
				&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-primary" id="searchBtn" title="Cari" onclick="view_data(this)"><i class="fas fa-search"></i></button>
				<button type="submit" class="btn btn-sm btn-success" title="Export Xls"><i class="fas fa-file-excel"></i></button>
			</td>
		</tr>
	</table>
<?php } ?>

<script type="text/javascript">
	$(function() {
		$('.dateclass').datepicker({
	        autoclose     : true,
	        format        : 'yyyy-mm-dd',
	        changeMonth   : true,
	        changeYear    : true,
	        todayHighlight: true,
	        toggleActive  : true
	    });

		$('.monthclass').datepicker({
	        autoclose     : true,
	        format        : 'yyyy-mm-dd',
	        changeMonth   : true,
	        changeYear    : true,
	        todayHighlight: true,
	        toggleActive  : true,
	        startView     : "months",
	        minViewMode   : "months"
	    });
	});
</script>