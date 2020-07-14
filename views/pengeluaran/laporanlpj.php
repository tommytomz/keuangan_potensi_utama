<?php
use app\models\Prodi;

$prodi=Prodi::find()->where(['id'=>$idprodi])->one();
?>

<!DOCTYPE html>
<html lang="en">
<head>

  <!-- <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
<style type="text/css">

</style>

</head>
<body>

<table border=0 width="100%">
    <tr>
        <td><img src="<?=Yii::$app->request->baseUrl;?>/images/logo_upu.jpg" width="70" height="70"></td>
        <td align="center" width="">
        	<h2>UNIVERSITAS POTENSI UTAMA</h2>
        	<h4>LAPORAN PERTANGGUNGJAWABAN</h4>
        	<h4><?=$prodi['nama_prodi']?></h4>
        </td>
    </tr>
</table>
<br><br>

<table width="100%" border="0" cellpadding="0" cellpadding="0" style="border-collapse: collapse;" class="tabel">
	<thead>
	    <tr style="background-color: #74b9ff;">
	    	<th align="left">No</th>
	    	<?php if(empty($idprodi)){?>
	    	<th align="left">Fakultas</th>
	    	<th align="left">Program Studi</th>
	    	<?php } ?>
	        <th align="left">Pendapatan</th>
	        <th align="left">Pengeluaran</th>
	        <th align="left">Jumlah</th>
	        <!-- <th align="left">Tahun Ajaran</th> -->
	    </tr>
    </thead>
    <tbody>
	    <?php 
	    $totalpendapatan = 0;
	    $totalpengeluaran = 0;
	    $total = 0;
	    $i=1;
	    foreach ($data as $key => $value) {
	    	$totalpendapatan += $value->pendapatan;
	    	$totalpengeluaran += $value->pengeluaran;
	    	$total += $value->jumlah;
	    	$warna = "";
	    	if($i%2==0){
	    		$warna = '#d3e7fd';
	    	}
	    	else{
	    		$warna = '#ffffff';
	    	}
	    ?>

	    <tr style="background-color: <?=$warna?>">
	    	<td height="30"><?=$i?></td>
	    	<?php if(empty($idprodi)){?>
	    		<td><?=$value->nama_fakultas?></td>
	    		<td><?=$value->nama_prodi?></td>
	    	<?php } ?>
	    	<td align="right"><?=number_format($value->pendapatan)?></td>
	    	<td align="right"><?=number_format($value->pengeluaran)?></td>
	    	<td align="right"><?=number_format($value->jumlah)?></td>
	    	
	    </tr>
		<?php $i++; } ?>
	</tbody>
	<tfoot>
		<tr style="background-color: #74b9ff;">
			<th align="left" height="30">Total :</th>
			<?php if(empty($idprodi)){?>
				<th align="left"></th>
				<th align="left"></th>
				<th align="right"><?=number_format($totalpendapatan)?></th>
				<th align="right"><?=number_format($totalpengeluaran)?></th>
				<th align="right"><?=number_format($total)?></th>
			<?php } else{ ?>
				<th align="right"><?=number_format($totalpendapatan)?></th>
				<th align="right"><?=number_format($totalpengeluaran)?></th>
	        	<th align="right"><?=number_format($total)?></th>
	    	<?php } ?>
	    </tr>
    </tfoot>
</table>

</body>
</html>