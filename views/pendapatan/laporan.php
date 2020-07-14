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
        	<h4>LAPORAN PENDAPATAN</h4>
        	<h4><?=$prodi['nama_prodi']?></h4>
        </td>
    </tr>
</table>
<br><br>

<table width="100%" border="0" cellpadding="0" cellpadding="0" style="border-collapse: collapse;" class="tabel">
	<thead>
	    <tr style="background-color: #74b9ff;">
	    	<th align="left" height="30">No</th>
	    	<?php if(empty($idprodi)){?>
	    	<th align="left">Fakultas</th>
	    	<th align="left">Program Studi</th>
	    	<?php } ?>
	        <th align="left">Jenis Pendapatan</th>
	        <th align="right">Jumlah</th>
	        <th align="left">Tahun Ajaran</th>
	        <th align="left">Tanggal</th>
	    </tr>
    </thead>
    <tbody>
	    <?php 
	    $total = 0;
	    $i=1;
	    foreach ($data as $key => $value) {
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
	    		<td><?=$value->fakultas->nama_fakultas?></td>
	    		<td><?=$value->prodi->nama_prodi?></td>
	    	<?php } ?>
	    	<td><?=$value->jenispendapatan->jenis_pendapatan?></td>
	    	<td align="right"><?=number_format($value->jumlah)?></td>
	    	<td><?=$value->tahunajaran->tahun_ajaran?></td>
	    	<td><?=$value->tanggal?></td>
	    </tr>
		<?php $i++; } ?>
	</tbody>
	<tfoot>
		<tr style="background-color: #74b9ff;">
			<th align="left" height="30">Total :</th>
			<?php if(empty($idprodi)){?>
				<th align="left"></th>
				<th align="left"></th>
			<?php } ?>
			<th align="left"></th>
	        <th align="right"><?=number_format($total)?></th>
	        <th align="right"></th>
	        <th align="right"></th>
	    </tr>
    </tfoot>
</table>
<br>
<table width="50%" border="0" cellpadding="0" cellpadding="0">
    <tr>
        <th></th>
        <th align="right">Pendapatan</th>
        <th align="right">Nilai Akhir</th>
    </tr>
    <tr style="background:#dbeffb; color:blue; font-weight:bold;">
        <td height='30' ><b>Mahasiswa</b></td>
        <td align="right"><?=number_format($pendapatanmahasiswa)?></td>
        <td align="right"><?=number_format($nilaiakhirmahasiswa)?></td>
    </tr>
    <tr hstyle="background:#defbdb; color:green; font-weight:bold;">
        <td height='30' ><b>Dosen</b></td>
        <td align="right"><?=number_format($pendapatandosen)?></td>
        <td align="right"><?=number_format($nilaiakhirdosen)?></td>
    </tr>
    <tr style="background:#fbfbdb; color:#fd7c27; font-weight:bold;">
        <td height='30' ><b>Lain-lain</b></td>
        <td align="right"><?=number_format($pendapatanlain)?></td>
        <td align="right"><?=number_format($nilaiakhirlain)?></td>
    </tr>
</table>
</body>
</html>